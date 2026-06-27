<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;
use App\Models\Employee;
use App\Models\ProjectStage;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with([
            'order.user',
            'order.items.product',
        ])->latest()->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create($order_id)
    {
        $order = Order::with([
            'user',
            'product',
            'variant',
            'selectedVolume',
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project',
        ])->findOrFail($order_id);

        if ($order->status_verify !== 'approved') {
            return back()->with('error', 'Project hanya bisa dibuat dari order yang sudah approved.');
        }

        if ($order->project) {
            return redirect('/admin/projects/' . $order->project->id)
                ->with('error', 'Project untuk order ini sudah dibuat.');
        }

        return view('admin.projects.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'project_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
        ]);

        $order = Order::with([
            'product',
            'items.product',
            'items.variant',
            'items.selectedVolume',
            'project',
        ])->findOrFail($request->order_id);

        if ($order->status_verify !== 'approved') {
            return back()->with('error', 'Order belum approved.');
        }

        if ($order->project) {
            return redirect('/admin/projects/' . $order->project->id)
                ->with('error', 'Project untuk order ini sudah dibuat.');
        }

        $project = Project::create([
            'order_id' => $order->id,
            'project_name' => $request->project_name,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => 'not_started',
            'progress_percent' => 0,
        ]);

        $this->createStagesForProject($project);

        return redirect('/admin/projects')
            ->with('success', 'Project berhasil dibuat beserta tahapan pekerjaan sesuai produk.');
    }

    private function createStagesForProject(Project $project): void
    {
        $project->load([
            'order.product',
            'order.items.product',
        ]);

        $order = $project->order;

        if (!$order) {
            return;
        }

        if ($order->items->count() > 0) {
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

    public function show($id)
    {
        $project = Project::with([
            'order.user',
            'order.product',
            'order.variant',
            'order.selectedVolume',
            'order.items.product',
            'order.items.variant',
            'order.items.selectedVolume',
            'assignments.employee',
            'logs',
            'workUpdates',
            'stages.employee',
            'stages.orderItem.product',
            'stages.orderItem.variant',
            'stages.orderItem.selectedVolume',
        ])->findOrFail($id);

        $employees = Employee::all();

        return view('admin.projects.show', compact('project', 'employees'));
    }

    public function monitoring($id)
    {
        $project = Project::with([
            'order.user',
            'order.product',
            'order.variant',
            'order.selectedVolume',
            'order.items.product',
            'order.items.variant',
            'order.items.selectedVolume',
            'logs' => function ($q) {
                $q->oldest();
            },
            'workUpdates' => function ($q) {
                $q->where('validation_status', 'approved')->latest();
            },
            'workUpdates.employee',
            'stages.employee',
            'stages.orderItem.product',
            'stages.orderItem.variant',
            'stages.orderItem.selectedVolume',
        ])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($id);

        return view('client.projects.monitoring', compact('project'));
    }

    public function board()
    {
        $projects = Project::with([
            'order.user',
            'order.items.product',
            'assignments.employee',
            'workUpdates',
        ])->latest()->get();

        $needApprovalProjects = $projects->filter(function ($project) {
            return $project->workUpdates
                ->where('validation_status', 'pending')
                ->count() > 0;
        });

        $todoProjects = $projects->where('status', 'not_started');

        $inProgressProjects = $projects->filter(function ($project) {
            return $project->status === 'in_progress'
                && $project->workUpdates->where('validation_status', 'pending')->count() === 0;
        });

        $doneProjects = $projects->where('status', 'done');

        return view('admin.projects.board', compact(
            'todoProjects',
            'inProgressProjects',
            'needApprovalProjects',
            'doneProjects'
        ));
    }

    public function pegawaiProjects()
    {
        $projects = Project::whereHas('assignments', function ($q) {
            $q->whereHas('employee', function ($q2) {
                $q2->where('user_id', auth()->id());
            });
        })
            ->with([
                'order.items.product',
                'stages.orderItem.product',
                'stages.employee',
            ])
            ->get();

        return view('pegawai.projects.index', compact('projects'));
    }

    public function pegawaiShow($id)
    {
        $project = Project::with([
            'order.user',
            'order.product',
            'order.variant',
            'order.selectedVolume',
            'order.items.product',
            'order.items.variant',
            'logs',
            'stages.employee',
            'stages.orderItem.product',
            'stages.orderItem.variant',
            'stages.orderItem.selectedVolume',
        ])
            ->whereHas('assignments', function ($q) {
                $q->whereHas('employee', function ($q2) {
                    $q2->where('user_id', auth()->id());
                });
            })
            ->findOrFail($id);

        return view('pegawai.projects.show', compact('project'));
    }

    public function pimpinanProjects()
    {
        $projects = Project::with([
            'order.user',
            'order.items.product',
            'assignments.employee',
            'workUpdates',
            'stages.employee',
            'stages.orderItem.product',
        ])->latest()->get();

        return view('pimpinan.projects.index', compact('projects'));
    }
}