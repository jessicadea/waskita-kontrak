<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Validasi Update Pekerjaan</h2>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-3">Project</th>
                        <th>Pegawai</th>
                        <th>Progress</th>
                        <th>Status Validasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                        <tr class="border-b">
                            <td class="py-3">{{ $update->project->project_name }}</td>
                            <td>{{ $update->employee->employee_name }}</td>
                            <td>{{ $update->progress_percent }}%</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $update->validation_status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $update->validation_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $update->validation_status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($update->validation_status) }}
                                </span>
                            </td>
                            <td>{{ $update->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="/pimpinan/work-updates/{{ $update->id }}" class="text-blue-600">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                Belum ada update pekerjaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>