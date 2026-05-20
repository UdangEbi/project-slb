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

<div class="grid grid-cols-[1fr_320px] gap-3">

    {{-- KIRI --}}
    <div class="space-y-3">

        {{-- DATA PELANGGAN --}}
        <section class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-3">
            <h2 class="text-xl font-extrabold text-[#212842] mb-3">
                Data Pelanggan
            </h2>

            <div class="grid grid-cols-3 gap-2">
                <input type="text" placeholder="Nama Customer"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-sm font-semibold">

                <input type="text" placeholder="No. Tlp / HP"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-sm font-semibold">

                <input type="text" placeholder="Instansi / Asal"
                    class="border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-sm font-semibold">
            </div>
        </section>

        {{-- PRODUK --}}
        <section class="bg-white/80 rounded-xl shadow-sm border border-[#D8CDB7] p-3">

            <div class="flex justify-between items-center mb-3">
                <div>
                    <h2 class="text-xl font-extrabold text-[#212842]">
                        Barang Dijual
                    </h2>
                    <p class="text-xs font-semibold text-gray-500">
                        Produk sesuai rombel yang dipilih
                    </p>
                </div>

                <input type="text" id="searchProduk" placeholder="Cari barang..."
                    oninput="cariProduk()"
                    class="w-56 border border-[#D8CDB7] rounded-lg px-3 py-2.5 text-xs font-semibold">
            </div>

            <div id="produkGrid" class="grid grid-cols-5 gap-2">

                @forelse ($produk as $item)
                    <div class="produk-card bg-white rounded-lg border border-gray-200 shadow-sm p-2.5 hover:shadow-md transition"
                        data-nama="{{ strtolower($item['nama']) }}">

                        <h3 class="text-sm font-extrabold text-[#212842] leading-tight min-h-[38px]">
                            {{ $item['nama'] }}
                        </h3>

                        <p class="text-base font-black text-[#CA0B00] mt-1.5">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </p>

                        <button
                            onclick="tambahKeranjang('{{ $item['nama'] }}', {{ $item['harga'] }})"
                            class="mt-2 w-full bg-[#212842] text-[#F0E7D5] py-1.5 rounded-md text-xs font-extrabold hover:bg-[#11172d] transition">
                            + Tambah
                        </button>

                    </div>
                @empty
                    <div class="col-span-5 text-center py-6 text-gray-500 font-bold text-sm">
                        Tidak ada produk untuk rombel ini
                    </div>
                @endforelse

            </div>

            <p id="produkTidakDitemukan" class="hidden text-center py-5 text-gray-500 font-bold text-sm">
                Barang tidak ditemukan.
            </p>

        </section>

    </div>

    {{-- KANAN: KERANJANG --}}
    <aside class="bg-white rounded-xl shadow-sm border border-[#D8CDB7] p-3 flex flex-col">

        <h2 class="text-xl font-extrabold text-[#212842] mb-3">
            Keranjang Belanja
        </h2>

        <div id="keranjangList" class="flex-1 space-y-2 overflow-y-auto">
            <p class="text-gray-400 font-semibold text-xs">
                Belum ada barang.
            </p>
        </div>

        <div class="border-t mt-3 pt-3 space-y-1.5">
            <div class="flex justify-between text-sm font-extrabold text-[#212842]">
                <span>Jumlah Item</span>
                <span id="jumlahItem">0 item</span>
            </div>

            <div class="flex justify-between text-xl font-extrabold">
                <span>Total</span>
                <span id="totalHarga" class="text-[#CA0B00]">Rp 0</span>
            </div>
        </div>

        <button onclick="kosongkanKeranjang()"
            class="mt-2.5 w-full bg-red-600 text-white py-2.5 rounded-lg text-sm font-extrabold hover:bg-red-700">
            Kosongkan Keranjang
        </button>

        <div class="mt-4">
            <h3 class="text-lg font-extrabold text-[#212842] mb-2">
                Pembayaran
            </h3>

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
    <div class="bg-white rounded-xl w-[390px] p-4 shadow-xl">
        <h2 class="text-xl font-extrabold text-[#212842] mb-3">
            Pembayaran Cash
        </h2>

        <div class="space-y-2.5">
            <div class="flex justify-between text-base font-bold">
                <span>Total Bayar</span>
                <span id="cashTotal" class="text-[#CA0B00]">Rp 0</span>
            </div>

            <div>
                <label class="block font-extrabold text-[#212842] mb-1.5 text-sm">
                    Tunai Diterima
                </label>

                <div class="flex border border-[#D8CDB7] rounded-lg overflow-hidden">
                    <span class="bg-[#F7F3EA] px-3 py-2.5 font-extrabold text-[#212842] text-sm">
                        Rp
                    </span>

                    <input type="text" id="tunaiDiterima"
                        inputmode="numeric"
                        placeholder="0"
                        class="w-full px-3 py-2.5 text-base font-bold outline-none">
                </div>
            </div>

            <div class="flex justify-between text-lg font-extrabold">
                <span>Kembalian</span>
                <span id="kembalian" class="text-green-700">Rp 0</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-4">
            <button onclick="tutupCash()"
                class="bg-gray-200 py-2.5 rounded-lg text-sm font-extrabold">
                Batal
            </button>

            <button onclick="selesaiBayar()"
                class="bg-green-600 text-white py-2.5 rounded-lg text-sm font-extrabold">
                Selesai
            </button>
        </div>
    </div>
</div>

{{-- MODAL QRIS --}}
<div id="modalQris" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-xl w-[350px] p-4 shadow-xl text-center">
        <h2 class="text-xl font-extrabold text-[#212842] mb-3">
            Pembayaran QRIS
        </h2>

        <p class="text-sm font-semibold text-gray-500">
            Total yang harus dibayar
        </p>

        <p id="qrisTotal" class="text-2xl font-black text-[#CA0B00] mt-2">
            Rp 0
        </p>

        <button onclick="selesaiBayar()"
            class="mt-4 w-full bg-blue-600 text-white py-2.5 rounded-lg text-base font-extrabold hover:bg-blue-700">
            OK, Sudah Dibayar
        </button>

        <button onclick="tutupQris()"
            class="mt-2.5 w-full bg-gray-200 py-2.5 rounded-lg font-extrabold text-sm">
            Batal
        </button>
    </div>
</div>

<script>
    let keranjang = JSON.parse(localStorage.getItem('keranjangKasir')) || [];
    let totalBelanja = 0;

    function simpanKeranjang() {
        localStorage.setItem('keranjangKasir', JSON.stringify(keranjang));
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function formatRupiahMinus(angka) {
        return '- Rp ' + Math.abs(angka).toLocaleString('id-ID');
    }

    function tambahKeranjang(nama, harga) {
        let item = keranjang.find(produk => produk.nama === nama);

        if (item) {
            item.qty++;
        } else {
            keranjang.push({
                nama: nama,
                harga: harga,
                qty: 1
            });
        }

        simpanKeranjang();
        renderKeranjang();
    }

    function kurangiQty(nama) {
        let item = keranjang.find(produk => produk.nama === nama);

        if (item) {
            item.qty--;

            if (item.qty <= 0) {
                keranjang = keranjang.filter(produk => produk.nama !== nama);
            }
        }

        simpanKeranjang();
        renderKeranjang();
    }

    function tambahQty(nama) {
        let item = keranjang.find(produk => produk.nama === nama);

        if (item) {
            item.qty++;
        }

        simpanKeranjang();
        renderKeranjang();
    }

    function hapusItem(nama) {
        keranjang = keranjang.filter(produk => produk.nama !== nama);
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

        let total = 0;
        let totalItem = 0;

        if (keranjang.length === 0) {
            list.innerHTML = `
                <p class="text-gray-400 font-semibold text-xs">
                    Belum ada barang.
                </p>
            `;

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
                        <div>
                            <p class="text-sm font-extrabold text-[#212842] leading-tight">${item.nama}</p>
                            <p class="text-xs text-gray-500">${item.qty} x ${formatRupiah(item.harga)}</p>
                        </div>

                        <button onclick="hapusItem('${item.nama}')"
                            class="w-7 h-7 rounded-full border-2 border-red-600 text-red-600 font-extrabold text-base">
                            ×
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-1.5">
                            <button onclick="kurangiQty('${item.nama}')"
                                class="w-7 h-7 bg-gray-200 rounded-md text-base font-bold">
                                -
                            </button>

                            <span class="text-sm font-extrabold">${item.qty}</span>

                            <button onclick="tambahQty('${item.nama}')"
                                class="w-7 h-7 bg-[#212842] text-[#F0E7D5] rounded-md text-base font-bold">
                                +
                            </button>
                        </div>

                        <p class="text-sm font-extrabold">
                            ${formatRupiah(subtotal)}
                        </p>
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

        if (ditemukan === 0) {
            kosong.classList.remove('hidden');
        } else {
            kosong.classList.add('hidden');
        }
    }

    function bukaCash() {
        if (totalBelanja <= 0) {
            alert('Keranjang masih kosong');
            return;
        }

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

        if (!inputTunai || !kembalianEl) {
            return;
        }

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

    function selesaiBayar() {
        const inputTunai = document.getElementById('tunaiDiterima');

        if (document.getElementById('modalCash').classList.contains('flex')) {
            let angka = inputTunai.value.replace(/[^0-9]/g, '');
            let tunai = angka === '' ? 0 : parseInt(angka);

            if (tunai < totalBelanja) {
                alert('Tunai diterima masih kurang.');
                return;
            }
        }

        alert('Pembayaran berhasil');

        keranjang = [];
        localStorage.removeItem('keranjangKasir');

        tutupCash();
        tutupQris();
        renderKeranjang();
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
