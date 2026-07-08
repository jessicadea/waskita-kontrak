<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Pegawai</h1>
                <p class="text-sm text-gray-500">Kelola data pegawai yang dapat ditugaskan ke project.</p>
            </div>

            <a href="{{ route('admin.employees.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-block">
                + Tambah Pegawai
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-700 p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Pegawai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No. Telepon</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jabatan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="border-t">
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $employee->employee_name }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $employee->user->email ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $employee->user->phone ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $employee->position ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                @if ($employee->status === 'active')
                                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menonaktifkan pegawai ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            Nonaktifkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>