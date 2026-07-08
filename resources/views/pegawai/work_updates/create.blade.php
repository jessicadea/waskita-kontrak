<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Update Tahap Pekerjaan</h2>
        <p class="text-sm text-gray-500 mt-1">
            Pilih produk dan tahap pekerjaan yang sudah dikerjakan untuk divalidasi pimpinan.
        </p>
    </x-slot>

    @php
        $groupedStages = $stages->groupBy(function ($stage) {
            return $stage->order_item_id ?: 'project_' . $stage->project_id;
        });
    @endphp

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

                    {{-- PILIH PRODUK --}}
                    <div>
                        <label class="text-sm font-medium">Produk yang Dikerjakan</label>

                        <select id="productSelect"
                                required
                                class="mt-1 w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih produk terlebih dahulu</option>

                            @foreach($groupedStages as $groupKey => $stageGroup)
                                @php
                                    $firstStage = $stageGroup->first();
                                    $orderItem = $firstStage->orderItem ?? null;

                                    $projectName = $firstStage->project->project_name ?? '-';

                                    $productName = $orderItem->product->product_name
                                        ?? $firstStage->orderItem->product->product_name
                                        ?? 'Produk Utama';

                                    $variantName = $orderItem->variant->type_name
                                        ?? '-';

                                    $volumeText = '-';

                                    if (!empty($orderItem?->selectedVolume)) {
                                        $volumeText = ($orderItem->selectedVolume->volume_value ?? '-') . ' ' . ($orderItem->selectedVolume->unit ?? '');
                                    } elseif (!empty($orderItem?->variantVolume)) {
                                        $volumeText = ($orderItem->variantVolume->volume_value ?? '-') . ' ' . ($orderItem->variantVolume->unit ?? '');
                                    } elseif (!empty($orderItem?->volume) && !is_object($orderItem->volume)) {
                                        $volumeText = $orderItem->volume;
                                    }

                                    $quantity = $orderItem->quantity ?? '-';
                                @endphp

                                <option value="{{ $groupKey }}"
                                        data-project="{{ $projectName }}"
                                        data-product="{{ $productName }}"
                                        data-variant="{{ $variantName }}"
                                        data-volume="{{ $volumeText }}"
                                        data-quantity="{{ $quantity }}">
                                    {{ $productName }} - {{ $variantName }} - {{ $volumeText }} - Qty: {{ $quantity }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-xs text-gray-500 mt-2">
                            Pilih produk terlebih dahulu agar sistem menampilkan tahapan sesuai produk tersebut.
                        </p>
                    </div>

                    {{-- TAHAP PEKERJAAN --}}
                    <div>
                        <label class="text-sm font-medium">Tahap Pekerjaan</label>

                        <select name="stage_id"
                                id="stageSelect"
                                required
                                disabled
                                class="mt-1 w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">Pilih produk terlebih dahulu</option>
                        </select>

                        @error('stage_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-gray-500 mt-2">
                            Progress project akan bertambah sesuai bobot tahap setelah disetujui pimpinan.
                        </p>
                    </div>

                    {{-- DATA STAGE TERSEMBUNYI UNTUK JAVASCRIPT --}}
                    <div id="stageData" class="hidden">
                        @foreach($groupedStages as $groupKey => $stageGroup)
                            @foreach($stageGroup as $stage)
                                @php
                                    $orderItem = $stage->orderItem ?? null;

                                    $productName = $orderItem->product->product_name ?? 'Produk Utama';
                                    $variantName = $orderItem->variant->type_name ?? '-';

                                    $volumeText = '-';

                                    if (!empty($orderItem?->selectedVolume)) {
                                        $volumeText = ($orderItem->selectedVolume->volume_value ?? '-') . ' ' . ($orderItem->selectedVolume->unit ?? '');
                                    } elseif (!empty($orderItem?->variantVolume)) {
                                        $volumeText = ($orderItem->variantVolume->volume_value ?? '-') . ' ' . ($orderItem->variantVolume->unit ?? '');
                                    } elseif (!empty($orderItem?->volume) && !is_object($orderItem->volume)) {
                                        $volumeText = $orderItem->volume;
                                    }
                                @endphp

                                <span class="stage-option"
                                      data-group="{{ $groupKey }}"
                                      data-id="{{ $stage->id }}"
                                      data-project="{{ $stage->project->project_name ?? '-' }}"
                                      data-product="{{ $productName }}"
                                      data-variant="{{ $variantName }}"
                                      data-volume="{{ $volumeText }}"
                                      data-stage="{{ $stage->stage_name }}"
                                      data-weight="{{ $stage->weight_percent }}"
                                      data-progress="{{ $stage->project->progress_percent ?? 0 }}"
                                      data-status="{{ $stage->status }}">
                                </span>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- DETAIL PRODUK --}}
                    <div id="productInfo" class="hidden rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm">
                        <p class="font-semibold text-slate-800 mb-2">Detail Produk Terpilih</p>
                        <p class="text-slate-600">Produk: <span id="infoProduct">-</span></p>
                        <p class="text-slate-600">Type: <span id="infoVariant">-</span></p>
                        <p class="text-slate-600">Volume / Ukuran: <span id="infoVolume">-</span></p>
                        <p class="text-slate-600">Quantity: <span id="infoQuantity">-</span></p>
                    </div>

                    {{-- DETAIL TAHAP --}}
                    <div id="stageInfo" class="hidden rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm">
                        <p class="font-semibold text-blue-800 mb-2">Detail Tahap Terpilih</p>
                        <p class="text-blue-700">Project: <span id="infoProject">-</span></p>
                        <p class="text-blue-700">Produk: <span id="infoStageProduct">-</span></p>
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
                    <li>• Pilih produk yang ingin diupdate.</li>
                    <li>• Pilih tahap pekerjaan sesuai produk yang dipilih.</li>
                    <li>• Bobot progress mengikuti tahapan produk tersebut.</li>
                    <li>• Progress resmi project berubah setelah disetujui pimpinan.</li>
                    <li>• Dokumentasi akan tampil setelah validasi disetujui.</li>
                </ul>
            </x-ui.card>

            <div class="mt-6">
                <x-ui.card>
                    <h3 class="font-semibold mb-3">Catatan Sistem</h3>
                    <p class="text-sm text-gray-600">
                        Pegawai tidak lagi mengisi progress manual. Sistem akan menghitung progress otomatis berdasarkan bobot tahap pada produk yang dipilih.
                    </p>
                </x-ui.card>
            </div>
        </div>

    </div>

    <script>
        const productSelect = document.getElementById('productSelect');
        const stageSelect = document.getElementById('stageSelect');

        const productInfo = document.getElementById('productInfo');
        const stageInfo = document.getElementById('stageInfo');

        const infoProduct = document.getElementById('infoProduct');
        const infoVariant = document.getElementById('infoVariant');
        const infoVolume = document.getElementById('infoVolume');
        const infoQuantity = document.getElementById('infoQuantity');

        const infoProject = document.getElementById('infoProject');
        const infoStageProduct = document.getElementById('infoStageProduct');
        const infoStage = document.getElementById('infoStage');
        const infoWeight = document.getElementById('infoWeight');
        const infoProgress = document.getElementById('infoProgress');

        const allStageOptions = document.querySelectorAll('.stage-option');

        function resetStageSelect() {
            stageSelect.innerHTML = '<option value="">Pilih tahap pekerjaan</option>';
            stageSelect.disabled = true;
            stageInfo.classList.add('hidden');
        }

        function showProductInfo(selectedProduct) {
            productInfo.classList.remove('hidden');

            infoProduct.innerText = selectedProduct.getAttribute('data-product') || '-';
            infoVariant.innerText = selectedProduct.getAttribute('data-variant') || '-';
            infoVolume.innerText = selectedProduct.getAttribute('data-volume') || '-';
            infoQuantity.innerText = selectedProduct.getAttribute('data-quantity') || '-';
        }

        productSelect.addEventListener('change', function () {
            const selectedProduct = this.options[this.selectedIndex];
            const selectedGroup = this.value;

            resetStageSelect();

            if (!selectedGroup) {
                productInfo.classList.add('hidden');
                stageSelect.innerHTML = '<option value="">Pilih produk terlebih dahulu</option>';
                return;
            }

            showProductInfo(selectedProduct);

            stageSelect.disabled = false;
            stageSelect.innerHTML = '<option value="">Pilih tahap pekerjaan</option>';

            allStageOptions.forEach(function (stage) {
                if (stage.getAttribute('data-group') === selectedGroup) {
                    const option = document.createElement('option');

                    option.value = stage.getAttribute('data-id');
                    option.textContent =
                        stage.getAttribute('data-stage') +
                        ' (' +
                        stage.getAttribute('data-weight') +
                        '%)';

                    option.setAttribute('data-project', stage.getAttribute('data-project'));
                    option.setAttribute('data-product', stage.getAttribute('data-product'));
                    option.setAttribute('data-stage', stage.getAttribute('data-stage'));
                    option.setAttribute('data-weight', stage.getAttribute('data-weight'));
                    option.setAttribute('data-progress', stage.getAttribute('data-progress'));

                    stageSelect.appendChild(option);
                }
            });
        });

        stageSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];

            if (!this.value) {
                stageInfo.classList.add('hidden');
                return;
            }

            stageInfo.classList.remove('hidden');

            infoProject.innerText = selected.getAttribute('data-project') || '-';
            infoStageProduct.innerText = selected.getAttribute('data-product') || '-';
            infoStage.innerText = selected.getAttribute('data-stage') || '-';
            infoWeight.innerText = (selected.getAttribute('data-weight') || '-') + '%';
            infoProgress.innerText = (selected.getAttribute('data-progress') || '-') + '%';
        });

        resetStageSelect();
    </script>
</x-app-layout>