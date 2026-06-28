<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(): View
    {
        $users = User::where('role', 'client')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function approve($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_approved' => true,
        ]);

        return back()->with('success', 'Akun berhasil disetujui.');
    }

    public function reject($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success', 'Akun berhasil ditolak dan dihapus.');
    }
}