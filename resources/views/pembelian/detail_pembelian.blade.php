@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Detail Pembelian Bahan Baku</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Detail Pembelian</a></li>
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
        <div class="col-xxl-8 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Pembelian Bahan Baku Dari Supplier {{ $ilc }}</h4>
                        {{-- <div class="flex-shrink-0">
                            <a href={{ route('produk.add') }} class="btn btn-info ">Tambah Produk</a>
                        </div> --}}
                        {{-- <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">Tambah Data</button>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <table class="table detailPembelian" id="detailPembelian">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Loin</th>
                                    <th>Grade</th>
                                    <th>Berat</th>
                                    <th>Harga</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total:</th>
                                    <th></th>
                                    <th></th>
                                    <th></th> <!-- berat total akan muncul disini -->
                                    <th></th> <!-- harga total akan muncul disini -->
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pembelian.update_harga')
@endsection
@push('scripts')
    <script>
        async function updateHarga(id) {
            $('#updateHargaModal').modal('show');
            $('#updateHargaModal').find('#id').val(id);
        }

        $(document).ready(function() {
            const datatable = $('.detailPembelian').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data",
                },
                ajax: "{{ route('pembelian.detailPembelianPerILC', $ilc) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'no_loin',
                        name: 'no_loin',
                    },
                    {
                        data: 'grade',
                        name: 'grade',
                    },
                    {
                        data: 'berat',
                        name: 'berat',
                    },
                    {
                        data: 'harga',
                        name: 'harga',
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
                            columns: [0, 1, 2, 3, 4]
                        },
                        footer: true
                    },
                    {
                        extend: 'print',
                        text: 'Print nota timbang',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        footer: true
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Ambil data tambahan dari AJAX response
                    let json = api.ajax.json();

                    // Tampilkan total di footer
                    $(api.column(3).footer()).html(parseFloat(json.totalBerat).toFixed(2) + ' Kg');
                    $(api.column(4).footer()).html('Rp ' + parseFloat(json.totalHarga)
                        .toLocaleString());
                }
            });

        });
    </script>
@endpush
