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
    <h4 class="mb-sm-0">Laporan Penjualan</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Penjualan</a></li>
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
                        <div class="card-header">
                            <div class="mt-3 list-inline list-inline-dots mb-0 text-secondary d-sm-block d-none">
                                <div class="list-inline-item col-2">
                                    <select class="form-select" id="filterMinggu">
                                        <option value="" selected disabled>- Pilih Minggu -</option>
                                        <option value="0">Minggu ini</option>
                                        <option value="1">1 Minggu lalu</option>
                                        <option value="2">2 Minggu lalu</option>
                                        <option value="3">3 Minggu lalu</option>
                                        <option value="4">4 Minggu lalu</option>
                                    </select>
                                </div>
                                <div class="list-inline-item col-2">
                                    <select class="form-select" id="filterBulan">
                                        <option value="" selected disabled>- Pilih Bulan -</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="list-inline-item col-2">
                                    <select class="form-select" id="filterTahun">
                                        <option value="" selected disabled>- Pilih Tahun -</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                    </select>
                                </div>
                                <div class="list-inline-item">
                                    <button class="btn   btn-icon mx-2" id="reload">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped mt-0 dataOrder" id="dataOrder"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
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
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const datatable = $('.dataOrder').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                // lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Po Number",
                },

                ajax: "{{ route('penjualan.dataLaporanPenjualan') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                    },
                    {
                        data: 'po_number',
                        name: 'po_number',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'id_user',
                        name: 'id_user',
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                    },
                    {
                        data: 'status',
                        name: 'status',
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
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
            });

            $('#filterMinggu').on('change', function() {
                $('#filterBulan').val('');
                $('#filterTahun').val('');
                var selectedMinggu = $(this).val();
                const url = '{{ route('penjualan.dataLaporanPenjualan') }}?filterMinggu=' + selectedMinggu;
                datatable.ajax.url(url).load();

            });

            $('#filterBulan').on('change', function() {
                $('#filterMinggu').val('');
                var selectedBulan = $(this).val();
                var selectedTahun = $('#filterTahun').val();

                if (selectedTahun === null) {
                    const url = '{{ route('penjualan.dataLaporanPenjualan') }}?filterBulan=' +
                        selectedBulan;
                    datatable.ajax.url(url).load();

                } else {
                    const url = '{{ route('penjualan.dataLaporanPenjualan') }}?filterBulan=' +
                        selectedBulan + '&filterTahun=' + selectedTahun;
                    datatable.ajax.url(url).load();
                }
            });

            $('#filterTahun').on('change', function() {
                $('#filterMinggu').val('');
                var selectedTahun = $(this).val();
                var selectedBulan = $('#filterBulan').val();
                if (selectedBulan === null) {
                    const url = '{{ route('penjualan.dataLaporanPenjualan') }}?filterTahun=' +
                        selectedTahun;
                    datatable.ajax.url(url).load();
                } else {
                    const url = '{{ route('penjualan.dataLaporanPenjualan') }}?filterBulan=' +
                        selectedBulan + '&filterTahun=' + selectedTahun;
                    datatable.ajax.url(url).load();
                }
            });

            $('#reload').on('click', function() {
                $('#filterMinggu').val('');
                $('#filterBulan').val('');
                $('#filterTahun').val('');
                const url = '{{ route('penjualan.dataLaporanPenjualan') }}';
                datatable.ajax.url(url).load();
            });

        });

        async function hapus(id, ilc) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '/receiving/' + id + '/' + ilc,
                        type: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            if (response.status) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data berhasil dihapus.',
                                    'success'
                                );
                                $('.datatable').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        },

                        error: function(error) {
                            console.log(error);
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
