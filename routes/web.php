<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PegawaiDashboardController;
use App\Http\Controllers\PimpinanDashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\EmployeeWorkUpdateController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\CompletionReportController;
use App\Http\Controllers\Admin\UserApprovalController;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| ROLE REDIRECT DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'admin' => redirect('/admin/dashboard'),
        'pegawai' => redirect('/pegawai/dashboard'),
        'pimpinan' => redirect('/pimpinan/dashboard'),
        default => redirect('/client/dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [OrderController::class, 'clientIndex'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

        Route::get('/kontrak', [OrderController::class, 'contracts'])->name('contracts.index');

        Route::get('/projects', [ProjectController::class, 'clientProjects'])->name('projects.index');
        Route::get('/projects/{id}/monitoring', [ProjectController::class, 'monitoring'])->name('projects.monitoring');

        Route::get('/laporan', [CompletionReportController::class, 'clientReports'])->name('reports.index');
    });

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | ADMIN - PESANAN
        |--------------------------------------------------------------------------
        */
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/verify', [OrderController::class, 'verify'])->name('orders.verify');
        Route::post('/orders/{id}/contract', [OrderController::class, 'uploadContract'])->name('orders.contract');

        /*
        |--------------------------------------------------------------------------
        | ADMIN - PROJECT
        |--------------------------------------------------------------------------
        */
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create/{order_id}', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
        Route::post('/projects/{id}/assign', [ProjectAssignmentController::class, 'store'])->name('projects.assign');

        Route::get('/project-board', [ProjectController::class, 'board'])->name('projects.board');

        // Route::get('/admin/projects', [ProjectController::class, 'index'])
        // ->name('admin.projects.index');

        /*
        |--------------------------------------------------------------------------
        | ADMIN - CRUD PEGAWAI
        |--------------------------------------------------------------------------
        */
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        /*
        |--------------------------------------------------------------------------
        | ADMIN - APPROVAL USER CLIENT
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [UserApprovalController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
        Route::delete('/users/{id}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    });

/*
|--------------------------------------------------------------------------
| PEGAWAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

        Route::get('/projects', [ProjectController::class, 'pegawaiProjects'])->name('projects.index');
        Route::get('/projects/{id}', [ProjectController::class, 'pegawaiShow'])->name('projects.show');

        Route::get('/work-updates', [EmployeeWorkUpdateController::class, 'index'])->name('work-updates.index');
        Route::get('/work-updates/create', [EmployeeWorkUpdateController::class, 'create'])->name('work-updates.create');
        Route::post('/work-updates', [EmployeeWorkUpdateController::class, 'store'])->name('work-updates.store');

        Route::get('/admin/employees', [EmployeeController::class, 'index'])
        ->name('admin.employees.index');
    });

/*
|--------------------------------------------------------------------------
| PIMPINAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pimpinan'])
    ->prefix('pimpinan')
    ->name('pimpinan.')
    ->group(function () {
        Route::get('/dashboard', [PimpinanDashboardController::class, 'index'])->name('dashboard');

        Route::get('/work-updates', [ValidationController::class, 'index'])->name('work-updates.index');
        Route::get('/work-updates/{id}', [ValidationController::class, 'show'])->name('work-updates.show');
        Route::post('/work-updates/{id}/approve', [ValidationController::class, 'approve'])->name('work-updates.approve');
        Route::post('/work-updates/{id}/reject', [ValidationController::class, 'reject'])->name('work-updates.reject');

        Route::get('/projects', [ProjectController::class, 'pimpinanProjects'])->name('projects.index');
    });

/*
|--------------------------------------------------------------------------
| REPORT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/reports', [CompletionReportController::class, 'store'])->name('reports.store');

    Route::get('/reports/projects/{id}/download', [CompletionReportController::class, 'downloadProjectReport'])
        ->name('reports.projects.download');
});

/*
|--------------------------------------------------------------------------
| API INTERNAL - PRODUCT VARIANTS
|--------------------------------------------------------------------------
*/
Route::get('/api/products/{id}/variants', function ($id) {
    return \App\Models\Product::with('variants.volumes')->findOrFail($id);
});

/*
|--------------------------------------------------------------------------
| API INTERNAL - ESTIMASI
|--------------------------------------------------------------------------
*/
Route::get('/api/estimate-workers', function (Request $request) {
    $request->validate([
        'product_id' => 'required',
        'variant_id' => 'required',
        'volume_id' => 'required',
        'quantity' => 'required|integer|min:1',
        'delivery_date' => 'required|date|after_or_equal:' . now()->addDays(30)->format('Y-m-d'),
    ]);

    $standard = \App\Models\ProductWorkStandard::where('product_id', $request->product_id)
        ->where('variant_id', $request->variant_id)
        ->where('volume_id', $request->volume_id)
        ->first();

    if (!$standard) {
        $standard = (object) [
            'capacity_per_day' => 10,
            'production_lead_days' => 30,
            'base_price_per_unit' => 1000000,
        ];
    }

    $today = \Carbon\Carbon::today();
    $deliveryDate = \Carbon\Carbon::parse($request->delivery_date);

    $availableDays = max($today->diffInDays($deliveryDate), 1);

    $capacityPerDay = max((int) $standard->capacity_per_day, 1);
    $normalLeadDays = max(30, (int) $standard->production_lead_days);

    $productionDays = (int) ceil($request->quantity / $capacityPerDay);
    $usagePercent = (int) round(($productionDays / $availableDays) * 100);

    $totalSdm = 18;

    $basePricePerUnit = isset($standard->base_price_per_unit)
        ? (int) $standard->base_price_per_unit
        : 1000000;

    $basePrice = (int) $request->quantity * $basePricePerUnit;

    $isAccelerated = $productionDays > $availableDays;

    $accelerationPercent = 0;
    $accelerationFee = 0;

    if ($isAccelerated) {
        $accelerationPercent = (int) round((($productionDays - $availableDays) / $productionDays) * 100);
        $accelerationFee = (int) round($basePrice * ($accelerationPercent / 100) * 0.5);
    }

    $totalEstimatedCost = $basePrice + $accelerationFee;

    if ($usagePercent <= 70) {
        $status = 'safe';
        $message = 'Produksi berada dalam kondisi aman.';
        $color = 'green';
    } elseif ($usagePercent <= 100) {
        $status = 'warning';
        $message = 'Waktu produksi cukup ketat, disarankan meninjau jadwal produksi.';
        $color = 'yellow';
    } else {
        $status = 'danger';
        $message = 'Waktu produksi tidak mencukupi, diperlukan percepatan produksi.';
        $color = 'red';
    }

    return response()->json([
        'capacity_per_day' => $capacityPerDay,
        'available_days' => $availableDays,
        'production_days' => $productionDays,
        'usage_percent' => $usagePercent,
        'normal_lead_days' => $normalLeadDays,
        'total_sdm' => $totalSdm,
        'base_price' => $basePrice,
        'acceleration_percent' => $accelerationPercent,
        'acceleration_fee' => $accelerationFee,
        'total_estimated_cost' => $totalEstimatedCost,
        'is_accelerated' => $isAccelerated,
        'status' => $status,
        'message' => $message,
        'color' => $color,
    ]);
});

require __DIR__ . '/auth.php';