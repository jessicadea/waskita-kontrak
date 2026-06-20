@php
    $borderColor = match($color) {
        'orange' => 'border-l-orange-500',
        'yellow' => 'border-l-yellow-500',
        'green' => 'border-l-green-500',
        default => 'border-l-slate-900',
    };

    $badgeColor = match($color) {
        'orange' => 'bg-orange-100 text-orange-700',
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'green' => 'bg-green-100 text-green-700',
        default => 'bg-slate-100 text-slate-700',
    };

    $pendingUpdates = $project->workUpdates->where('validation_status', 'pending')->count();
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 border-l-4 {{ $borderColor }} p-4 hover:shadow-md transition">
    <div class="flex justify-between gap-3">
        <div>
            <h4 class="font-bold text-slate-900">
                {{ $project->project_name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ $project->order->company_name ?? $project->order->user->name }}
            </p>
        </div>

        <span class="h-fit px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
        </span>
    </div>

    <div class="mt-4">
        <div class="flex justify-between text-xs mb-1">
            <span class="text-gray-500">Progress</span>
            <span class="font-semibold text-slate-900">{{ $project->progress_percent }}%</span>
        </div>

        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-slate-900 h-2 rounded-full"
                 style="width: {{ $project->progress_percent }}%">
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
        <span>👥 {{ $project->assignments->count() }} pegawai</span>
        <span>📅 {{ $project->due_date }}</span>
    </div>

    @if($pendingUpdates > 0)
        <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl p-3 text-xs font-semibold">
            {{ $pendingUpdates }} update menunggu validasi
        </div>
    @endif

    <div class="mt-4">
        <a href="/pimpinan/work-updates"
           class="block text-center bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-3 py-2 text-xs font-semibold">
            Lihat Validasi
        </a>
    </div>
</div>