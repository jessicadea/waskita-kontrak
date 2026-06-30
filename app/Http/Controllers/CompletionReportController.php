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

    public function clientReports()
    {
        $projects = \App\Models\Project::with(['order', 'stages'])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('client.reports.index', compact('projects'));
    }
}