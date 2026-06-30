<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Laporan Project</h2>
        <p class="text-sm text-gray-500 mt-1">
            Unduh laporan progress project berdasarkan pesanan yang sudah menjadi project.
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b">
            <h3 class="font-bold text-lg">Daftar Laporan</h3>
            <p class="text-sm text-gray-500">Pilih project untuk mengunduh laporan progress.</p>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($projects as $project)
                <div class="p-5 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-900">{{ $project->project_name }}</p>
                        <p class="text-sm text-gray-500">
                            Progress: {{ $project->progress_percent }}% •
                            Deadline: {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
                        </p>
                    </div>

                    <a href="/reports/projects/{{ $project->id }}/download"
                       class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold">
                        Download PDF
                    </a>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    Belum ada laporan project.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>