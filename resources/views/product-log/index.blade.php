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
    <!--- Select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    {{-- Moment.js untuk Memformat Tanggal di Frontend --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endpush
@section('title')
    <h4 class="mb-sm-0">Product Log</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Product Log</a></li>
            <li class="breadcrumb-item active">Buat produk</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-8">
            <div class="d-flex flex-column h-100">
                <div class="row mb-0">
                    <div class="col-md-5">
                        <div class="card mb-1">
                            <div class="card-body">
                                <h4 class="card-title mb-2">Detail : </h4>
                                <div class="col-sm-12">
                                    ILC Cutting : <span class="fw-bold"> {{ $data->ilc_cutting ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 my-1">
                                    Ekspor : <span class="fw-bold"> {{ $data->ekspor ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    Tgl Cutting : <span class="fw-bold">{{ $tanggal ?? '-' }}</span>
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Pilih Produk</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped mt-0 dataProduk" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kode</th>
                                            <th>berat</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card mb-0">
                            <div class="card-body">
                                <form id="productLogForm">
                                    <div class="row">
                                        <div class="col-6  mb-2">
                                            <label for="berat" class="form-label">Produk</label>
                                            <input type="text" class="form-control bg-light" placeholder="Produk"
                                                id="produk" readonly>
                                            <input type="hidden" class="form-control bg-light" id="id_produk"
                                                name="id_produk" readonly>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-0">
                                                <label for="no_loin" class="form-label">Nomor Loin</label>
                                                <select class="form-select mb-0" id="no_loin" name="no_loin">
                                                </select>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="berat" class="form-label">Berat Produk</label>
                                            <input type="number" class="form-control bg-light" placeholder="Berat"
                                                id="berat" name="berat" readonly>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <div class="text-start">
                                                <button type="submit" class="btn btn-primary">Buat Produk</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                {{-- <table class="table table-striped mt-0 datatable" id="datatable"> --}}
                                <table class="table table-striped mt-0 dataTableProductLogs" id="dataTableProductLogs"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Ekspor</th>
                                            <th>Total Berat</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <hr>
                                <div class="row align-items-start">
                                    <div class="col-sm-6 mb-1">
                                        Total Berat : <span class="fw-bold" id="total_berat"> kg</span>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        Sisa Berat : <span class="fw-bold" id="sisa_berat"> kg</span>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        Persentase : <span class="fw-bold" id="persentasePenggunaan"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function setProduct(id, nama, berat) {
            document.getElementById('id_produk').value = id;
            document.getElementById('produk').value = nama;
            document.getElementById('berat').value = berat;
        }

        document.getElementById('productLogForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const ilc = "{{ $data->ilc }}";
            const ekspor = "{{ $data->ekspor }}";

            const form = event.target;
            const formData = new FormData(form);
            formData.append('ilc', ilc);
            formData.append('ekspor', ekspor);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch('/product-log/store', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData,
                });

                const data = await response.json();
                console.log(data);
                if (!data.success) {
                    Object.keys(data.messages).forEach(fieldName => {
                        const inputField = document.getElementById(fieldName);
                        if (inputField) {
                            inputField.classList.add('is-invalid');
                            if (inputField.nextElementSibling) {
                                inputField.nextElementSibling.textContent = data.messages[
                                    fieldName][0];
                            }
                        }
                    });

                    // hapus error message jika form sudah di isi
                    const validFields = document.querySelectorAll('.is-invalid');
                    validFields.forEach(validField => {
                        const fieldName = validField.id;
                        if (!data.messages[fieldName]) {
                            validField.classList.remove('is-invalid');
                            if (validField.nextElementSibling) {
                                validField.nextElementSibling.textContent = '';
                            }
                        }
                    });

                } else {
                    const invalidInputs = document.querySelectorAll('.is-invalid');
                    invalidInputs.forEach(invalidInput => {
                        invalidInput.value = '';
                        invalidInput.classList.remove('is-invalid');
                        const errorNextSibling = invalidInput.nextElementSibling;
                        if (errorNextSibling && errorNextSibling.classList.contains(
                                'invalid-feedback')) {
                            errorNextSibling.textContent = '';
                        }
                    });

                    // const product = document.getElementById('id_produk');
                    // product.value = '';

                    document.getElementById('produk').value = '';
                    document.getElementById('berat').value = '';
                    $('#datatable').DataTable().ajax.reload();
                    $('#dataTableProductLogs').DataTable().ajax.reload();
                    $('#addModal').modal('hide');
                }
            } catch (error) {
                console.error(error);
            }
        });

        $(document).ready(function() {
            const ekspor = "{{ $data->ekspor }}";
            const datatable = $('.dataProduk').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                scrollCollapse: true,
                scrollY: '150px',
                targets: 0,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Nama Produk",
                },
                ajax: "{{ url('/product/productWithCustomerGroup') }}/" + ekspor,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: false,

                    },
                    {
                        data: 'kode',
                        name: 'kode',
                        orderable: false,

                    },
                    {
                        data: 'berat',
                        name: 'berat',
                        orderable: false,

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


            const dataTableProductLogs = $('#dataTableProductLogs').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data Produk",
                },
                ajax: "{{ url('/product/getAllDataProductLog') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'id_produk',
                        name: 'id_produk',
                        orderable: false,
                    },
                    {
                        data: 'ekspor',
                        name: 'ekspor',
                        orderable: false,

                    },
                    {
                        data: 'berat',
                        name: 'berat',
                        orderable: false,

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

        const ilc_cutting = "{{ $data->ilc_cutting }}";
        document.addEventListener('DOMContentLoaded', function() {
            async function getNoIkan(ilc_cutting) {
                try {
                    const response = await fetch('/retouching/getNumberLoin/' + ilc_cutting, {
                        method: 'GET',
                    });
                    const data = await response.json();
                    console.log(data)
                    if (response.ok) {
                        const noIkanSelect = document.getElementById('no_loin');
                        noIkanSelect.innerHTML = '<option value="" selected disabled>Pilih Nomor Ikan</option>';
                        data.forEach(no_loin => {
                            const option = document.createElement('option');
                            option.value = no_loin;
                            option.textContent = no_loin;
                            noIkanSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                }
            }

            getNoIkan(ilc_cutting);
        });

        // document.getElementById('no_loin').addEventListener('change', async function(event) {
        //     const ilc = "{{ $data->ilc }}";
        //     // const no_loin = event.target.value;
        //     try {
        //         const response = await fetch('/retouching/getBerat/' + ilc + '/' + event.target.value, {
        //             method: 'GET',
        //         });
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok ' + response.statusText);
        //         }
        //         const data = await response.json();
        //         console.log(data);
        //         document.getElementById('berat').value = data;
        //     } catch (error) {
        //         console.error('Fetch error: ', error);
        //         alert('Terjadi kesalahan saat mengambil data.');
        //     }
        // });

        // async function print(id_product, ilc) {
        //     try {
        //         const response = await fetch('/print/product-log-print/' + id_product + '/' + ilc, {
        //             method: 'GET',
        //         });

        //         if (!response.ok) {
        //             throw new Error('Network response was not ok ' + response.statusText);
        //         }

        //         // const data = await response.json();

        //         console.log(data);
        //     } catch (error) {
        //         console.error('Fetch error: ', error);
        //         // alert('Terjadi kesalahan saat mengambil data.');
        //     }
        // }



        async function hapus(id) {
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
                        url: '/product-log/' + id,
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
                                $('#dataTableProductLogs').DataTable().ajax.reload();
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
                                'Terjadi kesalahan saat menghapus data!',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
