@extends('layouts.master')
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
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    {{-- <script src="{{ asset('assets') }}/js/pages/datatables.init.js"></script> --}}
@endpush
@section('title')
    <h4 class="mb-sm-0">List PO</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Status PO</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="d-flex flex-column h-100">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Data Order</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped mt-0 dataOrder" id="dataOrder"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total Harga</th>
                                        <th>Total Berat</th>
                                        <th>Total Bayar</th>
                                        <th>Status PO</th>
                                        {{-- <th>Opsi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div>
    </div>

    @include('order.receipt_img')
@endsection

@push('scripts')
    <script src="{{ asset('assets') }}/libs/prismjs/prism.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            const myDataTable = $('.dataOrder').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                // lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Po Number",
                },

                ajax: "{{ route('order.poStatusData') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                    },
                    {
                        data: 'po_number',
                        name: 'po_number',
                    },
                    {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                    },
                    {
                        data: 'total_weight',
                        name: 'total_weight',
                    },
                    {
                        data: 'total_payment',
                        name: 'total_payment',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ],
                dom: 'Bftp',
                // dom: 'Bftip',
                buttons: [{
                        extend: 'excel',
                        className: 'btn btn-sm btn-success mx-2',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 6, 7]
                        }
                    }
                ]
            });
        });
    </script>
@endpush
