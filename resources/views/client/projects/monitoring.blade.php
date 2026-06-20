<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Monitoring Project</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pantau tahapan produksi, progress, dan dokumentasi project Anda.
        </p>
    </x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- MAIN CONTENT --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- PROJECT OVERVIEW --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <p class="text-sm text-gray-500">
                            PRJ-{{ $project->created_at->format('Y') }}-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                        </p>

                        <h3 class="text-xl font-bold text-slate-900 mt-1">
                            {{ $project->project_name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $project->order->product->product_name ?? '-' }}
                            @if($project->order->variant)
                                • {{ $project->order->variant->type_name }}
                            @endif
                            @if($project->order->selectedVolume)
                                • {{ $project->order->selectedVolume->volume_value }} {{ $project->order->selectedVolume->unit }}
                            @endif
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Progress</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">
                            {{ $project->progress_percent }}%
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">
                            {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Total Tahap</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">
                            {{ $project->stages->count() }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex justify-between mb-2">
                        <p class="font-semibold text-slate-900">Progress keseluruhan</p>
                        <p class="font-bold text-orange-500">{{ $project->progress_percent }}%</p>
                    </div>

                    <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                        <div class="h-3 rounded-full bg-[#0F172A] transition-all duration-700"
                             style="width: {{ $project->progress_percent }}%">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAHAPAN PRODUKSI --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tahapan Produksi</h3>
                        <p class="text-sm text-gray-500">
                            Tracking progress berdasarkan tahapan pekerjaan yang sudah divalidasi.
                        </p>
                    </div>

                    <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-sm font-semibold">
                        {{ $project->stages->where('status', 'approved')->sum('weight_percent') }}% Approved
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($project->stages as $stage)
                        @php
                            $statusClass = match($stage->status) {
                                'approved' => 'bg-green-100 text-green-700 border-green-200',
                                'pending_approval' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'in_progress' => 'bg-orange-100 text-orange-700 border-orange-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };

                            $iconClass = match($stage->status) {
                                'approved' => 'bg-green-500',
                                'pending_approval' => 'bg-yellow-500',
                                'in_progress' => 'bg-orange-500',
                                'rejected' => 'bg-red-500',
                                default => 'bg-slate-300',
                            };

                            $icon = match($stage->status) {
                                'approved' => '✓',
                                'pending_approval' => '!',
                                'in_progress' => '↻',
                                'rejected' => '×',
                                default => '•',
                            };

                            $label = match($stage->status) {
                                'approved' => 'Selesai',
                                'pending_approval' => 'Menunggu Validasi',
                                'in_progress' => 'Sedang Dikerjakan',
                                'rejected' => 'Perlu Revisi',
                                default => 'Belum Dikerjakan',
                            };
                        @endphp

                        <div class="border border-slate-200 rounded-2xl p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-full {{ $iconClass }} text-white flex items-center justify-center font-bold">
                                        {{ $icon }}
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-900">
                                            {{ $stage->stage_name }}
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Bobot progress {{ $stage->weight_percent }}%
                                        </p>

                                        @if($stage->note)
                                            <p class="text-sm text-gray-600 mt-2">
                                                {{ $stage->note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col md:items-end gap-2">
                                    <span class="px-3 py-1 rounded-full border text-xs font-semibold {{ $statusClass }}">
                                        {{ $label }}
                                    </span>

                                    @if($stage->documentation_file)
                                        <a href="{{ asset('storage/'.$stage->documentation_file) }}"
                                           target="_blank"
                                           class="text-xs font-semibold text-blue-600 hover:underline">
                                            Lihat Dokumentasi
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500">
                            Belum ada tahapan project.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TIMELINE LOG --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-5">Riwayat Progress</h3>

                <div class="relative pl-8 space-y-7">
                    <div class="absolute left-[11px] top-2 bottom-2 w-px bg-slate-200"></div>

                    <div class="relative">
                        <div class="absolute -left-8 top-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                            ✓
                        </div>
                        <div class="flex justify-between gap-4">
                            <div>
                                <h5 class="font-semibold text-slate-900">Order Disetujui</h5>
                                <p class="text-sm text-gray-500">Project mulai diproses oleh tim.</p>
                            </div>
                            <p class="text-sm text-gray-500 whitespace-nowrap">
                                {{ $project->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    @forelse($project->logs as $log)
                        <div class="relative">
                            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full
                                {{ $log->progress_percent >= 100 ? 'bg-green-500' : 'bg-orange-500' }}
                                text-white flex items-center justify-center text-xs">
                                {{ $log->progress_percent >= 100 ? '✓' : '•' }}
                            </div>

                            <div class="flex justify-between gap-4">
                                <div>
                                    <h5 class="font-semibold text-slate-900">
                                        Progress {{ $log->progress_percent }}%
                                    </h5>
                                    <p class="text-sm text-gray-500">
                                        {{ $log->note }}
                                    </p>
                                </div>

                                <p class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="relative">
                            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full bg-slate-300 text-white flex items-center justify-center text-xs">
                                ○
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-900">Menunggu progress</h5>
                                <p class="text-sm text-gray-500">
                                    Belum ada progress pekerjaan yang divalidasi pimpinan.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-6">

            {{-- DOCUMENTATION --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Dokumentasi Terbaru</h3>

                <div class="grid grid-cols-2 gap-3">
                    @php
                        $docs = $project->stages->whereNotNull('documentation_file')->take(4);
                    @endphp

                    @forelse($docs as $doc)
                        <a href="{{ asset('storage/'.$doc->documentation_file) }}"
                           target="_blank"
                           class="aspect-square rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-3xl hover:bg-orange-100 transition">
                            📷
                        </a>
                    @empty
                        <div class="col-span-2 bg-slate-50 rounded-2xl border border-slate-200 p-6 text-center">
                            <div class="text-3xl mb-2">📷</div>
                            <p class="text-sm text-gray-500">
                                Belum ada dokumentasi.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- CONTRACT --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Dokumen Kontrak</h3>

                @if($project->order->contract_file)
                    <a href="{{ asset('storage/'.$project->order->contract_file) }}"
                       target="_blank"
                       class="block text-center bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-4 py-3 text-sm font-semibold">
                        Download Kontrak
                    </a>
                @else
                    <p class="text-sm text-gray-500">
                        Kontrak belum tersedia.
                    </p>
                @endif
            </div>

            {{-- NOTES --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Catatan Terbaru</h3>

                <div class="space-y-4">
                    @forelse($project->logs->take(3) as $log)
                        <div class="border-l-4 border-orange-500 pl-4">
                            <p class="text-sm text-gray-600">
                                "{{ $log->note }}"
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $log->created_at->format('d M Y') }}
                            </p>
                        </div>
                    @empty
                        <div class="border-l-4 border-slate-300 pl-4">
                            <p class="text-sm text-gray-500">
                                Belum ada catatan progress.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- PDF --}}
            <div class="bg-slate-900 text-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold mb-3">Laporan Project</h3>
                <p class="text-sm text-slate-300 mb-5">
                    Unduh laporan progress project dalam format PDF.
                </p>

                <a href="/reports/projects/{{ $project->id }}/download"
                   class="block text-center bg-white text-slate-900 rounded-xl px-4 py-3 text-sm font-semibold hover:bg-slate-100">
                    Download Laporan PDF
                </a>
            </div>

        </div>
    </div>
</x-app-layout>