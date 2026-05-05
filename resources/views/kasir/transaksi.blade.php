@extends('layouts.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

<div class="grid grid-cols-[1fr_380px] gap-6">

    {{-- KIRI --}}
    <div class="space-y-6">

        {{-- DATA PELANGGAN --}}
        <section class="bg-white rounded-2xl shadow-sm border border-[#D8CDB7] p-5">
            <h2 class="text-2xl font-extrabold text-[#212842] mb-4">
                Data Pelanggan
            </h2>

            <div class="grid grid-cols-3 gap-4">
                <input type="text" placeholder="Nama Customer"
                    class="border border-[#D8CDB7] rounded-xl px-4 py-3 font-semibold">

                <input type="text" placeholder="No. Tlp / HP"
                    class="border border-[#D8CDB7] rounded-xl px-4 py-3 font-semibold">

                <input type="text" placeholder="Instansi / Asal"
                    class="border border-[#D8CDB7] rounded-xl px-4 py-3 font-semibold">
            </div>
        </section>

        {{-- PRODUK --}}
        <section class="bg-white/80 rounded-2xl shadow-sm border border-[#D8CDB7] p-5">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#212842]">
                        Barang Dijual
                    </h2>
                    <p class="text-sm font-semibold text-gray-500">
                        Produk sesuai rombel yang dipilih
                    </p>
                </div>

                <input type="text" placeholder="Cari barang..."
                    class="w-72 border border-[#D8CDB7] rounded-xl px-4 py-3 font-semibold">
            </div>

            <div class="grid grid-cols-4 gap-5">

                @forelse ($produk as $item)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 hover:shadow-lg transition">

                        <div class="h-24 rounded-xl bg-[#F7F3EA] border border-[#D8CDB7] flex items-center justify-center mb-4">
                            <span class="text-3xl font-extrabold text-[#212842]">
                                {{ strtoupper(substr($item['nama'], 0, 1)) }}
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-[#212842] leading-tight">
                            {{ $item['nama'] }}
                        </h3>

                        <p class="text-xl font-black text-[#CA0B00] mt-2">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </p>

                        <button
                            onclick="tambahKeranjang('{{ $item['nama'] }}', {{ $item['harga'] }})"
                            class="mt-4 w-full bg-[#212842] text-[#F0E7D5] py-3 rounded-xl text-lg font-extrabold hover:bg-[#11172d] transition">
                            + Tambah
                        </button>

                    </div>
                @empty
                    <div class="col-span-4 text-center py-10 text-gray-500 font-bold">
                        Tidak ada produk untuk rombel ini
                    </div>
                @endforelse

            </div>

        </section>

    </div>

    {{-- KANAN: KERANJANG --}}
    <aside class="bg-white rounded-2xl shadow-sm border border-[#D8CDB7] p-5 flex flex-col">

        <h2 class="text-2xl font-extrabold text-[#212842] mb-4">
            Keranjang Belanja
        </h2>

        <div id="keranjangList" class="flex-1 space-y-3 overflow-y-auto">
            <p class="text-gray-400 font-semibold">
                Belum ada barang.
            </p>
        </div>

        <div class="border-t mt-5 pt-5 flex justify-between text-2xl font-extrabold">
            <span>Total</span>
            <span id="totalHarga" class="text-[#CA0B00]">Rp 0</span>
        </div>

        <button onclick="kosongkanKeranjang()"
            class="mt-4 w-full bg-red-600 text-white py-3 rounded-xl font-extrabold hover:bg-red-700">
            Kosongkan Keranjang
        </button>

        <div class="mt-6">
            <h3 class="text-xl font-extrabold text-[#212842] mb-3">
                Pembayaran
            </h3>

            <div class="grid grid-cols-2 gap-3">
                <button class="bg-green-600 text-white py-4 rounded-xl text-lg font-extrabold hover:bg-green-700">
                    Cash
                </button>

                <button class="bg-blue-600 text-white py-4 rounded-xl text-lg font-extrabold hover:bg-blue-700">
                    QRIS
                </button>
            </div>
        </div>

    </aside>

</div>

<script>
    let keranjang = JSON.parse(localStorage.getItem('keranjangKasir')) || [];

    function simpanKeranjang() {
        localStorage.setItem('keranjangKasir', JSON.stringify(keranjang));
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
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

        list.innerHTML = '';

        let total = 0;

        if (keranjang.length === 0) {
            list.innerHTML = `
                <p class="text-gray-400 font-semibold">
                    Belum ada barang.
                </p>
            `;
            totalHarga.innerText = 'Rp 0';
            return;
        }

        keranjang.forEach(item => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            list.innerHTML += `
                <div class="border rounded-xl p-3">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <p class="font-extrabold text-[#212842]">${item.nama}</p>
                            <p class="text-sm text-gray-500">${item.qty} x ${formatRupiah(item.harga)}</p>
                        </div>

                        <button onclick="hapusItem('${item.nama}')"
                            class="text-red-600 text-xl font-extrabold">
                            ×
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-3">
                        <div class="flex items-center gap-2">
                            <button onclick="kurangiQty('${item.nama}')"
                                class="w-8 h-8 bg-gray-200 rounded-lg font-bold">
                                -
                            </button>

                            <span class="font-bold">${item.qty}</span>

                            <button onclick="tambahQty('${item.nama}')"
                                class="w-8 h-8 bg-[#212842] text-[#F0E7D5] rounded-lg font-bold">
                                +
                            </button>
                        </div>

                        <p class="font-extrabold">
                            ${formatRupiah(subtotal)}
                        </p>
                    </div>
                </div>
            `;
        });

        totalHarga.innerText = formatRupiah(total);
    }

    renderKeranjang();
</script>

@endsection