@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <style>
        .dataTables_wrapper {
            color: #212842;
            font-family: inherit;
            width: 100%;
            font-size: 13px;
        }

        .dataTables_wrapper label {
            color: #212842;
            font-weight: 600;
        }

        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0 !important;
        }

        table.dataTable thead th {
            background-color: #212842 !important;
            color: #F0E7D5 !important;
            border-bottom: none !important;
            font-weight: 800 !important;
            padding: 9px 12px !important;
            font-size: 13px;
        }

        table.dataTable tbody td {
            color: #212842;
            padding: 8px 12px !important;
            border-bottom: 1px solid #d1d5db;
            font-size: 13px;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #d1d5db !important;
        }

        .dataTables_info {
            color: #212842 !important;
            font-weight: 600;
            padding-top: 7px !important;
            font-size: 12px;
        }

        .dataTables_paginate {
            padding-top: 5px !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 26px;
            height: 26px;
            padding: 3px 7px !important;
            margin: 0 1px !important;
            border: 1px solid #212842 !important;
            border-radius: 4px !important;
            background: white !important;
            color: #212842 !important;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            line-height: 18px;
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
            padding: 3px 6px;
            color: #64748b;
            font-weight: 700;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-3">

        <!-- HEADER DASHBOARD -->
        <div class="flex items-end justify-between gap-4 shrink-0">
            <div>
                <h2 class="text-3xl font-extrabold text-[#212842] leading-tight">
                    Dashboard
                </h2>
                <p class="text-sm text-gray-600">
                    Ringkasan penjualan sistem kasir tahun {{ $tahun }}
                </p>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}">
                <label for="tahun" class="block text-sm font-bold text-[#212842] mb-1">
                    Tahun
                </label>

                <select name="tahun" id="tahun"
                    onchange="this.form.submit()"
                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-[#212842] font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-[#212842]">
                    @foreach ($daftarTahun as $item)
                        <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- CARD RINGKASAN -->
        <div class="grid grid-cols-5 gap-4 shrink-0">

            <!-- Saldo -->
            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-green-600">
                <p class="text-gray-500 font-bold text-sm mb-2">Saldo</p>
                <h3 class="text-2xl font-extrabold text-green-700">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h3>
            </div>

            <!-- Kas Masuk -->
            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-[#212842]">
                <p class="text-gray-500 font-bold text-sm mb-2">Kas Masuk</p>
                <h3 class="text-2xl font-extrabold text-[#212842]">
                    Rp {{ number_format($kasMasuk, 0, ',', '.') }}
                </h3>
            </div>

            <!-- Kas Keluar -->
            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-red-600">
                <p class="text-gray-500 font-bold text-sm mb-2">Kas Keluar</p>
                <h3 class="text-2xl font-extrabold text-red-700">
                    Rp {{ number_format($kasKeluar, 0, ',', '.') }}
                </h3>
            </div>

            <!-- Donasi -->
            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-blue-600">
                <p class="text-gray-500 font-bold text-sm mb-2">Donasi</p>
                <h3 class="text-2xl font-extrabold text-blue-700">
                    Rp {{ number_format($donasi, 0, ',', '.') }}
                </h3>
            </div>

            <!-- Piutang -->
            <div class="bg-white rounded-xl shadow p-4 border-l-8 border-orange-500">
                <p class="text-gray-500 font-bold text-sm mb-2">Piutang</p>
                <h3 class="text-2xl font-extrabold text-orange-600">
                    Rp {{ number_format($piutang, 0, ',', '.') }}
                </h3>
            </div>
        </div>


        <!-- GRAFIK BULANAN -->
        <div class="bg-white rounded-xl shadow p-4 shrink-0">
            <div class="mb-2">
                <h3 class="text-2xl font-extrabold text-[#212842] leading-tight">
                    Omzet Penjualan per Bulan
                </h3>
                <p class="text-sm text-gray-500">
                    Januari - Desember {{ $tahun }}
                </p>
            </div>

            <div class="h-[155px]">
                <canvas id="grafikBulanan"></canvas>
            </div>
        </div>

        <!-- ROMBEL + BARANG PALING LARIS -->
        <div class="grid grid-cols-2 gap-4 shrink-0">

            <!-- GRAFIK ROMBEL -->
            <div class="bg-white rounded-xl shadow p-4">
                <div class="mb-2">
                    <h3 class="text-2xl font-extrabold text-[#212842] leading-tight">
                        Omzet Penjualan per Rombel
                    </h3>
                    <p class="text-sm text-gray-500">
                        Total omzet berdasarkan rombongan belajar
                    </p>
                </div>

                <div class="h-[170px]">
                    <canvas id="grafikRombel"></canvas>
                </div>
            </div>

            <!-- BARANG PALING LARIS -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-4 py-4 pb-2">
                    <h3 class="text-2xl font-extrabold text-[#212842] leading-tight">
                        Barang Paling Laris
                    </h3>
                    <p class="text-sm text-gray-500">
                        Top barang berdasarkan jumlah terjual
                    </p>
                </div>

                <div class="px-4 pb-4">
                    <table class="w-full text-sm">
                        <thead class="bg-[#212842] text-[#F0E7D5]">
                            <tr>
                                <th class="px-4 py-2.5 text-left">No</th>
                                <th class="px-4 py-2.5 text-left">Nama Barang</th>
                                <th class="px-4 py-2.5 text-left">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($barangTerlaris as $index => $barang)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-2 font-bold text-[#212842]">
                                        {{ $barang['nama_barang'] }}
                                    </td>

                                    <td class="px-4 py-2 font-bold text-[#212842]">
                                        {{ $barang['jumlah_terjual'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                        Belum ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- DATA PEMBELI -->
        <div class="bg-white rounded-xl shadow overflow-hidden flex-1 min-h-0">
            <div class="px-4 py-3 pb-2">
                <h3 class="text-2xl font-extrabold text-[#212842] leading-tight">
                    Data Pembeli
                </h3>
                <p class="text-sm text-gray-500">
                    Data pembeli / instansi yang melakukan transaksi
                </p>
            </div>

            <div class="px-4 pb-3">
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

        // DATATABLE PEMBELI COMPACT
        $(document).ready(function () {
            $('#tabelPembeli').DataTable({
                pageLength: 5,
                lengthChange: true,
                lengthMenu: [
                    [5, 10, 25, 50],
                    [5, 10, 25, 50]
                ],
                searching: false,
                ordering: true,
                paging: true,
                pagingType: "full_numbers",
                autoWidth: false,
                info: true,
                dom: '<"flex items-center justify-between mb-3"l>rt<"flex items-center justify-between mt-3"ip>',
                language: {
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
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
                        width: "45px",
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
                    borderRadius: 5,
                    maxBarThickness: 34
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
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 11
                            },
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
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
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
