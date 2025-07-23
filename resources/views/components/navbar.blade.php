<nav class="bg-neutral-900 xl:px-60 px-6 py-5">
    <div class="flex justify-between items-center">
        <!-- Logo -->
        <a class="text-2xl text-white font-bold" href="/">
            <i class="fa-regular fa-book"></i> E-Perpus
        </a>

        <!-- Mobile Menu Button -->
        <div class="xl:hidden">
            <button @click="open = !open" class="text-white focus:outline-none" x-data="{ open: false }">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'block': !open }" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'block': open, 'hidden': !open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden xl:flex space-x-2 items-center">
            @auth
                <!-- Dropdown jika sudah login -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-yellow-400 font-semibold px-3 py-1 text-lg hover:text-white transition-colors">
                        Hallo, {{ Auth::user()->name }} <i class="fa-solid fa-caret-down"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-neutral-900 shadow-md z-10">
                        <a href="{{ route('show.history') }}" class="block px-4 py-2 text-gray-200 hover:bg-neutral-800"><i class="fa-regular fa-book"></i> Riwayat</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-neutral-800"><i class="fa-regular fa-arrow-left-from-bracket"></i> Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Tombol jika belum login -->
                <a href="{{ route('show.register') }}" class="border-yellow-400 border-2 rounded-sm font-semibold text-yellow-400 hover:text-white hover:bg-yellow-400 transition-colors px-3 py-1 text-lg">Daftar</a>
                <a href="{{ route('show.login') }}" class="bg-yellow-400 rounded-sm hover:bg-yellow-500 transition-colors font-semibold text-white px-3 py-1 text-lg">Masuk</a>
            @endauth
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="xl:hidden mt-4 space-y-2" x-data="{ open: false }" x-show="open" x-cloak>
        @auth
            <a href="{{ route('show.history') }}" class="block text-yellow-400 font-semibold">History</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block text-left text-red-500 font-semibold">Logout</button>
            </form>
        @else
            <a href="{{ route('show.register') }}" class="block text-yellow-400 font-semibold">Daftar</a>
            <a href="{{ route('show.login') }}" class="block text-white font-semibold">Masuk</a>
        @endauth
    </div>
</nav>
