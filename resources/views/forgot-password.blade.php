<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>LUPA PASSWORD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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

        {{-- KANAN: FORM LUPA PASSWORD --}}
        <div class="flex items-center justify-center px-10">

            <form action="{{ route('password.email') }}" method="POST"
                class="bg-white rounded-3xl shadow-xl p-10 w-full max-w-xl">

                @csrf

                <h2 class="text-4xl font-extrabold text-[#212842] text-center mb-4">
                    LUPA PASSWORD
                </h2>

                <p class="text-center text-gray-600 text-xl font-semibold mb-8">
                    Masukkan email yang terdaftar untuk mereset password akun.
                </p>

                <div class="mb-5">

                    <label class="block text-xl font-extrabold text-[#212842] mb-2">
                        EMAIL
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="MASUKKAN EMAIL"
                        required
                        class="w-full border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold outline-none">

                    @error('email')
                        <p class="text-red-600 font-bold mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <button type="submit"
                    class="w-full bg-[#212842] text-[#F0E7D5] py-4 rounded-xl text-2xl font-extrabold shadow-md hover:bg-[#151b33] transition">
                    RESET PASSWORD
                </button>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-[#212842] font-extrabold hover:underline">
                        Kembali ke Login
                    </a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
