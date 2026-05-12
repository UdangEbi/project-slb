@extends('layouts.kasir')

@section('title', 'Rekapitulasi Kasir')

@section('content')
<div class="w-full">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-[#212842]">
            Rekapitulasi Harian
        </h1>

        <button
            onclick="openModalTutupKasir()"
            class="bg-[#CA0B00] text-[#F0E7D5] px-6 py-3 rounded-lg font-extrabold shadow-md hover:bg-red-700 transition">
            Tutup Kasir
        </button>
    </div>

    {{-- CARD RINGKASAN --}}
    <div class="grid grid-cols-4 gap-5 mb-6">

        {{-- MODAL AWAL --}}
        <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
            <i class="bi bi-wallet2 text-3xl text-[#212842]"></i>
            <div>
                <p class="text-sm font-bold text-gray-500">Modal Awal</p>
                <h2 class="text-xl font-extrabold text-[#212842]">Rp250.000</h2>
            </div>
        </div>

        {{-- TOTAL PENERIMAAN --}}
        <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
            <i class="bi bi-graph-up-arrow text-3xl text-[#212842]"></i>
            <div>
                <p class="text-sm font-bold text-gray-500">Total Penerimaan</p>
                <h2 class="text-xl font-extrabold text-[#212842]">Rp245.850</h2>
            </div>
        </div>

        {{-- SALDO AKHIR --}}
        <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
            <i class="bi bi-cash-stack text-3xl text-[#212842]"></i>
            <div>
                <p class="text-sm font-bold text-gray-500">Saldo Akhir</p>
                <h2 class="text-xl font-extrabold text-[#212842]">Rp547.850</h2>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
            <i class="bi bi-check-circle text-3xl text-[#212842]"></i>
            <div>
                <p class="text-sm font-bold text-gray-500">Transaksi Selesai</p>
                <h2 class="text-xl font-extrabold text-[#212842]">8</h2>
            </div>
        </div>

    </div>

    {{-- DETAIL REKAP --}}
    <div class="bg-white rounded-xl shadow-md p-6">

        {{-- PENERIMAAN KASIR --}}
        <div class="mb-6">
            <h2 class="text-lg font-extrabold text-[#212842] mb-4">
                1. Penerimaan Kasir
            </h2>

            <div class="space-y-0 text-[#212842] font-bold">
                <div class="flex justify-between border-b py-3">
                    <span>Tunai</span>
                    <span>Rp90.800</span>
                </div>

                <div class="flex justify-between border-b py-3">
                    <span>QRIS</span>
                    <span>Rp155.050</span>
                </div>

                <div class="flex justify-between bg-blue-50 rounded-md px-4 py-3 font-extrabold">
                    <span>Total Penerimaan Kasir</span>
                    <span>Rp245.850</span>
                </div>
            </div>
        </div>

        {{-- REKAP KAS --}}
        <div class="mb-6">
            <h2 class="text-lg font-extrabold text-[#212842] mb-4">
                2. Rekap Kas
            </h2>

            <div class="space-y-0 text-[#212842] font-bold">
                <div class="flex justify-between border-b py-3">
                    <span>Modal Awal</span>
                    <span>Rp250.000</span>
                </div>

                <div class="flex justify-between border-b py-3">
                    <span>Total Penerimaan</span>
                    <span>Rp245.850</span>
                </div>

                <div class="flex justify-between border-b py-3">
                    <span>Kas Masuk</span>
                    <span>Rp100.000</span>
                </div>

                <div class="flex justify-between border-b py-3">
                    <span>Kas Keluar</span>
                    <span>Rp48.000</span>
                </div>

                <div class="flex justify-between bg-green-50 rounded-md px-4 py-3 font-extrabold">
                    <span>Saldo Akhir</span>
                    <span>Rp547.850</span>
                </div>
            </div>
        </div>

        {{-- REKAP TUNAI --}}
        <div>
            <h2 class="text-lg font-extrabold text-[#212842] mb-4">
                3. Rekap Tunai
            </h2>

            <div class="space-y-0 text-[#212842] font-bold">
                <div class="flex justify-between border-b py-3">
                    <span>Total Tunai Sistem</span>
                    <span>Rp392.800</span>
                </div>

                <div class="flex justify-between py-3">
                    <span>Total Tunai Aktual</span>
                    <span>Rp400.000</span>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- MODAL REKAP TUTUP KASIR --}}
<div
    id="modalTutupKasir"
    class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center">

    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl px-10 py-7 relative">

        {{-- HEADER --}}
        <div class="flex items-center gap-5 mb-8">

            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <span class="text-3xl font-extrabold text-red-600">!</span>
            </div>

            <div>
                <h2 class="text-3xl font-extrabold text-[#212842]">
                    Tutup Kasir
                </h2>

                <p class="text-gray-500 text-sm font-bold mt-1">
                    Cocokkan nominal sistem dengan uang aktual.
                </p>
            </div>

        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-2 gap-8">

            {{-- KIRI --}}
            <div>

                <div class="space-y-4">

                    <div class="flex justify-between items-center border-b pb-3">
                        <span class="text-base font-bold text-[#212842]">
                            Cash Sistem
                        </span>

                        <span class="text-xl font-extrabold text-[#212842]">
                            Rp90.800
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-b pb-3">
                        <span class="text-base font-bold text-[#212842]">
                            QRIS Sistem
                        </span>

                        <span class="text-xl font-extrabold text-[#212842]">
                            Rp155.050
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-b pb-3">
                        <span class="text-base font-bold text-[#212842]">
                            Total Sistem
                        </span>

                        <span class="text-2xl font-extrabold text-[#212842]">
                            Rp245.850
                        </span>
                    </div>

                </div>

            </div>

            {{-- KANAN --}}
            <div>

                {{-- INPUT --}}
                <div class="mb-5">

                    <label class="block text-base font-extrabold text-[#212842] mb-2">
                        Nominal Aktual
                    </label>

                    <input
                        type="text"
                        id="nominalAktual"
                        oninput="formatInputAktual(this); hitungSelisihKas();"
                        placeholder="Masukkan nominal asli"
                        class="w-full border-2 border-[#212842] rounded-xl px-4 py-3 text-lg font-extrabold outline-none">
                </div>

                {{-- HASIL --}}
                <div
                    id="hasilSelisih"
                    class="rounded-xl bg-[#F4F6F9] px-5 py-4">

                    <div class="flex justify-between items-center">

                        <div>
                            <h3 class="text-lg font-extrabold text-[#212842]">
                                Status Kas
                            </h3>

                            <p
                                id="keteranganKas"
                                class="text-sm font-bold text-gray-500 mt-1">

                                Belum dihitung
                            </p>
                        </div>

                        <div class="text-right">

                            <p class="text-sm font-bold text-gray-500">
                                Selisih
                            </p>

                            <h2
                                id="nominalSelisih"
                                class="text-2xl font-extrabold text-[#212842]">

                                Rp0
                            </h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-4 mt-8">

            <button
                onclick="closeModalTutupKasir()"
                class="px-7 py-3 border-2 border-[#212842] text-[#212842] rounded-xl font-extrabold text-base hover:bg-gray-100 transition">

                Batal
            </button>

            <button
                onclick="openConfirmModal()"
                class="px-7 py-3 bg-[#CA0B00] text-white rounded-xl font-extrabold text-base hover:bg-red-700 transition">

                Lanjut
            </button>

        </div>

    </div>
</div>
<script>
    const totalSistem = 245850;

    function openModalTutupKasir() {
        document.getElementById('modalTutupKasir').classList.remove('hidden');
    }

    function closeModalTutupKasir() {
        document.getElementById('modalTutupKasir').classList.add('hidden');
    }

    function openConfirmModal() {
        const yakin = confirm('Apakah Anda yakin ingin menutup kasir hari ini?');

        if (yakin) {
            alert('Kasir berhasil ditutup.');
            closeModalTutupKasir();
        }
    }

    function angkaSaja(value) {
        return value.replace(/\D/g, '');
    }

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
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

            keteranganKas.innerText = 'Nominal sesuai dengan sistem';
            keteranganKas.className = 'text-sm font-bold text-green-600 mt-1';

            nominalSelisih.className = 'text-2xl font-extrabold text-green-600';

            hasilSelisih.className =
                'rounded-xl bg-green-50 border border-green-200 px-5 py-4';

        } else if (selisih < 0) {
            nominalSelisih.innerText = '- ' + formatRupiah(Math.abs(selisih));

            keteranganKas.innerText = 'Kas minus dari nominal sistem';
            keteranganKas.className = 'text-sm font-bold text-red-600 mt-1';

            nominalSelisih.className = 'text-2xl font-extrabold text-red-600';

            hasilSelisih.className =
                'rounded-xl bg-red-50 border border-red-200 px-5 py-4';

        } else {
            nominalSelisih.innerText = formatRupiah(selisih);

            keteranganKas.innerText = 'Kas lebih dari nominal sistem';
            keteranganKas.className = 'text-sm font-bold text-green-600 mt-1';

            nominalSelisih.className = 'text-2xl font-extrabold text-green-600';

            hasilSelisih.className =
                'rounded-xl bg-green-50 border border-green-200 px-5 py-4';
        }
    }
</script>
@endsection
