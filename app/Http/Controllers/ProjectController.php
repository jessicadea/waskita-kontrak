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
        $projects = Project::with('order.user')->latest()->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create($order_id)
    {
        $order = Order::with('product', 'user')->findOrFail($order_id);

        if ($order->status_verify !== 'approved') {
            return back()->with('error', 'Project hanya bisa dibuat dari order yang sudah approved.');
        }

        if ($order->project) {
            return redirect('/admin/projects/'.$order->project->id)
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

        $order = Order::findOrFail($request->order_id);

        if ($order->status_verify !== 'approved') {
            return back()->with('error', 'Order belum approved.');
        }

        $project = Project::create([
            'order_id' => $order->id,
            'project_name' => $request->project_name,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => 'not_started',
            'progress_percent' => 0,
        ]);

        $defaultStages = [
        ['Persiapan Material', 10],
        ['Pembuatan Cetakan / Mould', 15],
        ['Pengecoran', 20],
        ['Curing Beton', 15],
        ['Quality Control', 15],
        ['Pengiriman', 10],
        ['Dokumentasi & Serah Terima', 15],
    ];

    foreach ($defaultStages as [$name, $weight]) {
        \App\Models\ProjectStage::create([
            'project_id' => $project->id,
            'stage_name' => $name,
            'weight_percent' => $weight,
            'status' => 'todo',
        ]);
    }

        return redirect('/admin/projects')->with('success', 'Project berhasil dibuat beserta tahapan pekerjaan.');
    }

    public function show($id)
    {
        $project = Project::with([
            'order.user',
            'order.product',
            'order.variant',
            'order.selectedVolume',
            'assignments.employee',
            'logs',
            'workUpdates',
            'stages.employee',
        ])->findOrFail($id);

        $employees = Employee::all();

        return view('admin.projects.show', compact('project', 'employees'));
    }

    public function monitoring($id)
    {
        $project = Project::with([
            'order.product',
            'logs' => function ($q) {
                $q->oldest();
            },
            'workUpdates' => function ($q) {
                $q->where('validation_status', 'approved')->latest();
            },
            'workUpdates.employee',
            'stages.employee',
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
            'order.product',
            'assignments.employee',
            'workUpdates'
        ])->latest()->get();

        // PROJECT YANG ADA PENDING VALIDATION
        $needApprovalProjects = $projects->filter(function ($project) {
            return $project->workUpdates
                ->where('validation_status', 'pending')
                ->count() > 0;
        });

        // TODO
        $todoProjects = $projects->where('status', 'not_started');

        $inProgressProjects = $projects->filter(function ($project) {
            return $project->status === 'in_progress'
                && $project->workUpdates
                    ->where('validation_status', 'pending')
                    ->count() == 0;
        });

        // DONE
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
        ->with(['order', 'stages'])
        ->get();

        return view('pegawai.projects.index', compact('projects'));
    }

    public function pegawaiShow($id)
    {
        $project = Project::with([
            'order',
            'logs',
            'stages.employee',
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
            'order.product',
            'assignments.employee',
            'workUpdates',
            'stages.employee',
        ])->latest()->get();

        return view('pimpinan.projects.index', compact('projects'));
    }
}