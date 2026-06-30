<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Project Aktif</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pantau daftar project berdasarkan status dan periode waktu.
        </p>
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Project</p>
                <h3 class="text-3xl font-black mt-2">{{ $summary['total'] }}</h3>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-500">Belum Mulai</p>
                <h3 class="text-3xl font-black mt-2 text-slate-700">{{ $summary['not_started'] }}</h3>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-orange-500">Berjalan</p>
                <h3 class="text-3xl font-black mt-2 text-orange-600">{{ $summary['in_progress'] }}</h3>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-green-600">Selesai</p>
                <h3 class="text-3xl font-black mt-2 text-green-600">{{ $summary['done'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h3 class="font-bold text-lg text-slate-900">Filter Project</h3>
            <p class="text-sm text-gray-500 mt-1">
                Kategorikan project berdasarkan status pekerjaan dan waktu pembuatan.
            </p>

            <form method="GET" action="{{ route('client.projects.index') }}"
                  class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-5 items-end">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Status Project</label>
                    <select name="status"
                            class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm">
                        <option value="">Semua Status</option>
                        <option value="not_started" {{ request('status') === 'not_started' ? 'selected' : '' }}>
                            Belum Mulai
                        </option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>
                            Berjalan
                        </option>
                        <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Periode Waktu</label>
                    <select name="period"
                            class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm">
                        <option value="">Semua Periode</option>
                        <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>
                            Hari Ini
                        </option>
                        <option value="this_week" {{ request('period') === 'this_week' ? 'selected' : '' }}>
                            Minggu Ini
                        </option>
                        <option value="this_month" {{ request('period') === 'this_month' ? 'selected' : '' }}>
                            Bulan Ini
                        </option>
                        <option value="last_3_months" {{ request('period') === 'last_3_months' ? 'selected' : '' }}>
                            3 Bulan Terakhir
                        </option>
                        <option value="this_year" {{ request('period') === 'this_year' ? 'selected' : '' }}>
                            Tahun Ini
                        </option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Terapkan
                    </button>

                    <a href="{{ route('client.projects.index') }}"
                       class="px-5 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($projects as $project)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <p class="text-xs text-gray-500">
                                PRJ-{{ $project->created_at->format('Y') }}-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                            </p>

                            <h3 class="font-bold text-lg text-slate-900 mt-1">
                                {{ $project->project_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Deadline: {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
                            </p>
                        </div>

                        @if($project->status === 'done')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Selesai
                            </span>
                        @elseif($project->status === 'in_progress')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                Berjalan
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Belum Mulai
                            </span>
                        @endif
                    </div>

                    <div class="mt-5">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-700">Progress</span>
                            <span class="text-sm font-bold text-blue-600">{{ $project->progress_percent }}%</span>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full bg-blue-600"
                                 style="width: {{ $project->progress_percent }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-between items-center">
                        <p class="text-xs text-gray-500">
                            Dibuat: {{ $project->created_at->format('d M Y') }}
                        </p>

                        <a href="/client/projects/{{ $project->id }}/monitoring"
                           class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                            Monitoring
                        </a>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
                    <div class="text-4xl mb-3">📭</div>
                    <p class="font-bold text-slate-900">Project tidak ditemukan</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Coba ubah filter status atau periode waktu.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>