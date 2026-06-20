<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Detail Project</h2>
        <p class="text-sm text-gray-500 mt-1">
            Kelola detail project, assign pegawai, dan pantau progress pekerjaan.
        </p>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- MAIN --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- INFO PROJECT --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-start gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">
                                PRJ-{{ $project->created_at->format('Y') }}-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                            </p>

                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $project->project_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $project->order->project_location ?? '-' }}
                            </p>
                        </div>

                        @if($project->status === 'done')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Selesai
                            </span>
                        @elseif($project->status === 'in_progress')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Berjalan
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Belum Mulai
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Client</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->user->name }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Produk</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->product->product_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $project->order->variant->type_name ?? '-' }}
                                @if($project->order->selectedVolume)
                                    • {{ $project->order->selectedVolume->volume_value }} {{ $project->order->selectedVolume->unit }}
                                @endif
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Tanggal Mulai</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->start_date }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Deadline</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->due_date }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Quantity</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->quantity ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Kondisi Pengiriman</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->delivery_cond ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between text-sm mb-2">
                            <p class="font-semibold text-slate-900">Progress Project</p>
                            <p class="font-bold text-blue-600">{{ $project->progress_percent }}%</p>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-700"
                                 style="width: {{ $project->progress_percent }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="/reports/projects/{{ $project->id }}/download"
                           class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800">
                            Download Laporan PDF
                        </a>
                    </div>
                </div>

                {{-- ASSIGN PEGAWAI --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Assign Pegawai</h3>

                    <form method="POST" action="/admin/projects/{{ $project->id }}/assign" class="space-y-4">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 font-medium">Pilih Mandor</label>
                                <select name="employee_id" class="w-full rounded-xl border-gray-300">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->employee_name }} - Mandor
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
                                    Assign Mandor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- LIST PEGAWAI --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Pegawai Ditugaskan</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr class="border-b border-slate-200 text-left">
                                    <th class="px-4 py-3 font-semibold">Nama</th>
                                    <th class="px-4 py-3 font-semibold">Jabatan</th>
                                    <th class="px-4 py-3 font-semibold">Role Tugas</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse($project->assignments as $assignment)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">
                                            {{ $assignment->employee->employee_name }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $assignment->employee->position }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                                {{ $assignment->assignment_role ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada pegawai ditugaskan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- TAHAPAN PROJECT --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                Tahapan Project
                            </h3>

                            <p class="text-sm text-gray-500">
                                Workflow produksi dan bobot progress project
                            </p>
                        </div>

                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-sm font-semibold">
                            Total:
                            {{ $project->stages->sum('weight_percent') }}%
                        </div>
                    </div>

                    <div class="space-y-4">

                        @foreach($project->stages as $stage)

                            @php
                                $statusColor = match($stage->status) {
                                    'approved' => 'bg-green-100 text-green-700',
                                    'pending_approval' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp

                            <div class="border border-slate-200 rounded-2xl p-5">

                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                    <div>
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <h4 class="font-bold text-slate-900">
                                                {{ $stage->stage_name }}
                                            </h4>

                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $stage->status)) }}
                                            </span>
                                        </div>

                                        <div class="mt-2 text-sm text-gray-500 space-y-1">
                                            <p>
                                                Bobot Progress:
                                                <span class="font-semibold text-slate-900">
                                                    {{ $stage->weight_percent }}%
                                                </span>
                                            </p>

                                            <p>
                                                Pegawai:
                                                <span class="font-semibold text-slate-900">
                                                    {{ $stage->employee->employee_name ?? 'Belum ditugaskan' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">

                                        @if($stage->status === 'approved')
                                            <div class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                ✓
                                            </div>

                                        @elseif($stage->status === 'pending_approval')
                                            <div class="bg-yellow-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                !
                                            </div>

                                        @elseif($stage->status === 'in_progress')
                                            <div class="bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                ↻
                                            </div>

                                        @else
                                            <div class="bg-slate-300 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                •
                                            </div>
                                        @endif

                                    </div>

                                </div>

                                @if($stage->note)
                                    <div class="mt-4 bg-slate-50 rounded-xl p-4 text-sm text-slate-600">
                                        {{ $stage->note }}
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>
                </div>

                {{-- LOG PROGRESS --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Log Progress</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr class="border-b border-slate-200 text-left">
                                    <th class="px-4 py-3 font-semibold">Progress</th>
                                    <th class="px-4 py-3 font-semibold">Catatan</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse($project->logs as $log)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 font-semibold text-blue-600">
                                            {{ $log->progress_percent }}%
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $log->note }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-500">
                                            {{ $log->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada log progress.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- SIDE PANEL --}}
            <div class="space-y-6">

                {{-- WORKFLOW --}}
                <div class="bg-slate-900 text-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-4">Workflow Project</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span>Order Disetujui</span>
                            <span class="text-green-400">✔</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Project Dibuat</span>
                            <span class="text-green-400">✔</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Pegawai Assigned</span>
                            <span class="{{ $project->assignments->count() > 0 ? 'text-green-400' : 'text-slate-400' }}">
                                {{ $project->assignments->count() > 0 ? '✔' : '•' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Progress Berjalan</span>
                            <span class="{{ $project->progress_percent > 0 ? 'text-green-400' : 'text-slate-400' }}">
                                {{ $project->progress_percent > 0 ? '✔' : '•' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Selesai</span>
                            <span class="{{ $project->progress_percent >= 100 ? 'text-green-400' : 'text-slate-400' }}">
                                {{ $project->progress_percent >= 100 ? '✔' : '•' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- TIMELINE PROJECT --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <h3 class="text-lg font-bold text-slate-900 mb-5">Timeline Project</h3>

    <div class="relative pl-8 space-y-6">

        <div class="absolute left-[11px] top-2 bottom-2 w-px bg-slate-200"></div>

        {{-- ORDER APPROVED --}}
        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                ✓
            </div>
            <div>
                <p class="font-semibold text-slate-900">Order Disetujui</p>
                <p class="text-xs text-gray-500">
                    {{ $project->order->updated_at->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- PROJECT CREATED --}}
        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                ✓
            </div>
            <div>
                <p class="font-semibold text-slate-900">Project Dibuat</p>
                <p class="text-xs text-gray-500">
                    {{ $project->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- ASSIGNED --}}
        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full
                {{ $project->assignments->count() > 0 ? 'bg-green-500' : 'bg-slate-300' }}
                text-white flex items-center justify-center text-xs">
                {{ $project->assignments->count() > 0 ? '✓' : '•' }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">Pegawai Ditugaskan</p>
                <p class="text-xs text-gray-500">
                    {{ $project->assignments->count() }} pegawai assigned
                </p>
            </div>
        </div>

        {{-- IN PROGRESS --}}
        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full
                {{ $project->progress_percent > 0 ? 'bg-blue-500' : 'bg-slate-300' }}
                text-white flex items-center justify-center text-xs">
                {{ $project->progress_percent > 0 ? '✓' : '•' }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">Produksi Berjalan</p>
                <p class="text-xs text-gray-500">
                    Progress saat ini {{ $project->progress_percent }}%
                </p>
            </div>
        </div>

        {{-- NEED APPROVAL --}}
        @php
            $pendingApproval = $project->workUpdates
                ->where('validation_status', 'pending')
                ->count();
        @endphp

        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full
                {{ $pendingApproval > 0 ? 'bg-yellow-500' : 'bg-slate-300' }}
                text-white flex items-center justify-center text-xs">
                {{ $pendingApproval > 0 ? '!' : '•' }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">Menunggu Approval</p>
                <p class="text-xs text-gray-500">
                    {{ $pendingApproval }} update menunggu validasi pimpinan
                </p>
            </div>
        </div>

        {{-- DONE --}}
        <div class="relative">
            <div class="absolute -left-8 top-0 w-6 h-6 rounded-full
                {{ $project->progress_percent >= 100 ? 'bg-green-500' : 'bg-slate-300' }}
                text-white flex items-center justify-center text-xs">
                {{ $project->progress_percent >= 100 ? '✓' : '•' }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">Project Selesai</p>
                <p class="text-xs text-gray-500">
                    {{ $project->progress_percent >= 100 ? 'Selesai 100%' : 'Belum selesai' }}
                </p>
            </div>
        </div>

    </div>
</div>

                {{-- ORDER SUMMARY --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Order</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Perusahaan</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->company_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Jenis Perusahaan</p>
                            <p class="font-semibold text-slate-900">
                                {{ $project->order->company_type }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Spesifikasi</p>
                            <p class="text-slate-600">
                                {{ $project->order->product_spec }}
                            </p>
                        </div>

                        @if($project->order->contract_file)
                            <div class="pt-3 border-t">
                                <a href="{{ asset('storage/'.$project->order->contract_file) }}"
                                   target="_blank"
                                   class="text-blue-600 font-semibold text-sm">
                                    Download Kontrak
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>