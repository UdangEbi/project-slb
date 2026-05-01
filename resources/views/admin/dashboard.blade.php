@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <style>
        .dataTables_wrapper {
            color: #212842;
            font-family: inherit;
            width: 100%;
        }

        .dataTables_wrapper label {
            color: #212842;
            font-weight: 600;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 18px;
        }

        .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 28px 6px 10px;
            background-color: white;
            color: #212842;
            outline: none;
            margin: 0 6px;
        }

        .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 10px;
            background-color: white;
            color: #212842;
            outline: none;
            margin-left: 8px;
            width: 260px;
        }

        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            border-color: #212842;
            box-shadow: 0 0 0 2px rgba(33, 40, 66, 0.15);
        }

        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 10px !important;
            margin-bottom: 18px !important;
        }

        table.dataTable thead th {
            background-color: #212842 !important;
            color: #F0E7D5 !important;
            border-bottom: none !important;
            font-weight: 800 !important;
            padding: 14px 16px !important;
        }

        table.dataTable tbody td {
            color: #212842;
            padding: 14px 16px !important;
            border-bottom: 1px solid #d1d5db;
        }

        table.dataTable tbody tr:hover {
            background-color: #f9fafb !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #d1d5db !important;
        }

        .dataTables_info {
            color: #212842 !important;
            font-weight: 600;
            padding-top: 14px !important;
        }

        .dataTables_paginate {
            padding-top: 10px !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 34px;
            height: 34px;
            padding: 6px 10px !important;
            margin: 0 2px !important;
            border: 1px solid #212842 !important;
            border-radius: 4px !important;
            background: white !important;
            color: #212842 !important;
            cursor: pointer;
            font-weight: 600;
            line-height: 20px;
            box-sizing: border-box;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #212842 !important;
            border: 1px solid #212842 !important;
            color: #F0E7D5 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #212842 !important;
            border: 1px solid #212842 !important;
            color: #F0E7D5 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            border: 1px solid #cbd5e1 !important;
            background: #f8fafc !important;
            color: #9ca3af !important;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_paginate .ellipsis {
            padding: 6px 8px;
            color: #64748b;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                float: none !important;
                text-align: left !important;
                width: 100%;
            }

            .dataTables_filter input {
                width: 100%;
                margin-left: 0;
                margin-top: 8px;
            }

            .dataTables_paginate {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">

        <!-- HEADER DASHBOARD -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-[#212842]">Dashboard</h2>
                <p class="text-gray-600 mt-1">
                    Ringkasan penjualan sistem kasir tahun {{ $tahun }}
                </p>
            </div>

            <!-- FILTER TAHUN -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-end gap-2">
                <div>
                    <label for="tahun" class="block text-sm font-bold text-[#212842] mb-2">
                        Tahun
                    </label>

                    <select name="tahun" id="tahun"
                        onchange="this.form.submit()"
                        class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-[#212842] font-semibold focus:outline-none focus:ring-2 focus:ring-[#212842]">
                        @foreach ($daftarTahun as $item)
                            <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- CARD RINGKASAN -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-xl shadow p-5 border-l-8 border-[#212842]">
                <p class="text-gray-500 font-bold mb-2">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-[#212842]">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-l-8 border-green-600">
                <p class="text-gray-500 font-bold mb-2">Laba Bersih</p>
                <h3 class="text-2xl font-extrabold text-green-700">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-l-8 border-yellow-500">
                <p class="text-gray-500 font-bold mb-2">Jumlah Produk Terjual</p>
                <h3 class="text-2xl font-extrabold text-yellow-600">
                    {{ number_format($jumlahProdukTerjual, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- GRAFIK BULANAN FULL WIDTH -->
        <div class="bg-white rounded-xl shadow p-5">
            <div class="mb-4">
                <h3 class="text-2xl font-extrabold text-[#212842]">
                    Omzet Penjualan per Bulan
                </h3>
                <p class="text-gray-500 text-sm">
                    Januari - Desember {{ $tahun }}
                </p>
            </div>

            <div class="h-[320px]">
                <canvas id="grafikBulanan"></canvas>
            </div>
        </div>

        <!-- BARIS KEDUA: ROMBEL + BARANG PALING LARIS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            <!-- GRAFIK ROMBEL -->
            <div class="bg-white rounded-xl shadow p-5 h-full">
                <div class="mb-4">
                    <h3 class="text-2xl font-extrabold text-[#212842]">
                        Omzet Penjualan per Rombel
                    </h3>
                    <p class="text-gray-500 text-sm">
                        Total omzet berdasarkan rombongan belajar
                    </p>
                </div>

                <div class="h-[320px]">
                    <canvas id="grafikRombel"></canvas>
                </div>
            </div>

            <!-- BARANG PALING LARIS -->
            <div class="bg-white rounded-xl shadow overflow-hidden h-full">
                <div class="px-5 py-4">
                    <h3 class="text-2xl font-extrabold text-[#212842]">
                        Barang Paling Laris
                    </h3>
                    <p class="text-gray-500">
                        Top barang berdasarkan jumlah terjual
                    </p>
                </div>

                <div class="px-5 pb-5 overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#212842] text-[#F0E7D5]">
                            <tr>
                                <th class="px-5 py-4 text-left">No</th>
                                <th class="px-5 py-4 text-left">Nama Barang</th>
                                <th class="px-5 py-4 text-left">Jumlah Terjual</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($barangTerlaris as $index => $barang)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-5 py-4 font-bold text-[#212842]">
                                        {{ $barang['nama_barang'] }}
                                    </td>

                                    <td class="px-5 py-4 font-bold text-[#212842]">
                                        {{ $barang['jumlah_terjual'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-6 text-center text-gray-500">
                                        Belum ada data barang terlaris.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- DATA PEMBELI -->
        <div class="bg-white rounded-xl shadow">
            <div class="px-5 py-4">
                <h3 class="text-2xl font-extrabold text-[#212842]">
                    Data Pembeli
                </h3>
                <p class="text-gray-500">
                    Data pembeli / instansi yang melakukan transaksi
                </p>
            </div>

            <div class="px-5 pb-8">
                <table id="tabelPembeli" class="display stripe hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Pembeli</th>
                            <th>Total Beli</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pembeli as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td data-order="{{ $item['tanggal'] }}">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                                </td>

                                <td class="font-bold text-[#212842]">
                                    {{ $item['kode_transaksi'] }}
                                </td>

                                <td>
                                    {{ $item['nama_pembeli'] }}
                                </td>

                                <td class="font-bold text-[#212842]" data-order="{{ $item['total'] }}">
                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500">
                                    Belum ada data pembeli.
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        const formatRupiah = new Intl.NumberFormat('id-ID');

        // DATATABLE PEMBELI
        $(document).ready(function () {
            $('#tabelPembeli').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: true,
                searching: true,
                paging: true,
                pagingType: "full_numbers",
                autoWidth: false,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "70px",
                        className: "text-center"
                    },
                    {
                        targets: 4,
                        className: "text-right"
                    }
                ]
            });
        });

        // GRAFIK OMZET PENJUALAN PER BULAN
        new Chart(document.getElementById('grafikBulanan'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($penjualanBulanan)),
                datasets: [{
                    label: 'Omzet',
                    data: @json(array_values($penjualanBulanan)),
                    backgroundColor: '#212842',
                    borderRadius: 6,
                    maxBarThickness: 42
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + formatRupiah.format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + formatRupiah.format(value);
                            }
                        }
                    }
                }
            }
        });

        // GRAFIK OMZET PENJUALAN PER ROMBEL
        new Chart(document.getElementById('grafikRombel'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($penjualanRombel)),
                datasets: [{
                    data: @json(array_values($penjualanRombel)),
                    backgroundColor: [
                        '#212842',
                        '#3E4B74',
                        '#5A678C',
                        '#7B86A7',
                        '#D97706',
                        '#DC2626',
                        '#059669'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + formatRupiah.format(context.raw);
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
