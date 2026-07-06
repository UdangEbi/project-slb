@extends('layouts.kasir')

@section('title', 'PENGELUARAN KAS')

@section('content')

    <div class="space-y-4">

        {{-- Judul --}}
        <div class="flex items-center justify-between mb-5">

            <h1 class="text-5xl font-black text-[#212842] uppercase tracking-widest">
                Pengeluaran Kas
            </h1>

            <button type="button" onclick="openKasKeluarModal()"
                class="bg-[#212842] text-[#F0E7D5] px-8 py-4 rounded-2xl text-2xl font-bold hover:opacity-90 transition flex items-center gap-3 uppercase tracking-wide">

                <i class="bi bi-plus-circle text-3xl uppercase tracking-wide"></i>
                Tambah Pengeluaran

            </button>

        </div>

        <div>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">

                <div class="px-8 py-6 border-b">

                    <h2 class="text-3xl font-extrabold text-[#212842] uppercase tracking-wide">
                        Riwayat Pengeluaran
                    </h2>

                    <p class="mt-2 text-lg text-gray-600 uppercase tracking-wide">
                        Daftar seluruh pengeluaran kas yang telah dicatat.
                    </p>

                </div>

                <div class="p-5">

                    <table class="w-full">

                        <thead class="bg-[#212842] text-[#F0E7D5] uppercase tracking-wide">

                            <tr>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    No
                                </th>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    Tanggal
                                </th>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    Kategori
                                </th>

                                <th class="text-center px-8 py-6 text-2xl font-extrabold uppercase tracking-wide">
                                    Keterangan
                                </th>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    Nominal
                                </th>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    Kasir
                                </th>

                                <th class="text-center px-6 py-5 text-2xl font-extrabold uppercase tracking-wide">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($kasKeluar as $index => $item)
                                <tr class="border-b">

                                    <td
                                        class="px-6 py-6 text-xl font-bold text-[#212842] text-center uppercase tracking-wide">
                                        {{ $index + 1 }}
                                    </td>

                                    <td
                                        class="px-6 py-6 text-xl font-bold text-[#212842] text-center uppercase tracking-wide">
                                        {{ $item->tanggal->format('d/m/Y') }}
                                    </td>

                                    <td
                                        class="px-6 py-6 text-xl font-bold text-[#212842] text-center uppercase tracking-wide">
                                        {{ $item->kategori->nama_kategori }}
                                    </td>

                                    <td
                                        class="px-6 py-6 text-xl font-bold text-[#212842] text-left uppercase tracking-wide">
                                        {{ $item->keterangan }}
                                    </td>

                                    <td class="px-6 py-5">

                                        <span
                                            class="inline-flex items-center justify-center
                                                bg-red-100 text-red-700
                                                rounded-full
                                                px-6 py-3
                                                text-2xl font-extrabold
                                                whitespace-nowrap">

                                            RP {{ number_format($item->nominal, 0, ',', '.') }}

                                        </span>

                                    </td>

                                    <td
                                        class="px-6 py-6 text-xl font-bold text-[#212842] text-center uppercase tracking-wide">
                                        {{ $item->user->name }}
                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <button type="button"
                                            onclick="editKasKeluar(
                                                '{{ $item->id_kas_keluar }}',
                                                '{{ $item->tanggal->format('Y-m-d') }}',
                                                '{{ $item->kategori_pengeluaran_id }}',
                                                '{{ $item->nominal }}',
                                                '{{ addslashes($item->keterangan) }}'
                                            )"
                                            class="bg-[#212842] text-[#F0E7D5]
                                                px-5 py-3 rounded-xl
                                                text-xl font-extrabold
                                                uppercase tracking-wide
                                                hover:opacity-90 transition">

                                            <i class="bi bi-pencil-square me-2"></i>

                                            EDIT

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="text-center py-6 text-gray-500 uppercase tracking-wide">

                                        Belum ada data pengeluaran.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
    <!-- MODAL TAMBAH PENGELUARAN -->
    <div id="kasKeluarModal" class="hidden fixed inset-0 z-[9999] bg-black/50 items-center justify-center p-6">

        <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl">

            {{-- Header --}}
            <div class="flex justify-between items-center border-b px-8 py-6">

                <h2 id="modalTitle" class="text-4xl font-extrabold text-[#212842] uppercase tracking-wide">
                    Tambah Pengeluaran Kas
                </h2>

                <button type="button" onclick="closeKasKeluarModal()"
                    class="text-gray-500 hover:text-red-600 text-4xl font-bold">

                    &times;

                </button>

            </div>

            {{-- Body --}}
            <form id="kasKeluarForm" action="{{ route('kasir.kas-keluar.store') }}"
                data-store="{{ route('kasir.kas-keluar.store') }}" data-update="{{ url('/kasir/kas-keluar') }}"
                method="POST">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" id="kas_keluar_id" name="kas_keluar_id">

                <div class="grid grid-cols-2 gap-8 p-8">

                    {{-- Tanggal --}}
                    <div>

                        <label class="block text-xl font-extrabold text-[#212842] mb-3 uppercase tracking-wide">
                            Tanggal
                        </label>

                        <input id="tanggal" type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-2xl border-2 border-gray-300 px-5 py-4 text-2xl uppercase tracking-wide font-bold focus:border-[#212842] focus:outline-none">

                    </div>

                    {{-- Kategori --}}
                    <div>

                        <label class="block text-xl font-extrabold text-[#212842] mb-3 uppercase tracking-wide">
                            KATEGORI
                        </label>

                        <select id="kategori_pengeluaran_id" name="kategori_pengeluaran_id" required
                            class="w-full rounded-2xl border-2 border-gray-300
                                px-5 py-4
                                text-2xl font-black uppercase tracking-wide
                                focus:border-[#212842] focus:outline-none">

                            <option value="" selected disabled hidden>
                                PILIH KATEGORI
                            </option>

                            @foreach ($kategori as $item)
                                <option value="{{ $item->id_kategori_pengeluaran }}" class="font-bold">
                                    {{ strtoupper($item->nama_kategori) }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- Nominal --}}
                    <div>

                        <label class="block text-xl font-extrabold text-[#212842] mb-3 uppercase tracking-wide">
                            Nominal
                        </label>

                        <input id="nominal" type="number" name="nominal" placeholder="Masukkan nominal"
                            class="w-full rounded-2xl border-2 border-gray-300 px-5 py-4 text-2xl font-bold uppercase tracking-wide placeholder:text-gray-400 focus:border-[#212842] focus:outline-none">

                    </div>

                    {{-- Keterangan --}}
                    <div>

                        <label class="block text-xl font-extrabold text-[#212842] mb-3 uppercase tracking-wide">
                            Keterangan
                        </label>

                        <textarea id="keterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan"
                            class="w-full rounded-2xl border-2 border-gray-300 px-5 py-4 text-2xl font-bold uppercase tracking-wide placeholder:text-gray-400 resize-none focus:border-[#212842] focus:outline-none"></textarea>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="border-t px-8 py-6 flex justify-end gap-4">

                    <button type="button" onclick="closeKasKeluarModal()"
                        class="px-8 py-4 rounded-2xl bg-gray-300 text-[#212842] text-2xl uppercase tracking-wide font-bold hover:bg-gray-400 transition">

                        Batal

                    </button>

                    <button id="submitButton" type="submit"
                        class="px-8 py-4 rounded-2xl bg-[#212842] text-[#F0E7D5] text-2xl uppercase tracking-wide font-bold hover:opacity-90 transition">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function openKasKeluarModal() {

            // Reset form
            document.getElementById('kasKeluarForm').reset();

            // Reset action ke STORE
            const form = document.getElementById('kasKeluarForm');

            form.action = form.dataset.store;

            // Method kembali POST
            document.getElementById('formMethod').value = "POST";

            // Reset ID
            document.getElementById('kas_keluar_id').value = "";

            // Judul
            document.getElementById('modalTitle').innerHTML =
                "TAMBAH PENGELUARAN KAS";

            // Tombol
            document.getElementById('submitButton').innerHTML =
                "SIMPAN";

            // Tanggal hari ini
            document.getElementById('tanggal').value =
                "{{ date('Y-m-d') }}";

            const modal = document.getElementById('kasKeluarModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeKasKeluarModal() {

            const modal = document.getElementById('kasKeluarModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }

        function editKasKeluar(id, tanggal, kategori, nominal, keterangan) {
            openKasKeluarModal();

            // simpan id
            document.getElementById('kas_keluar_id').value = id;

            // isi form
            document.getElementById('tanggal').value = tanggal;
            document.getElementById('kategori_pengeluaran_id').value = kategori;
            document.getElementById('nominal').value = nominal;
            document.getElementById('keterangan').value = keterangan;

            // ubah action form ke UPDATE
            const form = document.getElementById('kasKeluarForm');

            form.action =
                form.dataset.update + "/" + id;

            // ubah method menjadi PUT
            document.getElementById('formMethod').value = "PUT";

            // ubah judul
            document.getElementById('modalTitle').innerHTML =
                "EDIT PENGELUARAN KAS";

            // ubah tombol
            document.getElementById('submitButton').innerHTML =
                "UPDATE";
        }
    </script>
@endpush
