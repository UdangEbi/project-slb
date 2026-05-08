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
        <header class="h-15 bg-[#212842] px-6 flex items-center">

            <!-- LOGO -->
            <div class="w-48">
                <h1 class="text-2xl font-extrabold text-[#F0E7D5]">GARUDA</h1>
            </div>

            <!-- TAB MENU -->
            <nav class="flex-1 flex justify-center gap-5 text-xl font-extrabold">

                <a href="{{ route('kasir.transaksi') }}"
                    class="px-5 py-3 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.transaksi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Transaksi
                </a>

                <a href="{{ route('kasir.stok') }}"
                    class="px-5 py-3 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.stok')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Stok
                </a>

                <a href="{{ route('kasir.rekapitulasi') }}"
                    class="px-5 py-3 rounded-xl shadow-md transition duration-200
    {{ request()->routeIs('kasir.rekapitulasi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Rekapitulasi
                </a>

            </nav>

            <!-- PROFIL -->
            <div class="w-48 flex justify-end items-center gap-3">
                <img src="" class="w-10 h-10 rounded-full">
                <span class="text-xl font-extrabold text-[#F0E7D5]">Kasir</span>
            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-60 bg-[#F0E7D5] flex flex-col">

                <!-- JUDUL -->
                <div class="p-6 pb-3">
                    <h2 class="text-2xl font-extrabold text-[#212842]">Rombel</h2>
                </div>

                <!-- LIST ROMBEL: hanya 5 yang terlihat, sisanya scroll -->
                <div class="px-4 space-y-3 overflow-y-auto" style="height: 300px;">

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'graha']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel', 'graha') == 'graha'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Graha
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'membatik']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'membatik'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Membatik
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'perkayuan']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'perkayuan'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Perkayuan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'busana']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'busana'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Busana
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'tata-boga']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'tata-boga'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Tata Boga
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'kecantikan']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'kecantikan'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Kecantikan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'logam']) }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
    {{ request('rombel') == 'logam'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Logam
                    </a>

                </div>

                <!-- BAWAH SIDEBAR -->
                <div class="mt-auto p-6 ">

                    <button
                        class="w-full bg-[#CA0B00] text-[#F0E7D5] text-xl font-extrabold py-2 rounded-lg hover:bg-red-700">
                        Tutup Kasir
                    </button>

                    <div class="mt-6 pt-5">
                        <div id="tanggal" class="text-base font-bold mb-2 text-[#212842]"></div>
                        <div id="jam" class="text-2xl font-extrabold text-[#212842]"></div>
                    </div>

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
