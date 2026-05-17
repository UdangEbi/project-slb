@extends('layouts.admin')

@section('title', 'Piutang Admin')

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

        <!-- HEADER PIUTANG -->
        <div class="flex items-end justify-between gap-4 shrink-0">
            <div>
                <h2 class="text-3xl font-extrabold text-[#212842] leading-tight">
                    Piutang
                </h2>
                <p class="text-sm text-gray-600">
                    Data pembeli atau instansi yang masih memiliki utang.
                </p>
            </div>
        </div>

        <!-- DATA PIUTANG -->
        <div class="bg-white rounded-xl shadow overflow-hidden flex-1 min-h-0">
            <div class="px-4 py-3 pb-2">
            </div>

            <div class="px-4 pb-3">
                <table id="tabelPiutang" class="display stripe hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Total Utang</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($piutang as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td data-order="{{ $item['tanggal'] }}">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                                </td>

                                <td class="font-bold text-[#212842]">
                                    {{ $item['nama'] }}
                                </td>

                                <td class="font-bold text-[#212842]" data-order="{{ $item['jumlah'] }}">
                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $item['keterangan'] }}
                                </td>

                                <td>
                                    @if ($item['status'] === 'Lunas')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500">
                                    Belum ada data piutang.
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelPiutang').DataTable({
                pageLength: 5,
                lengthChange: true,
                lengthMenu: [
                    [5, 10, 25, 50],
                    [5, 10, 25, 50]
                ],
                searching: true,
                ordering: true,
                paging: true,
                pagingType: "full_numbers",
                autoWidth: false,
                info: true,
                dom: '<"flex items-center justify-between mb-3"lf>rt<"flex items-center justify-between mt-3"ip>',
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
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
                        targets: 3,
                        className: "text-right"
                    },
                    {
                        targets: 5,
                        className: "text-center"
                    }
                ]
            });
        });
    </script>
@endpush
