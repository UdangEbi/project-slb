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
                    <p class="text-sm text-gray-500 font-semibold mt-1">Donasi maksimal sebesar kembalian yang tersedia.</p>
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
        // NOTE: LOGO_BASE64 dipersingkat contohnya di sini untuk demonstrasi.
        // Pastikan tetap menggunakan string base64 LOGO ASLI dari file kamu (jangan dipotong) saat menyalin ke server.

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

        // ======================================================
        // updateKembalian():
        // - Donasi otomatis dibatasi (clamp) agar tidak melebihi kembalian yang tersedia
        // - Kembalian tidak akan pernah minus akibat donasi
        // - Kembalian tetap bisa merah/minus HANYA jika tunai < total belanja (itu memang harus dicegah saat submit)
        // ======================================================
        function updateKembalian() {
            const inputTunai = document.getElementById('tunaiDiterima');
            const inputDonasiEl = document.getElementById('inputDonasi');
            const el = document.getElementById('kembalian');
            if (!inputTunai || !el) return;

            let angka = inputTunai.value.replace(/[^0-9]/g, '');
            if (angka === '') { resetKembalian(); return; }

            let tunai = parseInt(angka);
            let kembalianSebelumDonasi = tunai - totalBelanja;

            let donasiAngka = inputDonasiEl ? inputDonasiEl.value.replace(/[^0-9]/g, '') : '';
            let donasi = donasiAngka === '' ? 0 : parseInt(donasiAngka);

            // Donasi tidak boleh melebihi kembalian yang tersedia
            const maxDonasi = Math.max(kembalianSebelumDonasi, 0);
            if (donasi > maxDonasi) {
                donasi = maxDonasi;
                if (inputDonasiEl) {
                    inputDonasiEl.value = donasi > 0 ? donasi.toLocaleString('id-ID') : '';
                }
            }

            let kembali = kembalianSebelumDonasi - donasi;

            if (kembali < 0) {
                el.innerText = formatRupiahMinus(kembali);
                el.classList.remove('text-green-700'); el.classList.add('text-red-600');
            } else {
                el.innerText = formatRupiah(kembali);
                el.classList.remove('text-red-600'); el.classList.add('text-green-700');
            }
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

                // Tunai wajib menutupi total belanja
                if (tunai < totalBelanja) { alert('Tunai diterima masih kurang dari total belanja.'); return; }

                // Donasi tidak boleh melebihi kembalian (tunai - total belanja)
                if (donasi > (tunai - totalBelanja)) { alert('Donasi tidak boleh melebihi kembalian.'); return; }
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

        // ===================== CETAK STRUK (A6) =====================
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
        @page { size: A6; margin: 8mm; }
        body { font-family:'Courier New',monospace; font-size:12px; width:100%; max-width:89mm; margin:0 auto; padding:0; color:#000; background:#fff; }
        @media print { body { width:100%; padding:0; } }

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
                if (kode) { try { JsBarcode(el, kode, { @extends('layouts.kasir')

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
                    <p class="text-sm text-gray-500 font-semibold mt-1">Donasi maksimal sebesar kembalian yang tersedia.</p>
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

        // ======================================================
        // updateKembalian():
        // - Donasi otomatis dibatasi (clamp) agar tidak melebihi kembalian yang tersedia
        // - Kembalian tidak akan pernah minus akibat donasi
        // - Kembalian tetap bisa merah/minus HANYA jika tunai < total belanja (itu memang harus dicegah saat submit)
        // ======================================================
        function updateKembalian() {
            const inputTunai = document.getElementById('tunaiDiterima');
            const inputDonasiEl = document.getElementById('inputDonasi');
            const el = document.getElementById('kembalian');
            if (!inputTunai || !el) return;

            let angka = inputTunai.value.replace(/[^0-9]/g, '');
            if (angka === '') { resetKembalian(); return; }

            let tunai = parseInt(angka);
            let kembalianSebelumDonasi = tunai - totalBelanja;

            let donasiAngka = inputDonasiEl ? inputDonasiEl.value.replace(/[^0-9]/g, '') : '';
            let donasi = donasiAngka === '' ? 0 : parseInt(donasiAngka);

            // Donasi tidak boleh melebihi kembalian yang tersedia
            const maxDonasi = Math.max(kembalianSebelumDonasi, 0);
            if (donasi > maxDonasi) {
                donasi = maxDonasi;
                if (inputDonasiEl) {
                    inputDonasiEl.value = donasi > 0 ? donasi.toLocaleString('id-ID') : '';
                }
            }

            let kembali = kembalianSebelumDonasi - donasi;

            if (kembali < 0) {
                el.innerText = formatRupiahMinus(kembali);
                el.classList.remove('text-green-700'); el.classList.add('text-red-600');
            } else {
                el.innerText = formatRupiah(kembali);
                el.classList.remove('text-red-600'); el.classList.add('text-green-700');
            }
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

                // Tunai wajib menutupi total belanja
                if (tunai < totalBelanja) { alert('Tunai diterima masih kurang dari total belanja.'); return; }

                // Donasi tidak boleh melebihi kembalian (tunai - total belanja)
                if (donasi > (tunai - totalBelanja)) { alert('Donasi tidak boleh melebihi kembalian.'); return; }
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

        // ===================== CETAK STRUK (A6) =====================
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
        @page { size: A6; margin: 8mm; }
        body { font-family:'Courier New',monospace; font-size:12px; width:100%; max-width:89mm; margin:0 auto; padding:0; color:#000; background:#fff; }
        @media print { body { width:100%; padding:0; } }

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

@endsection:'CODE128', width:1.2, height:32, displayValue:false, margin:0 }); } catch(e){} }
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