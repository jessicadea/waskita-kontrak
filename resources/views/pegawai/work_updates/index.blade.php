<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Riwayat Update Pekerjaan</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pantau status validasi update pekerjaan Anda.
        </p>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('pegawai.work-updates.create') }}"
        class="inline-flex px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            + Update Tahap
        </a>

        <x-ui.card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-gray-600">
                            <th class="py-3 px-3">Project</th>
                            <th class="px-3">Tahap</th>
                            <th class="px-3">Bobot</th>
                            <th class="px-3">Dokumentasi</th>
                            <th class="px-3">Status</th>
                            <th class="px-3">Catatan Validasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($updates as $update)
                            <tr class="border-b hover:bg-slate-50 transition">
                                
                                {{-- PROJECT --}}
                                <td class="py-3 px-3 font-semibold text-slate-900">
                                    {{ $update->project->project_name }}
                                </td>

                                {{-- TAHAP --}}
                                <td class="px-3">
                                    <div class="font-medium text-slate-800">
                                        {{ $update->stage->stage_name ?? '-' }}
                                    </div>

                                    @if($update->work_note)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ Str::limit($update->work_note, 40) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- BOBOT --}}
                                <td class="px-3">
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        {{ $update->stage->weight_percent ?? $update->progress_percent }}%
                                    </span>
                                </td>

                                {{-- FILE --}}
                                <td class="px-3">
                                    @if($update->documentation_file)
                                        <a href="{{ asset('storage/'.$update->documentation_file) }}"
                                           target="_blank"
                                           class="text-blue-600 font-medium hover:underline">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-3">
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
                                </td>

                                {{-- VALIDATION NOTE --}}
                                <td class="px-3 text-gray-600">
                                    {{ $update->validation_note ?? '-' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500">
                                    Belum ada update pekerjaan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>

    </div>
</x-app-layout>