{{-- Ganti seluruh file dengan versi yang sudah diperbaiki --}}
@extends('layouts.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

@if (!session('modal_awal'))
<div
    id="modalAwalPopup"
    class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center">

    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl p-10 uppercase">

        <div class="text-center mb-8">

            <div class="mx-auto w-28 h-28 bg-[#212842] rounded-3xl flex items-center justify-center shadow-lg mb-5">
                <span class="text-[#F0E7D5] text-5xl font-extrabold">
                    RP
                </span>
            </div>

            <h2 class="text-4xl font-extrabold text-[#212842]">
                MODAL AWAL
            </h2>

            <p class="text-lg font-bold text-gray-500 mt-2">
                MASUKKAN MODAL AWAL UNTUK MEMULAI TRANSAKSI
            </p>

        </div>

        <form action="{{ route('kasir.modal-awal.store') }}" method="POST">
            @csrf

            <label class="block text-xl font-extrabold text-[#212842] mb-3">
                NOMINAL MODAL AWAL (RP)
            </label>

            <div class="flex border-2 border-[#212842] rounded-2xl overflow-hidden mb-5">

                <span class="bg-[#ECEDEF] px-6 py-5 text-2xl font-extrabold text-[#212842]">
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
                SIMPAN MODAL
            </button>

        </form>

    </div>

</div>
@endif

<div class="xl:scale-[0.92] origin-top max-w-[1500px] mx-auto">

<div class="grid grid-cols-[1fr_360px] gap-4">

    {{-- KIRI --}}
    <div class="space-y-4">

        {{-- DATA PELANGGAN --}}
        <section class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-4">
            <h2 class="text-lg font-extrabold text-[#212842] mb-3">
                Data Pelanggan
            </h2>

            <div class="grid grid-cols-3 gap-3">
                <input type="text" id="inputNama" placeholder="NAMA CUSTOMER"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-base font-extrabold uppercase placeholder:font-extrabold placeholder:uppercase">

                <input type="text" id="inputTlp" placeholder="NO. TLP / HP"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-base font-extrabold uppercase">

                <input type="text" id="inputInstansi" placeholder="INSTANSI / ASAL"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-base font-extrabold uppercase">
            </div>
        </section>

        {{-- PRODUK --}}
        <section class="bg-white/80 rounded-xl shadow-sm border border-[#D8CDB7] p-4">

            <div class="flex justify-between items-center mb-3">
                <div>
                    <h2 class="text-lg font-extrabold text-[#212842]">Barang Dijual</h2>
                    <p class="text-sm font-semibold text-gray-500">Produk sesuai rombel yang dipilih</p>
                </div>

                <div class="flex gap-2">
                    <input type="text" id="searchProduk" placeholder="Cari barang..."
                        oninput="cariProduk()"
                        class="w-56 border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-base font-extrabold uppercase">

                    {{-- TOMBOL CETAK BARCODE --}}
                    <button onclick="bukaCetakBarcode()"
                        class="bg-[#212842] text-[#F0E7D5] px-4 py-2.5 rounded-lg text-sm font-extrabold hover:bg-[#11172d] flex items-center gap-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Barcode
                    </button>
                </div>
            </div>

            <div id="produkGrid" class="grid grid-cols-3 gap-3">

                @forelse ($produk as $item)
                   <div class="produk-card bg-white rounded-lg border border-gray-200 shadow-sm p-3 hover:shadow-md transition"
                        data-nama="{{ strtolower($item['nama']) }}"
                        data-kode="{{ $item['kode'] ?? 'PRD-' . rand(10000,99999) }}"
                        data-harga="{{ $item['harga'] }}">

                        {{-- BARCODE --}}
                        <div class="flex justify-center mb-2">
                            <svg class="barcode-produk"
                                data-kode="{{ $item['kode'] ?? 'PRD-' . rand(10000,99999) }}"
                                style="max-width:100%; height:40px;">
                            </svg>
                        </div>

                        <p class="text-xs font-bold text-center text-gray-400 mb-1 tracking-widest">
                            {{ $item['kode'] ?? '' }}
                        </p>

                        <h3 class="text-sm font-extrabold text-[#212842] leading-snug min-h-[36px]">
                            {{ $item['nama'] }}
                        </h3>

                        <p class="text-xl font-black text-[#CA0B00] mt-1">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </p>

                        <button
                            onclick="tambahKeranjang('{{ $item['nama'] }}', {{ $item['harga'] }}, '{{ $item['kode'] ?? '' }}')"
                            class="mt-2 w-full bg-[#212842] text-[#F0E7D5] py-2.5 rounded-md text-sm font-extrabold hover:bg-[#11172d] transition">
                            + TAMBAH
                        </button>

                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-500 font-bold text-base">
                        Tidak ada produk untuk rombel ini
                    </div>
                @endforelse

            </div>

            <p id="produkTidakDitemukan" class="hidden text-center py-5 text-gray-500 font-bold text-base">
                Barang tidak ditemukan.
            </p>

        </section>

    </div>

    {{-- KANAN: KERANJANG --}}
    <aside class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-4 flex flex-col">

        <h2 class="text-lg font-extrabold text-[#212842] mb-3">
            Keranjang Belanja
        </h2>

        <div id="keranjangList" class="flex-1 space-y-2 overflow-y-auto">
            <p class="text-gray-400 font-semibold text-sm">Belum ada barang.</p>
        </div>

        <div class="border-t mt-3 pt-3 space-y-1.5">
            <div class="flex justify-between text-sm font-extrabold text-[#212842]">
                <span>Jumlah Item</span>
                <span id="jumlahItem">0 item</span>
            </div>

            <div class="flex justify-between text-lg font-extrabold">
                <span>Total</span>
                <span id="totalHarga" class="text-[#CA0B00]">Rp 0</span>
            </div>
        </div>

        <button onclick="kosongkanKeranjang()"
            class="mt-3 w-full bg-red-600 text-white py-2.5 rounded-lg text-sm font-extrabold hover:bg-red-700">
            Kosongkan Keranjang
        </button>

        <div class="mt-4">
            <h3 class="text-sm font-extrabold text-[#212842] mb-2">Pembayaran</h3>

            <div class="grid grid-cols-2 gap-2">
                <button onclick="bukaCash()"
                    class="bg-green-600 text-white py-3 rounded-lg text-sm font-extrabold hover:bg-green-700">
                    Cash
                </button>

                <button onclick="bukaQris()"
                    class="bg-blue-600 text-white py-3 rounded-lg text-sm font-extrabold hover:bg-blue-700">
                    QRIS
                </button>
            </div>
        </div>

    </aside>

</div>

</div>

{{-- MODAL CASH --}}
<div id="modalCash" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-xl w-[420px] p-5 shadow-xl">
        <h2 class="text-lg font-extrabold text-[#212842] mb-4">Pembayaran Cash</h2>

        <div class="space-y-3">
            <div class="flex justify-between text-base font-bold">
                <span>Total Bayar</span>
                <span id="cashTotal" class="text-[#CA0B00]">Rp 0</span>
            </div>

            <div>
                <label class="block font-extrabold text-[#212842] mb-1.5 text-sm">Tunai Diterima</label>
                <div class="flex border border-[#D8CDB7] rounded-lg overflow-hidden">
                    <span class="bg-[#F7F3EA] px-3 py-2.5 font-extrabold text-[#212842] text-base">Rp</span>
                    <input type="text" id="tunaiDiterima"
                        inputmode="numeric"
                        placeholder="0"
                        class="w-full px-3 py-2.5 text-base font-bold outline-none">
                </div>
            </div>

            <div class="flex justify-between text-base font-extrabold">
                <span>Kembalian</span>
                <span id="kembalian" class="text-green-700">Rp 0</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <button onclick="tutupCash()" class="bg-gray-200 py-3 rounded-lg text-sm font-extrabold">Batal</button>
            <button onclick="selesaiBayar('cash')" class="bg-green-600 text-white py-3 rounded-lg text-sm font-extrabold">Selesai</button>
        </div>
    </div>
</div>

{{-- MODAL QRIS --}}
<div id="modalQris" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-xl w-[380px] p-5 shadow-xl text-center">
        <h2 class="text-lg font-extrabold text-[#212842] mb-3">Pembayaran QRIS</h2>
        <p class="text-sm font-semibold text-gray-500">Total yang harus dibayar</p>
        <p id="qrisTotal" class="text-2xl font-black text-[#CA0B00] mt-2">Rp 0</p>

        <button onclick="selesaiBayar('qris')"
            class="mt-5 w-full bg-blue-600 text-white py-3 rounded-lg text-sm font-extrabold hover:bg-blue-700">
            OK, Sudah Dibayar
        </button>
        <button onclick="tutupQris()" class="mt-2 w-full bg-gray-200 py-3 rounded-lg font-extrabold text-sm">Batal</button>
    </div>
</div>

{{-- MODAL SUKSES --}}
<div id="modalSukses" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-xl w-[400px] p-6 shadow-xl text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-lg font-extrabold text-[#212842] mb-1">Pembayaran Berhasil!</h2>
        <p class="text-sm text-gray-500 font-semibold mb-5">Transaksi telah selesai.</p>

        <div class="grid grid-cols-2 gap-3">
            <button onclick="tutupSukses()" class="bg-gray-200 py-3 rounded-lg text-sm font-extrabold">Tutup</button>
            <button onclick="cetakStruk()"
                class="bg-[#212842] text-[#F0E7D5] py-3 rounded-lg text-sm font-extrabold hover:bg-[#11172d] flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </button>
        </div>
    </div>
</div>

{{-- MODAL CETAK BARCODE --}}
<div id="modalBarcode" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-xl w-[680px] max-h-[80vh] p-5 shadow-xl flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-extrabold text-[#212842]">Cetak Label Barcode Produk</h2>
            <button onclick="tutupCetakBarcode()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">×</button>
        </div>

        {{-- PENGATURAN --}}
        <div class="grid grid-cols-3 gap-3 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
            <div>
                <label class="block text-xs font-extrabold text-gray-600 mb-1">Jumlah label/produk</label>
                <input type="number" id="jumlahLabel" value="1" min="1" max="100"
                    class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-gray-600 mb-1">Tampilkan harga</label>
                <select id="tampilHarga" class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-sm font-bold">
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-gray-600 mb-1">Tampilkan nama produk</label>
                <select id="tampilNama" class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-sm font-bold">
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>
            </div>
        </div>

        {{-- PILIH PRODUK --}}
        <div class="flex-1 overflow-y-auto">
            <p class="text-xs font-extrabold text-gray-500 mb-2">Pilih produk yang akan dicetak barcodenya:</p>
            <div id="listPilihBarcode" class="space-y-1.5">
                @foreach ($produk as $item)
                <label class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" class="cb-barcode w-4 h-4"
                        data-nama="{{ $item['nama'] }}"
                        data-kode="{{ $item['kode'] ?? '' }}"
                        data-harga="{{ $item['harga'] }}">
                    <div class="flex-1">
                        <p class="text-sm font-extrabold text-[#212842]">{{ $item['nama'] }}</p>
                        <p class="text-xs text-gray-500">{{ $item['kode'] ?? '-' }} · Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3 mt-4 pt-4 border-t">
            <button onclick="pilihSemuaBarcode()" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-extrabold hover:bg-gray-200">Pilih Semua</button>
            <button onclick="batalPilihBarcode()" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-extrabold hover:bg-gray-200">Batal Pilih</button>
            <button onclick="cetakBarcodeLabel()"
                class="flex-1 bg-[#212842] text-[#F0E7D5] py-2.5 rounded-lg text-sm font-extrabold hover:bg-[#11172d] flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Label Barcode
            </button>
        </div>
    </div>
</div>

{{-- STRUK PRINT (hidden) --}}
<div id="strukPrint" class="hidden">
    <div id="strukKonten"></div>
</div>

{{-- BARCODE PRINT (hidden) --}}
<div id="barcodePrint" class="hidden">
    <div id="barcodeKonten"></div>
</div>

{{-- PRINT STYLE --}}
<style>
@media print {
    body > * { display: none !important; }

    /* STRUK */
    #strukPrint:not(.hidden-print) {
        display: block !important;
        font-family: 'Courier New', monospace;
        width: 280px;
        margin: 0 auto;
        padding: 8px;
        font-size: 12px;
        color: #000;
    }

    /* BARCODE LABEL */
    #barcodePrint:not(.hidden-print) {
        display: block !important;
    }
    #barcodePrint {
        font-family: Arial, sans-serif;
    }
    .barcode-label-item {
        display: inline-block;
        width: 85mm;
        padding: 4mm;
        border: 0.5px solid #ccc;
        text-align: center;
        margin: 1mm;
        box-sizing: border-box;
        page-break-inside: avoid;
    }
    .barcode-label-item svg { display: block; margin: 0 auto; }
    .label-kode { font-size: 8pt; color: #666; letter-spacing: 1px; margin-top: 1mm; }
    .label-nama { font-size: 9pt; font-weight: 900; color: #000; margin-top: 1mm; white-space: normal; }
    .label-harga { font-size: 11pt; font-weight: 900; color: #CA0B00; margin-top: 1mm; }
    .label-toko { font-size: 7pt; color: #888; margin-top: 1mm; border-top: 0.5px dashed #ccc; padding-top: 1mm; }

    .struk-logo { text-align: center; font-size: 18px; font-weight: 900; letter-spacing: 2px; }
    .struk-sub { text-align: center; font-size: 10px; margin-bottom: 6px; }
    .struk-alamat { text-align: center; font-size: 10px; margin-bottom: 4px; color: #333; }
    .struk-divider { border-top: 1px dashed #000; margin: 6px 0; }
    .struk-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 2px; }
    .struk-row-item { margin-bottom: 5px; }
    .struk-total { font-weight: 900; font-size: 14px; }
    .struk-footer { text-align: center; font-size: 10px; margin-top: 10px; }
    .struk-footer-tagline { text-align: center; font-size: 11px; font-weight: 900; margin-top: 4px; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

<script>
    let keranjang = JSON.parse(localStorage.getItem('keranjangKasir')) || [];
    let totalBelanja = 0;
    let metodeBayarTerakhir = '';
    let tunaiTerakhir = 0;
    let snapshotKeranjang = [];
    let snapshotPelanggan = {};

    // ===== CONFIG TOKO (sesuaikan dengan data toko Anda) =====
    const TOKO = {
        nama: 'GAPURA',
        tagline: 'Gerakan Aktif Produktif',
        alamat: 'Jl. Contoh No. 1, Kota',
        telp: '(0274) 000-0000',
        ucapanTerima: 'Terima kasih atas kepercayaan Anda!',
        pesanBawah: 'Barang yang sudah dibeli tidak dapat dikembalikan.'
    };

    function simpanKeranjang() {
        localStorage.setItem('keranjangKasir', JSON.stringify(keranjang));
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function formatRupiahMinus(angka) {
        return '- Rp ' + Math.abs(angka).toLocaleString('id-ID');
    }

    function tambahKeranjang(nama, harga, kode) {
        let item = keranjang.find(p => p.nama === nama);
        if (item) {
            item.qty++;
        } else {
            keranjang.push({ nama, harga, kode: kode || '', qty: 1 });
        }
        simpanKeranjang();
        renderKeranjang();
    }

    function kurangiQty(nama) {
        let item = keranjang.find(p => p.nama === nama);
        if (item) {
            item.qty--;
            if (item.qty <= 0) keranjang = keranjang.filter(p => p.nama !== nama);
        }
        simpanKeranjang();
        renderKeranjang();
    }

    function tambahQty(nama) {
        let item = keranjang.find(p => p.nama === nama);
        if (item) item.qty++;
        simpanKeranjang();
        renderKeranjang();
    }

    function hapusItem(nama) {
        keranjang = keranjang.filter(p => p.nama !== nama);
        simpanKeranjang();
        renderKeranjang();
    }

    function kosongkanKeranjang() {
        keranjang = [];
        localStorage.removeItem('keranjangKasir');
        renderKeranjang();
    }

    function renderKeranjang() {
        const list = document.getElementById('keranjangList');
        const totalHarga = document.getElementById('totalHarga');
        const jumlahItem = document.getElementById('jumlahItem');

        list.innerHTML = '';
        let total = 0, totalItem = 0;

        if (keranjang.length === 0) {
            list.innerHTML = `<p class="text-gray-400 font-semibold text-sm">Belum ada barang.</p>`;
            totalHarga.innerText = 'Rp 0';
            jumlahItem.innerText = '0 item';
            totalBelanja = 0;
            updateKembalian();
            return;
        }

        keranjang.forEach(item => {
            const subtotal = item.harga * item.qty;
            total += subtotal;
            totalItem += item.qty;

            list.innerHTML += `
                <div class="border rounded-lg p-2.5">
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-[#212842] leading-tight truncate">${item.nama}</p>
                            <p class="text-xs text-gray-500">${item.qty} × ${formatRupiah(item.harga)}</p>
                        </div>
                        <button onclick="hapusItem('${item.nama}')"
                            class="w-6 h-6 flex-shrink-0 rounded-full border-2 border-red-600 text-red-600 font-extrabold text-sm leading-none">×</button>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-1">
                            <button onclick="kurangiQty('${item.nama}')" class="w-7 h-7 bg-gray-200 rounded text-sm font-bold">-</button>
                            <span class="text-sm font-extrabold px-1">${item.qty}</span>
                            <button onclick="tambahQty('${item.nama}')" class="w-7 h-7 bg-[#212842] text-[#F0E7D5] rounded text-sm font-bold">+</button>
                        </div>
                        <p class="text-sm font-extrabold">${formatRupiah(subtotal)}</p>
                    </div>
                </div>
            `;
        });

        totalBelanja = total;
        totalHarga.innerText = formatRupiah(total);
        jumlahItem.innerText = totalItem + ' item';
        updateKembalian();
    }

    function cariProduk() {
        const keyword = document.getElementById('searchProduk').value.toLowerCase();
        const cards = document.querySelectorAll('.produk-card');
        const kosong = document.getElementById('produkTidakDitemukan');
        let ditemukan = 0;

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama');
            if (nama.includes(keyword)) {
                card.style.display = 'block';
                ditemukan++;
            } else {
                card.style.display = 'none';
            }
        });

        kosong.classList.toggle('hidden', ditemukan > 0);
    }

    function bukaCash() {
        if (totalBelanja <= 0) { alert('Keranjang masih kosong'); return; }
        document.getElementById('cashTotal').innerText = formatRupiah(totalBelanja);
        document.getElementById('tunaiDiterima').value = '';
        resetKembalian();
        const modal = document.getElementById('modalCash');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupCash() {
        const modal = document.getElementById('modalCash');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function bukaQris() {
        if (totalBelanja <= 0) { alert('Keranjang masih kosong'); return; }
        document.getElementById('qrisTotal').innerText = formatRupiah(totalBelanja);
        const modal = document.getElementById('modalQris');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupQris() {
        const modal = document.getElementById('modalQris');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function resetKembalian() {
        const kembalianEl = document.getElementById('kembalian');
        kembalianEl.innerText = 'Rp 0';
        kembalianEl.classList.remove('text-red-600');
        kembalianEl.classList.add('text-green-700');
    }

    function updateKembalian() {
        const inputTunai = document.getElementById('tunaiDiterima');
        const kembalianEl = document.getElementById('kembalian');
        if (!inputTunai || !kembalianEl) return;

        let angka = inputTunai.value.replace(/[^0-9]/g, '');
        if (angka === '') { resetKembalian(); return; }

        let tunai = parseInt(angka);
        let kembali = tunai - totalBelanja;

        if (kembali < 0) {
            kembalianEl.innerText = formatRupiahMinus(kembali);
            kembalianEl.classList.remove('text-green-700');
            kembalianEl.classList.add('text-red-600');
        } else {
            kembalianEl.innerText = formatRupiah(kembali);
            kembalianEl.classList.remove('text-red-600');
            kembalianEl.classList.add('text-green-700');
        }
    }

    document.addEventListener('input', function(e) {
        if (e.target.id === 'tunaiDiterima') {
            let angka = e.target.value.replace(/[^0-9]/g, '');
            if (angka === '') { e.target.value = ''; resetKembalian(); return; }
            e.target.value = Number(angka).toLocaleString('id-ID');
            updateKembalian();
        }
    });

    function selesaiBayar(metode) {
        const inputTunai = document.getElementById('tunaiDiterima');

        if (metode === 'cash') {
            let angka = inputTunai.value.replace(/[^0-9]/g, '');
            let tunai = angka === '' ? 0 : parseInt(angka);
            if (tunai < totalBelanja) { alert('Tunai diterima masih kurang.'); return; }
            tunaiTerakhir = tunai;
        } else {
            tunaiTerakhir = totalBelanja;
        }

        metodeBayarTerakhir = metode;
        snapshotKeranjang = [...keranjang];
        snapshotPelanggan = {
            nama: document.getElementById('inputNama')?.value || '',
            tlp: document.getElementById('inputTlp')?.value || '',
            instansi: document.getElementById('inputInstansi')?.value || ''
        };

        tutupCash();
        tutupQris();

        const modalSukses = document.getElementById('modalSukses');
        modalSukses.classList.remove('hidden');
        modalSukses.classList.add('flex');
    }

    function tutupSukses() {
        const modalSukses = document.getElementById('modalSukses');
        modalSukses.classList.add('hidden');
        modalSukses.classList.remove('flex');

        keranjang = [];
        localStorage.removeItem('keranjangKasir');
        renderKeranjang();
    }

    // ===================== CETAK STRUK =====================
    function cetakStruk() {
        const sekarang = new Date();
        const tgl = sekarang.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
        const jam = sekarang.toLocaleTimeString('id-ID');
        const noStruk = 'TRX-' + sekarang.getFullYear() +
            String(sekarang.getMonth()+1).padStart(2,'0') +
            String(sekarang.getDate()).padStart(2,'0') + '-' +
            String(Math.floor(Math.random()*9000)+1000);

        let barisItems = '';
        snapshotKeranjang.forEach(item => {
            const subtotal = item.harga * item.qty;
            barisItems += `
                <div class="struk-row-item">
                    <div style="font-weight:bold;">${item.nama}</div>
                    <div class="struk-row">
                        <span>${item.qty} × ${formatRupiah(item.harga)}</span>
                        <span>${formatRupiah(subtotal)}</span>
                    </div>
                </div>
            `;
        });

        const kembalian = metodeBayarTerakhir === 'cash' ? (tunaiTerakhir - totalBelanja) : 0;
        const metodeTampil = metodeBayarTerakhir === 'cash' ? 'TUNAI' : 'QRIS';

        document.getElementById('strukKonten').innerHTML = `
            <div class="struk-logo">${TOKO.nama}</div>
            <div class="struk-sub">${TOKO.tagline}</div>
            <div class="struk-alamat">${TOKO.alamat}</div>
            <div class="struk-alamat">Telp: ${TOKO.telp}</div>
            <div class="struk-divider"></div>
            <div class="struk-row"><span>No. Struk</span><span>${noStruk}</span></div>
            <div class="struk-row"><span>Tanggal</span><span>${tgl}</span></div>
            <div class="struk-row"><span>Jam</span><span>${jam}</span></div>
            ${snapshotPelanggan.nama ? `<div class="struk-row"><span>Pelanggan</span><span>${snapshotPelanggan.nama}</span></div>` : ''}
            ${snapshotPelanggan.tlp ? `<div class="struk-row"><span>No. HP</span><span>${snapshotPelanggan.tlp}</span></div>` : ''}
            ${snapshotPelanggan.instansi ? `<div class="struk-row"><span>Instansi</span><span>${snapshotPelanggan.instansi}</span></div>` : ''}
            <div class="struk-divider"></div>
            ${barisItems}
            <div class="struk-divider"></div>
            <div class="struk-row struk-total"><span>TOTAL</span><span>${formatRupiah(totalBelanja)}</span></div>
            <div class="struk-row"><span>Metode</span><span>${metodeTampil}</span></div>
            ${metodeBayarTerakhir === 'cash' ? `
            <div class="struk-row"><span>Tunai</span><span>${formatRupiah(tunaiTerakhir)}</span></div>
            <div class="struk-row"><span>Kembalian</span><span>${formatRupiah(kembalian)}</span></div>
            ` : ''}
            <div class="struk-divider"></div>
            <div class="struk-footer">${TOKO.pesanBawah}</div>
            <div class="struk-footer-tagline">${TOKO.ucapanTerima}</div>
            <div class="struk-footer">★ ★ ★</div>
        `;

        // Sembunyikan barcodePrint saat cetak struk
        document.getElementById('barcodePrint').classList.add('hidden-print');
        document.getElementById('strukPrint').classList.remove('hidden-print');

        window.print();

        setTimeout(() => {
            tutupSukses();
        }, 500);
    }

    // ===================== MODAL BARCODE =====================
    function bukaCetakBarcode() {
        const modal = document.getElementById('modalBarcode');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupCetakBarcode() {
        const modal = document.getElementById('modalBarcode');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function pilihSemuaBarcode() {
        document.querySelectorAll('.cb-barcode').forEach(cb => cb.checked = true);
    }

    function batalPilihBarcode() {
        document.querySelectorAll('.cb-barcode').forEach(cb => cb.checked = false);
    }

    function cetakBarcodeLabel() {
        const dipilih = [...document.querySelectorAll('.cb-barcode:checked')];
        if (dipilih.length === 0) { alert('Pilih minimal satu produk.'); return; }

        const jumlah = parseInt(document.getElementById('jumlahLabel').value) || 1;
        const showHarga = document.getElementById('tampilHarga').value === 'ya';
        const showNama = document.getElementById('tampilNama').value === 'ya';

        let labelHTML = '';

        dipilih.forEach(cb => {
            const nama = cb.getAttribute('data-nama');
            const kode = cb.getAttribute('data-kode');
            const harga = parseInt(cb.getAttribute('data-harga'));

            for (let i = 0; i < jumlah; i++) {
                const svgId = 'bc-print-' + kode.replace(/[^a-zA-Z0-9]/g, '') + '-' + i;
                labelHTML += `
                    <div class="barcode-label-item">
                        <div class="label-toko" style="border:none; margin-bottom:1mm;">${TOKO.nama}</div>
                        ${showNama ? `<div class="label-nama">${nama}</div>` : ''}
                        <svg id="${svgId}" data-kode="${kode}"></svg>
                        <div class="label-kode">${kode}</div>
                        ${showHarga ? `<div class="label-harga">Rp ${harga.toLocaleString('id-ID')}</div>` : ''}
                    </div>
                `;
            }
        });

        const barcodeKonten = document.getElementById('barcodeKonten');
        barcodeKonten.innerHTML = labelHTML;

        // Generate semua barcode SVG
        barcodeKonten.querySelectorAll('svg[data-kode]').forEach(el => {
            const k = el.getAttribute('data-kode');
            if (k) {
                try {
                    JsBarcode(el, k, {
                        format: 'CODE128',
                        width: 1.5,
                        height: 40,
                        displayValue: false,
                        margin: 2
                    });
                } catch(e) {}
            }
        });

        tutupCetakBarcode();

        // Set mode print barcode
        document.getElementById('strukPrint').classList.add('hidden-print');
        document.getElementById('barcodePrint').classList.remove('hidden-print');

        setTimeout(() => {
            window.print();
        }, 300);
    }

    // ===================== INIT =====================
    function initBarcode() {
        document.querySelectorAll('.barcode-produk').forEach(el => {
            const kode = el.getAttribute('data-kode');
            if (kode) {
                try {
                    JsBarcode(el, kode, {
                        format: 'CODE128',
                        width: 1.2,
                        height: 32,
                        displayValue: false,
                        margin: 0
                    });
                } catch(e) {}
            }
        });
    }

    renderKeranjang();
        function formatModalAwal(input) {
        let angka = input.value.replace(/\D/g, '');

        if (angka === '') {
            input.value = '';
            return;
        }

        input.value = new Intl.NumberFormat('id-ID').format(angka);
    }

</script>

@endsection
