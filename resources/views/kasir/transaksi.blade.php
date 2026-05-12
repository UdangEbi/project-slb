@extends('layouts.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

<div class="grid grid-cols-[1fr_420px] gap-6">

    {{-- KIRI --}}
    <div class="space-y-6">

        {{-- DATA PELANGGAN --}}
        <section class="bg-white rounded-2xl shadow-sm border border-[#D8CDB7] p-5">
            <h2 class="text-3xl font-extrabold text-[#212842] mb-5">
                Data Pelanggan
            </h2>

            <div class="grid grid-cols-3 gap-4">
                <input type="text" placeholder="Nama Customer"
                    class="border border-[#D8CDB7] rounded-xl px-5 py-4 text-lg font-semibold">

                <input type="text" placeholder="No. Tlp / HP"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="border border-[#D8CDB7] rounded-xl px-5 py-4 text-lg font-semibold">

                <input type="text" placeholder="Instansi / Asal"
                    class="border border-[#D8CDB7] rounded-xl px-5 py-4 text-lg font-semibold">
            </div>
        </section>

        {{-- PRODUK --}}
        <section class="bg-white/80 rounded-2xl shadow-sm border border-[#D8CDB7] p-5">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-[#212842]">
                        Barang Dijual
                    </h2>
                    <p class="text-base font-semibold text-gray-500">
                        Produk sesuai rombel yang dipilih
                    </p>
                </div>

                <input type="text" id="searchProduk" placeholder="Cari barang..."
                    oninput="cariProduk()"
                    class="w-80 border border-[#D8CDB7] rounded-xl px-5 py-4 text-lg font-semibold">
            </div>

            <div id="produkGrid" class="grid grid-cols-3 gap-5">

                @forelse ($produk as $item)
                    <div class="produk-card bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:shadow-lg transition"
                        data-nama="{{ strtolower($item['nama']) }}">

                        <h3 class="text-3xl font-extrabold text-[#212842] leading-tight mb-4">
                            {{ $item['nama'] }}
                        </h3>

                        <p class="text-3xl font-black text-[#CA0B00] mb-5">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </p>

                        <button
                            onclick="tambahKeranjang('{{ $item['nama'] }}', {{ $item['harga'] }})"
                            class="w-full bg-[#212842] text-[#F0E7D5] py-4 rounded-xl text-xl font-extrabold hover:bg-[#11172d] transition">
                            Tambah
                        </button>

                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 text-gray-500 font-bold">
                        Tidak ada produk untuk rombel ini
                    </div>
                @endforelse

            </div>

            <p id="produkTidakDitemukan" class="hidden text-center py-8 text-gray-500 font-bold">
                Barang tidak ditemukan.
            </p>

        </section>

    </div>

    {{-- KANAN: KERANJANG --}}
    <aside class="bg-white rounded-2xl shadow-sm border border-[#D8CDB7] p-5 flex flex-col">

        <h2 class="text-3xl font-extrabold text-[#212842] mb-5">
            Keranjang Belanja
        </h2>

        <div id="keranjangList" class="flex-1 space-y-4 overflow-y-auto">
            <p class="text-gray-400 font-semibold">
                Belum ada barang.
            </p>
        </div>

        <div class="border-t mt-5 pt-5 space-y-2">
            <div class="flex justify-between text-xl font-extrabold text-[#212842]">
                <span>Jumlah Item</span>
                <span id="jumlahItem">0 item</span>
            </div>

            <div class="flex justify-between text-3xl font-extrabold">
                <span>Total</span>
                <span id="totalHarga" class="text-[#CA0B00]">Rp 0</span>
            </div>
        </div>

        <button onclick="kosongkanKeranjang()"
            class="mt-4 w-full bg-red-600 text-white py-4 rounded-xl text-lg font-extrabold hover:bg-red-700">
            Kosongkan Keranjang
        </button>

        <div class="mt-6">
            <h3 class="text-2xl font-extrabold text-[#212842] mb-3">
                Pembayaran
            </h3>

            <div class="grid grid-cols-2 gap-3">
                <button onclick="bukaCash()"
                    class="bg-green-600 text-white py-5 rounded-xl text-xl font-extrabold hover:bg-green-700">
                    Cash
                </button>

                <button onclick="bukaQris()"
                    class="bg-blue-600 text-white py-5 rounded-xl text-xl font-extrabold hover:bg-blue-700">
                    QRIS
                </button>
            </div>
        </div>

    </aside>

</div>

{{-- MODAL CASH --}}
<div id="modalCash" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl w-[460px] p-6 shadow-xl">
        <h2 class="text-3xl font-extrabold text-[#212842] mb-5">
            Pembayaran Cash
        </h2>

        <div class="space-y-4">
            <div class="flex justify-between text-xl font-bold">
                <span>Total Bayar</span>
                <span id="cashTotal" class="text-[#CA0B00]">Rp 0</span>
            </div>

            <div>
                <label class="block font-extrabold text-[#212842] mb-2">
                    Tunai Diterima
                </label>

                <div class="flex border border-[#D8CDB7] rounded-xl overflow-hidden">
                    <span class="bg-[#F7F3EA] px-4 py-4 font-extrabold text-[#212842]">
                        Rp
                    </span>

                    <input type="text" id="tunaiDiterima"
                        inputmode="numeric"
                        placeholder="0"
                        class="w-full px-4 py-4 text-xl font-bold outline-none">
                </div>
            </div>

            <div class="flex justify-between text-2xl font-extrabold">
                <span>Kembalian</span>
                <span id="kembalian" class="text-green-700">Rp 0</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-6">
            <button onclick="tutupCash()"
                class="bg-gray-200 py-4 rounded-xl text-lg font-extrabold">
                Batal
            </button>

            <button onclick="selesaiBayar()"
                class="bg-green-600 text-white py-4 rounded-xl text-lg font-extrabold">
                Selesai
            </button>
        </div>
    </div>
</div>

{{-- MODAL QRIS --}}
<div id="modalQris" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl w-[420px] p-6 shadow-xl text-center">
        <h2 class="text-3xl font-extrabold text-[#212842] mb-5">
            Pembayaran QRIS
        </h2>

        <p class="text-lg font-semibold text-gray-500">
            Total yang harus dibayar
        </p>

        <p id="qrisTotal" class="text-4xl font-black text-[#CA0B00] mt-3">
            Rp 0
        </p>

        <button onclick="selesaiBayar()"
            class="mt-6 w-full bg-blue-600 text-white py-4 rounded-xl text-xl font-extrabold hover:bg-blue-700">
            OK, Sudah Dibayar
        </button>

        <button onclick="tutupQris()"
            class="mt-3 w-full bg-gray-200 py-3 rounded-xl font-extrabold">
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
                <p class="text-gray-400 font-semibold">
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
                <div class="border rounded-xl p-4">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <p class="text-xl font-extrabold text-[#212842]">${item.nama}</p>
                            <p class="text-base text-gray-500">${item.qty} x ${formatRupiah(item.harga)}</p>
                        </div>

                        <button onclick="hapusItem('${item.nama}')"
                            class="w-9 h-9 rounded-full border-2 border-red-600 text-red-600 font-extrabold text-xl">
                            ×
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="flex items-center gap-3">
                            <button onclick="kurangiQty('${item.nama}')"
                                class="w-10 h-10 bg-gray-200 rounded-lg text-xl font-bold">
                                -
                            </button>

                            <span class="text-xl font-extrabold">${item.qty}</span>

                            <button onclick="tambahQty('${item.nama}')"
                                class="w-10 h-10 bg-[#212842] text-[#F0E7D5] rounded-lg text-xl font-bold">
                                +
                            </button>
                        </div>

                        <p class="text-xl font-extrabold">
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
</script>

@endsection