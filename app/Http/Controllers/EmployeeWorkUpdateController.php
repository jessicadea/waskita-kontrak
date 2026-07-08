<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeWorkUpdate;
use App\Models\Project;
use App\Models\ProjectStage;
use Illuminate\Http\Request;

class EmployeeWorkUpdateController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', auth()->id())->firstOrFail();

        $updates = EmployeeWorkUpdate::with([
            'project',
            'stage',
            'stage.orderItem.product',
            'stage.orderItem.variant',
            'stage.orderItem.selectedVolume',
        ])
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        return view('pegawai.work_updates.index', compact('updates'));
    }

    public function create(Request $request)
    {
        $employee = Employee::where('user_id', auth()->id())->firstOrFail();
        $projectId = $request->project_id;

        if ($projectId) {
            $project = Project::with([
                'order.user',
                'order.items.product',
                'order.items.variant',
                'order.items.selectedVolume',
                'stages.project',
                'stages.orderItem.product',
                'stages.orderItem.variant',
                'stages.orderItem.selectedVolume',
                'stages.employee',
                'assignments',
            ])->findOrFail($projectId);

            $isAssigned = $project->assignments()
                ->where('employee_id', $employee->id)
                ->exists();

            if (!$isAssigned) {
                return redirect('/pegawai/projects')
                    ->with('error', 'Project ini belum ditugaskan ke akun pegawai Anda.');
            }

            $stages = $this->getAvailableNextStages($project);

            return view('pegawai.work_updates.create', compact('stages', 'project'));
        }

        $project = null;

        $projects = Project::with([
            'order.user',
            'order.items.product',
            'order.items.variant',
            'order.items.selectedVolume',
            'stages.project',
            'stages.orderItem.product',
            'stages.orderItem.variant',
            'stages.orderItem.selectedVolume',
            'stages.employee',
            'assignments',
        ])
            ->whereHas('assignments', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->get();

        $stages = collect();

        foreach ($projects as $assignedProject) {
            $stages = $stages->merge($this->getAvailableNextStages($assignedProject));
        }

        return view('pegawai.work_updates.create', compact('stages', 'project'));
    }

    private function getAvailableNextStages(Project $project)
    {
        $stages = $project->stages()
            ->with([
                'project',
                'orderItem.product',
                'orderItem.variant',
                'orderItem.selectedVolume',
                'employee',
            ])
            ->orderBy('order_item_id')
            ->orderBy('id')
            ->get()
            ->groupBy(function ($stage) {
                return $stage->order_item_id ?? 'general';
            });

        $availableStages = collect();

        foreach ($stages as $group) {
            $nextStage = $group->first(function ($stage) {
                return $stage->status !== 'approved';
            });

            if ($nextStage && in_array($nextStage->status, ['todo', 'in_progress', 'rejected'])) {
                $availableStages->push($nextStage);
            }
        }

        return $availableStages;
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:project_stages,id',
            'work_note' => 'required|string',
            'documentation_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $employee = Employee::where('user_id', auth()->id())->firstOrFail();

        $stage = ProjectStage::with([
            'project.stages',
            'project.assignments',
            'orderItem.product',
            'orderItem.variant',
            'orderItem.selectedVolume',
        ])
            ->where('id', $request->stage_id)
            ->whereHas('project.assignments', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->firstOrFail();

        if (!in_array($stage->status, ['todo', 'in_progress', 'rejected'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'stage_id' => 'Tahap ini sedang menunggu validasi atau sudah disetujui.',
                ]);
        }

        $nextAllowedStage = $this->getNextAllowedStage($stage->project, $stage->order_item_id);

        if (!$nextAllowedStage || $nextAllowedStage->id !== $stage->id) {
            return back()
                ->withInput()
                ->withErrors([
                    'stage_id' => 'Tahapan harus dikerjakan sesuai urutan. Selesaikan tahap sebelumnya terlebih dahulu.',
                ]);
        }

        $documentationFile = null;

        if ($request->hasFile('documentation_file')) {
            $documentationFile = $request->file('documentation_file')->store('documentation', 'public');
        }

        $stage->update([
            'status' => 'pending_approval',
            'employee_id' => $employee->id,
            'note' => $request->work_note,
            'documentation_file' => $documentationFile,
        ]);

        EmployeeWorkUpdate::create([
            'project_id' => $stage->project_id,
            'project_stage_id' => $stage->id,
            'employee_id' => $employee->id,
            'progress_percent' => $stage->weight_percent,
            'work_note' => $request->work_note,
            'documentation_file' => $documentationFile,
            'validation_status' => 'pending',
        ]);

        if ($stage->project->status === 'not_started') {
            $stage->project->update([
                'status' => 'in_progress',
            ]);
        }

        return redirect('/pegawai/work-updates')
            ->with('success', 'Tahap pekerjaan berhasil dikirim dan menunggu validasi pimpinan.');
    }

    private function getNextAllowedStage(Project $project, $orderItemId)
    {
        return $project->stages()
            ->where(function ($q) use ($orderItemId) {
                if ($orderItemId) {
                    $q->where('order_item_id', $orderItemId);
                } else {
                    $q->whereNull('order_item_id');
                }
            })
            ->orderBy('id')
            ->get()
            ->first(function ($stage) {
                return $stage->status !== 'approved';
            });
    }
}