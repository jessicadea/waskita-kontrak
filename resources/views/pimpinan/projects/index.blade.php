<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Monitoring Project</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pantau status project dan progress yang membutuhkan validasi.
        </p>
    </x-slot>

    @php
        $needApprovalProjects = $projects->filter(function ($project) {
            return $project->workUpdates->where('validation_status', 'pending')->count() > 0
                || $project->stages->where('status', 'pending_approval')->count() > 0;
        });

        $todoProjects = $projects->where('status', 'not_started');

        $inProgressProjects = $projects->filter(function ($project) {
            return $project->status === 'in_progress'
                && $project->workUpdates->where('validation_status', 'pending')->count() == 0
                && $project->stages->where('status', 'pending_approval')->count() == 0;
        });

        $doneProjects = $projects->where('status', 'done');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-5">

        {{-- TO DO --}}
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-900">To Do</h3>
                <span class="bg-slate-200 text-slate-700 px-2 py-1 rounded-full text-xs">
                    {{ $todoProjects->count() }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($todoProjects as $project)
                    @include('pimpinan.projects.partials.project-card', ['project' => $project, 'color' => 'slate'])
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">Belum ada project.</p>
                @endforelse
            </div>
        </div>

        {{-- IN PROGRESS --}}
        <div class="bg-orange-50 rounded-2xl border border-orange-200 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-orange-900">In Progress</h3>
                <span class="bg-orange-200 text-orange-700 px-2 py-1 rounded-full text-xs">
                    {{ $inProgressProjects->count() }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($inProgressProjects as $project)
                    @include('pimpinan.projects.partials.project-card', ['project' => $project, 'color' => 'orange'])
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">Belum ada project berjalan.</p>
                @endforelse
            </div>
        </div>

        {{-- NEED APPROVAL --}}
        <div class="bg-yellow-50 rounded-2xl border border-yellow-200 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-yellow-900">Need Approval</h3>
                <span class="bg-yellow-200 text-yellow-700 px-2 py-1 rounded-full text-xs">
                    {{ $needApprovalProjects->count() }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($needApprovalProjects as $project)
                    @include('pimpinan.projects.partials.project-card', ['project' => $project, 'color' => 'yellow'])
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">Belum ada yang perlu approval.</p>
                @endforelse
            </div>
        </div>

        {{-- DONE --}}
        <div class="bg-green-50 rounded-2xl border border-green-200 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-green-900">Done</h3>
                <span class="bg-green-200 text-green-700 px-2 py-1 rounded-full text-xs">
                    {{ $doneProjects->count() }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($doneProjects as $project)
                    @include('pimpinan.projects.partials.project-card', ['project' => $project, 'color' => 'green'])
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">Belum ada project selesai.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>