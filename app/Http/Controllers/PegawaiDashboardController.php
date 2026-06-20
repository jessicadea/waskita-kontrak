<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeWorkUpdate;
use App\Models\Project;
use Illuminate\Http\Request;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee) {
            return redirect()
                ->route('profile.edit')
                ->with('error', 'Akun pegawai belum terhubung dengan data pegawai.');
        }

        $projects = Project::with(['order', 'stages', 'assignments.employee'])
            ->whereHas('assignments', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->latest()
            ->get();

        $updates = EmployeeWorkUpdate::with(['project', 'stage'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        $stages = $projects->flatMap(function ($project) {
            return $project->stages->map(function ($stage) use ($project) {
                $stage->project_name = $project->project_name;
                $stage->project_due_date = $project->due_date;
                return $stage;
            });
        });

        $activeStages = $stages->whereIn('status', ['todo', 'in_progress', 'rejected']);
        $pendingStages = $stages->where('status', 'pending_approval');
        $rejectedStages = $stages->where('status', 'rejected');
        $approvedStages = $stages->where('status', 'approved');

        $completedWeight = $approvedStages->sum('weight_percent');

        return view('pegawai.dashboard', compact(
            'employee',
            'projects',
            'updates',
            'activeStages',
            'pendingStages',
            'rejectedStages',
            'completedWeight'
        ));
    }
}