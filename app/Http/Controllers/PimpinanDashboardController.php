<?php

namespace App\Http\Controllers;

use App\Models\EmployeeWorkUpdate;
use App\Models\Project;

class PimpinanDashboardController extends Controller
{
    public function index()
    {
        $pendingUpdates = EmployeeWorkUpdate::where('validation_status', 'pending')->count();
        $approvedUpdates = EmployeeWorkUpdate::where('validation_status', 'approved')->count();
        $rejectedUpdates = EmployeeWorkUpdate::where('validation_status', 'rejected')->count();
        $activeProjects = Project::where('status', 'in_progress')->count();

        $latestUpdates = EmployeeWorkUpdate::with(['project', 'employee'])
            ->latest()
            ->take(5)
            ->get();

        return view('pimpinan.dashboard', compact(
            'pendingUpdates',
            'approvedUpdates',
            'rejectedUpdates',
            'activeProjects',
            'latestUpdates'
        ));
    }
}