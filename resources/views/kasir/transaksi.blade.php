{{-- Ganti seluruh file dengan versi yang sudah diperbaiki --}}
@extends('layouts.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

    @if (!session('modal_awal'))
        <div id="modalAwalPopup" class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center">

            <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl p-10 uppercase">

                <div class="text-center mb-6">

                    <div class="mx-auto w-28 h-28 bg-[#212842] rounded-3xl flex items-center justify-center shadow-lg mb-4">
                        <span class="text-[#F0E7D5] text-5xl font-extrabold">
                            RP
                        </span>
                    </div>

                    <h2 class="text-5xl font-extrabold text-[#212842]">
                        MODAL AWAL
                    </h2>

                    <p class="text-2xl font-bold text-gray-500 mt-2">
                        MASUKKAN MODAL AWAL UNTUK MEMULAI TRANSAKSI
                    </p>

                </div>

                <form action="{{ route('kasir.modal-awal.store') }}" method="POST"
                    onsubmit="localStorage.removeItem('keranjangKasir')">
                    @csrf

                    <label class="block text-2xl font-extrabold text-[#212842] mb-3">
                        NOMINAL MODAL AWAL (RP)
                    </label>

                    <div class="flex border-2 border-[#212842] rounded-2xl overflow-hidden mb-4">

                        <span class="bg-[#ECEDEF] px-6 py-5 text-3xl font-extrabold text-[#212842]">
                            RP
                        </span>

                        <input type="text" name="modal_awal" id="modalAwal" value="250.000"
                            oninput="formatModalAwal(this)" class="w-full px-6 py-5 text-3xl font-bold outline-none">

                    </div>

                    <button type="submit"
                        class="w-full bg-[#212842] text-[#F0E7D5] py-5 rounded-2xl text-3xl font-extrabold shadow-md hover:bg-[#151b33] transition">
                        SIMPAN MODAL
                    </button>

                </form>

            </div>

        </div>
    @endif

    <div class="w-full">

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-3 items-start">

            {{-- KIRI --}}
            <div class="space-y-2 min-w-0 self-start">

                {{-- DATA PELANGGAN --}}
                <section class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-3">
                    <h2 class="text-2xl font-extrabold text-[#212842] mb-2">
                        Data Pelanggan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" id="inputNama" placeholder="NAMA CUSTOMER"
                            class="border border-[#D8CDB7] rounded-lg px-3 py-3 text-lg font-extrabold uppercase placeholder:font-extrabold placeholder:uppercase">

                        <input type="text" id="inputTlp" placeholder="NO. TLP / HP" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="border border-[#D8CDB7] rounded-lg px-3 py-3 text-lg font-extrabold uppercase">

                        <input type="text" id="inputInstansi" placeholder="INSTANSI / ASAL"
                            class="border border-[#D8CDB7] rounded-lg px-3 py-3 text-lg font-extrabold uppercase">
                    </div>
                </section>

                {{-- PRODUK --}}
                <section class="bg-white/80 rounded-xl shadow-sm border border-[#D8CDB7] p-3">

                    <div class="flex flex-wrap justify-between items-center gap-3 mb-2">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#212842]">Barang Dijual</h2>
                            <p class="text-lg font-semibold text-gray-500">Produk sesuai rombel yang dipilih</p>
                        </div>

                        <div class="flex gap-2">
                            <input type="text" id="searchProduk" placeholder="Cari barang..." oninput="cariProduk()"
                                class="w-56 border border-[#D8CDB7] rounded-lg px-3 py-3 text-lg font-extrabold uppercase">

                            {{-- TOMBOL CETAK BARCODE --}}
                            <button onclick="bukaCetakBarcode()"
                                class="bg-[#212842] text-[#F0E7D5] px-5 py-3 rounded-lg text-lg font-extrabold hover:bg-[#11172d] flex items-center gap-2 whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Barcode
                            </button>
                        </div>
                    </div>

                    <div id="produkGrid" class="grid gap-3"
                        style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">

                        @forelse ($produk as $item)
                            <div class="produk-card bg-white rounded-lg border border-gray-200 shadow-sm p-3 hover:shadow-md transition"
                                data-nama="{{ strtolower($item['nama']) }}"
                                data-kode="{{ $item['kode'] ?? 'PRD-' . rand(10000, 99999) }}"
                                data-harga="{{ $item['harga'] }}">

                                {{-- BARCODE --}}
                                <div class="flex justify-center mb-2">
                                    <svg class="barcode-produk"
                                        data-kode="{{ $item['kode'] ?? 'PRD-' . rand(10000, 99999) }}"
                                        style="max-width:100%; height:40px;">
                                    </svg>
                                </div>

                                <p class="text-base font-bold text-center text-gray-400 mb-1 tracking-widest">
                                    {{ $item['kode'] ?? '' }}
                                </p>

                                <h3 class="text-lg font-extrabold text-[#212842] leading-snug min-h-[40px] text-center">
                                    {{ $item['nama'] }}
                                </h3>

                                <p class="text-2xl font-black text-[#CA0B00] mt-1 text-center">
                                    Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                </p>

                                <button
                                    onclick="tambahKeranjang({{ $item['id'] }}, '{{ $item['nama'] }}', {{ $item['harga'] }}, '{{ $item['kode'] ?? '' }}')"
                                    class="mt-2 w-full bg-[#212842] text-[#F0E7D5] py-3 rounded-md text-lg font-extrabold hover:bg-[#11172d] transition">
                                    + TAMBAH
                                </button>

                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500 font-bold text-xl">
                                Tidak ada produk untuk rombel ini
                            </div>
                        @endforelse

                    </div>

                    <p id="produkTidakDitemukan" class="hidden text-center py-5 text-gray-500 font-bold text-xl">
                        Barang tidak ditemukan.
                    </p>

                </section>

            </div>

            {{-- KANAN: KERANJANG --}}
            <aside
                class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-3 flex flex-col lg:sticky lg:top-0 lg:self-start lg:max-h-screen">

                <h2 class="text-2xl font-extrabold text-[#212842] mb-2">
                    Keranjang Belanja
                </h2>

                <div id="keranjangList" class="flex-1 space-y-2 overflow-y-auto">
                    <p class="text-gray-400 font-semibold text-lg">Belum ada barang.</p>
                </div>

                <div class="border-t mt-2 pt-2 space-y-1.5">
                    <div class="flex justify-between text-lg font-extrabold text-[#212842]">
                        <span>Jumlah Item</span>
                        <span id="jumlahItem">0 item</span>
                    </div>

                    <div class="flex justify-between text-2xl font-extrabold">
                        <span>Total</span>
                        <span id="totalHarga" class="text-[#CA0B00]">Rp 0</span>
                    </div>
                </div>

                <button onclick="kosongkanKeranjang()"
                    class="mt-2 w-full bg-red-600 text-white py-3 rounded-lg text-lg font-extrabold hover:bg-red-700">
                    Kosongkan Keranjang
                </button>

                <div class="mt-3">
                    <h3 class="text-lg font-extrabold text-[#212842] mb-2">Pembayaran</h3>

                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="bukaCash()"
                            class="bg-green-600 text-white py-4 rounded-lg text-xl font-extrabold hover:bg-green-700">
                            Cash
                        </button>

                        <button onclick="bukaQris()"
                            class="bg-blue-600 text-white py-4 rounded-lg text-xl font-extrabold hover:bg-blue-700">
                            QRIS
                        </button>
                    </div>
                </div>

            </aside>

        </div>

    </div>

    {{-- MODAL CASH --}}
    <div id="modalCash" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl w-[460px] p-6 shadow-xl">
            <h2 class="text-2xl font-extrabold text-[#212842] mb-4">Pembayaran Cash</h2>

            <div class="space-y-3">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total Bayar</span>
                    <span id="cashTotal" class="text-[#CA0B00]">Rp 0</span>
                </div>

                <div>
                    <label class="block font-extrabold text-[#212842] mb-1.5 text-lg">Tunai Diterima</label>
                    <div class="flex border border-[#D8CDB7] rounded-lg overflow-hidden">
                        <span class="bg-[#F7F3EA] px-4 py-3 font-extrabold text-[#212842] text-xl">Rp</span>
                        <input type="text" id="tunaiDiterima" inputmode="numeric" placeholder="0"
                            class="w-full px-4 py-3 text-xl font-bold outline-none">
                    </div>
                </div>

                <div class="flex justify-between text-xl font-extrabold">
                    <span>Kembalian</span>
                    <span id="kembalian" class="text-green-700">Rp 0</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <button onclick="tutupCash()" class="bg-gray-200 py-4 rounded-lg text-lg font-extrabold">Batal</button>
                <button id="btnSelesaiCash" onclick="selesaiBayar('cash')"
                    class="bg-green-600 text-white py-4 rounded-lg text-lg font-extrabold">Selesai</button>
            </div>
        </div>
    </div>

    {{-- MODAL QRIS --}}
    <div id="modalQris" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl w-[400px] p-6 shadow-xl text-center">
            <h2 class="text-2xl font-extrabold text-[#212842] mb-3">Pembayaran QRIS</h2>
            <p class="text-lg font-semibold text-gray-500">Total yang harus dibayar</p>
            <p id="qrisTotal" class="text-3xl font-black text-[#CA0B00] mt-2">Rp 0</p>

            <button id="btnSelesaiQris" onclick="selesaiBayar('qris')"
                class="mt-5 w-full bg-blue-600 text-white py-4 rounded-lg text-lg font-extrabold hover:bg-blue-700">
                OK, Sudah Dibayar
            </button>
            <button onclick="tutupQris()"
                class="mt-2 w-full bg-gray-200 py-4 rounded-lg font-extrabold text-lg">Batal</button>
        </div>
    </div>

    {{-- MODAL SUKSES --}}
    <div id="modalSukses" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl w-[420px] p-6 shadow-xl text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-[#212842] mb-1">Pembayaran Berhasil!</h2>
            <p class="text-lg text-gray-500 font-semibold mb-5">Transaksi telah selesai.</p>

            <div class="grid grid-cols-2 gap-3">
                <button onclick="tutupSukses()" class="bg-gray-200 py-4 rounded-lg text-lg font-extrabold">Tutup</button>
                <button onclick="cetakStruk()"
                    class="bg-[#212842] text-[#F0E7D5] py-4 rounded-lg text-lg font-extrabold hover:bg-[#11172d] flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
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
                <h2 class="text-xl font-extrabold text-[#212842]">Cetak Label Barcode Produk</h2>
                <button onclick="tutupCetakBarcode()"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold">×</button>
            </div>

            {{-- PENGATURAN --}}
            <div class="grid grid-cols-3 gap-3 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div>
                    <label class="block text-sm font-extrabold text-gray-600 mb-1">Jumlah label/produk</label>
                    <input type="number" id="jumlahLabel" value="1" min="1" max="100"
                        class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-base font-bold">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-gray-600 mb-1">Tampilkan harga</label>
                    <select id="tampilHarga"
                        class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-base font-bold">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-gray-600 mb-1">Tampilkan nama produk</label>
                    <select id="tampilNama"
                        class="w-full border border-[#D8CDB7] rounded-lg px-3 py-2 text-base font-bold">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
            </div>

            {{-- PILIH PRODUK --}}
            <div class="flex-1 overflow-y-auto">
                <p class="text-sm font-extrabold text-gray-500 mb-2">Pilih produk yang akan dicetak barcodenya:</p>
                <div id="listPilihBarcode" class="space-y-1.5">
                    @foreach ($produk as $item)
                        <label
                            class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" class="cb-barcode w-5 h-5" data-nama="{{ $item['nama'] }}"
                                data-kode="{{ $item['kode'] ?? '' }}" data-harga="{{ $item['harga'] }}">
                            <div class="flex-1">
                                <p class="text-base font-extrabold text-[#212842]">{{ $item['nama'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['kode'] ?? '-' }} · Rp
                                    {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 mt-4 pt-4 border-t">
                <button onclick="pilihSemuaBarcode()"
                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg text-base font-extrabold hover:bg-gray-200">Pilih
                    Semua</button>
                <button onclick="batalPilihBarcode()"
                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg text-base font-extrabold hover:bg-gray-200">Batal
                    Pilih</button>
                <button onclick="cetakBarcodeLabel()"
                    class="flex-1 bg-[#212842] text-[#F0E7D5] py-3 rounded-lg text-base font-extrabold hover:bg-[#11172d] flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Label Barcode
                </button>
            </div>
        </div>
    </div>

    {{-- STRUK PRINT (hidden di mode normal, dikontrol JS saat print) --}}
    <div id="strukPrint" style="display:none;">
        <div id="strukKonten"></div>
    </div>

    {{-- BARCODE PRINT (hidden di mode normal, dikontrol JS saat print) --}}
    <div id="barcodePrint" style="display:none;">
        <div id="barcodeKonten"></div>
    </div>

    {{-- PRINT STYLE --}}
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body>*:not(#strukPrint):not(#barcodePrint) {
                display: none !important;
            }

            /* STRUK */
            #strukPrint.tampil-print {
                display: block !important;
                font-family: 'Courier New', monospace;
                width: 100%;
                margin: 0 auto;
                padding: 4mm;
                font-size: 12px;
                color: #000;
                box-sizing: border-box;
            }

            /* BARCODE LABEL */
            #barcodePrint.tampil-print {
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

            .barcode-label-item svg {
                display: block;
                margin: 0 auto;
            }

            .label-kode {
                font-size: 8pt;
                color: #666;
                letter-spacing: 1px;
                margin-top: 1mm;
            }

            .label-nama {
                font-size: 9pt;
                font-weight: 900;
                color: #000;
                margin-top: 1mm;
                white-space: normal;
            }

            .label-harga {
                font-size: 11pt;
                font-weight: 900;
                color: #CA0B00;
                margin-top: 1mm;
            }

            .label-toko {
                font-size: 7pt;
                color: #888;
                margin-top: 1mm;
                border-top: 0.5px dashed #ccc;
                padding-top: 1mm;
            }

            .struk-logo {
                text-align: center;
                font-size: 18px;
                font-weight: 900;
                letter-spacing: 2px;
            }

            .struk-sub {
                text-align: center;
                font-size: 10px;
                margin-bottom: 6px;
            }

            .struk-alamat {
                text-align: center;
                font-size: 10px;
                margin-bottom: 4px;
                color: #333;
            }

            .struk-divider {
                border-top: 1px dashed #000;
                margin: 6px 0;
            }

            .struk-row {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 2px;
            }

            .struk-row-item {
                margin-bottom: 5px;
            }

            .struk-total {
                font-weight: 900;
                font-size: 14px;
            }

            .struk-footer {
                text-align: center;
                font-size: 10px;
                margin-top: 10px;
            }

            .struk-footer-tagline {
                text-align: center;
                font-size: 11px;
                font-weight: 900;
                margin-top: 4px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>
        let keranjang = JSON.parse(localStorage.getItem('keranjangKasir')) || [];
        let totalBelanja = 0;
        let metodeBayarTerakhir = '';
        let tunaiTerakhir = 0;
        let dataStruk = null; // data struk dari backend
        let snapshotPelanggan = {};

        // ===== CONFIG TOKO =====
        const TOKO = {
            nama: 'GAPURA',
            tagline: 'Gerakan Aktif Produktif',
            alamat: 'Jl. Imogiri Tim. No.224, Giwangan, Umbulharjo, Yogyakarta 55163',
            telp: '(0274) 371243',
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

        // ===================== KERANJANG (pakai id sebagai kunci unik) =====================
        function tambahKeranjang(id, nama, harga, kode) {
            let item = keranjang.find(p => p.id === id);
            if (item) {
                item.qty++;
            } else {
                keranjang.push({
                    id,
                    nama,
                    harga,
                    kode: kode || '',
                    qty: 1
                });
            }
            simpanKeranjang();
            renderKeranjang();
        }

        function kurangiQty(id) {
            let item = keranjang.find(p => p.id === id);
            if (item) {
                item.qty--;
                if (item.qty <= 0) keranjang = keranjang.filter(p => p.id !== id);
            }
            simpanKeranjang();
            renderKeranjang();
        }

        function tambahQty(id) {
            let item = keranjang.find(p => p.id === id);
            if (item) item.qty++;
            simpanKeranjang();
            renderKeranjang();
        }

        function hapusItem(id) {
            keranjang = keranjang.filter(p => p.id !== id);
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
            let total = 0,
                totalItem = 0;

            if (keranjang.length === 0) {
                list.innerHTML = `<p class="text-gray-400 font-semibold text-lg">Belum ada barang.</p>`;
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
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-extrabold text-[#212842] leading-tight truncate">${item.nama}</p>
                            <p class="text-base text-gray-500">${item.qty} × ${formatRupiah(item.harga)}</p>
                        </div>
                        <button onclick="hapusItem(${item.id})"
                            class="w-8 h-8 flex-shrink-0 rounded-full border-2 border-red-600 text-red-600 font-extrabold text-lg leading-none">×</button>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-1">
                            <button onclick="kurangiQty(${item.id})" class="w-9 h-9 bg-gray-200 rounded text-lg font-bold">-</button>
                            <span class="text-lg font-extrabold px-2">${item.qty}</span>
                            <button onclick="tambahQty(${item.id})" class="w-9 h-9 bg-[#212842] text-[#F0E7D5] rounded text-lg font-bold">+</button>
                        </div>
                        <p class="text-lg font-extrabold">${formatRupiah(subtotal)}</p>
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

        function validasiPelanggan() {
            const nama = document.getElementById('inputNama').value.trim();
            const tlp = document.getElementById('inputTlp').value.trim();
            const instansi = document.getElementById('inputInstansi').value.trim();
            if (!nama) {
                alert('Nama customer wajib diisi.');
                document.getElementById('inputNama').focus();
                return false;
            }
            if (!tlp) {
                alert('No. Tlp / HP wajib diisi.');
                document.getElementById('inputTlp').focus();
                return false;
            }
            if (!instansi) {
                alert('Instansi / Asal wajib diisi.');
                document.getElementById('inputInstansi').focus();
                return false;
            }
            return true;
        }

        function bukaCash() {
            if (totalBelanja <= 0) {
                alert('Keranjang masih kosong');
                return;
            }
            if (!validasiPelanggan()) return;
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
            if (totalBelanja <= 0) {
                alert('Keranjang masih kosong');
                return;
            }
            if (!validasiPelanggan()) return;
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
            if (angka === '') {
                resetKembalian();
                return;
            }

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
                if (angka === '') {
                    e.target.value = '';
                    resetKembalian();
                    return;
                }
                e.target.value = Number(angka).toLocaleString('id-ID');
                updateKembalian();
            }
        });

        // ===================== CHECKOUT: KIRIM KE BACKEND =====================
        function setLoadingTombolBayar(loading) {
            const btnCash = document.getElementById('btnSelesaiCash');
            const btnQris = document.getElementById('btnSelesaiQris');
            [btnCash, btnQris].forEach(btn => {
                if (!btn) return;
                btn.disabled = loading;
                btn.classList.toggle('opacity-50', loading);
            });
        }

        async function selesaiBayar(metode) {
            if (keranjang.length === 0) {
                alert('Keranjang masih kosong.');
                return;
            }

            const inputTunai = document.getElementById('tunaiDiterima');
            let tunai;

            if (metode === 'cash') {
                let angka = inputTunai.value.replace(/[^0-9]/g, '');
                tunai = angka === '' ? 0 : parseInt(angka);
                if (tunai < totalBelanja) {
                    alert('Tunai diterima masih kurang.');
                    return;
                }
            } else {
                tunai = totalBelanja;
            }
            const namaPembeli = document.getElementById('inputNama').value;
            const noTlp = document.getElementById('inputTlp').value;
            const instansi = document.getElementById('inputInstansi').value;

            const payload = {
                keranjang: keranjang.map(item => ({
                    id: item.id,
                    qty: item.qty
                })),
                total: totalBelanja,
                metode: metode, // 'cash' atau 'qris'
                bayar: tunai,

                nama_pembeli: namaPembeli,
                no_tlp: noTlp,
                instansi: instansi,
            };

            setLoadingTombolBayar(true);

            try {
                const response = await fetch("{{ route('kasir.transaksi.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (!response.ok || !data.sukses) {
                    alert(data.pesan || 'Transaksi gagal disimpan.');
                    setLoadingTombolBayar(false);
                    return;
                }

                // Sukses — simpan data struk dari backend, lalu tampilkan modal sukses
                dataStruk = data.struk;
                metodeBayarTerakhir = metode;
                tunaiTerakhir = tunai;
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

            } catch (err) {
                alert('Terjadi kesalahan koneksi. Coba lagi.');
                console.error(err);
            } finally {
                setLoadingTombolBayar(false);
            }
        }

        function tutupSukses() {
            const modalSukses = document.getElementById('modalSukses');
            modalSukses.classList.add('hidden');
            modalSukses.classList.remove('flex');

            keranjang = [];
            localStorage.removeItem('keranjangKasir');
            renderKeranjang();

            // Reload halaman supaya stok produk yang ditampilkan ter-update
            window.location.reload();
        }

        // ===================== CETAK STRUK =====================
        function cetakStruk() {
            if (!dataStruk) {
                alert('Data struk tidak tersedia.');
                return;
            }

            const pelanggan = snapshotPelanggan;
            const metodeTampil = dataStruk.metode === 'tunai' ? 'TUNAI' : 'QRIS';

            let barisItems = '';
            dataStruk.items.forEach(item => {
                barisItems += `
                <tr>
                    <td colspan="2" style="font-weight:bold; padding-top:4px;">${item.nama}</td>
                </tr>
                <tr>
                    <td>${item.qty} &times; Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                    <td style="text-align:right;">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>
            `;
            });

            const htmlStruk = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Struk - ${dataStruk.no_nota}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        width: 280px;
                        margin: 0 auto;
                        padding: 8px;
                        color: #000;
                        background: #fff;
                    }
                    @page { size: 80mm auto; margin: 0; }
                    @media print {
                        body { width: 100%; padding: 4mm; }
                    }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .logo { font-size: 18px; font-weight: 900; letter-spacing: 2px; text-align: center; }
                    .sub { font-size: 10px; text-align: center; margin-bottom: 4px; }
                    .divider { border-top: 1px dashed #000; margin: 6px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    td { font-size: 12px; padding: 1px 0; vertical-align: top; }
                    td:last-child { text-align: right; }
                    .total-row td { font-weight: 900; font-size: 14px; border-top: 1px dashed #000; padding-top: 4px; }
                    .footer { text-align: center; font-size: 10px; margin-top: 8px; }
                    .footer-tagline { text-align: center; font-size: 11px; font-weight: 900; margin-top: 4px; }
                </style>
            </head>
            <body>
                <div class="logo">${TOKO.nama}</div>
                <div class="sub">${TOKO.tagline}</div>
                <div class="sub">${TOKO.alamat}</div>
                <div class="sub">Telp: ${TOKO.telp}</div>
                <div class="divider"></div>
                <table>
                    <tr><td>No. Struk</td><td>${dataStruk.no_nota}</td></tr>
                    <tr><td>Tanggal</td><td>${dataStruk.tanggal}</td></tr>
                    <tr><td>Jam</td><td>${dataStruk.jam}</td></tr>
                    <tr><td>Kasir</td><td>${dataStruk.kasir}</td></tr>
                    ${pelanggan.nama ? `<tr><td>Pelanggan</td><td>${pelanggan.nama}</td></tr>` : ''}
                    ${pelanggan.tlp ? `<tr><td>No. HP</td><td>${pelanggan.tlp}</td></tr>` : ''}
                    ${pelanggan.instansi ? `<tr><td>Instansi</td><td>${pelanggan.instansi}</td></tr>` : ''}
                </table>
                <div class="divider"></div>
                <table>${barisItems}</table>
                <div class="divider"></div>
                <table>
                    <tr class="total-row"><td>TOTAL</td><td>Rp ${Number(dataStruk.total).toLocaleString('id-ID')}</td></tr>
                    <tr><td>Metode</td><td>${metodeTampil}</td></tr>
                    ${dataStruk.metode === 'tunai' ? `
                            <tr><td>Tunai</td><td>Rp ${Number(dataStruk.bayar).toLocaleString('id-ID')}</td></tr>
                            <tr><td>Kembalian</td><td>Rp ${Number(dataStruk.kembalian).toLocaleString('id-ID')}</td></tr>
                            ` : ''}
                </table>
                <div class="divider"></div>
                <div class="footer">${TOKO.pesanBawah}</div>
                <div class="footer-tagline">${TOKO.ucapanTerima}</div>
                <div class="footer">&#9733; &#9733; &#9733;</div>
                <script>
                    window.onload = function() { window.print(); }
                <\/script>
            </body>
            </html>
        `;

            const win = window.open('', '_blank', 'width=350,height=600');
            win.document.write(htmlStruk);
            win.document.close();

            // Tutup modal sukses dan kosongkan keranjang setelah cetak struk
            // setTimeout(() => {
            //     tutupSukses();
            // }, 800);
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
            if (dipilih.length === 0) {
                alert('Pilih minimal satu produk.');
                return;
            }

            const jumlah = parseInt(document.getElementById('jumlahLabel').value) || 1;
            const showHarga = document.getElementById('tampilHarga').value === 'ya';
            const showNama = document.getElementById('tampilNama').value === 'ya';

            let labelItems = [];

            dipilih.forEach(cb => {
                const nama = cb.getAttribute('data-nama');
                const kode = cb.getAttribute('data-kode');
                const harga = parseInt(cb.getAttribute('data-harga'));
                for (let i = 0; i < jumlah; i++) {
                    labelItems.push({
                        nama,
                        kode,
                        harga
                    });
                }
            });

            // Buat HTML barcode di window baru
            let labelHTML = '';
            labelItems.forEach((item, idx) => {
                const svgId = 'bc-' + idx;
                labelHTML += `
                <div class="label-item">
                    <div class="label-toko">${TOKO.nama}</div>
                    ${showNama ? `<div class="label-nama">${item.nama}</div>` : ''}
                    <svg id="${svgId}" data-kode="${item.kode}"></svg>
                    <div class="label-kode">${item.kode}</div>
                    ${showHarga ? `<div class="label-harga">Rp ${item.harga.toLocaleString('id-ID')}</div>` : ''}
                </div>
            `;
            });

            const htmlBarcode = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Label Barcode</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: Arial, sans-serif; background: #fff; }
                    .label-item {
                        display: inline-block;
                        width: 85mm;
                        padding: 3mm;
                        border: 0.5px solid #ccc;
                        text-align: center;
                        margin: 1mm;
                        vertical-align: top;
                        page-break-inside: avoid;
                    }
                    .label-toko { font-size: 7pt; color: #888; margin-bottom: 1mm; }
                    .label-nama { font-size: 9pt; font-weight: 900; color: #000; margin-bottom: 1mm; }
                    .label-kode { font-size: 8pt; color: #666; letter-spacing: 1px; margin-top: 1mm; }
                    .label-harga { font-size: 11pt; font-weight: 900; color: #CA0B00; margin-top: 1mm; }
                    svg { display: block; margin: 0 auto; }
                    @media print {
                        @page { margin: 5mm; }
                        body { background: #fff; }
                    }
                </style>
            </head>
            <body>
                ${labelHTML}
                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script>
                <script>
                    window.onload = function() {
                        document.querySelectorAll('svg[data-kode]').forEach(function(el) {
                            var k = el.getAttribute('data-kode');
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
                        setTimeout(function() { window.print(); }, 500);
                    };
                <\/script>
            </body>
            </html>
        `;

            tutupCetakBarcode();

            const win = window.open('', '_blank', 'width=700,height=500');
            win.document.write(htmlBarcode);
            win.document.close();
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
                    } catch (e) {}
                }
            });
        }

        renderKeranjang();
        initBarcode();

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
