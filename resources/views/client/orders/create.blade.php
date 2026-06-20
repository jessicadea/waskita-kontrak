<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Buat Pesanan Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Lengkapi detail produk dan project untuk membuat order pemesanan.
        </p>
    </x-slot>

    <form action="{{ route('client.orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">
                <x-ui.card>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium">Jenis Produk *</label>
                            <select id="product" name="product_id" class="mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            @error('product_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Type Produk *</label>
                            <select id="variant" name="variant_id" class="mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih type</option>
                            </select>
                            @error('variant_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Volume / Ukuran *</label>
                            <select id="volume" name="volume_id" class="mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih volume</option>
                            </select>
                            @error('volume_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" min="1"
                                   placeholder="Contoh: 20"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Nama Perusahaan *</label>
                            <input type="text" name="company_name"
                                   value="{{ auth()->user()->name }}"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('company_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Jenis Perusahaan *</label>
                            <input type="text" name="company_type"
                                   placeholder="Contoh: Kontraktor / Developer"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('company_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Nama Project *</label>
                            <input type="text" name="project_name"
                                   placeholder="Nama project"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('project_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Lokasi Project *</label>
                            <input type="text" name="project_location"
                                   placeholder="Kota / Provinsi"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('project_location') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Kondisi Pengiriman *</label>
                            <select name="delivery_cond"
                                    class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50">
                                <option value="">Pilih kondisi pengiriman</option>
                                <option value="Franco Lokasi">Franco Lokasi</option>
                                <option value="Pickup">Pickup</option>
                            </select>
                            @error('delivery_cond') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Tanggal Kirim *</label>
                            <input type="date"
                                   id="delivery_date"
                                   name="delivery_date"
                                   min="{{ now()->addDays(30)->format('Y-m-d') }}"
                                   class="mt-1 w-full rounded-xl border-slate-200">

                            <p class="text-xs text-gray-500 mt-1">
                                Tanggal pengiriman minimal 30 hari setelah tanggal pemesanan.
                            </p>

                            @error('delivery_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="productInfo" class="hidden mt-5 rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm">
                        <p class="font-semibold text-blue-800 mb-1">Detail Produk Terpilih</p>
                        <p class="text-blue-700">Type: <span id="infoVariant">-</span></p>
                        <p class="text-blue-700">Volume: <span id="infoVolume">-</span></p>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-medium">Spesifikasi / Catatan Tambahan *</label>
                        <textarea name="product_spec"
                                  rows="4"
                                  placeholder="Spesifikasi khusus, catatan pengiriman, dll"
                                  class="mt-1 w-full rounded-xl border-slate-200"></textarea>
                        @error('product_spec') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </x-ui.card>
            </div>

            <div>
                <x-ui.card>
                    <h3 class="font-semibold mb-3">Dokumen Pendukung</h3>

                    <div class="border-2 border-dashed rounded-xl p-6 text-center bg-slate-50">
                        <p class="text-sm text-gray-500">
                            Drag & drop atau klik untuk upload
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            PDF, DOCX, XLSX hingga 10MB
                        </p>

                        <input type="file" name="file" class="mt-3 text-sm">
                    </div>

                    <div class="mt-5 bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm">
                        <p class="font-semibold text-slate-800 mb-2">Ringkasan Pesanan</p>
                        <p class="text-slate-600">Produk: <span id="summaryProduct">-</span></p>
                        <p class="text-slate-600">Type: <span id="summaryVariant">-</span></p>
                        <p class="text-slate-600">Volume: <span id="summaryVolume">-</span></p>
                    </div>

                    <div id="productionAlert" class="hidden mt-4 rounded-xl p-4 text-sm font-semibold"></div>

                    <div id="workerEstimateBox" class="hidden mt-4 bg-orange-50 border border-orange-100 rounded-xl p-4 text-sm">
                        <p class="font-semibold text-orange-800 mb-2">
                            Estimasi SDM Proyek
                        </p>

                        <p class="text-orange-700">Kapasitas/hari: <span id="estCapacity">-</span></p>
                        <p class="text-orange-700">Hari tersedia: <span id="estDays">-</span></p>
                        <p class="text-orange-700">Estimasi hari produksi: <span id="estProductionDays">-</span></p>

                        <p class="text-orange-700 font-bold mt-3">
                            Kebutuhan SDM Standar Proyek:
                            <span id="estWorkers" class="text-xl font-black text-orange-900">-</span>
                        </p>

                        <p class="text-xs text-orange-700 mt-2">
                            Estimasi SDM didasarkan pada kebutuhan sumber daya standar untuk satu proyek precast beton.
                            Pelaksana proyek akan ditentukan melalui proses penugasan pegawai setelah proyek dibuat.
                        </p>

                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-orange-700 mb-1">
                                <span>Pemakaian waktu produksi</span>
                                <span id="capacityPercent">0%</span>
                            </div>

                            <div class="w-full bg-orange-100 rounded-full h-3 overflow-hidden">
                                <div id="capacityBar"
                                     class="h-3 rounded-full transition-all duration-700"
                                     style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <p id="recommendation" class="text-xs text-orange-700 mt-3"></p>

                        <div id="costEstimateBox" class="mt-4 border-t border-orange-200 pt-4">
                            <p class="font-semibold text-orange-800 mb-2">
                                Estimasi Biaya
                            </p>

                            <p class="text-orange-700">
                                Harga Dasar:
                                <span id="estBasePrice">-</span>
                            </p>

                            <p class="text-orange-700">
                                Tingkat Percepatan:
                                <span id="estAccelerationPercent">-</span>
                            </p>

                            <p class="text-orange-700">
                                Biaya Percepatan:
                                <span id="estAccelerationFee">-</span>
                            </p>

                            <p class="font-bold text-lg text-orange-800 mt-2">
                                Total Estimasi:
                                <span id="estTotalCost">-</span>
                            </p>

                            <p class="text-xs text-orange-700 mt-2">
                                Nilai yang ditampilkan merupakan estimasi awal sebagai simulasi biaya percepatan dan bukan nilai kontrak final.
                            </p>
                        </div>
                    </div>

                    <div id="accelerationBox"
                         class="hidden mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-4">

                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="requires_acceleration"
                                   value="1"
                                   class="mt-1">

                            <div>
                                <p class="font-semibold text-yellow-800">
                                    Ajukan Percepatan Produksi
                                </p>

                                <p class="text-sm text-yellow-700">
                                    Pesanan melebihi kapasitas produksi normal.
                                    Client dapat mengajukan percepatan dan pesanan akan ditinjau oleh admin.
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between mt-5">
                        <x-ui.button variant="secondary" type="button">
                            Simpan Draft
                        </x-ui.button>

                        <x-ui.button type="submit">
                            Kirim Pesanan
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </form>

    <script>
        let variantsData = [];

        const productSelect = document.getElementById('product');
        const variantSelect = document.getElementById('variant');
        const volumeSelect = document.getElementById('volume');
        const quantityInput = document.getElementById('quantity');
        const deliveryDateInput = document.getElementById('delivery_date');

        const minDeliveryDate = "{{ now()->addDays(30)->format('Y-m-d') }}";
        deliveryDateInput.setAttribute('min', minDeliveryDate);

        const productInfo = document.getElementById('productInfo');
        const infoVariant = document.getElementById('infoVariant');
        const infoVolume = document.getElementById('infoVolume');

        const summaryProduct = document.getElementById('summaryProduct');
        const summaryVariant = document.getElementById('summaryVariant');
        const summaryVolume = document.getElementById('summaryVolume');

        const workerEstimateBox = document.getElementById('workerEstimateBox');
        const productionAlert = document.getElementById('productionAlert');
        const capacityBar = document.getElementById('capacityBar');
        const capacityPercent = document.getElementById('capacityPercent');
        const recommendation = document.getElementById('recommendation');
        const accelerationBox = document.getElementById('accelerationBox');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number || 0);
        }

        function hideEstimate() {
            workerEstimateBox.classList.add('hidden');
            productionAlert.classList.add('hidden');
            accelerationBox.classList.add('hidden');
            capacityBar.style.width = '0%';
            capacityPercent.innerText = '0%';
            recommendation.innerText = '';
        }

        function calculateWorkerEstimate() {
            const productId = productSelect.value;
            const variantId = variantSelect.value;
            const volumeId = volumeSelect.value;
            const quantity = quantityInput.value;
            const deliveryDate = deliveryDateInput.value;

            if (!productId || !variantId || !volumeId || !quantity || !deliveryDate) {
                hideEstimate();
                return;
            }

            if (deliveryDate < minDeliveryDate) {
                hideEstimate();
                productionAlert.classList.remove('hidden');
                productionAlert.className = 'mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm font-semibold';
                productionAlert.innerText = '❌ Tanggal pengiriman minimal 30 hari setelah tanggal pemesanan.';
                return;
            }

            fetch(`/api/estimate-workers?product_id=${productId}&variant_id=${variantId}&volume_id=${volumeId}&quantity=${quantity}&delivery_date=${deliveryDate}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.total_sdm) {
                        hideEstimate();
                        console.log(data.message ?? 'Data estimasi tidak tersedia');
                        return;
                    }

                    workerEstimateBox.classList.remove('hidden');

                    document.getElementById('estCapacity').innerText = data.capacity_per_day + ' unit';
                    document.getElementById('estDays').innerText = data.available_days + ' hari';
                    document.getElementById('estProductionDays').innerText = data.production_days + ' hari';
                    document.getElementById('estWorkers').innerText = data.total_sdm + ' orang';

                    document.getElementById('estBasePrice').innerText = formatRupiah(data.base_price);
                    document.getElementById('estAccelerationPercent').innerText = data.acceleration_percent + '%';
                    document.getElementById('estAccelerationFee').innerText = formatRupiah(data.acceleration_fee);
                    document.getElementById('estTotalCost').innerText = formatRupiah(data.total_estimated_cost);

                    productionAlert.classList.remove('hidden');
                    accelerationBox.classList.add('hidden');

                    if (data.color === 'green') {
                        productionAlert.className = 'mt-4 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 text-sm font-semibold';
                        productionAlert.innerText = '✔️ ' + data.message;
                        capacityBar.className = 'h-3 rounded-full transition-all duration-700 bg-green-500';
                        recommendation.innerText = 'Produksi berada dalam kondisi aman.';
                    } else if (data.color === 'yellow') {
                        productionAlert.className = 'mt-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl p-4 text-sm font-semibold';
                        productionAlert.innerText = '⚠️ ' + data.message;
                        capacityBar.className = 'h-3 rounded-full transition-all duration-700 bg-yellow-500';
                        recommendation.innerText = 'Pesanan perlu ditinjau kembali oleh admin.';
                    } else {
                        accelerationBox.classList.remove('hidden');

                        productionAlert.className = 'mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm font-semibold';
                        productionAlert.innerText = '❌ ' + data.message;
                        capacityBar.className = 'h-3 rounded-full transition-all duration-700 bg-red-500';
                        recommendation.innerText = 'Pesanan dapat diajukan dengan percepatan produksi dan akan ditinjau admin.';
                    }

                    const usage = data.usage_percent ?? 0;
                    const displayUsage = Math.min(usage, 100);

                    capacityBar.style.width = displayUsage + '%';
                    capacityPercent.innerText = usage + '%';
                })
                .catch(error => {
                    hideEstimate();
                    console.error('Estimate error:', error);
                });
        }

        productSelect.addEventListener('change', function () {
            const productId = this.value;
            const productText = this.options[this.selectedIndex].text;

            summaryProduct.innerText = productId ? productText : '-';
            summaryVariant.innerText = '-';
            summaryVolume.innerText = '-';

            variantSelect.innerHTML = '<option value="">Pilih type</option>';
            volumeSelect.innerHTML = '<option value="">Pilih volume</option>';
            productInfo.classList.add('hidden');

            hideEstimate();

            if (!productId) return;

            fetch(`/api/products/${productId}/variants`)
                .then(response => response.json())
                .then(data => {
                    variantsData = data.variants ?? [];

                    variantsData.forEach(variant => {
                        variantSelect.innerHTML += `
                            <option value="${variant.id}">
                                ${variant.type_name}
                            </option>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Product variant error:', error);
                });
        });

        variantSelect.addEventListener('change', function () {
            const selectedVariant = variantsData.find(v => v.id == this.value);

            volumeSelect.innerHTML = '<option value="">Pilih volume</option>';
            summaryVariant.innerText = '-';
            summaryVolume.innerText = '-';
            productInfo.classList.add('hidden');

            hideEstimate();

            if (!selectedVariant) return;

            summaryVariant.innerText = selectedVariant.type_name;
            infoVariant.innerText = selectedVariant.type_name;
            productInfo.classList.remove('hidden');

            selectedVariant.volumes.forEach(volume => {
                volumeSelect.innerHTML += `
                    <option value="${volume.id}" data-label="${volume.volume_value} ${volume.unit}">
                        ${volume.volume_value} ${volume.unit}
                    </option>
                `;
            });

            calculateWorkerEstimate();
        });

        volumeSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const label = selected.getAttribute('data-label') ?? '-';

            summaryVolume.innerText = this.value ? label : '-';
            infoVolume.innerText = this.value ? label : '-';

            calculateWorkerEstimate();
        });

        quantityInput.addEventListener('input', calculateWorkerEstimate);
        deliveryDateInput.addEventListener('change', calculateWorkerEstimate);
    </script>
</x-app-layout>