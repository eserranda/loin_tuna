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
    <h4 class="mb-sm-0">Retouching</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Retouching</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="row mb-0">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0 flex-grow-1">Data Cutting</h4>
                                <hr class="mt-2">
                                <table class="table table-striped mt-0 datatableCutting" id="datatableCutting"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ILC </th>
                                            <th>Tanggal </th>
                                            {{-- <th>Ekspor</th> --}}
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card mb-2">
                            {{-- <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Receiving</h4>
                            </div> --}}
                            <div class="card-body">
                                <form id="cuttingForm">
                                    <div class="row">
                                        <div class="col-6  mb-2">
                                            <label for="berat" class="form-label">ILC Cutting</label>
                                            <input type="text" class="form-control bg-light"
                                                placeholder="Internal Lot Code" id="ilc_cutting" name="ilc_cutting"
                                                readonly>
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
                                            <label for="berat" class="form-label">Total Berat Loin</label>
                                            <input type="number" class="form-control" placeholder="Berat" id="berat"
                                                name="berat" step="0.01">
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <div class="text-start">
                                                <button type="submit" class="btn btn-primary">Buat Retoucing</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-0">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Retouching</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped mt-0 datatableRetouching" id="datatableRetouching"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ILC</th>
                                            {{-- <th>Tanggal</th> --}}
                                            <th>Supplier</th>
                                            {{-- <th>Ekspor</th> --}}
                                            <th>Berat</th>
                                            <th>Persen</th>
                                            <th></th>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function kodeILC(ilc_cutting) {
            try {
                const response = await fetch('/retouching/getNumberLoinCutting/' + ilc_cutting, {
                    method: 'GET',
                });
                const data = await response.json();
                console.log(data)
                if (response.ok) {
                    const noLoinSelect = document.getElementById('no_loin');
                    noLoinSelect.innerHTML = '<option value="" selected disabled>Pilih Nomor Loin</option>';
                    data.forEach(no_loin => {
                        const option = document.createElement('option');
                        option.value = no_loin;
                        option.textContent = no_loin;
                        noLoinSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
            document.getElementById('ilc_cutting').value = ilc_cutting;
        }

        document.getElementById('no_loin').addEventListener('change', async function(event) {
            var ilc_cutting = document.getElementById('ilc_cutting').value;
            var noIkanValue = event.target.value;
            try {
                const response = await fetch('/retouching/calculateLoin/' + ilc_cutting + '/' + noIkanValue, {
                    method: 'GET',
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                console.log(data);
                document.getElementById('berat').value = data;
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cuttingForm').addEventListener('submit', async (event) => {
                event.preventDefault();

                const form = event.target;
                const formData = new FormData(form);

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const response = await fetch('{{ route('retouching.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });

                    const data = await response.json();
                    if (response.ok) {
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

                        $('.datatableRetouching').DataTable().ajax.reload();
                    } else {
                        Object.keys(data.errors).forEach(fieldName => {
                            const inputField = document.getElementById(fieldName);
                            if (inputField) {
                                inputField.classList.add('is-invalid');
                                if (inputField.nextElementSibling) {
                                    inputField.nextElementSibling.textContent = data.errors[
                                        fieldName][0];
                                }
                            }
                        });

                        const validFields = document.querySelectorAll('.is-invalid');
                        validFields.forEach(validField => {
                            const fieldName = validField.id;
                            if (!data.errors[fieldName]) {
                                validField.classList.remove('is-invalid');
                                if (validField.nextElementSibling) {
                                    validField.nextElementSibling.textContent = '';
                                }
                            }
                        });
                    }

                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                }
            });
        });

        $(document).ready(function() {
            const datatable = $('.datatableCutting').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari ILC Atau Ekspor",
                },
                ajax: "{{ route('retouching.getAllCutting') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,

                    },
                    {
                        data: 'ilc_cutting',
                        name: 'ilc_cutting',
                        orderable: false,
                    },
                    // {
                    //     data: 'ekspor',
                    //     name: 'ekspor',
                    //     orderable: false,
                    // },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return moment(data).format(
                                'DD-MM-YYYY'
                            );
                        },
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

            const datatableRetouching = $('.datatableRetouching').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data Retouching",
                },
                ajax: "{{ route('retouching.getAll') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'ilc_cutting',
                        name: 'ilc_cutting',
                        orderable: false,

                    },
                    // {
                    //     data: 'tanggal',
                    //     name: 'tanggal',
                    //     orderable: false,

                    // },
                    {
                        data: 'id_supplier',
                        name: 'id_supplier',
                        orderable: false,

                    },
                    // {
                    //     data: 'ekspor',
                    //     name: 'ekspor',
                    //     orderable: false,

                    // },
                    {
                        data: 'total_berat',
                        name: 'total_berat',
                        orderable: false,

                    },
                    {
                        data: 'inspection',
                        name: 'inspection',
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
                        url: '/retouching/' + id,
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
                                $('.datatableRetouching').DataTable().ajax.reload();

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
