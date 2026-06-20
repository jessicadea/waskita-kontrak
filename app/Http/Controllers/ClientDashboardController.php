<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalOrders = Order::where('user_id', $userId)->count();

        $pendingOrders = Order::where('user_id', $userId)
            ->where('status_verify', 'pending')
            ->count();

        $activeProjects = Project::whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'in_progress')
            ->count();

        $doneProjects = Project::whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'done')
            ->count();

        $latestOrders = Order::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'activeProjects',
            'doneProjects',
            'latestOrders'
        ));
    }
}