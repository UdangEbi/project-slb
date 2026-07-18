@extends('layouts.kasir')

@section('title', 'REKAPITULASI KASIR')

@section('content')


    <div class="w-full p-6 uppercase">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">

            <h1 class="text-5xl font-extrabold text-[#212842] uppercase tracking-wide">
                REKAPITULASI HARIAN
            </h1>

            <button onclick="openModalTutupKasir()"
                class="bg-[#CA0B00] text-white px-8 py-4 rounded-xl font-extrabold text-2xl shadow-md hover:bg-red-700 transition uppercase">
                TUTUP KASIR
            </button>

        </div>

        {{-- CARD --}}
        <div class="grid grid-cols-5 gap-6 mb-8">

            <div class="bg-white rounded-3xl shadow-lg h-[200px] px-6 py-5">
                <div class="flex flex-col items-center text-center h-full">
                    {{-- Icon --}}
                    <i class="bi bi-wallet2 text-3xl text-[#212842] mb-2"></i>
                    {{-- Judul --}}
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        MODAL AWAL
                    </h3>
                    {{-- Nominal --}}
                    <h2 class="text-4xl font-black text-[#212842] whitespace-nowrap leading-none">
                        RP{{ number_format($modalAwal, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg h-[200px] px-6 py-5">
                <div class="flex flex-col items-center text-center">
                    <i class="bi bi-graph-up-arrow text-3xl text-[#212842] mb-2"></i>
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        TOTAL PENERIMAAN
                    </h3>
                    <h2 class="text-4xl font-black text-[#212842] whitespace-nowrap leading-none">
                        RP{{ number_format($totalPenerimaan, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg h-[200px] px-6 py-5">
                <div class="flex flex-col items-center text-center h-full">
                    <i class="bi bi-cash-coin text-3xl text-[#CA0B00] mb-2"></i>
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        KAS KELUAR
                    </h3>
                    <h2 class="text-4xl font-black text-[#CA0B00] whitespace-nowrap leading-none">
                        RP{{ number_format($kasKeluar, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg h-[200px] px-6 py-5">
                <div class="flex flex-col items-center text-center h-full">
                    <i class="bi bi-cash-stack text-3xl text-[#212842] mb-2"></i>
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        SALDO AKHIR
                    </h3>
                    <h2 class="text-4xl font-black text-[#212842] whitespace-nowrap leading-none">
                        RP{{ number_format($saldoAkhir, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg h-[200px] px-6 py-5">
                <div class="flex flex-col items-center text-center h-full">
                    <i class="bi bi-check-circle text-3xl text-[#212842] mb-2"></i>
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        TOTAL TRANSAKSI
                    </h3>
                    <h2 class="text-4xl font-black text-[#212842] whitespace-nowrap leading-none">
                        {{ $totalTransaksi }}
                    </h2>
                </div>
            </div>

        </div>

        {{-- DETAIL --}}
        <div class="bg-white rounded-2xl shadow-md p-8 uppercase">

            {{-- PENERIMAAN --}}
            <div class="mb-10">

                <h2 class="text-3xl font-extrabold text-[#212842] mb-6 tracking-wide">
                    1. PENERIMAAN KASIR
                </h2>

                <div class="space-y-0 text-[#212842] font-bold">

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">TUNAI</span>
                        <span class="text-3xl font-extrabold">
                            RP{{ number_format($tunai, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">QRIS</span>
                        <span class="text-3xl font-extrabold">
                            RP{{ number_format($qris, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-blue-50 rounded-xl px-6 py-5 font-extrabold mt-4">
                        <span class="text-3xl">TOTAL PENERIMAAN KASIR</span>
                        <span class="text-3xl">
                            RP{{ number_format($totalPenerimaan, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- REKAP KAS --}}
            <div class="mb-10">

                <h2 class="text-3xl font-extrabold text-[#212842] mb-6 tracking-wide">
                    2. REKAP KAS
                </h2>

                <div class="space-y-0 text-[#212842] font-bold">

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">MODAL AWAL</span>

                        <span class="text-3xl">
                            RP{{ number_format($modalAwal, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">TOTAL PENERIMAAN</span>

                        <span class="text-3xl">
                            RP{{ number_format($totalPenerimaan, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">
                            KAS KELUAR
                        </span>
                        <span class="text-3xl text-[#CA0B00] font-extrabold">
                            RP{{ number_format($kasKeluar, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-yellow-50 rounded-xl px-6 py-6 font-extrabold mt-5">
                        <span class="text-3xl">SALDO AKHIR</span>

                        <span class="text-4xl text-yellow-700">
                            RP{{ number_format($saldoAkhir, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- REKAP PEMASUKAN --}}
            <div>

                <h2 class="text-3xl font-extrabold text-[#212842] mb-6 tracking-wide">
                    3. REKAP PEMASUKAN
                </h2>

                <div class="space-y-0 text-[#212842] font-bold">

                    <div class="flex justify-between bg-green-50 rounded-xl px-6 py-6 font-extrabold mt-5">
                        <span class="text-3xl">TOTAL PEMASUKAN</span>
                        <span class="text-3xl text-green-700">
                            RP{{ number_format($totalPenerimaan, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL --}}
    <div id="modalTutupKasir" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center uppercase">

        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl px-10 py-8">

            <div class="flex items-center gap-5 mb-8">

                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-4xl font-extrabold text-red-600">
                        !
                    </span>
                </div>

                <div>
                    <h2 class="text-4xl font-extrabold text-[#212842]">
                        TUTUP KASIR
                    </h2>

                    <p class="text-xl text-gray-500 font-bold mt-2">
                        COCOKKAN NOMINAL SISTEM DENGAN UANG AKTUAL
                    </p>
                </div>

            </div>

            <form id="formTutupKas" action="{{ route('kasir.tutup-kas') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-15">

                    <div class="space-y-5">

                        <div class="flex justify-between border-b pb-4">
                            <span class="text-2xl font-bold">CASH SISTEM</span>
                            <span class="text-3xl font-extrabold">
                                RP{{ number_format($tunai, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between border-b pb-4">
                            <span class="text-2xl font-bold">QRIS SISTEM</span>
                            <span class="text-3xl font-extrabold">
                                RP{{ number_format($qris, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between border-b pb-4">
                            <span class="text-2xl font-bold">TOTAL SISTEM</span>
                            <span class="text-4xl font-extrabold">
                                RP{{ number_format($totalPenerimaan, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>

                    <div>

                        <label class="block text-2xl font-extrabold text-[#212842] mb-3">
                            NOMINAL AKTUAL
                        </label>

                        <input type="text" id="nominalAktual" name="uang_fisik"
                            oninput="formatInputAktual(this); hitungSelisihKas();" placeholder="MASUKKAN NOMINAL ASLI"
                            class="w-full border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-extrabold outline-none uppercase">
                        <div id="hasilSelisih" class="rounded-xl bg-[#F4F6F9] px-6 py-5 mt-6 uppercase">

                            <div class="flex justify-between items-center">

                                <div>
                                    <h3 class="text-xl font-extrabold text-[#212842]">
                                        STATUS KAS
                                    </h3>

                                    <p id="keteranganKas" class="text-base font-bold text-gray-500 mt-2">
                                        BELUM DIHITUNG
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-base font-bold text-gray-500">
                                        SELISIH
                                    </p>

                                    <h2 id="nominalSelisih" class="text-2xl font-extrabold text-[#212842]">
                                        RP0
                                    </h2>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="flex justify-end gap-4 mt-8">

                    <button onclick="closeModalTutupKasir()"
                        class="px-8 py-4 border-2 border-[#212842] text-[#212842] rounded-xl font-extrabold text-2xl hover:bg-gray-100 transition uppercase">
                        BATAL
                    </button>

                    <button type="submit" onclick="return openConfirmModal()"
                        class="px-8 py-4 bg-[#CA0B00] text-white rounded-xl font-extrabold text-2xl hover:bg-red-700 transition uppercase">
                        LANJUT
                    </button>

                </div>

        </div>
        </form>
    </div>

    <script>
        const totalSistem = {{ $totalPenerimaan }};

        function togglePiutang() {
            const dropdownPiutang = document.getElementById('dropdownPiutang');
            const iconPiutang = document.getElementById('iconPiutang');

            dropdownPiutang.classList.toggle('hidden');

            if (dropdownPiutang.classList.contains('hidden')) {
                iconPiutang.classList.remove('rotate-180');
            } else {
                iconPiutang.classList.add('rotate-180');
            }
        }

        function openModalTutupKasir() {
            document.getElementById('modalTutupKasir').classList.remove('hidden');
        }

        function closeModalTutupKasir() {
            document.getElementById('modalTutupKasir').classList.add('hidden');
        }

        function openConfirmModal() {
            return confirm('APAKAH ANDA YAKIN INGIN MENUTUP KASIR HARI INI?');
        }

        function angkaSaja(value) {
            return value.replace(/\D/g, '');
        }

        function formatRupiah(angka) {
            return 'RP' + angka.toLocaleString('id-ID');
        }

        function formatInputAktual(input) {

            let angka = angkaSaja(input.value);

            if (angka === '') {
                input.value = '';
                return;
            }

            input.value = parseInt(angka).toLocaleString('id-ID');
        }

        function hitungSelisihKas() {

            const inputAktual = document.getElementById('nominalAktual').value;

            const aktual = parseInt(angkaSaja(inputAktual)) || 0;

            const selisih = aktual - totalSistem;

            const nominalSelisih = document.getElementById('nominalSelisih');

            const keteranganKas = document.getElementById('keteranganKas');

            const hasilSelisih = document.getElementById('hasilSelisih');

            if (selisih === 0) {

                nominalSelisih.innerText = formatRupiah(0);

                keteranganKas.innerText = 'NOMINAL SESUAI DENGAN SISTEM';

                hasilSelisih.className =
                    'rounded-xl bg-white border border-black px-6 py-5 mt-6 uppercase';

            } else if (selisih < 0) {

                nominalSelisih.innerText =
                    '- ' + formatRupiah(Math.abs(selisih));

                keteranganKas.innerText = 'KAS MINUS';

                hasilSelisih.className =
                    'rounded-xl bg-red-50 border border-red-200 px-6 py-5 mt-6 uppercase';

            } else {

                nominalSelisih.innerText =
                    formatRupiah(selisih);

                keteranganKas.innerText = 'KAS LEBIH';

                hasilSelisih.className =
                    'rounded-xl bg-green-50 border border-green-200 px-6 py-5 mt-6 uppercase';
            }
        }
    </script>

@endsection
