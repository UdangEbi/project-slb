@extends('layouts.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

    @if (!$rekapAktif)
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
                    onsubmit="localStorage.removeItem('keranjangKasir'); localStorage.removeItem('dataPelangganKasir')">
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
                            oninput="this.value = this.value.replace(/[0-9]/g, '')"
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

                    <div class="max-h-[60vh] overflow-y-auto pr-1">

                    <div id="produkGrid" class="grid gap-3"
                        style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">

                        @forelse ($produk as $item)
                            @continue(isset($item['stok']) && $item['stok'] <= 0)
                            <div class="produk-card bg-white rounded-lg border border-gray-200 shadow-sm p-3 hover:shadow-md transition"
                                data-nama="{{ strtolower($item['nama']) }}"
                                data-kode="{{ $item['kode'] ?? 'PRD-' . rand(10000, 99999) }}"
                                data-harga="{{ $item['harga'] }}">

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

                    </div>

                </section>

            </div>

            {{-- KANAN: KERANJANG --}}
            <aside
                class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-3 flex flex-col sticky top-2 max-h-[calc(100vh-1rem)]">

                <h2 class="text-2xl font-extrabold text-[#212842] mb-2 flex-shrink-0">
                    Keranjang Belanja
                </h2>

                <div id="keranjangList" class="space-y-2 overflow-y-auto pr-1" style="max-height: 40vh;">
                    <p class="text-gray-400 font-semibold text-lg">Belum ada barang.</p>
                </div>

                <div class="border-t mt-2 pt-2 space-y-1.5 flex-shrink-0">
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
                    class="mt-2 w-full bg-red-600 text-white py-3 rounded-lg text-lg font-extrabold hover:bg-red-700 flex-shrink-0">
                    Kosongkan Keranjang
                </button>

                <div class="mt-3 flex-shrink-0">
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
        <div class="bg-white rounded-xl w-[500px] p-6 shadow-xl">
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
                            class="w-full px-4 py-3 text-xl font-bold text-right outline-none">
                    </div>
                </div>

                {{-- DONASI --}}
                <div class="border-t pt-3">
                    <label class="block font-extrabold text-[#212842] mb-1.5 text-lg">Donasi (opsional)</label>
                    <div class="flex border border-[#D8CDB7] rounded-lg overflow-hidden">
                        <span class="bg-[#F7F3EA] px-4 py-3 font-extrabold text-[#212842] text-xl">Rp</span>
                        <input type="text" id="inputDonasi" inputmode="numeric" placeholder="0"
                            class="w-full px-4 py-3 text-xl font-bold text-right outline-none">
                    </div>
                </div>

                <div class="flex justify-between text-xl font-extrabold border-t pt-3">
                    <span>Kembalian</span>
                    <span id="kembalian" class="text-green-700">Rp 0</span>
                </div>

            </div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <button onclick="tutupCash()"
                    class="border-2 border-gray-300 text-gray-700 py-4 rounded-lg text-lg font-extrabold hover:bg-gray-50">Batal</button>
                <button id="btnSelesaiCash" onclick="selesaiBayar('cash')"
                    class="border-2 border-green-600 text-green-600 py-4 rounded-lg text-lg font-extrabold hover:bg-green-50">Selesai</button>
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
                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg text-base font-extrabold hover:bg-gray-200">Pilih Semua</button>
                <button onclick="batalPilihBarcode()"
                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg text-base font-extrabold hover:bg-gray-200">Batal Pilih</button>
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

    <div id="strukPrint" style="display:none;"><div id="strukKonten"></div></div>
    <div id="barcodePrint" style="display:none;"><div id="barcodeKonten"></div></div>

    <style>
        @media print {
            @page { size: 80mm auto; margin: 0; }
            body>*:not(#strukPrint):not(#barcodePrint) { display: none !important; }
            #strukPrint.tampil-print {
                display: block !important;
                font-family: 'Courier New', monospace;
                width: 100%; margin: 0 auto; padding: 4mm;
                font-size: 12px; color: #000; box-sizing: border-box;
            }
            #barcodePrint.tampil-print { display: block !important; }
            #barcodePrint { font-family: Arial, sans-serif; }
            .barcode-label-item {
                display: inline-block; width: 85mm; padding: 4mm;
                border: 0.5px solid #ccc; text-align: center;
                margin: 1mm; box-sizing: border-box; page-break-inside: avoid;
            }
            .barcode-label-item svg { display: block; margin: 0 auto; }
            .label-kode { font-size: 8pt; color: #666; letter-spacing: 1px; margin-top: 1mm; }
            .label-nama { font-size: 9pt; font-weight: 900; color: #000; margin-top: 1mm; white-space: normal; }
            .label-harga { font-size: 11pt; font-weight: 900; color: #CA0B00; margin-top: 1mm; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>
        let keranjang = JSON.parse(localStorage.getItem('keranjangKasir')) || [];
        let totalBelanja = 0;
        let dataStruk = null;

        const TOKO = {
            nama: 'GAPURA',
            alamat: 'Jl. Imogiri Tim. No.224, Giwangan, Umbulharjo, Yogyakarta 55163',
            telp: '(0274) 371243',
            ucapanTerima: 'Terima kasih atas kepercayaan Anda!',
            pesanBawah: 'Barang yang sudah dibeli tidak dapat dikembalikan.'
        };

        const LOGO_BASE64 = "data:image/png;base64,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";

        function simpanKeranjang() { localStorage.setItem('keranjangKasir', JSON.stringify(keranjang)); }
        function formatRupiah(angka) { return 'Rp ' + angka.toLocaleString('id-ID'); }
        function formatRupiahMinus(angka) { return '- Rp ' + Math.abs(angka).toLocaleString('id-ID'); }

        function simpanDataPelanggan() {
            const data = {
                nama: document.getElementById('inputNama').value,
                tlp: document.getElementById('inputTlp').value,
                instansi: document.getElementById('inputInstansi').value,
            };
            localStorage.setItem('dataPelangganKasir', JSON.stringify(data));
        }

        function muatDataPelanggan() {
            const simpanan = JSON.parse(localStorage.getItem('dataPelangganKasir') || 'null');
            if (!simpanan) return;
            document.getElementById('inputNama').value = simpanan.nama || '';
            document.getElementById('inputTlp').value = simpanan.tlp || '';
            document.getElementById('inputInstansi').value = simpanan.instansi || '';
        }

        document.addEventListener('input', function(e) {
            if (e.target.id === 'tunaiDiterima') {
                let angka = e.target.value.replace(/[^0-9]/g, '');
                if (angka === '') { e.target.value = ''; resetKembalian(); return; }
                e.target.value = Number(angka).toLocaleString('id-ID');
                updateKembalian();
            }
            if (e.target.id === 'inputDonasi') {
                let angka = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = angka === '' ? '' : Number(angka).toLocaleString('id-ID');
                updateKembalian();
            }
            if (e.target.id === 'inputNama' || e.target.id === 'inputTlp' || e.target.id === 'inputInstansi') {
                simpanDataPelanggan();
            }
        });

        function tambahKeranjang(id, nama, harga, kode) {
            let item = keranjang.find(p => p.id === id);
            let itemBaru = false;
            if (item) { item.qty++; } else { keranjang.push({ id, nama, harga, kode: kode || '', qty: 1 }); itemBaru = true; }
            simpanKeranjang(); renderKeranjang(itemBaru);
        }
        function kurangiQty(id) {
            let item = keranjang.find(p => p.id === id);
            if (item) { item.qty--; if (item.qty <= 0) keranjang = keranjang.filter(p => p.id !== id); }
            simpanKeranjang(); renderKeranjang();
        }
        function tambahQty(id) {
            let item = keranjang.find(p => p.id === id);
            if (item) item.qty++;
            simpanKeranjang(); renderKeranjang();
        }
        function hapusItem(id) { keranjang = keranjang.filter(p => p.id !== id); simpanKeranjang(); renderKeranjang(); }
        function kosongkanKeranjang() { keranjang = []; localStorage.removeItem('keranjangKasir'); renderKeranjang(); }

        function renderKeranjang(scrollKeBawah = false) {
            const list = document.getElementById('keranjangList');
            const totalHarga = document.getElementById('totalHarga');
            const jumlahItem = document.getElementById('jumlahItem');
            list.innerHTML = '';
            let total = 0, totalItem = 0;
            if (keranjang.length === 0) {
                list.innerHTML = `<p class="text-gray-400 font-semibold text-lg">Belum ada barang.</p>`;
                totalHarga.innerText = 'Rp 0'; jumlahItem.innerText = '0 item';
                totalBelanja = 0; updateKembalian(); return;
            }
            keranjang.forEach(item => {
                const subtotal = item.harga * item.qty;
                total += subtotal; totalItem += item.qty;
                list.innerHTML += `
                    <div class="border rounded-lg p-3">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-lg font-extrabold text-[#212842] leading-tight truncate">${item.nama}</p>
                                <p class="text-base text-gray-500">${item.qty} \xd7 ${formatRupiah(item.harga)}</p>
                            </div>
                            <button onclick="hapusItem(${item.id})"
                                class="w-8 h-8 flex-shrink-0 rounded-full border-2 border-red-600 text-red-600 font-extrabold text-lg leading-none">\xd7</button>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div class="flex items-center gap-1">
                                <button onclick="kurangiQty(${item.id})" class="w-9 h-9 bg-gray-200 rounded text-lg font-bold">-</button>
                                <span class="text-lg font-extrabold px-2">${item.qty}</span>
                                <button onclick="tambahQty(${item.id})" class="w-9 h-9 bg-[#212842] text-[#F0E7D5] rounded text-lg font-bold">+</button>
                            </div>
                            <p class="text-lg font-extrabold">${formatRupiah(subtotal)}</p>
                        </div>
                    </div>`;
            });
            totalBelanja = total;
            totalHarga.innerText = formatRupiah(total);
            jumlahItem.innerText = totalItem + ' item';
            updateKembalian();
            if (scrollKeBawah) {
                list.scrollTop = list.scrollHeight;
            }
        }

        function cariProduk() {
            const keyword = document.getElementById('searchProduk').value.toLowerCase();
            const cards = document.querySelectorAll('.produk-card');
            const kosong = document.getElementById('produkTidakDitemukan');
            let ditemukan = 0;
            cards.forEach(card => {
                if (card.getAttribute('data-nama').includes(keyword)) { card.style.display = 'block'; ditemukan++; }
                else { card.style.display = 'none'; }
            });
            kosong.classList.toggle('hidden', ditemukan > 0);
        }

        function validasiPelanggan() {
            const nama = document.getElementById('inputNama').value.trim();
            const tlp = document.getElementById('inputTlp').value.trim();
            const instansi = document.getElementById('inputInstansi').value.trim();
            if (!nama) { alert('Nama customer wajib diisi.'); document.getElementById('inputNama').focus(); return false; }
            if (/[0-9]/.test(nama)) { alert('Nama customer tidak boleh mengandung angka.'); document.getElementById('inputNama').focus(); return false; }
            if (!tlp) { alert('No. Tlp / HP wajib diisi.'); document.getElementById('inputTlp').focus(); return false; }
            if (!instansi) { alert('Instansi / Asal wajib diisi.'); document.getElementById('inputInstansi').focus(); return false; }
            return true;
        }

        function bukaCash() {
            if (totalBelanja <= 0) { alert('Keranjang masih kosong'); return; }
            if (!validasiPelanggan()) return;
            document.getElementById('cashTotal').innerText = formatRupiah(totalBelanja);
            document.getElementById('tunaiDiterima').value = '';
            document.getElementById('inputDonasi').value = '';
            resetKembalian();
            const modal = document.getElementById('modalCash');
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }
        function tutupCash() { const m = document.getElementById('modalCash'); m.classList.add('hidden'); m.classList.remove('flex'); }

        function bukaQris() {
            if (totalBelanja <= 0) { alert('Keranjang masih kosong'); return; }
            if (!validasiPelanggan()) return;
            document.getElementById('qrisTotal').innerText = formatRupiah(totalBelanja);
            const modal = document.getElementById('modalQris');
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }
        function tutupQris() { const m = document.getElementById('modalQris'); m.classList.add('hidden'); m.classList.remove('flex'); }

        function resetKembalian() {
            const el = document.getElementById('kembalian');
            el.innerText = 'Rp 0'; el.classList.remove('text-red-600'); el.classList.add('text-green-700');
        }
        function updateKembalian() {
            const inputTunai = document.getElementById('tunaiDiterima');
            const inputDonasiEl = document.getElementById('inputDonasi');
            const el = document.getElementById('kembalian');
            if (!inputTunai || !el) return;
            let angka = inputTunai.value.replace(/[^0-9]/g, '');
            if (angka === '') { resetKembalian(); return; }
            let donasiAngka = inputDonasiEl ? inputDonasiEl.value.replace(/[^0-9]/g, '') : '';
            let donasi = donasiAngka === '' ? 0 : parseInt(donasiAngka);
            let kembali = parseInt(angka) - totalBelanja - donasi;
            if (kembali < 0) { el.innerText = formatRupiahMinus(kembali); el.classList.remove('text-green-700'); el.classList.add('text-red-600'); }
            else { el.innerText = formatRupiah(kembali); el.classList.remove('text-red-600'); el.classList.add('text-green-700'); }
        }

        function setLoadingTombolBayar(loading) {
            ['btnSelesaiCash','btnSelesaiQris'].forEach(id => {
                const btn = document.getElementById(id);
                if (!btn) return;
                btn.disabled = loading; btn.classList.toggle('opacity-50', loading);
            });
        }

        async function selesaiBayar(metode) {
            if (keranjang.length === 0) { alert('Keranjang masih kosong.'); return; }
            let tunai;
            let donasi = 0;

            if (metode === 'cash') {
                let angka = document.getElementById('tunaiDiterima').value.replace(/[^0-9]/g, '');
                tunai = angka === '' ? 0 : parseInt(angka);
                const donasiAngka = document.getElementById('inputDonasi').value.replace(/[^0-9]/g, '');
                donasi = donasiAngka === '' ? 0 : parseInt(donasiAngka);
                if (tunai < totalBelanja + donasi) { alert('Tunai diterima masih kurang untuk total belanja + donasi.'); return; }
            } else {
                // QRIS tidak ada donasi
                tunai = totalBelanja;
                donasi = 0;
            }

            const payload = {
                keranjang: keranjang.map(item => ({ id: item.id, qty: item.qty })),
                total: totalBelanja,
                metode: metode,
                bayar: tunai,
                nama_pembeli: document.getElementById('inputNama').value.trim(),
                no_tlp: document.getElementById('inputTlp').value.trim(),
                instansi: document.getElementById('inputInstansi').value.trim(),
                donasi: donasi,
            };

            setLoadingTombolBayar(true);
            try {
                const response = await fetch("{{ route('kasir.transaksi.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json();
                if (!response.ok || !data.sukses) { alert(data.pesan || 'Transaksi gagal disimpan.'); setLoadingTombolBayar(false); return; }

                dataStruk = data.struk;
                dataStruk.donasi = donasi;

                tutupCash(); tutupQris();
                const modalSukses = document.getElementById('modalSukses');
                modalSukses.classList.remove('hidden'); modalSukses.classList.add('flex');
            } catch (err) {
                alert('Terjadi kesalahan koneksi. Coba lagi.'); console.error(err);
            } finally {
                setLoadingTombolBayar(false);
            }
        }

        function tutupSukses() {
            const m = document.getElementById('modalSukses');
            m.classList.add('hidden'); m.classList.remove('flex');
            keranjang = []; localStorage.removeItem('keranjangKasir'); renderKeranjang();
            localStorage.removeItem('dataPelangganKasir');
            document.getElementById('inputNama').value = '';
            document.getElementById('inputTlp').value = '';
            document.getElementById('inputInstansi').value = '';
            window.location.reload();
        }

        // ===================== CETAK STRUK =====================
        function cetakStruk() {
            if (!dataStruk) { alert('Data struk tidak tersedia.'); return; }

            const metodeTampil = dataStruk.metode === 'tunai' ? 'TUNAI' : 'QRIS';
            const donasi = dataStruk.donasi || 0;

            let barisItems = '';
            dataStruk.items.forEach(item => {
                const hargaSatuan = item.harga ?? (item.subtotal / item.qty);
                barisItems += `
                    <tr><td colspan="2" class="nama-produk">${item.nama}</td></tr>
                    <tr>
                        <td class="qty-harga">${item.qty} x ${Number(hargaSatuan).toLocaleString('id-ID')}</td>
                        <td class="subtotal">${Number(item.subtotal).toLocaleString('id-ID')}</td>
                    </tr>`;
            });

            const htmlStruk = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk - ${dataStruk.no_nota}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Courier New',monospace; font-size:12px; width:280px; margin:0 auto; padding:8px; color:#000; background:#fff; }
        @page { size:80mm auto; margin:0; }
        @media print { body { width:100%; padding:4mm; } }

        /* HEADER: logo di atas, nama toko di bawahnya (stacked, center) */
        .header-col { text-align:center; margin-bottom:2px; }
        .header-col img { width:56px; height:56px; object-fit:contain; display:block; margin:0 auto 4px; }
        .header-col .nama-toko { font-size:16px; font-weight:900; letter-spacing:2px; line-height:1.1; }
        .header-col .slb { font-size:10px; line-height:1.2; margin-top:1px; }

        .center-line { font-size:10px; text-align:center; margin-top:1px; }
        .divider { border-top:1px dashed #000; margin:6px 0; }

        /* INFO TRANSAKSI */
        .info-table { width:100%; border-collapse:collapse; }
        .info-table td { font-size:12px; padding:1px 0; vertical-align:top; }
        .info-table td:last-child { text-align:right; }

        /* PRODUK */
        .produk-table { width:100%; border-collapse:collapse; }
        .produk-table td { font-size:12px; padding:1px 0; vertical-align:top; }
        .produk-table .nama-produk { font-weight:bold; padding-top:4px; }
        .produk-table .qty-harga { width:65%; text-align:left; }
        .produk-table .subtotal { width:35%; text-align:right; }

        /* TOTAL */
        .total-table { width:100%; border-collapse:collapse; }
        .total-table td { font-size:12px; padding:1px 0; vertical-align:top; }
        .total-table td:last-child { text-align:right; }
        .total-row td { font-weight:900; font-size:14px; padding-top:2px; }
        .donasi-row td { font-style:italic; }

        .footer { text-align:center; font-size:10px; margin-top:8px; }
        .footer-tagline { text-align:center; font-size:11px; font-weight:900; margin-top:4px; }
    </style>
</head>
<body>
    <div class="header-col">
        <img src="${LOGO_BASE64}" alt="Logo">
        <div class="nama-toko">GAPURA</div>
        <div class="slb">SLB Negeri Pembina Yogyakarta</div>
    </div>
    <div class="center-line">${TOKO.alamat}</div>
    <div class="center-line">Telp: ${TOKO.telp}</div>

    <div class="divider"></div>

    <table class="info-table">
        <tr><td>No. Struk</td><td>${dataStruk.no_nota}</td></tr>
        <tr><td>Tanggal</td><td>${dataStruk.tanggal}</td></tr>
        <tr><td>Jam</td><td>${dataStruk.jam}</td></tr>
        <tr><td>Kasir</td><td>${dataStruk.kasir}</td></tr>
        ${dataStruk.nama_pembeli ? `<tr><td>Pelanggan</td><td>${dataStruk.nama_pembeli}</td></tr>` : ''}
        ${dataStruk.no_tlp ? `<tr><td>No. HP</td><td>${dataStruk.no_tlp}</td></tr>` : ''}
        ${dataStruk.instansi ? `<tr><td>Instansi</td><td>${dataStruk.instansi}</td></tr>` : ''}
    </table>

    <div class="divider"></div>

    <table class="produk-table">
        ${barisItems}
    </table>

    <div class="divider"></div>

    <table class="total-table">
        <tr><td>Subtotal</td><td>${Number(dataStruk.total).toLocaleString('id-ID')}</td></tr>
        <tr class="total-row"><td>TOTAL</td><td>${Number(dataStruk.total).toLocaleString('id-ID')}</td></tr>
        <tr><td>Metode</td><td>${metodeTampil}</td></tr>
        ${dataStruk.metode === 'tunai' ? `
        <tr><td>Tunai</td><td>${Number(dataStruk.bayar).toLocaleString('id-ID')}</td></tr>
        <tr><td>Kembalian</td><td>${Number(dataStruk.kembalian).toLocaleString('id-ID')}</td></tr>` : ''}
        ${donasi > 0 ? `<tr class="donasi-row"><td>Donasi</td><td>${donasi.toLocaleString('id-ID')}</td></tr>` : ''}
    </table>

    <div class="divider"></div>
    <div class="footer">${TOKO.pesanBawah}</div>
    <div class="footer-tagline">${TOKO.ucapanTerima}</div>
    <div class="footer">&#9733; &#9733; &#9733;</div>
    <script>window.onload = function() { window.print(); }<\/script>
</body>
</html>`;

            const win = window.open('', '_blank', 'width=350,height=600');
            win.document.write(htmlStruk);
            win.document.close();
            setTimeout(() => { tutupSukses(); }, 800);
        }

        // ===================== MODAL BARCODE =====================
        function bukaCetakBarcode() { const m = document.getElementById('modalBarcode'); m.classList.remove('hidden'); m.classList.add('flex'); }
        function tutupCetakBarcode() { const m = document.getElementById('modalBarcode'); m.classList.add('hidden'); m.classList.remove('flex'); }
        function pilihSemuaBarcode() { document.querySelectorAll('.cb-barcode').forEach(cb => cb.checked = true); }
        function batalPilihBarcode() { document.querySelectorAll('.cb-barcode').forEach(cb => cb.checked = false); }

        function cetakBarcodeLabel() {
            const dipilih = [...document.querySelectorAll('.cb-barcode:checked')];
            if (dipilih.length === 0) { alert('Pilih minimal satu produk.'); return; }
            const jumlah = parseInt(document.getElementById('jumlahLabel').value) || 1;
            const showHarga = document.getElementById('tampilHarga').value === 'ya';
            const showNama = document.getElementById('tampilNama').value === 'ya';
            let labelItems = [];
            dipilih.forEach(cb => {
                const nama = cb.getAttribute('data-nama');
                const kode = cb.getAttribute('data-kode');
                const harga = parseInt(cb.getAttribute('data-harga'));
                for (let i = 0; i < jumlah; i++) { labelItems.push({ nama, kode, harga }); }
            });
            let labelHTML = '';
            labelItems.forEach((item, idx) => {
                labelHTML += `<div class="label-item">
                    <div class="label-toko">${TOKO.nama}</div>
                    ${showNama ? `<div class="label-nama">${item.nama}</div>` : ''}
                    <svg id="bc-${idx}" data-kode="${item.kode}"></svg>
                    <div class="label-kode">${item.kode}</div>
                    ${showHarga ? `<div class="label-harga">Rp ${item.harga.toLocaleString('id-ID')}</div>` : ''}
                </div>`;
            });
            const htmlBarcode = `<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Label Barcode</title>
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:Arial,sans-serif;background:#fff; }
.label-item { display:inline-block;width:85mm;padding:3mm;border:0.5px solid #ccc;text-align:center;margin:1mm;vertical-align:top;page-break-inside:avoid; }
.label-toko { font-size:7pt;color:#888;margin-bottom:1mm; }
.label-nama { font-size:9pt;font-weight:900;color:#000;margin-bottom:1mm; }
.label-kode { font-size:8pt;color:#666;letter-spacing:1px;margin-top:1mm; }
.label-harga { font-size:11pt;font-weight:900;color:#CA0B00;margin-top:1mm; }
svg { display:block;margin:0 auto; }
@media print { @page { margin:5mm; } }
</style></head><body>${labelHTML}
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script>
<script>window.onload=function(){document.querySelectorAll('svg[data-kode]').forEach(function(el){var k=el.getAttribute('data-kode');if(k){try{JsBarcode(el,k,{format:'CODE128',width:1.5,height:40,displayValue:false,margin:2});}catch(e){}}});setTimeout(function(){window.print();},500);};<\/script>
</body></html>`;
            tutupCetakBarcode();
            const win = window.open('', '_blank', 'width=700,height=500');
            win.document.write(htmlBarcode); win.document.close();
        }

        function initBarcode() {
            document.querySelectorAll('.barcode-produk').forEach(el => {
                const kode = el.getAttribute('data-kode');
                if (kode) { try { JsBarcode(el, kode, { format:'CODE128', width:1.2, height:32, displayValue:false, margin:0 }); } catch(e){} }
            });
        }

        function formatModalAwal(input) {
            let angka = input.value.replace(/\D/g, '');
            input.value = angka === '' ? '' : new Intl.NumberFormat('id-ID').format(angka);
        }

        renderKeranjang();
        initBarcode();
        muatDataPelanggan();
    </script>

@endsection
