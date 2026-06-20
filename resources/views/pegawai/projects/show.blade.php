<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Detail Project</h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">{{ $project->project_name }}</h3>
        <p>Status: {{ $project->status }}</p>
        <p>Progress: {{ $project->progress_percent }}%</p>

        <div class="mt-4">
            <a href="{{ route('pegawai.work-updates.create', ['project_id' => $project->id]) }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">
                + Update Tahap
            </a>
        </div>
    </div>
</x-app-layout>