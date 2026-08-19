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
                    <i class="bi bi-heart text-3xl text-[#0eb12f] mb-2"></i>
                    <h3
                        class="h-20 flex items-center justify-center text-2xl font-extrabold uppercase text-gray-500 leading-tight text-center">
                        DONASI
                    </h3>
                    <h2 class="text-4xl font-black text-[#0eb12f] whitespace-nowrap leading-none">
                        RP{{ number_format($donasi, 0, ',', '.') }}
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

                    <div class="flex justify-between border-b py-5">
                        <span class="text-2xl">
                            DONASI
                        </span>
                        <span class="text-3xl text-[#0eb12f] font-extrabold">
                            RP{{ number_format($donasi, 0, ',', '.') }}
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

        </div>

    </div>

    {{-- MODAL --}}
    <div id="modalTutupKasir"
        class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-6 overflow-y-auto uppercase">

        <div class="bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl relative ">

            <button type="button" onclick="closeModalTutupKasir()"
                class="absolute right-6 top-6 text-gray-500 hover:text-black">

                <i class="bi bi-x-lg text-2xl"></i>

            </button>

            <div class="px-8 py-5 border-b">

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">

                        <i class="bi bi-safe2-fill text-red-600 text-3xl"></i>

                    </div>

                    <div>

                        <h2 class="text-3xl font-extrabold text-[#212842] leading-none">
                            TUTUP KASIR
                        </h2>

                        <p class="text-gray-500 text-base mt-1">
                            Cocokkan nominal sistem dengan uang aktual sebelum menutup kas.
                        </p>

                    </div>

                </div>

            </div>

            <form id="formTutupKas" action="{{ route('kasir.tutup-kas') }}" method="POST">

                @csrf

                <div class="grid grid-cols-2 gap-8 p-8">

                    <div>

                        <h3 class="text-2xl font-extrabold text-[#212842] mb-6">

                            RINGKASAN SISTEM

                        </h3>

                        <div class="border-b border-gray-200 overflow-hidden">

                            <div class="flex justify-between items-center px-5 py-4 border-b">

                                <div class="flex items-center gap-4">

                                    <span class="font-bold text-lg">

                                        Modal Awal

                                    </span>

                                </div>

                                <span class="font-extrabold text-xl">

                                    Rp{{ number_format($modalAwal, 0, ',', '.') }}

                                </span>

                            </div>

                            <div class="flex justify-between items-center px-5 py-4 border-b">

                                <div class="flex items-center gap-4 ml-8">
                                    <span class="font-bold text-lg">
                                        Penerimaan Tunai
                                    </span>
                                </div>

                                <span class="font-extrabold text-xl">
                                    Rp{{ number_format($tunai, 0, ',', '.') }}
                                </span>

                            </div>

                            <div class="flex justify-between items-center px-5 py-4 border-b">

                                <div class="flex items-center gap-4 ml-8">
                                    <span class="font-bold text-lg">
                                        Penerimaan QRIS
                                    </span>
                                </div>

                                <span class="font-extrabold text-xl">
                                    Rp{{ number_format($qris, 0, ',', '.') }}
                                </span>

                            </div>

                            <div class="flex justify-between items-center px-5 py-4 border-b">

                                <div class="flex items-center gap-4">

                                    <span class="font-bold text-lg">

                                        Total Penerimaan

                                    </span>

                                </div>

                                <span class="font-extrabold text-xl">

                                    Rp{{ number_format($totalPenerimaan, 0, ',', '.') }}

                                </span>

                            </div>

                            <div class="flex justify-between items-center px-5 py-4 border-b">

                                <div class="flex items-center gap-4">

                                    <span class="font-bold text-lg">

                                        Kas Keluar

                                    </span>

                                </div>

                                <span class="font-extrabold text-xl text-red-600">

                                    Rp{{ number_format($kasKeluar, 0, ',', '.') }}

                                </span>

                            </div>
                        </div>

                        <div
                            class="flex justify-between items-center bg-yellow-50 rounded-2xl p-5 border-2 border-yellow-300 mt-5">

                            <div class="flex items-center gap-4">

                                <span class="font-bold text-xl">

                                    Saldo Akhir Sistem

                                </span>

                            </div>

                            <span class="font-black text-2xl text-yellow-700">

                                Rp{{ number_format($saldoAkhir, 0, ',', '.') }}

                            </span>

                        </div>

                    </div>

                    <div>

                        <h3 class="text-2xl font-extrabold text-[#212842] mb-6">

                            NOMINAL AKTUAL

                        </h3>

                        <input type="text" id="nominalAktual" name="uang_fisik"
                            oninput="formatInputAktual(this);hitungSelisihKas();" autocomplete="off"
                            placeholder="MASUKKAN NOMINAL SALDO AKHIR"
                            class="w-full border-2 border-[#212842] rounded-2xl px-5 py-4 text-lg font-extrabold">

                        <div id="hasilSelisih" class="mt-6 rounded-2xl bg-gray-100 p-6">

                            <div class="flex justify-between items-center">

                                <div>

                                    <p class="text-sm font-bold text-gray-500">

                                        STATUS

                                    </p>

                                    <h3 id="keteranganKas" class="text-2xl font-extrabold text-[#212842]">

                                        BELUM DIHITUNG

                                    </h3>

                                </div>

                                <div class="text-right">

                                    <p class="text-sm font-bold text-gray-500">

                                        SELISIH

                                    </p>

                                    <h2 id="nominalSelisih" class="text-3xl font-black">

                                        Rp0

                                    </h2>

                                </div>

                            </div>

                        </div>

                        <div class="mt-6">

                            <label class="block text-lg font-bold mb-2">

                                Catatan

                            </label>

                            <textarea name="catatan" rows="3" placeholder="Tambahkan catatan penutupan kas (opsional)..."
                                class="w-full border rounded-2xl p-4 resize-none uppercase font-bold"></textarea>

                        </div>

                    </div>

                </div>

                <div class="border-t px-8 py-6 flex justify-end gap-4">

                    <button type="button" onclick="closeModalTutupKasir()"
                        class="px-8 py-3 rounded-xl border-2 border-[#212842] font-bold">

                        BATAL

                    </button>

                    <button type="submit" onclick="return openConfirmModal()"
                        class="px-8 py-3 rounded-xl bg-[#CA0B00] text-white font-bold hover:bg-red-700">

                        TUTUP KASIR

                    </button>

                </div>

            </form>

        </div>

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

            document
                .getElementById("modalTutupKasir")
                .classList
                .remove("hidden");

            document
                .body
                .classList
                .add("overflow-hidden");

            document.getElementById("nominalAktual").focus();

        }

        function closeModalTutupKasir() {

            document
                .getElementById("modalTutupKasir")
                .classList
                .add("hidden");

            document
                .body
                .classList
                .remove("overflow-hidden");

        }

        window.addEventListener("keydown", function (e) {

            if (e.key === "Escape") {

                closeModalTutupKasir();

            }

        });


        function openConfirmModal() {
            return confirm('APAKAH ANDA YAKIN INGIN MENUTUP KASIR HARI INI?');
        }

        function angkaSaja(value) {
            return value.replace(/\D/g, '');
        }

        function formatRupiah(angka) {

            return new Intl.NumberFormat("id-ID").format(angka);

        }

        function formatInputAktual(input) {

            let angka = input.value.replace(/\D/g, '');

            if (angka === "") {

                input.value = "";

                return;

            }

            input.value = formatRupiah(parseInt(angka));

        }

        function hitungSelisihKas() {

            const totalSistem = {{ $saldoAkhir }};

            const nominalInput = document
                .getElementById("nominalAktual")
                .value
                .replace(/\./g, '');

            const nominalAktual = nominalInput === ""
                ? 0
                : parseInt(nominalInput);

            const selisih = nominalAktual - totalSistem;

            const hasil = document.getElementById("hasilSelisih");
            const nominal = document.getElementById("nominalSelisih");
            const status = document.getElementById("keteranganKas");

            nominal.innerHTML = "Rp" + formatRupiah(Math.abs(selisih));

            hasil.className =
                "mt-6 rounded-2xl p-6";

            if (nominalInput === "") {

                hasil.classList.add("bg-gray-100");

                status.innerHTML = "BELUM DIHITUNG";

                status.className =
                    "text-2xl font-extrabold text-gray-600";

                nominal.className =
                    "text-3xl font-black text-gray-600";

                return;

            }

            if (selisih === 0) {

                hasil.classList.add(
                    "bg-green-100",
                    "border",
                    "border-green-300"
                );

                status.innerHTML = "KAS SESUAI";

                status.className =
                    "text-2xl font-extrabold text-green-700";

                nominal.className =
                    "text-3xl font-black text-green-700";

            }

            else if (selisih > 0) {

                hasil.classList.add(
                    "bg-blue-100",
                    "border",
                    "border-blue-300"
                );

                status.innerHTML = "UANG LEBIH";

                status.className =
                    "text-2xl font-extrabold text-blue-700";

                nominal.className =
                    "text-3xl font-black text-blue-700";

            }

            else {

                hasil.classList.add(
                    "bg-red-100",
                    "border",
                    "border-red-300"
                );

                status.innerHTML = "UANG KURANG";

                status.className =
                    "text-2xl font-extrabold text-red-700";

                nominal.className =
                    "text-3xl font-black text-red-700";

            }

        }

        document
            .getElementById("nominalAktual")
            .addEventListener("keyup", hitungSelisihKas);

        document
            .getElementById("nominalAktual")
            .addEventListener("change", hitungSelisihKas);

        function openConfirmModal() {

            const nominal = document
                .getElementById("nominalAktual")
                .value
                .replace(/\./g, '');

            if (nominal === "") {

                alert("MASUKKAN NOMINAL SALDO AKHIR TERLEBIH DAHULU!");

                return false;

            }

            return confirm(
                "APAKAH ANDA YAKIN INGIN MENUTUP KASIR HARI INI?"
            );

        }

    </script>

@endsection
