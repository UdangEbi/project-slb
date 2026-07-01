<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#F0E7D5] text-black overflow-hidden">

    <div class="h-screen flex flex-col">

        <!-- HEADER -->
        <header class="h-17 bg-[#212842] px-3 flex items-center relative shadow-md">

            <div class="flex items-center gap-3 min-w-max">
                <h1 class="text-3xl font-extrabold text-[#F0E7D5] leading-none">
                    GAPURA
                </h1>

                <div class="w-px h-9 bg-[#F0E7D5]/50"></div>

                <p class="text-sm font-semibold text-[#F0E7D5] whitespace-nowrap leading-none">
                    Gerakan Aktif Produktif
                </p>
            </div>



            <!-- TAB MENU -->
            <nav class="absolute left-1/2 -translate-x-1/2 flex items-center gap-4 text-2xl font-extrabold">

                <a href="{{ route('kasir.transaksi') }}"
                    class="px-4 py-2 rounded-2xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.transaksi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    TRANSAKSI
                </a>

                <a href="{{ route('kasir.stok') }}"
                    class="px-4 py-2 rounded-2xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.stok')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    STOK
                </a>

                <div x-data="{ open: false }" class="relative">

                    <button type="button" @click="open = !open"
                        class="px-4 py-2 rounded-2xl shadow-md transition duration-200 flex items-center gap-2
        {{ request()->routeIs('kasir.rekapitulasi') || request()->routeIs('kasir.kas-keluar.*')
            ? 'bg-[#F0E7D5] text-[#212842]'
            : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">

                        REKAPITULASI

                        <i class="bi bi-chevron-down transition duration-200" :class="{ 'rotate-180': open }"></i>

                    </button>

                    <div x-cloak x-show="open" @click.outside="open = false" x-transition
                        class="absolute left-1/2 -translate-x-1/2 mt-3 w-60 bg-[#F0E7D5] rounded-2xl shadow-xl overflow-hidden z-[9999]">

                        <a href="{{ route('kasir.rekapitulasi') }}"
                            class="flex items-center gap-3 px-5 py-4 font-bold text-[#212842]
                   hover:bg-[#212842] hover:text-[#F0E7D5] transition">

                            <i class="bi bi-bar-chart-fill"></i>

                            <span>Rekapitulasi</span>

                        </a>

                        <a href="{{ route('kasir.kas-keluar') }}"
                            class="flex items-center gap-3 px-5 py-4 font-bold text-[#212842]
                   hover:bg-[#212842] hover:text-[#F0E7D5] transition">

                            <i class="bi bi-cash-stack"></i>

                            <span>Pengeluaran Kas</span>

                        </a>

                    </div>

                </div>

            </nav>

            <!-- PROFIL -->
            <div class="ml-auto w-96 flex justify-end items-center gap-4">

                <!-- TANGGAL & JAM -->
                <div class="text-right leading-none">
                    <div class="flex items-center justify-end gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F0E7D5]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11
                    9h12a2 2 0 002-2V7a2 2 0
                    00-2-2H6a2 2 0 00-2 2v11a2
                    2 0 002 2z" />
                        </svg>

                        <div id="tanggal" class="text-sm text-[#F0E7D5]">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-1 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F0E7D5]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0
                    11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <div id="jam" class="text-sm text-[#F0E7D5]">
                        </div>
                    </div>
                </div>

                <!-- GARIS -->
                <div class="h-8 w-px bg-[#F0E7D5]/40"></div>

                <div x-data="{ open: false }" class="relative">

                    <button type="button" @click="open = !open" class="flex items-center gap-3 focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=Kasir&background=F0E7D5&color=212842&size=80"
                            class="w-8 h-8 rounded-full bg-white">

                        <span class="text-lg font-extrabold text-[#F0E7D5]">
                            Kasir
                        </span>

                        <i class="bi bi-chevron-down text-[#F0E7D5] text-sm transition duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-cloak x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-3 w-56 bg-[#F0E7D5] rounded-2xl shadow-xl overflow-hidden z-50">

                        <button type="button" onclick="openPasswordModal()"
                            class="w-full flex items-center gap-3 px-5 py-4 text-[#212842] font-bold hover:bg-[#212842] hover:text-[#F0E7D5] transition">

                            <i class="bi bi-key-fill"></i>
                            <span>Ganti Password</span>
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-5 py-4 text-left text-[#CA0B00] font-bold hover:bg-[#CA0B00] hover:text-[#F0E7D5] transition">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Log Out</span>
                            </button>
                        </form>

                    </div>
                </div>
        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">
            @if (!request()->routeIs(['kasir.rekapitulasi', 'kasir.kas-keluar']))
                <!-- SIDEBAR -->
                <aside class="w-58 bg-[#F0E7D5] flex flex-col">

                    <!-- JUDUL -->
                    <div class="px-3 pt-2 pb-3">
                        <h2 class="text-2xl font-extrabold text-[#212842]">ROMBEL</h2>
                    </div>

                    <div class="px-3 space-y-2 flex-1 overflow-y-auto max-h-[500px]">

                        @foreach ($kategori as $item)
                            <a href="{{ request()->routeIs('kasir.transaksi')
                                ? route('kasir.transaksi', ['kategori' => $item->id_kategori])
                                : route('kasir.stok', ['kategori' => $item->id_kategori]) }}"
                                class="block px-2 py-1.5 text-2xl font-bold transition duration-200
                                {{ $kategoriId == $item->id_kategori
                                    ? 'bg-[#212842] text-[#F0E7D5]'
                                    : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">

                                {{ strtoupper($item->nama_kategori) }}

                            </a>
                        @endforeach

                    </div>
                </aside>
            @endif
            <!-- CONTENT -->
            <main class="flex-1 px-6 pb-6 pt-2 overflow-y-auto">
                @yield('content')
            </main>

        </div>

    </div>
    <script>
        function openPasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openResetPasswordModal() {
            closePasswordModal();

            const modal = document.getElementById('resetPasswordModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('resetPasswordModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function updateWaktu() {
            const now = new Date();

            const tanggal = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const jam = now.toLocaleTimeString('id-ID');

            document.getElementById('tanggal').innerText = tanggal;
            document.getElementById('jam').innerText = jam;
        }

        setInterval(updateWaktu, 1000);
        updateWaktu();
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- MODAL GANTI PASSWORD -->
    <div id="passwordModal" class="hidden fixed inset-0 bg-black/40 z-[9999] items-center justify-center">

        <div class="bg-white w-[420px] rounded-2xl p-6 shadow-2xl">
            <h2 class="text-2xl font-extrabold text-[#212842] mb-5">
                Ganti Password
            </h2>

            <form action="/ganti-password" method="POST" class="space-y-4">
                @csrf

                <input type="password" name="password_lama" placeholder="Password Lama"
                    class="w-full border rounded-xl px-4 py-3" required>

                <input type="password" name="password_baru" placeholder="Password Baru"
                    class="w-full border rounded-xl px-4 py-3" required>

                <input type="password" name="password_baru_confirmation" placeholder="Konfirmasi Password Baru"
                    class="w-full border rounded-xl px-4 py-3" required>
                <div class="text-right">
                    <button type="button" onclick="openResetPasswordModal()"
                        class="text-sm font-bold text-[#212842] hover:underline">
                        Reset Password
                    </button>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closePasswordModal()"
                        class="flex-1 py-3 rounded-xl bg-gray-200 font-bold">
                        Batal
                    </button>

                    <button type="submit" class="flex-1 py-3 rounded-xl bg-[#212842] text-white font-bold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- MODAL LUPA PASSWORD -->
    <div id="resetPasswordModal" class="hidden fixed inset-0 bg-black/40 z-[9999] items-center justify-center">

        <div class="bg-white w-[420px] rounded-2xl p-6 shadow-2xl">

            <h2 class="text-2xl font-extrabold text-[#212842] mb-3">
                Lupa Password
            </h2>

            <p class="text-sm text-gray-600 mb-5">
                Masukkan email akun kasir
            </p>

            <form action="{{ route('password.reset') }}" method="POST" class="space-y-4">
                @csrf

                <p class="text-sm text-gray-600">
                    Password akan direset menjadi:
                    <span class="font-bold">12345678</span>
                </p>

                <div class="flex gap-3 pt-2">

                    <button type="button" onclick="closeResetPasswordModal()"
                        class="flex-1 py-3 rounded-xl bg-gray-200 font-bold">

                        Batal
                    </button>

                    <button type="submit" class="flex-1 py-3 rounded-xl bg-[#212842] text-white font-bold">

                        Reset Password
                    </button>

                </div>
            </form>
        </div>
    </div>
</body>
@stack('scripts')

</html>
