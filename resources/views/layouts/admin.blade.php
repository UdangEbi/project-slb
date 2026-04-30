<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F0E7D5] text-black overflow-hidden">

    <div class="h-screen flex flex-col">

        <!-- HEADER -->
        <header class="h-15 bg-[#212842] px-6 flex items-center justify-between">

            <!-- LOGO -->
            <div class="w-48">
                <h1 class="text-2xl font-extrabold text-[#F0E7D5]">GARUDA</h1>
            </div>

            <!-- PROFIL KANAN -->
            <div class=" w-48 flex justify-end items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin&background=F0E7D5&color=212842&size=80"
                    class="w-10 h-10 rounded-full">

                <span class="text-xl font-extrabold text-[#F0E7D5]">
                    Admin
                </span>
            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-60 bg-[#F0E7D5] flex flex-col">

                <div class="p-6 pb-3">
                    <h2 class="text-2xl font-extrabold text-[#212842]">Menu</h2>
                </div>

                <div class="px-4 space-y-3 overflow-y-auto" style="height: 300px;">

                    <a href="{{ url('/admin/dashboard') }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
            {{ request()->is('admin/dashboard')
                ? 'bg-[#212842] text-[#F0E7D5]'
                : 'text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Dashboard
                    </a>

                    <a href="{{ url('/admin/rekapitulasi') }}"
                        class="block px-4 py-2 text-xl font-bold transition duration-200
            {{ request()->is('admin/rekapitulasi')
                ? 'bg-[#212842] text-[#F0E7D5]'
                : 'text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Rekapitulasi
                    </a>

                </div>

                <!-- BAWAH SIDEBAR -->
                <div class="mt-auto p-6 ">

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
