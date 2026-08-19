@extends('layouts.kasir')

@section('title', 'Tambah Barang')

@section('content')
    <div class="w-full uppercase pt-8">

        <form action="{{ route('kasir.stok.store') }}" method="POST">

            @csrf

            <input type="hidden" name="kategori_id" value="{{ $kategoriId }}">

            <h1 class="text-4xl font-extrabold text-[#212842] mb-8">
                TAMBAH BARANG
            </h1>

            <div class="bg-transparent w-full max-w-6xl">

                {{-- Tanggal & Kode --}}
                <div class="grid grid-cols-2 gap-10 mb-8">

                    <div>
                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            TANGGAL
                        </label>

                        <input type="date" value="{{ date('Y-m-d') }}" readonly
                            class="w-full max-w-sm bg-[#ECEDEF] border rounded-xl px-5 py-4 text-xl font-bold text-[#9AA1A9]">
                    </div>

                    <div>
                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            KODE BARANG
                        </label>

                        <input type="text" value="{{ $kodeProduk }}" readonly
                            class="w-full max-w-md bg-[#ECEDEF] border rounded-xl px-5 py-4 text-xl font-bold text-[#9AA1A9]">
                    </div>

                </div>

                {{-- Nama --}}
                <div class="mb-8">

                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        NAMA BARANG
                    </label>

                    <input type="text" name="nama_produk" required
                        class="w-full max-w-md bg-[#FFFFFF] border rounded-xl px-5 py-4 text-xl font-bold uppercase">

                </div>

                {{-- Harga --}}
                <div class="grid grid-cols-2 gap-10 mb-8">

                    <div>

                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            HARGA BELI
                        </label>

                        <input type="text" name="harga_beli" id="hargaBeli"
                            class="rupiah w-full max-w-md bg-[#FFFFFF] border rounded-xl px-5 py-4 text-xl font-bold"
                            autocomplete="off" required>

                    </div>

                    <div>

                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            HARGA JUAL
                        </label>

                        <input type="text" name="harga_jual" id="hargaJual"
                            class="rupiah w-full max-w-md bg-[#FFFFFF] border rounded-xl px-5 py-4 text-xl font-bold"
                            autocomplete="off" required>

                        @error('harga_jual')
                            <p class="text-red-600 text-base font-bold mt-2 normal-case">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

                {{-- Stok & Satuan --}}
                <div class="grid grid-cols-2 gap-10 mb-10">

                    <div>

                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            STOK AWAL
                        </label>

                        <input type="number" name="stok" value="0" min="0" required
                            class="w-full max-w-md bg-[#FFFFFF] border rounded-xl px-5 py-4 text-xl font-bold uppercase">

                    </div>

                    <div>

                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            SATUAN
                        </label>

                        <input type="text" name="satuan" value="PCS" required
                            class="w-full max-w-md bg-[#FFFFFF] border rounded-xl px-5 py-4 text-xl font-bold uppercase">

                    </div>

                </div>

                <div class="flex justify-between">

                    <a href="{{ route('kasir.stok') }}"
                        class="bg-white border-2 border-[#212842] text-[#212842] px-8 py-4 rounded-xl text-xl font-bold">

                        BATAL

                    </a>

                    <button type="submit" class="bg-[#212842] text-white px-8 py-4 rounded-xl text-xl font-bold">

                        SIMPAN

                    </button>

                </div>

            </div>

        </form>
    </div>
    <script>

        document.querySelectorAll('.rupiah').forEach(function (input) {

            input.addEventListener('input', function () {

                let angka = this.value.replace(/\D/g, '');

                if (angka === '') {
                    this.value = '';
                    return;
                }

                this.value = new Intl.NumberFormat('id-ID').format(angka);
            });

        });

        const form = document.querySelector('form');
        const hargaBeli = document.getElementById('hargaBeli');
        const hargaJual = document.getElementById('hargaJual');

        form.addEventListener('submit', function (e) {

            const beli = parseInt(hargaBeli.value.replace(/\D/g, '')) || 0;
            const jual = parseInt(hargaJual.value.replace(/\D/g, '')) || 0;

            if (jual <= beli) {
                e.preventDefault();

                hargaJual.setCustomValidity(
                    'Harga jual harus lebih tinggi dari harga beli.'
                );

                hargaJual.reportValidity();
                hargaJual.focus();
            } else {
                hargaJual.setCustomValidity('');
            }
        });

        hargaJual.addEventListener('input', function () {
            hargaJual.setCustomValidity('');
        });

    </script>
@endsection
