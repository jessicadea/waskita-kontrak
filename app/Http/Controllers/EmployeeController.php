<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'supervisor'])
            ->latest()
            ->get();

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
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $password = $request->filled('password')
                ? $request->password
                : 'password';

            $user = User::create([
                'name' => $request->employee_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($password),
                'role' => 'pegawai',
                'is_approved' => true,
            ]);

            Employee::create([
                'user_id' => $user->id,
                'supervisor_id' => null,
                'employee_name' => $request->employee_name,
                'position' => $request->position ?: 'Mandor',
                'status' => $request->status,
            ]);
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan. Akun login otomatis dibuat dengan password default: password');
    }

    public function edit($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $request->validate([
            'employee_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($request, $employee) {
            $employee->update([
                'employee_name' => $request->employee_name,
                'position' => $request->position ?: 'Mandor',
                'status' => $request->status,
            ]);

            if ($employee->user) {
                $userData = [
                    'name' => $request->employee_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'role' => 'pegawai',
                    'is_approved' => true,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $employee->user->update($userData);
            }
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        /*
        | Pegawai tidak dihapus permanen.
        | Lebih aman dinonaktifkan agar riwayat project, assignment,
        | dan update progress tidak rusak.
        */
        DB::transaction(function () use ($employee) {
            $employee->update([
                'status' => 'inactive',
            ]);
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil dinonaktifkan.');
    }
}