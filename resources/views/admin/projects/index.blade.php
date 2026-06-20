<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Daftar Project</h2>
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
                        <th>Client</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr class="border-b">
                            <td class="py-3">{{ $project->project_name }}</td>
                            <td>{{ $project->order->user->name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $project->status)) }}</td>
                            <td>{{ $project->progress_percent }}%</td>
                            <td>
                                <a href="/admin/projects/{{ $project->id }}" class="text-blue-600">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                Belum ada project.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>