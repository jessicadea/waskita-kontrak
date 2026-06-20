<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pending = Order::where('status_verify', 'pending')->count();
        $approved = Order::where('status_verify', 'approved')->count();
        $rejected = Order::where('status_verify', 'rejected')->count();

        $projectNotStarted = Project::where('status', 'not_started')->count();
        $projectInProgress = Project::where('status', 'in_progress')->count();
        $projectDone = Project::where('status', 'done')->count();

        $latestOrders = Order::with(['user','product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pending',
            'approved',
            'rejected',
            'projectNotStarted',
            'projectInProgress',
            'projectDone',
            'latestOrders'
        ));
    }
}