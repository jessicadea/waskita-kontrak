<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectStage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function clientIndex()
    {
        $orders = Order::with([
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project',
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('client.orders.index', compact('orders'));
    }

    public function index(Request $request)
    {
        $query = Order::with([
            'user',
            'product',
            'variant',
            'selectedVolume',
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('project_location', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('items.product', function ($productQuery) use ($search) {
                        $productQuery->where('product_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status_verify')) {
            $query->where('status_verify', $request->status_verify);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->get();

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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.volume_id' => 'required|exists:product_variant_volumes,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.product_spec' => 'nullable|string',

            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'project_location' => 'required|string|max:255',
            'delivery_cond' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:' . now()->addDays(30)->format('Y-m-d'),
        ], [
            'delivery_date.after_or_equal' => 'Tanggal pengiriman minimal 30 hari setelah tanggal pemesanan.',
        ]);

        DB::transaction(function () use ($request) {
            $firstItem = $request->items[0];

            $order = Order::create([
                'user_id' => auth()->id(),

                // Kolom lama tetap diisi agar fitur lama tidak error
                'product_id' => $firstItem['product_id'],
                'variant_id' => $firstItem['variant_id'],
                'volume_id' => $firstItem['volume_id'],
                'quantity' => $firstItem['quantity'],
                'volume' => $firstItem['quantity'],
                'product_spec' => $firstItem['product_spec'] ?? '-',

                'requires_acceleration' => $request->boolean('requires_acceleration'),

                'company_name' => $request->company_name,
                'company_type' => $request->company_type,
                'project_name' => $request->project_name,
                'project_location' => $request->project_location,
                'delivery_cond' => $request->delivery_cond,
                'delivery_date' => $request->delivery_date,
                'status_verify' => 'pending',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'volume_id' => $item['volume_id'],
                    'quantity' => $item['quantity'],
                    'volume' => $item['quantity'],
                    'product_spec' => $item['product_spec'] ?? '-',
                ]);
            }
        });

        return redirect('/client/orders')
            ->with('success', 'Order berhasil dibuat dan menunggu verifikasi admin.');
    }

    public function show($id)
    {
        if (auth()->user()->role === 'client') {
            $order = Order::with([
                'items.product',
                'items.variant',
                'items.selectedVolume',
                'project',
            ])
                ->where('user_id', auth()->id())
                ->findOrFail($id);

            return view('client.orders.show', compact('order'));
        }

        $order = Order::with([
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'user',
            'project.stages',
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
            $order->load('project.stages');
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
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project.stages',
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
        $project->load([
            'order.product',
            'order.items.product',
        ]);

        $order = $project->order;

        if ($order && $order->items->count() > 0) {
            foreach ($order->items as $item) {
                $stages = $this->getStagesByProductName($item->product->product_name ?? '');

                foreach ($stages as $stage) {
                    ProjectStage::create([
                        'project_id' => $project->id,
                        'order_item_id' => $item->id,
                        'stage_name' => $stage['name'],
                        'weight_percent' => $stage['weight'],
                        'status' => 'todo',
                    ]);
                }
            }

            return;
        }

        $stages = $this->getStagesByProductName($order->product->product_name ?? '');

        foreach ($stages as $stage) {
            ProjectStage::create([
                'project_id' => $project->id,
                'order_item_id' => null,
                'stage_name' => $stage['name'],
                'weight_percent' => $stage['weight'],
                'status' => 'todo',
            ]);
        }
    }

    private function getStagesByProductName(?string $productName): array
    {
        $name = strtolower($productName ?? '');

        if (str_contains($name, 'spun')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pembuatan Tulangan Spiral', 'weight' => 15],
                ['name' => 'Pemasangan Cetakan Spun Pile', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Proses Spinning', 'weight' => 15],
                ['name' => 'Steam / Curing Beton', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'girder')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Perakitan Tulangan & Tendon', 'weight' => 20],
                ['name' => 'Pemasangan Bekisting', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Prestressing / Stressing', 'weight' => 10],
                ['name' => 'Steam Curing', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'box')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pembuatan Bekisting Box Culvert', 'weight' => 15],
                ['name' => 'Pemasangan Tulangan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Curing Beton', 'weight' => 15],
                ['name' => 'Pembongkaran Cetakan', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'u-ditch') || str_contains($name, 'uditch')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pembuatan Cetakan U-Ditch', 'weight' => 15],
                ['name' => 'Pemasangan Tulangan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Curing Beton', 'weight' => 15],
                ['name' => 'Pembongkaran Cetakan', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'barrier')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pemasangan Tulangan', 'weight' => 15],
                ['name' => 'Persiapan Cetakan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Curing Beton', 'weight' => 15],
                ['name' => 'Finishing Permukaan', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'tiang')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pembuatan Tulangan', 'weight' => 15],
                ['name' => 'Pemasangan Cetakan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Proses Spinning', 'weight' => 15],
                ['name' => 'Steam Curing', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        if (str_contains($name, 'panel') || str_contains($name, 'pagar')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pemasangan Tulangan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Curing Beton', 'weight' => 15],
                ['name' => 'Finishing Permukaan', 'weight' => 15],
                ['name' => 'Quality Control', 'weight' => 15],
                ['name' => 'Pengiriman', 'weight' => 10],
            ];
        }

        if (str_contains($name, 'sheet')) {
            return [
                ['name' => 'Persiapan Material', 'weight' => 10],
                ['name' => 'Pembuatan Tulangan', 'weight' => 15],
                ['name' => 'Pemasangan Cetakan', 'weight' => 15],
                ['name' => 'Pengecoran Beton', 'weight' => 20],
                ['name' => 'Steam Curing', 'weight' => 15],
                ['name' => 'Finishing', 'weight' => 10],
                ['name' => 'Quality Control', 'weight' => 10],
                ['name' => 'Pengiriman', 'weight' => 5],
            ];
        }

        return [
            ['name' => 'Persiapan Material', 'weight' => 10],
            ['name' => 'Persiapan Produksi', 'weight' => 15],
            ['name' => 'Pengecoran Beton', 'weight' => 20],
            ['name' => 'Curing Beton', 'weight' => 20],
            ['name' => 'Quality Control', 'weight' => 15],
            ['name' => 'Finishing', 'weight' => 10],
            ['name' => 'Pengiriman', 'weight' => 10],
        ];
    }

    private function generateContractPdf(Order $order)
    {
        $order->load([
            'user',
            'product',
            'variant',
            'selectedVolume',
            'items.product',
            'items.variant',
            'items.selectedVolume',
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
        $orders = Order::with([
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project',
        ])
            ->where('user_id', auth()->id())
            ->whereNotNull('contract_file')
            ->latest()
            ->get();

        return view('client.contracts.index', compact('orders'));
    }
}