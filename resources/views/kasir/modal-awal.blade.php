<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MODAL AWAL KASIR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F0E7D5] min-h-screen overflow-hidden font-sans">

    <div class="min-h-screen grid grid-cols-2">

        {{-- KIRI --}}
        <div class="flex items-center justify-center px-10">

            <div class="text-center">

                <div class="mx-auto w-36 h-36 bg-[#212842] rounded-3xl flex items-center justify-center shadow-lg mb-6">
                    <span class="text-[#F0E7D5] text-6xl font-extrabold">
                        RP
                    </span>
                </div>

                <h1 class="text-6xl font-extrabold text-[#212842] tracking-wide">
                    MODAL AWAL
                </h1>

                <p class="text-2xl font-extrabold text-[#212842] mt-4 leading-snug">
                    MASUKKAN MODAL AWAL KASIR<br>
                    UNTUK MEMULAI TRANSAKSI
                </p>

            </div>

        </div>

        {{-- KANAN --}}
        <div class="flex items-center justify-center px-10">

            <form
                action="{{ route('kasir.modal-awal.store') }}"
                method="POST"
                class="bg-white rounded-3xl shadow-xl p-10 w-full max-w-xl">

                @csrf

                <h2 class="text-4xl font-extrabold text-[#212842] text-center mb-8">
                    MASUKKAN MODAL AWAL
                </h2>

                <label class="block text-xl font-extrabold text-[#212842] mb-3">
                    NOMINAL MODAL AWAL (RP)
                </label>

                <div class="flex border-2 border-[#212842] rounded-2xl overflow-hidden mb-4">

                    <span class="bg-[#ECEDEF] px-7 py-5 text-2xl font-extrabold text-[#212842]">
                        RP
                    </span>

                    <input
                        type="text"
                        name="modal_awal"
                        id="modalAwal"
                        value="250.000"
                        oninput="formatModalAwal(this)"
                        class="w-full px-6 py-5 text-2xl font-bold outline-none">

                </div>

                <button
                    type="submit"
                    class="w-full bg-[#212842] text-[#F0E7D5] py-5 rounded-2xl text-2xl font-extrabold shadow-md hover:bg-[#151b33] transition">
                    LANJUTKAN
                </button>

            </form>

        </div>

    </div>

    <script>
        function formatModalAwal(input) {
            let angka = input.value.replace(/\D/g, '');

            if (angka === '') {
                input.value = '';
                return;
            }

            input.value = new Intl.NumberFormat('id-ID').format(angka);
        }
    </script>

</body>
</html>
