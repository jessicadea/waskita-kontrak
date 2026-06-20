<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class CompletionReportController extends Controller
{
    public function store()
    {
        return back()->with('success', 'Laporan berhasil disimpan.');
    }

    public function downloadProjectReport($id)
    {
        $project = Project::with([
            'order.user',
            'order.product',
            'assignments.employee',
            'logs',
            'workUpdates.employee',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('reports.project_pdf', compact('project'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-project-' . $project->id . '.pdf');
    }
}