<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'supervisor'])->latest()->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $request->employee_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'is_approved' => true,
        ]);

        Employee::create([
            'user_id' => $user->id,
            'supervisor_id' => null,
            'employee_name' => $request->employee_name,
            'position' => 'Mandor',
            'status' => $request->status,
        ]);

        return redirect('/admin/employees')
            ->with('success', 'Mandor berhasil ditambahkan. Akun login otomatis dibuat dengan password default: password');
    }
}