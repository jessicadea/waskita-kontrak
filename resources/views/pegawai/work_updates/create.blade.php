<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Update Tahap Pekerjaan</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pilih tahap pekerjaan yang sudah dikerjakan untuk divalidasi pimpinan.
        </p>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <x-ui.card>
                <form method="POST" action="/pegawai/work-updates" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    @if(isset($project) && $project)
                        <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                            <p class="text-sm text-blue-600 font-semibold">Project Dipilih</p>
                            <h3 class="text-lg font-bold text-slate-900 mt-1">
                                {{ $project->project_name }}
                            </h3>
                        </div>
                    @endif

                    {{-- TAHAP PEKERJAAN --}}
                    <div>
                        <label class="text-sm font-medium">Tahap Pekerjaan</label>

                        <select name="stage_id" required
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih tahap pekerjaan</option>

                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}">
                                    {{ $stage->stage_name }} ({{ $stage->weight_percent }}%)
                                </option>
                            @endforeach
                        </select>

                        @error('stage_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-gray-500 mt-2">
                            Progress project akan bertambah sesuai bobot tahap setelah disetujui pimpinan.
                        </p>
                    </div>

                    {{-- DETAIL TAHAP --}}
                    <div id="stageInfo" class="hidden rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm">
                        <p class="font-semibold text-blue-800 mb-2">Detail Tahap Terpilih</p>
                        <p class="text-blue-700">Project: <span id="infoProject">-</span></p>
                        <p class="text-blue-700">Tahap: <span id="infoStage">-</span></p>
                        <p class="text-blue-700">Bobot Progress: <span id="infoWeight">-</span></p>
                        <p class="text-blue-700">Progress Project Saat Ini: <span id="infoProgress">-</span></p>
                    </div>

                    {{-- NOTE --}}
                    <div>
                        <label class="text-sm font-medium">Catatan Pekerjaan</label>
                        <textarea name="work_note" rows="5"
                                  class="mt-1 w-full rounded-xl border-slate-200"
                                  placeholder="Jelaskan pekerjaan yang sudah dilakukan pada tahap ini..."></textarea>
                        @error('work_note')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FILE --}}
                    <div>
                        <label class="text-sm font-medium">Dokumentasi Pekerjaan</label>
                        <input type="file" name="documentation_file"
                               class="mt-1 w-full rounded-xl border border-slate-200 p-3 bg-slate-50">
                        <p class="text-xs text-gray-500 mt-1">
                            Format: JPG, PNG, PDF. Maksimal 5MB.
                        </p>
                        @error('documentation_file')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                        Kirim Tahap untuk Validasi
                    </button>
                </form>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card>
                <h3 class="font-semibold mb-3">Panduan Upload</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Pilih tahap pekerjaan yang sudah selesai dikerjakan.</li>
                    <li>• Bobot progress mengikuti tahapan project.</li>
                    <li>• Progress resmi project berubah setelah disetujui pimpinan.</li>
                    <li>• Dokumentasi akan tampil setelah validasi disetujui.</li>
                </ul>
            </x-ui.card>

            <div class="mt-6">
                <x-ui.card>
                    <h3 class="font-semibold mb-3">Catatan Sistem</h3>
                    <p class="text-sm text-gray-600">
                        Pegawai tidak lagi mengisi progress manual. Sistem akan menghitung progress otomatis berdasarkan bobot tahap.
                    </p>
                </x-ui.card>
            </div>
        </div>

    </div>

    <script>
        const stageSelect = document.getElementById('stageSelect');
        const stageInfo = document.getElementById('stageInfo');

        const infoProject = document.getElementById('infoProject');
        const infoStage = document.getElementById('infoStage');
        const infoWeight = document.getElementById('infoWeight');
        const infoProgress = document.getElementById('infoProgress');

        stageSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];

            if (!this.value) {
                stageInfo.classList.add('hidden');
                return;
            }

            stageInfo.classList.remove('hidden');
            infoProject.innerText = selected.getAttribute('data-project') ?? '-';
            infoStage.innerText = selected.getAttribute('data-stage') ?? '-';
            infoWeight.innerText = (selected.getAttribute('data-weight') ?? '-') + '%';
            infoProgress.innerText = (selected.getAttribute('data-progress') ?? '-') + '%';
        });
    </script>
</x-app-layout>