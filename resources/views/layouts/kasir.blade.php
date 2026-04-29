<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kasir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F0E7D5] text-black overflow-hidden">

    <div class="h-screen flex flex-col">

        <!-- HEADER -->
        <header class="h-28 bg-[#212842] px-8 flex items-center">

            <!-- LOGO -->
            <div class="w-72">
                <h1 class="text-5xl font-extrabold text-[#F0E7D5]">GARUDA</h1>
            </div>

            <!-- TAB MENU -->
            <nav class="flex-1 flex justify-center gap-8 text-4xl font-extrabold">

                <a href="{{ url('/kasir/transaksi') }}"
                    class="px-8 py-4 rounded-xl shadow-md transition duration-200
    {{ request()->is('kasir/transaksi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Transaksi
                </a>

                <a href="{{ url('/kasir/stok') }}"
                    class="px-8 py-4 rounded-xl shadow-md transition duration-200
    {{ request()->is('kasir/stok')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Stok
                </a>

                <a href="{{ url('/kasir/rekapitulasi') }}"
                    class="px-8 py-4 rounded-xl shadow-md transition duration-200
    {{ request()->is('kasir/rekapitulasi')
        ? 'bg-[#F0E7D5] text-[#212842]'
        : 'bg-[#212842] text-[#F0E7D5] hover:bg-[#F0E7D5] hover:text-[#212842]' }}">
                    Rekapitulasi
                </a>

            </nav>

            <!-- PROFIL -->
            <div class="w-72 flex justify-end items-center gap-5">
                <img src="" class="w-16 h-16 rounded-full">
                <span class="text-4xl font-extrabold text-[#F0E7D5]">Kasir</span>
            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-96 bg-[#F0E7D5] flex flex-col">

                <!-- JUDUL -->
                <div class="p-8 pb-4">
                    <h2 class="text-4xl font-extrabold text-[#212842]">Rombel</h2>
                </div>

                <!-- LIST ROMBEL: hanya 5 yang terlihat, sisanya scroll -->
                <div class="px-6 space-y-4 overflow-y-auto" style="height: 500px;">

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'graha']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel', 'graha') == 'graha'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Graha
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'membatik']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'membatik'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Membatik
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'perkayuan']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'perkayuan'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Perkayuan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'busana']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'busana'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Busana
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'tata-boga']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'tata-boga'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Tata Boga
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'kecantikan']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'kecantikan'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Kecantikan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['rombel' => 'logam']) }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
    {{ request('rombel') == 'logam'
        ? 'bg-[#212842] text-[#F0E7D5] border-l-8 border-[#F0E7D5]'
        : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Logam
                    </a>

                </div>

                <!-- BAWAH SIDEBAR -->
                <div class="mt-auto p-6 ">

                    <button
                        class="w-full bg-[#CA0B00] text-[#F0E7D5] text-3xl font-extrabold py-5 rounded-xl hover:bg-red-700">
                        Tutup Kasir
                    </button>

                    <div class="mt-6 pt-5">
                        <div id="tanggal" class="text-2xl font-bold mb-3 text-[#212842]"></div>
                        <div id="jam" class="text-5xl font-extrabold text-[#212842]"></div>
                    </div>

                </div>

            </aside>

            <!-- CONTENT -->
            <main class="flex-1 p-10 overflow-y-auto">
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
