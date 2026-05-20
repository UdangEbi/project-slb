@extends('layouts.kasir')

@section('title', 'STOK KASIR')

@section('content')
<div class="w-full uppercase">

    {{-- LIST STOK --}}
    <div id="stokList">
        <h1 class="text-4xl font-extrabold text-[#212842] mb-8">
            STOK BARANG
        </h1>

        <div class="grid grid-cols-4 gap-8">

            {{-- ADD NEW ITEM --}}
            <button
                type="button"
                onclick="showFormTambah()"
                class="bg-[#212842] rounded-3xl shadow-md p-8 h-56 border-2 border-dashed border-[#212842] hover:scale-105 transition flex flex-col justify-center items-center">

                <div class="text-6xl font-extrabold text-[#F0E7D5] leading-none">
                    +
                </div>

                <p class="text-3xl font-extrabold text-[#F0E7D5] mt-5 text-center leading-tight">
                    ADD NEW<br>ITEM
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
                    class="bg-white rounded-3xl shadow-md px-8 py-7 h-56 text-left hover:scale-105 transition border-2 border-transparent hover:border-[#212842] flex flex-col justify-center">

                    <h2 class="text-3xl font-extrabold text-[#212842] leading-tight mb-6">
                        {{ strtoupper($item['nama']) }}
                    </h2>

                    <p class="text-xl font-extrabold text-gray-700 mb-3">
                        STOK: {{ $item['stok'] }} PCS
                    </p>

                    <p class="text-xl font-extrabold text-gray-700">
                        RP {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>
                </button>
            @endforeach

        </div>
    </div>
    {{-- PENUTUP stokList ADA DI SINI --}}

    {{-- FORM TAMBAH / EDIT STOK --}}
    <div id="stokForm" class="hidden">
        <h1 id="judulForm" class="text-4xl font-extrabold text-[#212842] mb-8">
            TAMBAH STOK
        </h1>

        <div class="bg-transparent w-full max-w-6xl">

            <div class="grid grid-cols-2 gap-10 mb-8">

                <div>
                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        TANGGAL
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                        class="w-full max-w-sm bg-white border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold"
                        value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        KODE BARANG
                    </label>

                    <input
                        type="text"
                        id="kodeBarang"
                        readonly
                        class="w-full max-w-md bg-[#ECEDEF] text-[#9AA1A9] border border-[#BFC5CC] rounded-xl px-5 py-4 text-xl font-bold cursor-not-allowed">

                    <p class="text-base font-bold text-[#212842] mt-2">
                        KODE DIBUAT OTOMATIS SAAT DISIMPAN
                    </p>
                </div>

            </div>

            <div class="mb-8">
                <label class="block text-xl font-bold text-[#212842] mb-3">
                    NAMA BARANG *
                </label>

                <input
                    type="text"
                    id="namaBarang"
                    placeholder="MASUKKAN NAMA BARANG"
                    class="w-full bg-white border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold uppercase">
            </div>

            <div class="grid grid-cols-3 gap-10 mb-8">

                <div>
                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        STOK SAAT INI
                    </label>

                    <div class="flex bg-[#ECEDEF] border border-[#BFC5CC] rounded-xl overflow-hidden max-w-xs cursor-not-allowed">
                        <input
                            type="number"
                            id="stokSaatIni"
                            readonly
                            class="w-full px-5 py-4 text-xl font-bold text-[#9AA1A9] outline-none bg-[#ECEDEF] cursor-not-allowed">

                        <span class="px-5 py-4 text-xl font-bold text-[#9AA1A9] bg-[#ECEDEF]">
                            PCS
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        TAMBAH STOK *
                    </label>

                    <div class="flex bg-white border-2 border-[#212842] rounded-xl overflow-hidden max-w-xs">
                        <button
                            type="button"
                            onclick="kurangTambahStok()"
                            class="px-6 py-4 text-3xl font-bold border-r-2 border-[#212842]">
                            −
                        </button>

                        <input
                            type="number"
                            id="tambahStok"
                            value="0"
                            min="0"
                            oninput="hitungStokSetelah()"
                            class="w-full text-center px-5 py-4 text-xl font-bold outline-none">

                        <button
                            type="button"
                            onclick="tambahTambahStok()"
                            class="px-6 py-4 text-3xl font-bold border-l-2 border-r-2 border-[#212842]">
                            +
                        </button>

                        <span class="px-5 py-4 text-xl font-bold">
                            PCS
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xl font-bold text-[#212842] mb-3">
                        STOK SETELAH DITAMBAH
                    </label>

                    <div class="flex bg-[#ECEDEF] border border-[#BFC5CC] rounded-xl overflow-hidden max-w-xs cursor-not-allowed">
                        <input
                            type="number"
                            id="stokSetelah"
                            readonly
                            class="w-full px-5 py-4 text-xl font-bold text-[#9AA1A9] outline-none bg-[#ECEDEF] cursor-not-allowed">

                        <span class="px-5 py-4 text-xl font-bold text-[#9AA1A9] bg-[#ECEDEF]">
                            PCS
                        </span>
                    </div>
                </div>

            </div>

            <div class="mb-16">
                <label class="block text-xl font-bold text-[#212842] mb-3">
                    HARGA SATUAN (RP) *
                </label>

                <input
                    type="text"
                    id="hargaBarang"
                    placeholder="MASUKKAN HARGA SATUAN"
                    class="w-full max-w-md bg-white border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold uppercase">
            </div>

            <div class="flex justify-between items-center">
                <button
                    type="button"
                    onclick="kembaliKeList()"
                    class="bg-white border-2 border-[#212842] text-[#212842] px-9 py-4 rounded-xl text-xl font-extrabold">
                    BATAL
                </button>

                <button
                    type="button"
                    onclick="kembaliKeList()"
                    class="bg-white border-2 border-[#212842] text-[#212842] px-9 py-4 rounded-xl text-xl font-extrabold">
                    SIMPAN
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

        document.getElementById('judulForm').innerText = 'TAMBAH BARANG';
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

        document.getElementById('judulForm').innerText = 'TAMBAH STOK';
        document.getElementById('kodeBarang').value = kode;
        document.getElementById('namaBarang').value = nama.toUpperCase();
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

    const hargaInput = document.getElementById('hargaBarang');

    hargaInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });
</script>
@endsection
