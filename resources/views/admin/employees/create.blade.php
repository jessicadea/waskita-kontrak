<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Tambah Mandor</h2>
        <p class="text-sm text-gray-500 mt-1">
            Buat akun login mandor sekaligus data pegawai.
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 max-w-3xl">
        <form method="POST" action="/admin/employees" class="space-y-5">
            @csrf

            <div>
                <label class="block mb-1 text-sm font-medium">Nama Mandor *</label>
                <input type="text"
                       name="employee_name"
                       value="{{ old('employee_name') }}"
                       placeholder="Contoh: Budi Santoso"
                       class="w-full rounded-xl border-gray-300">
                @error('employee_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Email Login *</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Contoh: budi@waskita.test"
                       class="w-full rounded-xl border-gray-300">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-500 mt-1">
                    Email ini akan digunakan mandor untuk login ke sistem.
                </p>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">No HP</label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="Contoh: 081234567890"
                       class="w-full rounded-xl border-gray-300">
                @error('phone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Jabatan</label>
                <input type="text"
                       value="Mandor"
                       disabled
                       class="w-full rounded-xl border-gray-300 bg-slate-100 text-gray-600">
                <input type="hidden" name="position" value="Mandor">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Password Default</label>
                <input type="text"
                       value="password"
                       disabled
                       class="w-full rounded-xl border-gray-300 bg-slate-100 text-gray-600">
                <p class="text-xs text-gray-500 mt-1">
                    Password awal mandor adalah <strong>password</strong>.
                </p>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Status</label>
                <select name="status" class="w-full rounded-xl border-gray-300">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold">
                Simpan Mandor
            </button>
        </form>
    </div>
</x-app-layout>