<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Data Pegawai</h2>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <a href="/admin/employees/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Pegawai
        </a>

        <div class="bg-white rounded-xl shadow p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-3">Nama Pegawai</th>
                        <th>User Login</th>
                        <th>Supervisor</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr class="border-b">
                            <td class="py-3">{{ $employee->employee_name }}</td>
                            <td>{{ $employee->user->email }}</td>
                            <td>{{ $employee->supervisor->name ?? '-' }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ ucfirst($employee->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                Belum ada data pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>