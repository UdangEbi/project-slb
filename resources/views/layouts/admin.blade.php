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
        <header class="h-28 bg-[#212842] px-8 flex items-center justify-between">

            <!-- LOGO -->
            <div>
                <h1 class="text-5xl font-extrabold text-[#F0E7D5]">GARUDA</h1>
            </div>

            <!-- PROFIL KANAN -->
            <div class="flex items-center gap-5">
                <img src="https://ui-avatars.com/api/?name=Admin&background=F0E7D5&color=212842&size=80"
                    class="w-16 h-16 rounded-full">

                <span class="text-4xl font-extrabold text-[#F0E7D5]">
                    Admin
                </span>
            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-96 bg-[#F0E7D5] flex flex-col px-6 py-8">

                <h2 class="text-4xl font-extrabold text-[#212842] mb-6">
                    Menu
                </h2>

                <div class="space-y-8">

                    <a href="{{ url('/admin/dashboard') }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
            {{ request()->is('admin/dashboard')
                ? 'bg-[#212842] text-[#F0E7D5]'
                : 'text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Dashboard
                    </a>

                    <a href="{{ url('/admin/rekapitulasi') }}"
                        class="block px-7 py-5 text-3xl font-bold transition duration-200
            {{ request()->is('admin/rekapitulasi')
                ? 'bg-[#212842] text-[#F0E7D5]'
                : 'text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Rekapitulasi
                    </a>

                </div>

                <div class="mt-auto">
                    <div id="tanggal" class="text-2xl font-bold mb-3 text-[#212842]"></div>
                    <div id="jam" class="text-5xl font-extrabold text-[#212842]"></div>
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
