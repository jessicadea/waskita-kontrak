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
                                    <select name="items[0][product_id]" class="productSelect mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.0.product_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Type Produk *</label>
                                    <select name="items[0][variant_id]" class="variantSelect mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih type</option>
                                    </select>
                                    @error('items.0.variant_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Volume / Ukuran *</label>
                                    <select name="items[0][volume_id]" class="volumeSelect mt-1 w-full rounded-xl border-slate-200">
                                        <option value="">Pilih volume</option>
                                    </select>
                                    @error('items.0.volume_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" min="1"
                                           placeholder="Contoh: 20"
                                           class="quantityInput mt-1 w-full rounded-xl border-slate-200">
                                    @error('items.0.quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-sm font-medium">Spesifikasi / Catatan Tambahan *</label>
                                <textarea name="items[0][product_spec]"
                                          rows="3"
                                          placeholder="Spesifikasi khusus, catatan pengiriman, dll"
                                          class="mt-1 w-full rounded-xl border-slate-200"></textarea>
                                @error('items.0.product_spec') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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

        function bindItemEvents(item) {
            const productSelect = item.querySelector('.productSelect');
            const variantSelect = item.querySelector('.variantSelect');
            const volumeSelect = item.querySelector('.volumeSelect');
            const removeBtn = item.querySelector('.removeItem');

            let variantsData = [];

            productSelect.addEventListener('change', function () {
                const productId = this.value;

                variantSelect.innerHTML = '<option value="">Pilih type</option>';
                volumeSelect.innerHTML = '<option value="">Pilih volume</option>';

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

                if (!selectedVariant) return;

                selectedVariant.volumes.forEach(volume => {
                    volumeSelect.innerHTML += `
                        <option value="${volume.id}">
                            ${volume.volume_value} ${volume.unit}
                        </option>
                    `;
                });
            });

            removeBtn.addEventListener('click', function () {
                item.remove();
                refreshItemTitles();
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
                            <select name="items[${itemIndex}][product_id]" class="productSelect mt-1 w-full rounded-xl border-slate-200">
                                ${productsOptions}
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Type Produk *</label>
                            <select name="items[${itemIndex}][variant_id]" class="variantSelect mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih type</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Volume / Ukuran *</label>
                            <select name="items[${itemIndex}][volume_id]" class="volumeSelect mt-1 w-full rounded-xl border-slate-200">
                                <option value="">Pilih volume</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Quantity *</label>
                            <input type="number" name="items[${itemIndex}][quantity]" min="1"
                                   placeholder="Contoh: 20"
                                   class="quantityInput mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-medium">Spesifikasi / Catatan Tambahan *</label>
                        <textarea name="items[${itemIndex}][product_spec]"
                                  rows="3"
                                  placeholder="Spesifikasi khusus, catatan pengiriman, dll"
                                  class="mt-1 w-full rounded-xl border-slate-200"></textarea>
                    </div>
                </div>
            `;

            itemsWrapper.insertAdjacentHTML('beforeend', html);

            const newItem = itemsWrapper.lastElementChild;
            bindItemEvents(newItem);

            itemIndex++;
            refreshItemTitles();
        });

        document.querySelectorAll('.order-item').forEach(item => {
            bindItemEvents(item);
        });

        refreshItemTitles();
    </script>
</x-app-layout>