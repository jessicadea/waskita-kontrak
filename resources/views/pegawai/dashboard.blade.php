<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Dashboard Pegawai</h2>
        <p class="text-sm text-gray-500 mt-1">
            Ringkasan tugas project dan status update pekerjaan Anda.
        </p>
    </x-slot>

    <div class="space-y-6">

        {{-- WELCOME --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-sm">
            <h3 class="text-2xl font-bold">
                Selamat datang, {{ $employee->employee_name }} 👋
            </h3>
            <p class="text-blue-100 mt-2">
                Pantau tugas project, tahap pekerjaan, dan status validasi Anda.
            </p>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Project</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-2">{{ $projects->count() }}</h3>
            </div>

            <div class="bg-yellow-50 rounded-2xl border border-yellow-200 p-5 shadow-sm">
                <p class="text-sm text-yellow-700">Pending Approval</p>
                <h3 class="text-3xl font-bold text-yellow-700 mt-2">{{ $pendingStages->count() }}</h3>
            </div>

            <div class="bg-red-50 rounded-2xl border border-red-200 p-5 shadow-sm">
                <p class="text-sm text-red-700">Rejected</p>
                <h3 class="text-3xl font-bold text-red-700 mt-2">{{ $rejectedStages->count() }}</h3>
            </div>

            <div class="bg-green-50 rounded-2xl border border-green-200 p-5 shadow-sm">
                <p class="text-sm text-green-700">Bobot Disetujui</p>
                <h3 class="text-3xl font-bold text-green-700 mt-2">{{ $completedWeight }}%</h3>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- ACTIVE PROJECTS --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Project Aktif</h3>
                            <p class="text-sm text-gray-500">Project yang ditugaskan kepada Anda</p>
                        </div>

                        <a href="/pegawai/projects"
                           class="text-sm font-semibold text-blue-600 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($projects as $project)
                            <div class="p-5 hover:bg-slate-50 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h4 class="font-bold text-slate-900">
                                            {{ $project->project_name }}
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Deadline: {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
                                        </p>
                                    </div>

                                    @if($project->status === 'done')
                                        <span class="w-fit px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Selesai
                                        </span>
                                    @elseif($project->status === 'in_progress')
                                        <span class="w-fit px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                            Berjalan
                                        </span>
                                    @else
                                        <span class="w-fit px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-500">Progress</span>
                                        <span class="font-bold text-blue-600">
                                            {{ $project->progress_percent }}%
                                        </span>
                                    </div>

                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                             style="width: {{ $project->progress_percent }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                Belum ada project yang ditugaskan.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- QUICK ACTION --}}
                <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold mb-3">Aksi Cepat</h3>
                    <p class="text-sm text-slate-300 mb-5">
                        Kirim update tahap pekerjaan yang sudah selesai dikerjakan.
                    </p>

                    <a href="/pegawai/work-updates/create"
                       class="block text-center bg-blue-600 hover:bg-blue-700 rounded-xl px-4 py-3 text-sm font-semibold">
                        + Update Pekerjaan
                    </a>
                </div>

                {{-- RECENT UPDATES --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900">Update Terakhir</h3>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($updates as $update)
                            <div class="p-5">
                                <p class="font-semibold text-slate-900">
                                    {{ $update->stage->stage_name ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $update->project->project_name ?? '-' }}
                                </p>

                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        {{ $update->created_at->format('d M Y') }}
                                    </span>

                                    @if($update->validation_status === 'approved')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Approved
                                        </span>
                                    @elseif($update->validation_status === 'pending')
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-sm text-gray-500">
                                Belum ada update pekerjaan.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>