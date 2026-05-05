@extends('layouts.kasir')

@section('title', 'Tambah Stok')

@section('content')

<div class="grid grid-cols-[1fr_420px] gap-6">

    {{-- KIRI: DATA BARANG --}}
    <section class="bg-white rounded-2xl shadow-sm border p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-[#212842]">
                    Data Barang
                </h2>
                <p class="text-gray-500 font-semibold">
                    Pilih barang sesuai rombel
                </p>
            </div>

            <input type="text"
                id="searchBarang"
                oninput="cariBarang()"
                placeholder="Cari barang..."
                class="w-80 border rounded-xl px-5 py-4 text-lg font-semibold">
        </div>

        <div id="barangGrid" class="grid grid-cols-2 gap-6">

            @forelse ($barang as $index => $item)
                @php
                    $kode = 'BRG-' . strtoupper(substr($item['rombel'], 0, 3)) . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
                @endphp

                <div class="barang-card bg-white border rounded-2xl shadow-sm p-6 hover:shadow-lg transition"
                    data-nama="{{ strtolower($item['nama']) }}"
                    onclick="pilihBarang(
                        '{{ $item['nama'] }}',
                        '{{ $kode }}',
                        {{ $item['stok'] }},
                        {{ $item['harga'] }}
                    )">

                    <h3 class="text-3xl font-extrabold text-[#212842] mb-3">
                        {{ $item['nama'] }}
                    </h3>

                    <p class="text-lg font-bold text-gray-600">
                        Kode: {{ $kode }}
                    </p>

                    <p class="text-lg font-bold text-gray-600">
                        Stok Saat Ini: {{ $item['stok'] }}
                    </p>

                    <p class="text-3xl font-black text-red-600 mt-3">
                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>

                    <button type="button"
                        class="mt-5 w-full bg-[#212842] text-white py-4 rounded-xl text-xl font-extrabold">
                        Pilih Barang
                    </button>

                </div>
            @empty
                <div class="col-span-2 text-center py-10 text-gray-500 font-bold">
                    Tidak ada barang untuk rombel ini
                </div>
            @endforelse

        </div>

    </section>

    {{-- KANAN: FORM STOK --}}
    <aside class="bg-white rounded-2xl shadow-sm border p-6">

        <h2 class="text-3xl font-extrabold text-[#212842] mb-6">
            Tambah Stok
        </h2>

        <form class="space-y-5">

            {{-- TANGGAL --}}
            <div>
                <label class="font-extrabold text-[#212842]">
                    Tanggal
                </label>

                <input type="date"
                    id="tanggal"
                    value="{{ date('Y-m-d') }}"
                    class="mt-2 w-full border rounded-xl px-5 py-4 text-lg font-semibold">
            </div>

            {{-- KODE BARANG --}}
            <div>
                <label class="font-extrabold text-[#212842]">
                    Kode Barang
                </label>

                <input type="text"
                    id="kodeBarang"
                    value="Pilih barang dulu"
                    readonly
                    class="mt-2 w-full border rounded-xl px-5 py-4 text-lg font-semibold bg-gray-100">
            </div>

            {{-- NAMA BARANG --}}
            <div>
                <label class="font-extrabold text-[#212842]">
                    Nama Barang
                </label>

                <input type="text"
                    id="namaBarang"
                    readonly
                    placeholder="Pilih barang"
                    class="mt-2 w-full border rounded-xl px-5 py-4 text-lg font-semibold bg-gray-100">
            </div>

            {{-- STOK --}}
            <div class="grid grid-cols-3 gap-3">

                <div>
                    <label class="font-extrabold text-[#212842]">
                        Stok Saat Ini
                    </label>

                    <input type="text"
                        id="stokSaatIni"
                        readonly
                        value="0"
                        class="mt-2 w-full border rounded-xl px-4 py-4 text-xl font-bold text-center bg-gray-100">
                </div>

                <div>
                    <label class="font-extrabold text-[#212842]">
                        Tambah Stok
                    </label>

                    <div class="mt-2 flex border rounded-xl overflow-hidden">
                        <button type="button"
                            onclick="kurangiStok()"
                            class="w-12 bg-gray-200 text-2xl font-bold">
                            -
                        </button>

                        <input type="text"
                            id="tambahStok"
                            value="0"
                            readonly
                            class="w-full text-center text-xl font-extrabold">

                        <button type="button"
                            onclick="tambahStokBtn()"
                            class="w-12 bg-[#212842] text-white text-2xl font-bold">
                            +
                        </button>
                    </div>
                </div>

                <div>
                    <label class="font-extrabold text-[#212842]">
                        Stok Setelah
                    </label>

                    <input type="text"
                        id="stokSetelah"
                        readonly
                        value="0"
                        class="mt-2 w-full border rounded-xl px-4 py-4 text-xl font-bold text-center bg-gray-100">
                </div>

            </div>

            {{-- HARGA SATUAN --}}
            <div>
                <label class="font-extrabold text-[#212842]">
                    Harga Satuan
                </label>

                <div class="mt-2 flex border rounded-xl overflow-hidden">
                    <span class="bg-gray-100 px-4 py-4 font-extrabold">
                        Rp
                    </span>

                    <input type="text"
                        id="hargaSatuan"
                        placeholder="0"
                        oninput="formatHarga(this)"
                        class="w-full px-5 py-4 text-lg font-bold outline-none">
                </div>

                <p class="text-sm text-gray-500 mt-1">
                    Harga satuan bisa diedit
                </p>
            </div>

            {{-- BUTTON --}}
            <button type="button"
                onclick="simpanStok()"
                class="w-full bg-green-600 text-white py-5 rounded-xl text-2xl font-extrabold hover:bg-green-700">
                Simpan
            </button>

        </form>

    </aside>

</div>

<script>
    let stokAwal = 0;
    let tambahan = 0;

    function pilihBarang(nama, kode, stok, harga) {
        stokAwal = stok;
        tambahan = 0;

        document.getElementById('namaBarang').value = nama;
        document.getElementById('kodeBarang').value = kode;
        document.getElementById('stokSaatIni').value = stokAwal;
        document.getElementById('tambahStok').value = tambahan;
        document.getElementById('stokSetelah').value = stokAwal;
        document.getElementById('hargaSatuan').value = harga.toLocaleString('id-ID');
    }

    function tambahStokBtn() {
        tambahan++;
        updateStok();
    }

    function kurangiStok() {
        if (tambahan > 0) {
            tambahan--;
            updateStok();
        }
    }

    function updateStok() {
        document.getElementById('tambahStok').value = tambahan;
        document.getElementById('stokSetelah').value = stokAwal + tambahan;
    }

    function formatHarga(input) {
        let angka = input.value.replace(/[^0-9]/g, '');

        if (angka === '') {
            input.value = '';
            return;
        }

        input.value = Number(angka).toLocaleString('id-ID');
    }

    function cariBarang() {
        const keyword = document.getElementById('searchBarang').value.toLowerCase();
        const cards = document.querySelectorAll('.barang-card');

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama');
            card.style.display = nama.includes(keyword) ? 'block' : 'none';
        });
    }

    function simpanStok() {
        const namaBarang = document.getElementById('namaBarang').value;
        const kodeBarang = document.getElementById('kodeBarang').value;
        const hargaSatuan = document.getElementById('hargaSatuan').value;

        if (!namaBarang) {
            alert('Pilih barang terlebih dahulu');
            return;
        }

        if (tambahan <= 0) {
            alert('Tambah stok minimal 1');
            return;
        }

        alert(
            'Stok berhasil disimpan\n\n' +
            'Barang: ' + namaBarang + '\n' +
            'Kode: ' + kodeBarang + '\n' +
            'Tambah Stok: ' + tambahan + '\n' +
            'Stok Setelah: ' + (stokAwal + tambahan) + '\n' +
            'Harga: Rp ' + hargaSatuan
        );
    }
</script>

@endsection