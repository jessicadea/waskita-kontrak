<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Detail Validasi Progress</h2>
        <p class="text-sm text-gray-500 mt-1">
            Validasi progress pekerjaan pegawai dan approval tahapan project.
        </p>
    </x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- MAIN --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- INFO --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">
                            {{ $update->project->project_name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            Update Progress Project
                        </p>
                    </div>

                    @if($update->validation_status == 'approved')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            Approved
                        </span>
                    @elseif($update->validation_status == 'rejected')
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            Rejected
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                            Pending
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-gray-500">Pegawai</p>
                        <p class="font-semibold">
                            {{ $update->employee->employee_name }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-gray-500">Tahap Project</p>
                        <p class="font-semibold">
                            {{ $update->stage->stage_name ?? '-' }}
                        </p>

                        @if($update->stage)
                            <p class="text-xs text-blue-600 mt-1">
                                Bobot {{ $update->stage->weight_percent }}%
                            </p>
                        @endif
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-gray-500">Progress Sebelum</p>
                        <p class="font-semibold text-slate-900">
                            {{ $update->project->progress_percent }}%
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-gray-500">Progress Diajukan</p>
                        <p class="font-bold text-blue-600 text-lg">
                            {{ $update->progress_percent }}%
                        </p>
                    </div>

                </div>

                {{-- NOTE --}}
                <div class="mt-6">
                    <p class="font-semibold mb-2">Catatan Pekerjaan</p>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-700">
                        {{ $update->work_note }}
                    </div>
                </div>

                {{-- FILE --}}
                @if($update->documentation_file)
                    <div class="mt-6">
                        <p class="font-semibold mb-2">Dokumentasi</p>

                        <a href="{{ asset('storage/'.$update->documentation_file) }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold">
                            Lihat Dokumentasi
                        </a>
                    </div>
                @endif
            </div>

            {{-- VALIDASI --}}
            @if($update->validation_status == 'pending')

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">

                    <h3 class="text-lg font-bold mb-5">
                        Approval Pimpinan
                    </h3>

                    <div class="flex flex-wrap gap-3">

                        {{-- APPROVE --}}
                        <form method="POST"
                              action="/pimpinan/work-updates/{{ $update->id }}/approve">
                            @csrf

                            <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-sm font-semibold">
                                Approve Progress
                            </button>
                        </form>

                        {{-- REJECT --}}
                        <form method="POST"
                              action="/pimpinan/work-updates/{{ $update->id }}/reject"
                              class="flex-1">
                            @csrf

                            <textarea name="validation_note"
                                      rows="3"
                                      class="w-full rounded-xl border-slate-200 text-sm"
                                      placeholder="Alasan penolakan..."></textarea>

                            <button class="mt-3 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-sm font-semibold">
                                Reject Progress
                            </button>
                        </form>

                    </div>

                </div>

            @else

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-lg font-bold mb-3">Status Validasi</h3>

                    <p class="text-sm text-slate-600">
                        Update ini sudah divalidasi oleh:
                        <span class="font-semibold">
                            {{ $update->validator->name ?? 'Pimpinan' }}
                        </span>
                    </p>

                    @if($update->validation_note)
                        <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                            {{ $update->validation_note }}
                        </div>
                    @endif
                </div>

            @endif

        </div>

        {{-- SIDE --}}
        <div class="space-y-6">

            {{-- TIMELINE --}}
            <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm">

                <h3 class="text-lg font-bold mb-5">
                    Timeline Validasi
                </h3>

                <div class="space-y-5 text-sm">

                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-400 mt-1"></div>

                        <div>
                            <p class="font-semibold">
                                Update Dikirim
                            </p>

                            <p class="text-slate-400 text-xs">
                                {{ $update->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($update->validation_status != 'pending')
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 rounded-full bg-green-400 mt-1"></div>

                            <div>
                                <p class="font-semibold">
                                    Sudah Divalidasi
                                </p>

                                <p class="text-slate-400 text-xs">
                                    {{ $update->updated_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>
</x-app-layout>