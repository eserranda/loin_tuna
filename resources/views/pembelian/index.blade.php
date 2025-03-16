@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Laporan Pembelian Bahan baku</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan Pembelian</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@push('head_component')
    <style>
        .dataTables_filter {
            width: 100%;
            text-align: left;
            /* Memulai dari kiri */
            display: flex;
            justify-content: flex-start;
            /* Memulai dari kiri */
            margin-top: 10px;
        }

        .dataTables_filter label {
            width: 100%;
            display: flex;
            justify-content: flex-start;
            /* Memulai dari kiri */
        }

        .dataTables_filter input {
            width: auto;
            flex: 1;
            /* Menyesuaikan lebar input dengan kontainer */
        }
    </style>
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
    <!--- Datatable -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-xxl-12 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Pembelian Bahan Baku</h4>
                        {{-- <div class="flex-shrink-0">
                            <a href={{ route('produk.add') }} class="btn btn-info ">Tambah Produk</a>
                        </div> --}}
                        {{-- <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">Tambah Data</button>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <table class="table dataPembelian" id="dataPembelian">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>ILC</th>
                                    <th>Total Loin</th>
                                    <th>Total Berat</th>
                                    <th>Total Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const datatable = $('.dataPembelian').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data",
                },
                ajax: "{{ route('pembelian.getAllDataPembelian') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'ilc',
                        name: 'ilc',
                    },
                    {
                        data: 'total_loin',
                        name: 'total_loin',
                    },
                    {
                        data: 'total_berat',
                        name: 'total_berat',
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ],
                dom: 'Bftp',
                buttons: [{
                        extend: 'excel',
                        className: 'btn btn-sm btn-success mx-2',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }
                ]
            });
        });
    </script>
@endpush
