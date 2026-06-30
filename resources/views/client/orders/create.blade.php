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
                    <div id="itemsWrapper">
                        <div class="order-item border border-slate-200 rounded-xl p-4 mb-4" data-index="0">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold text-slate-800">Produk 1</h3>
                                <button type="button" class="removeItem hidden text-red-600 text-sm font-semibold">
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium">Jenis Produk *</label>
                                    <select name="items[0][product_id]"
                                            class="productSelect searchable-select mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.0.product_id')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Type Produk *</label>
                                    <select name="items[0][variant_id]"
                                            class="variantSelect mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih type</option>
                                    </select>
                                    @error('items.0.variant_id')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Volume / Ukuran *</label>
                                    <select name="items[0][volume_id]"
                                            class="volumeSelect mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih volume</option>
                                    </select>
                                    @error('items.0.volume_id')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Quantity *</label>
                                    <input type="number"
                                           name="items[0][quantity]"
                                           min="1"
                                           placeholder="Contoh: 20"
                                           class="quantityInput mt-1 w-full rounded-xl border-slate-200">
                                    @error('items.0.quantity')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-sm font-medium">Spesifikasi / Catatan Tambahan</label>
                                <textarea name="items[0][product_spec]"
                                          rows="3"
                                          placeholder="Spesifikasi khusus, catatan pengiriman, dll"
                                          class="mt-1 w-full rounded-xl border-slate-200"></textarea>
                                @error('items.0.product_spec')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="button"
                            id="addItem"
                            class="mt-2 px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold text-sm">
                        + Tambah Produk
                    </button>

                    <hr class="my-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Nama Perusahaan *</label>
                            <input type="text"
                                   name="company_name"
                                   value="{{ auth()->user()->name }}"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('company_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Jenis Perusahaan *</label>
                            <input type="text"
                                   name="company_type"
                                   placeholder="Contoh: Kontraktor / Developer"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('company_type')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Nama Project *</label>
                            <input type="text"
                                   name="project_name"
                                   placeholder="Nama project"
                                   class="mt-1 w-full rounded-xl border-slate-200">
                            @error('project_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Lokasi Project *</label>
                            <select name="project_location"
                                    class="searchable-select mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih lokasi project</option>
                                <option value="Jakarta, DKI Jakarta">Jakarta, DKI Jakarta</option>
                                <option value="Bekasi, Jawa Barat">Bekasi, Jawa Barat</option>
                                <option value="Karawang, Jawa Barat">Karawang, Jawa Barat</option>
                                <option value="Bandung, Jawa Barat">Bandung, Jawa Barat</option>
                                <option value="Cirebon, Jawa Barat">Cirebon, Jawa Barat</option>
                                <option value="Semarang, Jawa Tengah">Semarang, Jawa Tengah</option>
                                <option value="Solo, Jawa Tengah">Solo, Jawa Tengah</option>
                                <option value="Yogyakarta, DI Yogyakarta">Yogyakarta, DI Yogyakarta</option>
                                <option value="Surabaya, Jawa Timur">Surabaya, Jawa Timur</option>
                                <option value="Sidoarjo, Jawa Timur">Sidoarjo, Jawa Timur</option>
                                <option value="Gresik, Jawa Timur">Gresik, Jawa Timur</option>
                                <option value="Malang, Jawa Timur">Malang, Jawa Timur</option>
                                <option value="Denpasar, Bali">Denpasar, Bali</option>
                                <option value="Medan, Sumatera Utara">Medan, Sumatera Utara</option>
                                <option value="Palembang, Sumatera Selatan">Palembang, Sumatera Selatan</option>
                                <option value="Balikpapan, Kalimantan Timur">Balikpapan, Kalimantan Timur</option>
                                <option value="Samarinda, Kalimantan Timur">Samarinda, Kalimantan Timur</option>
                                <option value="Makassar, Sulawesi Selatan">Makassar, Sulawesi Selatan</option>
                            </select>
                            @error('project_location')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Kondisi Pengiriman *</label>
                            <select name="delivery_cond"
                                    class="searchable-select mt-2 w-full rounded-xl border-slate-200 bg-slate-50">
                                <option value="">Pilih kondisi pengiriman</option>
                                <option value="Franco Lokasi">Franco Lokasi</option>
                                <option value="Pickup">Pickup</option>
                            </select>
                            @error('delivery_cond')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                        <p class="text-slate-600">
                            Client dapat memilih lebih dari satu produk dalam satu kontrak.
                        </p>
                    </div>

                    <div id="priceEstimateBox"
                         class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm hidden">
                        <p class="font-semibold text-blue-900 mb-3">Estimasi Harga</p>

                        <div class="space-y-2">
                            <div class="flex justify-between gap-3">
                                <span class="text-blue-700">Harga Dasar</span>
                                <span id="basePriceText" class="font-bold text-blue-900 text-right">Rp 0</span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-blue-700">Biaya Percepatan</span>
                                <span id="accelerationFeeText" class="font-bold text-blue-900 text-right">Rp 0</span>
                            </div>

                            <hr class="border-blue-200">

                            <div class="flex justify-between gap-3 text-base">
                                <span class="font-bold text-blue-900">Total Estimasi</span>
                                <span id="totalEstimatedCostText" class="font-black text-blue-900 text-right">Rp 0</span>
                            </div>
                        </div>

                        <p class="text-xs text-blue-700 mt-3">
                            Estimasi harga dapat berubah setelah dilakukan verifikasi admin.
                        </p>
                    </div>

                    <div id="accelerationBox"
                         class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
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
                                    Pesanan akan ditinjau oleh admin jika membutuhkan percepatan produksi.
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
        let itemIndex = 1;

        const productsOptions = `
            <option value="">Pilih produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
            @endforeach
        `;

        const itemsWrapper = document.getElementById('itemsWrapper');
        const addItemBtn = document.getElementById('addItem');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value || 0);
        }

        async function calculatePriceEstimate() {
            const deliveryDate = document.getElementById('delivery_date')?.value;
            const box = document.getElementById('priceEstimateBox');

            if (!deliveryDate) {
                box.classList.add('hidden');
                return;
            }

            const items = document.querySelectorAll('.order-item');

            let basePriceTotal = 0;
            let accelerationFeeTotal = 0;
            let totalEstimatedCost = 0;
            let validItemCount = 0;

            for (const item of items) {
                const productId = item.querySelector('.productSelect')?.value;
                const variantId = item.querySelector('.variantSelect')?.value;
                const volumeId = item.querySelector('.volumeSelect')?.value;
                const quantity = item.querySelector('.quantityInput')?.value;

                if (!productId || !variantId || !volumeId || !quantity) {
                    continue;
                }

                const url = `/api/estimate-workers?product_id=${productId}&variant_id=${variantId}&volume_id=${volumeId}&quantity=${quantity}&delivery_date=${deliveryDate}`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();

                    if (!response.ok) {
                        continue;
                    }

                    basePriceTotal += Number(data.base_price || 0);
                    accelerationFeeTotal += Number(data.acceleration_fee || 0);
                    totalEstimatedCost += Number(data.total_estimated_cost || 0);
                    validItemCount++;
                } catch (error) {
                    console.error('Estimate price error:', error);
                }
            }

            if (validItemCount === 0) {
                box.classList.add('hidden');
                return;
            }

            box.classList.remove('hidden');

            document.getElementById('basePriceText').innerText = formatRupiah(basePriceTotal);
            document.getElementById('accelerationFeeText').innerText = formatRupiah(accelerationFeeTotal);
            document.getElementById('totalEstimatedCostText').innerText = formatRupiah(totalEstimatedCost);
        }

        function bindItemEvents(item) {
            const productSelect = item.querySelector('.productSelect');
            const variantSelect = item.querySelector('.variantSelect');
            const volumeSelect = item.querySelector('.volumeSelect');
            const quantityInput = item.querySelector('.quantityInput');
            const removeBtn = item.querySelector('.removeItem');

            let variantsData = [];

            productSelect.addEventListener('change', function () {
                const productId = this.value;

                variantSelect.innerHTML = '<option value="">Pilih type</option>';
                volumeSelect.innerHTML = '<option value="">Pilih volume</option>';

                variantsData = [];

                calculatePriceEstimate();

                if (!productId) return;

                fetch(`/api/products/${productId}/variants`)
                    .then(response => response.json())
                    .then(data => {
                        variantsData = data.variants ?? [];

                        variantsData.forEach(variant => {
                            const option = document.createElement('option');
                            option.value = variant.id;
                            option.textContent = variant.type_name;
                            variantSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Product variant error:', error);
                    });
            });

            variantSelect.addEventListener('change', function () {
                const selectedVariant = variantsData.find(v => v.id == this.value);

                volumeSelect.innerHTML = '<option value="">Pilih volume</option>';

                calculatePriceEstimate();

                if (!selectedVariant) return;

                selectedVariant.volumes.forEach(volume => {
                    const option = document.createElement('option');
                    option.value = volume.id;
                    option.textContent = `${volume.volume_value} ${volume.unit}`;
                    volumeSelect.appendChild(option);
                });
            });

            volumeSelect.addEventListener('change', calculatePriceEstimate);
            quantityInput.addEventListener('input', calculatePriceEstimate);

            removeBtn.addEventListener('click', function () {
                item.remove();
                refreshItemTitles();
                calculatePriceEstimate();
            });
        }

        function refreshItemTitles() {
            const items = document.querySelectorAll('.order-item');

            items.forEach((item, index) => {
                item.querySelector('h3').innerText = `Produk ${index + 1}`;

                const removeBtn = item.querySelector('.removeItem');

                if (items.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            });
        }

        addItemBtn.addEventListener('click', function () {
            const html = `
                <div class="order-item border border-slate-200 rounded-xl p-4 mb-4" data-index="${itemIndex}">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-slate-800">Produk ${itemIndex + 1}</h3>
                        <button type="button" class="removeItem text-red-600 text-sm font-semibold">
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Jenis Produk *</label>
                            <select name="items[${itemIndex}][product_id]"
                                    class="productSelect searchable-select mt-1 w-full rounded-xl border-slate-200">
                                ${productsOptions}
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Type Produk *</label>
                            <select name="items[${itemIndex}][variant_id]"
                                    class="variantSelect mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih type</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Volume / Ukuran *</label>
                            <select name="items[${itemIndex}][volume_id]"
                                    class="volumeSelect mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih volume</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Quantity *</label>
                            <input type="number"
                                   name="items[${itemIndex}][quantity]"
                                   min="1"
                                   placeholder="Contoh: 20"
                                   class="quantityInput mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-medium">Spesifikasi / Catatan Tambahan</label>
                        <textarea name="items[${itemIndex}][product_spec]"
                                  rows="3"
                                  placeholder="Spesifikasi khusus, catatan pengiriman, dll"
                                  class="mt-1 w-full rounded-xl border-slate-200"></textarea>
                    </div>
                </div>
            `;

            itemsWrapper.insertAdjacentHTML('beforeend', html);

            const newItem = itemsWrapper.lastElementChild;

            if (typeof window.initSearchableSelect === 'function') {
                window.initSearchableSelect();
            }

            bindItemEvents(newItem);

            itemIndex++;
            refreshItemTitles();
            calculatePriceEstimate();
        });

        if (typeof window.initSearchableSelect === 'function') {
            window.initSearchableSelect();
        }

        document.querySelectorAll('.order-item').forEach(item => {
            bindItemEvents(item);
        });

        const deliveryDateInput = document.getElementById('delivery_date');

        if (deliveryDateInput) {
            deliveryDateInput.addEventListener('change', calculatePriceEstimate);
        }

        refreshItemTitles();
    </script>
</x-app-layout>