<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kasir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-[#F0E7D5] text-black overflow-hidden">

    <div class="h-screen flex flex-col">

        <!-- HEADER -->
        <header class="h-13 bg-[#212842] px-6 flex items-center relative">

            <!-- LOGO -->
            <div class="w-48">
                <h1 class="text-2xl font-extrabold text-[#F0E7D5]">GARUDA</h1>
            </div>

            <!-- TAB MENU -->
            <nav class="absolute left-1/2 -translate-x-1/2 flex items-center gap-4 text-lg font-extrabold">

                <a href="{{ route('kasir.transaksi') }}"
                    class="px-4 py-2 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.transaksi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Transaksi
                </a>

                <a href="{{ route('kasir.stok') }}"
                    class="px-4 py-2 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.stok')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Stok
                </a>

                <a href="{{ route('kasir.rekapitulasi') }}"
                    class="px-4 py-2 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.rekapitulasi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Rekapitulasi
                </a>

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

                <!-- PROFIL -->
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Kasir&background=F0E7D5&color=212842&size=80"
                        class="w-8 h-8 rounded-full bg-white">

                    <span class="text-lg font-extrabold text-[#F0E7D5]">
                        Kasir
                    </span>
                </div>

            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-52 bg-[#F0E7D5] flex flex-col">

                <!-- JUDUL -->
                <div class="p-6 pb-3">
                    <h2 class="text-2xl font-extrabold text-[#212842]">Rombel</h2>
                </div>

                <!-- LIST ROMBEL: hanya 5 yang terlihat, sisanya scroll -->
                <div class="px-4 space-y-2 flex-1">

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'graha']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel', 'graha') == 'graha'
        ? 'bg-[#212842] text-[#F0E7D5] '
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Graha
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'membatik']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'membatik'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Membatik
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'perkayuan']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'perkayuan'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Perkayuan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'busana']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'busana'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Busana
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'tata-boga']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'tata-boga'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Tata Boga
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'kecantikan']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'kecantikan'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Kecantikan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'logam']) }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
    {{ request('rombel') == 'logam'
        ? 'bg-[#212842] text-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Logam
                    </a>

                </div>
            </aside>

            <!-- CONTENT -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>

        </div>

    </div>

    <script>
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

</body>

</html>
