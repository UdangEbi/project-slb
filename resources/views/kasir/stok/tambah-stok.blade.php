@extends('layouts.kasir')

@section('title', 'Tambah Stok')

@section('content')
    <div class="w-full uppercase pt-8">
        {{-- FORM TAMBAH / EDIT STOK --}}
        <form action="{{ route('kasir.stok.tambah') }}" method="POST">

            @csrf

            <input type="hidden" name="produk_id" value="{{ $produk->id_produk }}">

            <h1 id="judulForm" class="text-4xl font-extrabold text-[#212842] mb-8">
                TAMBAH STOK
            </h1>

            <div class="bg-transparent w-full max-w-6xl">

                <div class="grid grid-cols-2 gap-10 mb-8">

                    <div>
                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            TANGGAL
                        </label>

                        <input type="date" id="tanggal"
                            class="w-full max-w-sm bg-white border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            KODE BARANG
                        </label>

                        <input type="text" id="kodeBarang" value="{{ $produk->kode_produk }}" readonly
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

                    <input type="text" id="namaBarang" value="{{ strtoupper($produk->nama_produk) }}" readonly
                        class="w-full bg-[#ECEDEF] border border-[#BFC5CC] rounded-xl px-5 py-4 text-xl font-bold text-[#9AA1A9] uppercase">
                </div>

                <div class="grid grid-cols-3 gap-10 mb-8">

                    <div>
                        <label class="block text-xl font-bold text-[#212842] mb-3">
                            STOK SAAT INI
                        </label>

                        <div
                            class="flex bg-[#ECEDEF] border border-[#BFC5CC] rounded-xl overflow-hidden max-w-xs cursor-not-allowed">
                            <input type="number" id="stokSaatIni" value="{{ $produk->stok }}" readonly
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
                            <button type="button" onclick="kurangTambahStok()"
                                class="px-6 py-4 text-3xl font-bold border-r-2 border-[#212842]">
                                −
                            </button>

                            <input type="number" name="jumlah" id="tambahStok" value="0" min="0"
                                oninput="hitungStokSetelah()"
                                class="w-full text-center px-5 py-4 text-xl font-bold outline-none">

                            <button type="button" onclick="tambahTambahStok()"
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

                        <div
                            class="flex bg-[#ECEDEF] border border-[#BFC5CC] rounded-xl overflow-hidden max-w-xs cursor-not-allowed">
                            <input type="number" id="stokSetelah" value="{{ $produk->stok }}" readonly
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

                    <input type="text" name="harga_jual" id="hargaBarang" value="{{ number_format($produk->harga_jual, 0, ',', '.') }}"
                        class="w-full max-w-sm bg-white border-2 border-[#212842] rounded-xl px-5 py-4 text-xl font-bold">
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="window.location='{{ route('kasir.stok') }}'"
                        class="bg-white border-2 border-[#212842] text-[#212842] px-9 py-4 rounded-xl text-xl font-extrabold">
                        BATAL
                    </button>

                    <button type="submit" class="bg-[#212842] text-white px-9 py-4 rounded-xl text-xl font-extrabold">
                        SIMPAN
                    </button>
                </div>

            </div>
        </form>
    </div>
    <script>
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

        const hargaInput = document.getElementById('hargaBarang');

        hargaInput.addEventListener('input', function () {

            let angka = this.value.replace(/\D/g, '');

            if (angka === '') {
                this.value = '';
                return;
            }

            this.value = new Intl.NumberFormat('id-ID').format(angka);
        });
    </script>
@endsection
