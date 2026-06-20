<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;

class ProjectAssignmentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assignment_role' => 'nullable|string|max:255',
        ]);

        ProjectAssignment::create([
            'project_id' => $id,
            'employee_id' => $request->employee_id,
            'role' => 'Mandor',
        ]);

        return back()->with('success', 'Pegawai berhasil ditugaskan ke project.');
    }
}