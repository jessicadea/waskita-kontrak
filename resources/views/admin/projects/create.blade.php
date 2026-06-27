<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Buat Project</h2>
    </x-slot>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
            <p><b>Order:</b> {{ $order->project_name }}</p>
            <p><b>Client:</b> {{ $order->user->name }}</p>

            <div class="mt-3">
                <p class="font-semibold mb-2">Daftar Produk:</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-blue-100">
                        <thead class="bg-blue-100 text-blue-800">
                            <tr>
                                <th class="border px-3 py-2 text-left">No</th>
                                <th class="border px-3 py-2 text-left">Produk</th>
                                <th class="border px-3 py-2 text-left">Type</th>
                                <th class="border px-3 py-2 text-left">Volume</th>
                                <th class="border px-3 py-2 text-left">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-3 py-2">{{ $item->product->product_name ?? '-' }}</td>
                                    <td class="border px-3 py-2">{{ $item->variant->type_name ?? '-' }}</td>
                                    <td class="border px-3 py-2">
                                        @if($item->selectedVolume)
                                            {{ $item->selectedVolume->volume_value }} {{ $item->selectedVolume->unit }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border px-3 py-2">{{ $item->quantity }} unit</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border px-3 py-3 text-center text-gray-500">
                                        Belum ada produk pada order ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div>
                <label class="block mb-1">Nama Project</label>
                <input type="text"
                       name="project_name"
                       value="{{ old('project_name', $order->project_name) }}"
                       class="w-full rounded border-gray-300">
                @error('project_name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       value="{{ old('start_date') }}"
                       class="w-full rounded border-gray-300">
                @error('start_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1">Deadline</label>
                <input type="date"
                       name="due_date"
                       value="{{ old('due_date') }}"
                       class="w-full rounded border-gray-300">
                @error('due_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Simpan Project
            </button>
        </form>
    </div>
</x-app-layout>