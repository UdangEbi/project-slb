@extends('layouts.kasir')

@section('title', 'STOK KASIR')

@section('content')
    <div class="w-full uppercase pt-8">

        {{-- LIST STOK --}}
        <div id="stokList">

            <h1 class="text-4xl font-extrabold text-[#212842] mb-8">
                STOK BARANG
            </h1>

            <div class="grid grid-cols-3 gap-6 pr-6">

                {{-- ADD NEW ITEM --}}
                <button type="button"
                    onclick="window.location='{{ route('kasir.stok.create', ['kategori' => $kategoriId]) }}'"
                    class="bg-[#212842] rounded-3xl shadow-md p-8 h-56 border-2 border-dashed border-[#212842] hover:scale-105 transition flex flex-col justify-center items-center">

                    <div class="text-5xl font-extrabold text-[#F0E7D5] leading-none">
                        +
                    </div>

                    <p class="text-2xl font-extrabold text-[#F0E7D5] mt-4 text-center leading-tight">
                        ADD NEW<br>ITEM
                    </p>
                </button>

                {{-- BARANG --}}
                @if(isset($barang) && count($barang) > 0)

                    @foreach ($barang as $item)

                        <div
                            class="relative bg-white rounded-3xl shadow-md px-8 py-7 h-56 border-2 border-transparent hover:border-[#212842]">

                            <button type="button" onclick="window.location='{{ route('kasir.stok.edit', $item->id_produk) }}'"
                                class="w-full h-full text-left flex flex-col justify-center">


                                <h2 class="text-2xl font-extrabold text-[#212842] leading-tight">
                                    {{ strtoupper($item->nama_produk) }}
                                </h2>

                                <p class="text-xl font-extrabold text-gray-700 mb-3">
                                    STOK: {{ $item->stok }} {{ strtoupper($item->satuan) }}
                                </p>

                                <p class="text-xl font-extrabold text-gray-700">
                                    RP {{ number_format($item->harga_jual, 0, ',', '.') }}
                                </p>

                            </button>

                            <div class="absolute bottom-5 right-5 flex gap-2">

                                <form action="{{ route('kasir.stok.destroy', $item->id_produk) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold">
                                        HAPUS
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                @endif

            </div>
        </div>

    </div>

    </div>

    <script>
        document.addEventListener('keydown', function (e) {
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
