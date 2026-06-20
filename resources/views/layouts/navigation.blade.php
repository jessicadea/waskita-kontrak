<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo + Menu -->
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                    <!-- Dashboard -->
                    <x-nav-link :href="url('/'.auth()->user()->role.'/dashboard')" 
                        :active="request()->is(auth()->user()->role.'/dashboard')">
                        Dashboard
                    </x-nav-link>

                    <!-- CLIENT -->
                    @if(auth()->user()->role === 'client')
                        <x-nav-link :href="url('/client/orders')" :active="request()->is('client/orders*')">
                            Order Saya
                        </x-nav-link>
                    @endif

                    <!-- ADMIN -->
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="url('/admin/orders')" :active="request()->is('admin/orders*')">
                            Order Masuk
                        </x-nav-link>

                        <x-nav-link :href="url('/admin/projects')" :active="request()->is('admin/projects*')">
                            Project
                        </x-nav-link>

                        <x-nav-link :href="url('/admin/employees')" :active="request()->is('admin/employees*')">
                            Pegawai
                        </x-nav-link>
                    @endif

                    <!-- PEGAWAI -->
                    @if(auth()->user()->role === 'pegawai')
                        <x-nav-link :href="url('/pegawai/projects')" :active="request()->is('pegawai/projects*')">
                            Project Saya
                        </x-nav-link>

                        <x-nav-link :href="url('/pegawai/work-updates')" :active="request()->is('pegawai/work-updates*')">
                            Update Pekerjaan
                        </x-nav-link>
                    @endif

                    <!-- PIMPINAN -->
                    @if(auth()->user()->role === 'pimpinan')
                        <x-nav-link :href="url('/pimpinan/work-updates')" :active="request()->is('pimpinan/work-updates*')">
                            Validasi Update
                        </x-nav-link>

                        <x-nav-link :href="url('/pimpinan/projects')" :active="request()->is('pimpinan/projects*')">
                            Monitoring Project
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm">
                            <div>{{ Auth::user()->name }}</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="url('/'.auth()->user()->role.'/dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'client')
                <x-responsive-nav-link :href="url('/client/orders')">
                    Order Saya
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="url('/admin/orders')">
                    Order Masuk
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/admin/projects')">
                    Project
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/admin/employees')">
                    Pegawai
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'pegawai')
                <x-responsive-nav-link :href="url('/pegawai/projects')">
                    Project Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/pegawai/work-updates')">
                    Update Pekerjaan
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'pimpinan')
                <x-responsive-nav-link :href="url('/pimpinan/work-updates')">
                    Validasi Update
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/pimpinan/projects')">
                    Monitoring Project
                </x-responsive-nav-link>
            @endif

        </div>
    </div>
</nav>