@extends('layouts.kasir')

@section('title', 'Stok Kasir')

@section('content')
<div class="w-full">

    {{-- LIST STOK --}}
    <div id="stokList">
        <h1 class="text-3xl font-extrabold text-[#212842] mb-6">
            Stok Barang
        </h1>

        <div class="grid grid-cols-3 gap-6">

            {{-- ADD NEW ITEM --}}
            <button
                type="button"
                onclick="showFormTambah()"
                class="bg-[#212842] rounded-xl shadow-md p-5 h-36 text-left border-2 border-dashed border-[#212842] hover:scale-105 transition flex flex-col justify-center items-center">

                <div class="text-4xl font-extrabold text-[#F0E7D5]">+</div>
                <p class="text-xl font-extrabold text-[#F0E7D5] mt-2">
                    Add New Item
                </p>
            </button>

            @foreach ($barang as $index => $item)
                <button
                    type="button"
                    onclick="showFormEdit(
                        '{{ $item['nama'] }}',
                        '{{ $item['stok'] }}',
                        '{{ number_format($item['harga'], 0, ',', '.') }}',
                        '{{ 'BRG-' . date('ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}'
                    )"
                    class="bg-white rounded-xl shadow-md p-5 h-36 text-left hover:scale-105 transition border-2 border-transparent hover:border-[#212842]">

                    <h2 class="text-xl font-extrabold text-[#212842] mb-3">
                        {{ $item['nama'] }}
                    </h2>

                    <p class="text-base font-bold text-gray-700">
                        Stok: {{ $item['stok'] }} pcs
                    </p>

                    <p class="text-base font-bold text-gray-700">
                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>
                </button>
            @endforeach

        </div>
    </div>

    {{-- FORM TAMBAH / EDIT STOK --}}
    <div id="stokForm" class="hidden">
        <h1 class="text-3xl font-extrabold text-[#212842] mb-6">
            Tambah Stok
        </h1>

        <div class="bg-transparent w-full max-w-6xl">

            {{-- BARIS TANGGAL DAN KODE --}}
            <div class="grid grid-cols-2 gap-10 mb-8">

                <div>
                    <label class="block text-lg font-bold text-[#212842] mb-2">
                        Tanggal
                    </label>
                    <input
                        type="date"
                        id="tanggal"
                        class="w-full max-w-sm bg-white border-2 border-[#212842] rounded-md px-4 py-3 text-lg font-bold"
                        value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label class="block text-lg font-bold text-[#212842] mb-2">
                        Kode Barang
                    </label>
                    <input
                        type="text"
                        id="kodeBarang"
                        readonly
                        class="w-full max-w-md bg-white border-2 border-[#212842] rounded-md px-4 py-3 text-lg font-bold">
                    <p class="text-sm font-bold text-[#212842] mt-2">
                        Kode dibuat otomatis saat disimpan
                    </p>
                </div>

            </div>

            {{-- NAMA BARANG --}}
            <div class="mb-8">
                <label class="block text-lg font-bold text-[#212842] mb-2">
                    Nama Barang *
                </label>
                <input
                    type="text"
                    id="namaBarang"
                    placeholder="Masukkan nama barang"
                    class="w-full bg-white border-2 border-[#212842] rounded-md px-4 py-3 text-lg font-bold">
            </div>

            {{-- STOK --}}
            <div class="grid grid-cols-3 gap-10 mb-8">

                <div>
                    <label class="block text-lg font-bold text-[#212842] mb-2">
                        Stok Saat Ini
                    </label>
                    <div class="flex bg-white border-2 border-[#212842] rounded-md overflow-hidden max-w-xs">
                        <input
                            type="number"
                            id="stokSaatIni"
                            readonly
                            class="w-full px-4 py-3 text-lg font-bold outline-none">
                        <span class="px-4 py-3 text-lg font-bold">Pcs</span>
                    </div>
                </div>

                <div>
                    <label class="block text-lg font-bold text-[#212842] mb-2">
                        Tambah Stok *
                    </label>
                    <div class="flex bg-white border-2 border-[#212842] rounded-md overflow-hidden max-w-xs">
                        <button
                            type="button"
                            onclick="kurangTambahStok()"
                            class="px-5 py-3 text-2xl font-bold border-r-2 border-[#212842]">
                            −
                        </button>

                        <input
                            type="number"
                            id="tambahStok"
                            value="0"
                            min="0"
                            oninput="hitungStokSetelah()"
                            class="w-full text-center px-4 py-3 text-lg font-bold outline-none">

                        <button
                            type="button"
                            onclick="tambahTambahStok()"
                            class="px-5 py-3 text-2xl font-bold border-l-2 border-r-2 border-[#212842]">
                            +
                        </button>

                        <span class="px-4 py-3 text-lg font-bold">Pcs</span>
                    </div>
                </div>

                <div>
                    <label class="block text-lg font-bold text-[#212842] mb-2">
                        Stok Setelah Ditambah
                    </label>
                    <div class="flex bg-white border-2 border-[#212842] rounded-md overflow-hidden max-w-xs">
                        <input
                            type="number"
                            id="stokSetelah"
                            readonly
                            class="w-full px-4 py-3 text-lg font-bold outline-none">
                        <span class="px-4 py-3 text-lg font-bold">Pcs</span>
                    </div>
                </div>

            </div>

            {{-- HARGA --}}
            <div class="mb-16">
                <label class="block text-lg font-bold text-[#212842] mb-2">
                    Harga Satuan (Rp) *
                </label>
                <input
                    type="text"
                    id="hargaBarang"
                    placeholder="Masukkan harga satuan"
                    class="w-full max-w-md bg-white border-2 border-[#212842] rounded-md px-4 py-3 text-lg font-bold">
                <p class="text-sm font-bold text-[#212842] mt-2">
                    Harga satuan bisa diedit
                </p>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-between items-center">
                <button
                    type="button"
                    onclick="kembaliKeList()"
                    class="bg-white border-2 border-[#212842] text-[#212842] px-8 py-3 rounded-md font-extrabold">
                    Batal
                </button>

                <button
                    type="button"
                    onclick="kembaliKeList()"
                    class="bg-white border-2 border-[#212842] text-[#212842] px-8 py-3 rounded-md font-extrabold">
                    Simpan
                </button>
            </div>

        </div>
    </div>

</div>

<script>
    function generateKodeBarang() {
        const now = new Date();

        const yy = String(now.getFullYear()).slice(2);
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const dd = String(now.getDate()).padStart(2, '0');

        const random = Math.floor(Math.random() * 9999) + 1;
        const nomor = String(random).padStart(4, '0');

        return `BRG-${yy}${mm}${dd}-${nomor}`;
    }

    function showFormTambah() {
        document.getElementById('stokList').classList.add('hidden');
        document.getElementById('stokForm').classList.remove('hidden');

        document.getElementById('kodeBarang').value = generateKodeBarang();
        document.getElementById('namaBarang').value = '';
        document.getElementById('stokSaatIni').value = 0;
        document.getElementById('tambahStok').value = 0;
        document.getElementById('stokSetelah').value = 0;
        document.getElementById('hargaBarang').value = '';
    }

    function showFormEdit(nama, stok, harga, kode) {
        document.getElementById('stokList').classList.add('hidden');
        document.getElementById('stokForm').classList.remove('hidden');

        document.getElementById('kodeBarang').value = kode;
        document.getElementById('namaBarang').value = nama;
        document.getElementById('stokSaatIni').value = stok;
        document.getElementById('tambahStok').value = 1;
        document.getElementById('hargaBarang').value = harga;

        hitungStokSetelah();
    }

    function hitungStokSetelah() {
        const stokSaatIni = parseInt(document.getElementById('stokSaatIni').value) || 0;
        const tambahStok = parseInt(document.getElementById('tambahStok').value) || 0;

        document.getElementById('stokSetelah').value = stokSaatIni + tambahStok;
    }

    function tambahTambahStok() {
        const input = document.getElementById('tambahStok');
        input.value = (parseInt(input.value) || 0) + 1;
        hitungStokSetelah();
    }

    function kurangTambahStok() {
        const input = document.getElementById('tambahStok');
        let value = parseInt(input.value) || 0;

        if (value > 0) {
            input.value = value - 1;
        }

        hitungStokSetelah();
    }

    function kembaliKeList() {
        document.getElementById('stokForm').classList.add('hidden');
        document.getElementById('stokList').classList.remove('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            kembaliKeList();
        }

        if (e.key === 'F2') {
            e.preventDefault();
            kembaliKeList();
        }
    });
</script>
@endsection
