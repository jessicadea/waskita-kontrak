<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfYear();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfYear();

        $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');

        $orderQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $projectQuery = Project::whereBetween('created_at', [$startDate, $endDate]);

        $totalOrders = (clone $orderQuery)->count();
        $pending = (clone $orderQuery)->where('status_verify', 'pending')->count();
        $approved = (clone $orderQuery)->where('status_verify', 'approved')->count();
        $rejected = (clone $orderQuery)->where('status_verify', 'rejected')->count();

        $completedProjects = (clone $projectQuery)->where('status', 'done')->count();

        $totalClients = User::where('role', 'client')->count();

        $projectNotStarted = Project::where('status', 'not_started')->count();
        $projectInProgress = Project::where('status', 'in_progress')->count();
        $projectDone = Project::where('status', 'done')->count();

        /*
        |--------------------------------------------------------------------------
        | Perlu Tindakan
        |--------------------------------------------------------------------------
        */
        $pendingOrders = Order::where('status_verify', 'pending')->count();

        $pendingClients = User::where('role', 'client')
            ->where('is_approved', false)
            ->count();

        $deadlineProjects = Project::where('status', '!=', 'done')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->count();

        $latestOrders = Order::with([
            'user',
            'product',
            'variant',
            'items.product',
            'items.variant',
            'items.selectedVolume',
        ])
            ->latest()
            ->take(5)
            ->get();

        $monthlyOrders = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $trendLabels = [];
        $trendData = [];

        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($cursor <= $lastMonth) {
            $label = $cursor->translatedFormat('M Y');

            $found = $monthlyOrders->first(function ($item) use ($cursor) {
                return (int) $item->year === (int) $cursor->year
                    && (int) $item->month === (int) $cursor->month;
            });

            $trendLabels[] = $label;
            $trendData[] = $found ? (int) $found->total : 0;

            $cursor->addMonth();
        }

        $peakSeasonLabel = '-';
        $peakSeasonTotal = 0;

        if (count($trendData) > 0 && max($trendData) > 0) {
            $peakIndex = array_search(max($trendData), $trendData);
            $peakSeasonLabel = $trendLabels[$peakIndex];
            $peakSeasonTotal = $trendData[$peakIndex];
        }

        $topProducts = OrderItem::select(
                'products.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(order_items.id) as total_order_item')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.product_name')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $topProductName = $topProducts->first()->product_name ?? '-';
        $topProductQty = $topProducts->first()->total_quantity ?? 0;

        return view('admin.dashboard', compact(
            'startDate',
            'endDate',
            'periodLabel',
            'totalOrders',
            'pending',
            'approved',
            'rejected',
            'completedProjects',
            'totalClients',
            'projectNotStarted',
            'projectInProgress',
            'projectDone',
            'pendingOrders',
            'pendingClients',
            'deadlineProjects',
            'latestOrders',
            'trendLabels',
            'trendData',
            'peakSeasonLabel',
            'peakSeasonTotal',
            'topProducts',
            'topProductName',
            'topProductQty'
        ));
    }
}