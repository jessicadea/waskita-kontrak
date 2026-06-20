<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectStage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function clientIndex()
    {
        $orders = Order::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('client.orders.index', compact('orders'));
    }

    public function index()
    {
        $orders = Order::with(['user', 'product'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();

        return view('client.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'volume_id' => 'required|exists:product_variant_volumes,id',
            'quantity' => 'required|integer|min:1',

            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'project_location' => 'required|string|max:255',
            'product_spec' => 'required|string',
            'delivery_cond' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:' . now()->addDays(30)->format('Y-m-d'),
        ], [
            'delivery_date.after_or_equal' => 'Tanggal pengiriman minimal 30 hari setelah tanggal pemesanan.',
        ]);

        Order::create([
            'user_id' => auth()->id(),

            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'volume_id' => $request->volume_id,
            'quantity' => $request->quantity,

            'requires_acceleration' => $request->boolean('requires_acceleration'),

            'volume' => $request->quantity,

            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'project_name' => $request->project_name,
            'project_location' => $request->project_location,
            'product_spec' => $request->product_spec,
            'delivery_cond' => $request->delivery_cond,
            'delivery_date' => $request->delivery_date,
            'status_verify' => 'pending',
        ]);

        return redirect('/client/orders')->with('success', 'Order berhasil dibuat dan menunggu verifikasi admin.');
    }

    public function show($id)
    {
        if (auth()->user()->role === 'client') {
            $order = Order::with([
                'product',
                'variant',
                'selectedVolume',
                'project'
            ])
                ->where('user_id', auth()->id())
                ->findOrFail($id);

            return view('client.orders.show', compact('order'));
        }

        $order = Order::with([
            'user',
            'product',
            'variant',
            'selectedVolume',
            'project'
        ])->findOrFail($id);

        if (
            auth()->user()->role === 'admin' &&
            $order->status_verify === 'approved' &&
            !$order->contract_file
        ) {
            $contractPath = $this->generateContractPdf($order);

            $order->update([
                'contract_file' => $contractPath,
            ]);

            $order->refresh();
        }

        if (
            auth()->user()->role === 'admin' &&
            $order->status_verify === 'approved' &&
            $order->project &&
            $order->project->stages()->count() === 0
        ) {
            $this->createDefaultProjectStages($order->project);
            $order->load('project');
        }

        return view('admin.orders.show', compact('order'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status_verify' => 'required|in:approved,rejected',
            'verify_note' => 'nullable|string',
        ]);

        $order = Order::with([
            'user',
            'product',
            'variant',
            'selectedVolume',
            'project'
        ])->findOrFail($id);

        $order->update([
            'status_verify' => $request->status_verify,
            'verify_note' => $request->verify_note,
        ]);

        if ($request->status_verify === 'approved') {
            if (!$order->contract_file) {
                $contractPath = $this->generateContractPdf($order);

                $order->update([
                    'contract_file' => $contractPath,
                ]);
            }

            if (!$order->project) {
                $project = Project::create([
                    'order_id' => $order->id,
                    'project_name' => $order->project_name,
                    'start_date' => now()->toDateString(),
                    'due_date' => $order->delivery_date,
                    'status' => 'not_started',
                    'progress_percent' => 0,
                ]);

                $this->createDefaultProjectStages($project);
            } elseif ($order->project->stages()->count() === 0) {
                $this->createDefaultProjectStages($order->project);
            }
        }

        return redirect('/admin/orders/' . $order->id)
            ->with('success', 'Verifikasi berhasil disimpan. Kontrak, project, dan tahapan otomatis dibuat jika order disetujui.');
    }

    private function createDefaultProjectStages(Project $project)
    {
        $defaultStages = [
            ['Persiapan Material', 10],
            ['Pembuatan Cetakan / Mould', 15],
            ['Pengecoran', 10],
            ['Curing Beton', 15],
            ['Quality Control', 15],
            ['Pengiriman', 10],
            ['Dokumentasi & Serah Terima', 10],
        ];

        foreach ($defaultStages as [$name, $weight]) {
            ProjectStage::create([
                'project_id' => $project->id,
                'stage_name' => $name,
                'weight_percent' => $weight,
                'status' => 'todo',
            ]);
        }
    }

    private function generateContractPdf(Order $order)
    {
        $order->load([
            'user',
            'product',
            'variant',
            'selectedVolume',
        ]);

        $fileName = 'contract-order-' . $order->id . '-' . now()->format('YmdHis') . '.pdf';
        $filePath = 'contracts/' . $fileName;

        $pdf = Pdf::loadView('contracts.order-contract', [
            'order' => $order,
        ])->setPaper('A4', 'portrait');

        Storage::disk('public')->put($filePath, $pdf->output());

        return $filePath;
    }

    public function uploadContract(Request $request, $id)
    {
        $request->validate([
            'contract_file' => 'required|mimes:pdf|max:5120',
        ]);

        $order = Order::findOrFail($id);

        $file = $request->file('contract_file')->store('contracts', 'public');

        $order->update([
            'contract_file' => $file,
        ]);

        return back()->with('success', 'Dokumen kontrak berhasil diupload.');
    }

    public function contracts()
    {
        $orders = Order::with(['product', 'project'])
            ->where('user_id', auth()->id())
            ->whereNotNull('contract_file')
            ->latest()
            ->get();

        return view('client.contracts.index', compact('orders'));
    }
}