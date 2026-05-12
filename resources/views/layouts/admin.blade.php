<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#F0E7D5] text-black overflow-hidden">

    <div class="h-screen flex flex-col">

        <!-- HEADER -->
        <header class="h-13 bg-[#212842] px-6 flex items-center relative">

            <!-- LOGO -->
            <div class="w-48">
                <h1 class="text-2xl font-extrabold text-[#F0E7D5]">
                    GARUDA
                </h1>
            </div>


            <!-- PROFIL -->
            <div class="ml-auto w-96 flex justify-end items-center gap-4">

                <!-- TANGGAL & JAM -->
                {{-- <div class="text-right leading-tight">
                    <div class="flex items-center justify-end gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F0E7D5]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>

                        <div id="tanggal" class="text-sm font-bold text-[#F0E7D5]"></div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F0E7D5]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <div id="jam" class="text-sm font-extrabold text-[#F0E7D5]"></div>
                    </div>
                </div> --}}

                <!-- GARIS -->
                <div class="h-8 w-px bg-[#F0E7D5]/40"></div>

                <!-- PROFIL -->
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=F0E7D5&color=212842&size=80"
                        class="w-8 h-8 rounded-full bg-white">

                    <span class="text-lg font-extrabold text-[#F0E7D5]">
                        Admin
                    </span>
                </div>

            </div>

        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR -->
            <aside class="w-52 bg-[#F0E7D5] flex flex-col">

                <div class="p-6 pb-3">
                    <h2 class="text-2xl font-extrabold text-[#212842]">
                        Menu
                    </h2>
                </div>

                <div class="px-4 space-y-2 flex-1">

                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-[#212842] text-[#F0E7D5]'
                            : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.rekapitulasi') }}"
                        class="block px-3 py-1.5 text-lg font-bold transition duration-200
                        {{ request()->routeIs('admin.rekapitulasi')
                            ? 'bg-[#212842] text-[#F0E7D5]'
                            : 'bg-[#F0E7D5] text-[#212842] hover:bg-[#212842] hover:text-[#F0E7D5]' }}">
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

    @stack('scripts')

</body>

</html>
