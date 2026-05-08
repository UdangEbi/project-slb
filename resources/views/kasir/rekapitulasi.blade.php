@extends('layouts.kasir')

@section('title', 'Rekapitulasi Kasir')

@section('content')
<div class="w-full">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-[#212842]">
            Rekapitulasi Harian
        </h1>

        <button class="bg-[#212842] text-[#F0E7D5] px-6 py-3 rounded-lg font-extrabold shadow-md">
            Rekap Hari Ini
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
@endsection
