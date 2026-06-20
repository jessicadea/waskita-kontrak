<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Project Saya</h2>
        <p class="text-sm text-gray-500 mt-1">
            Daftar project yang sedang Anda kerjakan.
        </p>
    </x-slot>

    <div class="space-y-6">

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Project</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-2">
                    {{ $projects->count() }}
                </h3>
            </div>

            <div class="bg-orange-50 rounded-2xl border border-orange-200 p-5 shadow-sm">
                <p class="text-sm text-orange-600">Sedang Berjalan</p>
                <h3 class="text-3xl font-bold text-orange-700 mt-2">
                    {{ $projects->where('status', 'in_progress')->count() }}
                </h3>
            </div>

            <div class="bg-green-50 rounded-2xl border border-green-200 p-5 shadow-sm">
                <p class="text-sm text-green-600">Selesai</p>
                <h3 class="text-3xl font-bold text-green-700 mt-2">
                    {{ $projects->where('status', 'done')->count() }}
                </h3>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">
                    Daftar Tugas Project
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="text-left">
                            <th class="px-6 py-4 font-semibold">Project</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Progress</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50 transition">

                                {{-- PROJECT --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $project->project_name }}
                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">
                                            Deadline:
                                            {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
                                        </p>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">

                                    @if($project->status === 'done')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Selesai
                                        </span>

                                    @elseif($project->status === 'in_progress')
                                        <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                            Berjalan
                                        </span>

                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                            Belum Mulai
                                        </span>
                                    @endif

                                </td>

                                {{-- PROGRESS --}}
                                <td class="px-6 py-4 w-72">

                                    <div class="flex justify-between text-xs mb-2">
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

                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">

                                    <a href="/pegawai/projects/{{ $project->id }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada project yang ditugaskan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

    </div>
</x-app-layout>