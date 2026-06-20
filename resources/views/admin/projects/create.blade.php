<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Buat Project</h2>
    </x-slot>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
            <p><b>Order:</b> {{ $order->project_name }}</p>
            <p><b>Client:</b> {{ $order->user->name }}</p>
            <p><b>Produk:</b> {{ $order->product->product_name }}</p>
        </div>

        <form method="POST" action="/admin/projects" class="space-y-4">
            @csrf

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div>
                <label class="block mb-1">Nama Project</label>
                <input type="text" name="project_name" value="{{ old('project_name', $order->project_name) }}"
                       class="w-full rounded border-gray-300">
                @error('project_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" class="w-full rounded border-gray-300">
                @error('start_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1">Deadline</label>
                <input type="date" name="due_date" class="w-full rounded border-gray-300">
                @error('due_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Simpan Project
            </button>
        </form>
    </div>
</x-app-layout>