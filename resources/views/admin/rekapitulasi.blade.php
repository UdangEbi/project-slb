@extends('layouts.admin')

@section('content')
    <div class="space-y-4">

        {{-- JUDUL --}}
        <h1 class="text-2xl font-extrabold text-[#212842]">
            Rekapitulasi Admin
        </h1>

        {{-- CARD RINGKASAN --}}
        <div class="grid grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-[#212842]">
                <p class="text-sm font-bold text-gray-500">
                    Kas Masuk
                </p>
                <h2 class="text-xl font-extrabold text-[#212842] mt-1">
                    Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-[#CA0B00]">
                <p class="text-sm font-bold text-gray-500">
                    Kas Keluar
                </p>
                <h2 class="text-xl font-extrabold text-[#CA0B00] mt-1">
                    Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-green-700">
                <p class="text-sm font-bold text-gray-500">
                    Saldo
                </p>
                <h2 class="text-xl font-extrabold text-green-700 mt-1">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h2>
            </div>

        </div>

        {{-- FILTER --}}
        <form id="filterForm" method="GET" action="{{ route('admin.rekapitulasi') }}"
            class="bg-white rounded-xl shadow p-4 flex items-end gap-4">

            <div>
                <label class="block text-sm font-bold text-[#212842] mb-1">
                    Periode Awal
                </label>

                <input type="date" name="periode_awal" id="periode_awal" value="{{ request('periode_awal') }}"
                    class="w-44 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#212842]">
            </div>

            <div>
                <label class="block text-sm font-bold text-[#212842] mb-1">
                    Periode Akhir
                </label>

                <input type="date" name="periode_akhir" id="periode_akhir" value="{{ request('periode_akhir') }}"
                    class="w-44 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#212842]">
            </div>

            {{-- <div>
                <label class="block text-sm font-bold text-[#212842] mb-1">
                    Rombel
                </label>

                <select name="rombel"
                    class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#212842]">
                    <option value="">Semua Rombel</option>
                    <option value="graha" {{ $rombel == 'graha' ? 'selected' : '' }}>Graha</option>
                    <option value="membatik" {{ $rombel == 'membatik' ? 'selected' : '' }}>Membatik</option>
                    <option value="perkayuan" {{ $rombel == 'perkayuan' ? 'selected' : '' }}>Perkayuan</option>
                    <option value="busana" {{ $rombel == 'busana' ? 'selected' : '' }}>Busana</option>
                    <option value="tata-boga" {{ $rombel == 'tata-boga' ? 'selected' : '' }}>Tata Boga</option>
                    <option value="kecantikan" {{ $rombel == 'kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                    <option value="logam" {{ $rombel == 'logam' ? 'selected' : '' }}>Logam</option>
                </select>
            </div> --}}

            <a href="{{ route('admin.rekapitulasi.pdf', request()->query()) }}"
                class="bg-[#CA0B00] text-[#F0E7D5] px-5 py-2 rounded-lg text-sm font-bold hover:opacity-90 transition">
                Download PDF
            </a>

        </form>

        {{-- TABEL --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="p-4 border-b">
                <h2 class="text-lg font-extrabold text-[#212842]">
                    Detail Rekapitulasi
                </h2>
                <p class="text-sm text-gray-500">
                    Data transaksi penjualan dan kas keluar berdasarkan periode yang dipilih.
                </p>
            </div>

            {{-- Scroll khusus tabel --}}
            <div class="max-h-[280px] overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#212842] text-[#F0E7D5] sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Rombel</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Kas Masuk</th>
                            <th class="px-4 py-3">Kas Keluar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rekapitulasi as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 capitalize">
                                    {{ str_replace('-', ' ', $item['rombel']) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item['keterangan'] }}
                                </td>

                                <td class="px-4 py-3 font-bold text-[#212842]">
                                    Rp {{ number_format($item['kas_masuk'], 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 font-bold text-[#CA0B00]">
                                    Rp {{ number_format($item['kas_keluar'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data untuk periode dan rombel ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        const filterForm = document.getElementById('filterForm');
        const awal = document.getElementById('periode_awal');
        const akhir = document.getElementById('periode_akhir');
        const rombel = document.querySelector('select[name="rombel"]');

        function submitFilter() {
            if (awal.value && akhir.value) {
                filterForm.submit();
            }
        }

        awal.addEventListener('change', function() {
            if (!awal.value) return;

            const tanggalAwal = new Date(awal.value);
            const tanggalMaks = new Date(tanggalAwal);
            tanggalMaks.setDate(tanggalMaks.getDate() + 30);

            akhir.min = awal.value;
            akhir.max = tanggalMaks.toISOString().split('T')[0];

            if (akhir.value && akhir.value > akhir.max) {
                akhir.value = akhir.max;
            }

            submitFilter();
        });

        akhir.addEventListener('change', submitFilter);
        rombel.addEventListener('change', submitFilter);
    </script>
@endpush
