@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Inspection</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Inspection</a></li>
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
        <div class="col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Data Inspection</h4>
                        {{-- <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">Tambah Data</button>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <table class="table datatable" id="datatable"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ILC</th>
                                    <th>Stage</th>
                                    <th>Uji Lab</th>
                                    <th>Tekstur</th>
                                    <th>Bau</th>
                                    <th>ES</th>
                                    <th>Suhu</th>
                                    <th>Hasil</th>
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

    @include('inspection.edit')
@endsection
@push('scripts')
    <script>
        function update(id, ilc, stage) {
            $('#updateModal').modal('show');
            $('#updateModal').find('#id').val(id);
            $('#updateModal').find('#ilc').val(ilc);
            $('#updateModal').find('#stage').val(stage);
        }

        $(document).ready(function() {
            const datatable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data",
                },
                ajax: "{{ route('inspection.getAll') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'ilc',
                        name: 'ilc',
                    },
                    {
                        data: 'stage',
                        name: 'stage',
                    },
                    {
                        data: 'uji_lab',
                        name: 'uji_lab',
                    },
                    {
                        data: 'tekstur',
                        name: 'tekstur',
                    },
                    {
                        data: 'bau',
                        name: 'bau',
                    },
                    {
                        data: 'es',
                        name: 'es',
                    },
                    {
                        data: 'suhu',
                        name: 'suhu',
                    },
                    {
                        data: 'hasil',
                        name: 'hasil',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ],
                dom: 'Bftp',
            });
        });
    </script>
@endpush
