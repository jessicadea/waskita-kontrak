<?php

namespace App\Http\Controllers;

use App\Models\EmployeeWorkUpdate;
use App\Models\ProjectProgressLog;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index()
    {
        $updates = EmployeeWorkUpdate::with(['project', 'employee', 'stage'])
            ->where('validation_status', 'pending')
            ->latest()
            ->get();

        return view('pimpinan.work_updates.index', compact('updates'));
    }

    public function show($id)
    {
        $update = EmployeeWorkUpdate::with(['project', 'employee', 'validator', 'stage'])
            ->findOrFail($id);

        return view('pimpinan.work_updates.show', compact('update'));
    }

    public function approve($id)
    {
        $update = EmployeeWorkUpdate::with(['project.stages', 'stage'])->findOrFail($id);

        if ($update->validation_status === 'approved') {
            return redirect('/pimpinan/work-updates')
                ->with('success', 'Update ini sudah pernah disetujui.');
        }

        $update->update([
            'validation_status' => 'approved',
            'validated_by' => auth()->id(),
            'validation_note' => null,
        ]);

        $project = $update->project;

        if ($update->stage) {
            $update->stage->update([
                'status' => 'approved',
                'note' => $update->work_note,
                'documentation_file' => $update->documentation_file,
            ]);

            // Auto aktifkan tahap berikutnya
            $nextStage = $project->stages()
                ->where('id', '>', $update->stage->id)
                ->where('status', 'todo')
                ->orderBy('id')
                ->first();

            if ($nextStage) {
                $nextStage->update([
                    'status' => 'in_progress',
                ]);
            }
        }

        $approvedProgress = $project->stages()
            ->where('status', 'approved')
            ->sum('weight_percent');

        $approvedProgress = min($approvedProgress, 100);

        if ($approvedProgress >= 100) {
            $status = 'done';
        } elseif ($approvedProgress > 0) {
            $status = 'in_progress';
        } else {
            $status = 'not_started';
        }

        $project->update([
            'progress_percent' => $approvedProgress,
            'status' => $status,
        ]);

        ProjectProgressLog::updateOrCreate(
            [
                'work_update_id' => $update->id,
            ],
            [
                'project_id' => $project->id,
                'progress_percent' => $approvedProgress,
                'note' => $update->stage
                    ? $update->stage->stage_name . ' selesai. ' . $update->work_note
                    : $update->work_note,
                'updated_by' => auth()->id(),
            ]
        );

        return redirect('/pimpinan/work-updates')
            ->with('success', 'Tahap pekerjaan disetujui. Tahap berikutnya otomatis aktif.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'validation_note' => 'required|string',
        ]);

        $update = EmployeeWorkUpdate::with('stage')->findOrFail($id);

        $update->update([
            'validation_status' => 'rejected',
            'validated_by' => auth()->id(),
            'validation_note' => $request->validation_note,
        ]);

        if ($update->stage) {
            $update->stage->update([
                'status' => 'rejected',
            ]);
        }

        return redirect('/pimpinan/work-updates')
            ->with('success', 'Update pekerjaan berhasil ditolak. Tahap dikembalikan untuk diperbaiki.');
    }
}