<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Manajemen User</h2>
        <p class="text-sm text-gray-500 mt-1">
            Kelola persetujuan akun client yang melakukan registrasi.
        </p>
    </x-slot>

    <div class="space-y-5">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900">Daftar Akun Client</h3>
                    <p class="text-sm text-gray-500">Akun baru perlu disetujui admin sebelum bisa login.</p>
                </div>

                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                    {{ $users->count() }} User
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $user->name }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 capitalize">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($user->is_approved)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Approved
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if(!$user->is_approved)
                                            <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                                @csrf
                                                <button class="px-3 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs font-bold">
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.users.reject', $user->id) }}"
                                                  onsubmit="return confirm('Yakin ingin menolak dan menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 font-semibold">Tidak ada aksi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada akun client.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>