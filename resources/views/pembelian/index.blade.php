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
    <div class="col-xxl-12 col-lg-12 col-md-12">
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



            <div class="card-body border-bottom py-3 ">
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
                    <tfoot>
                        <tr>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
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
                        },
                        footer: true
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        footer: true
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Ambil data tambahan dari AJAX response
                    let json = api.ajax.json();

                    // Tampilkan total di footer
                    $(api.column(3).footer()).html(parseFloat(json.totalLoin).toFixed(0) + ' Loin');
                    $(api.column(4).footer()).html(parseFloat(json.totalBerat).toFixed(2) + ' Kg');
                    $(api.column(5).footer()).html('Rp' + parseFloat(json.totalHarga).toLocaleString());
                }
            });

            $('#filterMinggu').on('change', function() {
                $('#filterBulan').val('');
                $('#filterTahun').val('');
                var selectedMinggu = $(this).val();
                const url = '{{ route('pembelian.getAllDataPembelian') }}?filterMinggu=' + selectedMinggu;
                datatable.ajax.url(url).load();

            });

            $('#filterBulan').on('change', function() {
                $('#filterMinggu').val('');
                var selectedBulan = $(this).val();
                var selectedTahun = $('#filterTahun').val();

                if (selectedTahun === null) {
                    const url = '{{ route('pembelian.getAllDataPembelian') }}?filterBulan=' +
                        selectedBulan;
                    datatable.ajax.url(url).load();

                } else {
                    const url = '{{ route('pembelian.getAllDataPembelian') }}?filterBulan=' +
                        selectedBulan + '&filterTahun=' + selectedTahun;
                    datatable.ajax.url(url).load();
                }
            });

            $('#filterTahun').on('change', function() {
                $('#filterMinggu').val('');
                var selectedTahun = $(this).val();
                var selectedBulan = $('#filterBulan').val();
                if (selectedBulan === null) {
                    const url = '{{ route('pembelian.getAllDataPembelian') }}?filterTahun=' +
                        selectedTahun;
                    datatable.ajax.url(url).load();
                } else {
                    const url = '{{ route('pembelian.getAllDataPembelian') }}?filterBulan=' +
                        selectedBulan + '&filterTahun=' + selectedTahun;
                    datatable.ajax.url(url).load();
                }
            });

            $('#reload').on('click', function() {
                $('#filterMinggu').val('');
                $('#filterBulan').val('');
                $('#filterTahun').val('');
                const url = '{{ route('pembelian.getAllDataPembelian') }}';
                datatable.ajax.url(url).load();
            });


            // $('#search').on('click', function() {
            //     var selectedMinggu = $('#filterMinggu').val();
            //     var selectedBulan = $('#filterBulan').val();
            //     var selectedTahun = $('#filterTahun').val();

            //     // if (!selectedMinggu && !selectedBulan && !selectedTahun) {
            //     //     alert('Pilih minggu, bulan, atau tahun terlebih dahulu');
            //     //     return;
            //     // }

            //     const url = '{{ route('pembelian.getAllDataPembelian') }}?filterMinggu=' + selectedMinggu +
            //         '&filterBulan=' + selectedBulan + '&filterTahun=' + selectedTahun;
            //     datatable.ajax.url(url).load();
            // });
        });
    </script>
@endpush
