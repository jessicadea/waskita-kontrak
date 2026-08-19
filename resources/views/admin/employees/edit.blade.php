<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Pegawai</h1>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Pegawai</label>
                    <input type="text"
                           name="employee_name"
                           value="{{ old('employee_name', $employee->employee_name) }}"
                           class="w-full border rounded-lg px-3 py-2"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $employee->user->email ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">No. Telepon</label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $employee->user->phone ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender">
                        <option value="">Pilih Gender</option>
                        <option value="Laki-laki" {{ old('gender', $employee->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender', $employee->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jabatan</label>
                    <input type="text"
                           name="position"
                           value="{{ old('position', $employee->position) }}"
                           class="w-full border rounded-lg px-3 py-2"
                           placeholder="Contoh: Mandor">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password Baru</label>
                    <input type="password"
                           name="password"
                           class="w-full border rounded-lg px-3 py-2"
                           placeholder="Kosongkan jika tidak ingin mengganti password">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.employees.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>