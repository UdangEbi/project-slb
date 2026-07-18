@extends('layouts.kasir')

@section('title', 'Titip Jual')

@section('content')
    <div class="w-full p-6 uppercase">
        <div class="space-y-6">

            {{-- Header --}}
            <div class="flex justify-between items-center">

                <div>
                    <h1 class="text-4xl font-extrabold text-[#212842]">
                        Titip Jual
                    </h1>

                    <p class="text-gray-500 mt-1 font-bold">
                        Rekap penjualan barang titipan
                    </p>
                </div>

                <form method="GET" class="flex items-end gap-3">

                    <div>
                        <label class="font-bold text-black-600 text-xl">
                            Dari
                        </label>

                        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}"
                            class="rounded-xl  bg-white border-2 border-[#212842] px-4 py-2 text-2xl font-bold">
                    </div>

                    <div>
                        <label class="font-bold text-black-600 text-xl">
                            Sampai
                        </label>

                        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}"
                            class="rounded-xl  bg-white border-2 border-[#212842] px-4 py-2 text-2xl font-bold">
                    </div>

                    <button class="bg-[#212842] text-white px-6 py-2 rounded-xl text-2xl font-extrabold hover:opacity-90">

                        Cari

                    </button>

                </form>

            </div>

            <div class="grid grid-cols-3 gap-5">

                {{-- Produk --}}
                <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">

                    <p class="text-xl text-gray-500 font-bold">
                        Produk Titip Jual
                    </p>

                    <h2 class="mt-2 text-4xl font-extrabold text-[#212842]">
                        {{ $totalProduk }}
                    </h2>

                    <p class="text-lg text-gray-400 mt-2 font-bold">
                        Jenis produk yang dititipkan
                    </p>

                </div>

                {{-- Terjual --}}
                <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">

                    <p class="text-xl text-gray-500 font-bold">
                        Barang Terjual
                    </p>

                    <h2 class="mt-2 text-4xl font-extrabold text-green-600">
                        {{ number_format($totalTerjual) }}
                    </h2>

                    <p class="text-lg text-gray-400 mt-2 font-bold">
                        Total produk yang terjual
                    </p>

                </div>

                {{-- Pembayaran --}}
                <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">

                    <p class="text-xl text-gray-500 font-bold">
                        Harus Dibayar
                    </p>

                    <h2 class="mt-2 text-3xl font-extrabold text-red-600">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </h2>

                    <p class="text-lg text-gray-400 mt-2 font-bold">
                        Berdasarkan harga beli
                    </p>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="px-6 py-4 border-b">

                    <h2 class="text-2xl font-bold text-[#212842]">
                        Rekap Barang Titip Jual
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-[#212842] text-white">

                            <tr>

                                <th class="px-4 py-3 text-center text-xl">Kode</th>

                                <th class="px-4 py-3 text-left text-xl">Nama Produk</th>

                                <th class="px-4 py-3 text-center text-xl">Masuk</th>

                                <th class="px-4 py-3 text-center text-xl">Terjual</th>

                                <th class="px-4 py-3 text-center text-xl">Sisa</th>

                                <th class="px-4 py-3 text-right text-xl">Harga Beli</th>

                                <th class="px-4 py-3 text-center text-xl">Harus Dibayar</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($produk as $item)

                                <tr class="border-b hover:bg-gray-50">

                                    <td class="px-4 py-3 text-center font-bold text-lg">
                                        {{ $item->kode_produk }}
                                    </td>

                                    <td class="px-4 py-3 font-bold text-lg">
                                        {{ $item->nama_produk }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-bold text-lg">
                                        {{ $item->total_masuk }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-bold text-green-600 text-lg">
                                        {{ $item->total_terjual }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-bold text-lg">
                                        {{ $item->stok }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-bold text-lg">
                                        Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-bold text-red-600 text-lg">
                                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="py-10 text-center text-gray-500">

                                        Belum ada data titip jual.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

@endsection
