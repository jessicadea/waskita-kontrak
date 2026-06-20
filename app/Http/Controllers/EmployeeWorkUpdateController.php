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

        $updates = EmployeeWorkUpdate::with(['project', 'stage'])
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
            $project = Project::with('stages')->findOrFail($projectId);

            $isAssigned = $project->assignments()
                ->where('employee_id', $employee->id)
                ->exists();

            if (!$isAssigned) {
                return redirect('/pegawai/projects')
                    ->with('error', 'Project ini belum ditugaskan ke akun pegawai Anda.');
            }

            if ($project->stages()->count() === 0) {
                $this->createDefaultProjectStages($project);
            }

            $stages = $project->stages()
                ->whereIn('status', ['todo', 'in_progress', 'rejected'])
                ->orderBy('id')
                ->get();

            return view('pegawai.work_updates.create', compact('stages', 'project'));
        }

        $project = null;

        $stages = ProjectStage::with('project')
            ->whereHas('project.assignments', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->whereIn('status', ['todo', 'in_progress', 'rejected'])
            ->orderBy('project_id')
            ->orderBy('id')
            ->get();

        return view('pegawai.work_updates.create', compact('stages', 'project'));
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

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:project_stages,id',
            'work_note' => 'required|string',
            'documentation_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $employee = Employee::where('user_id', auth()->id())->firstOrFail();

        $stage = ProjectStage::with('project')
            ->where('id', $request->stage_id)
            ->whereHas('project.assignments', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->firstOrFail();

        if (!in_array($stage->status, ['todo', 'rejected'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'stage_id' => 'Tahap ini sudah sedang diproses atau sudah disetujui.',
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
}