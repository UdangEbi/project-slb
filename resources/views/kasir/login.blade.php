<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>LOGIN KASIR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@if(session('error'))

    <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl mb-6 text-center font-extrabold">

        {{ session('error') }}

    </div>

@endif

<body class="bg-[#F0E7D5] min-h-screen overflow-hidden font-sans">

    <div class="min-h-screen grid grid-cols-2">

        {{-- KIRI: LOGO --}}
        <div class="flex items-center justify-center px-10">

            <div class="text-center">

                <div class="flex justify-center -mb-6">
                    <img src="{{ asset('images/logo-gapura.png') }}" alt="Logo GAPURA" class="w-64 object-contain">
                </div>

                <h1 class="text-7xl font-extrabold text-[#212842] tracking-wide">
                    GAPURA
                </h1>

                <p class="text-2xl font-extrabold text-[#212842] tracking-[0.35em] mt-3">
                    SISTEM KASIR
                </p>

            </div>

        </div>

        {{-- KANAN: FORM LOGIN --}}
        <div class="flex items-center justify-center px-10">

            <form action="{{ route('login.process') }}" method="POST"
                class="bg-white rounded-3xl shadow-xl p-10 w-full max-w-xl">

                @csrf

                <h2 class="text-4xl font-extrabold text-[#212842] text-center mb-8">
                    LOGIN KASIR
                </h2>

                <div class="mb-5">
                    <label class="block text-xl font-extrabold text-[#212842] mb-2">
                        USERNAME
                    </label>

                    <input type="text" name="username" placeholder="MASUKKAN USERNAME"
                        class="w-full border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold uppercase outline-none">
                </div>

                <div class="mb-5">
                    <label class="block text-xl font-extrabold text-[#212842] mb-2">
                        PASSWORD
                    </label>

                    <input type="password" name="password" placeholder="MASUKKAN PASSWORD"
                        class="w-full border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-[#212842] text-[#F0E7D5] py-4 rounded-xl text-2xl font-extrabold shadow-md hover:bg-[#151b33] transition">
                    LOGIN
                </button>

            </form>

        </div>

    </div>

</body>

</html>
