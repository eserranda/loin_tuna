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
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    {{-- <script src="{{ asset('assets') }}/js/pages/datatables.init.js"></script> --}}
@endpush
@section('title')
    <h4 class="mb-sm-0">Receiving</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Receving</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-8">
            <div class="d-flex flex-column h-100">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Buat Receving Baru</h4>
                            </div>
                            <div class="card-body">
                                <form id="receivingForm">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="error-messages" class="alert alert-danger d-none"></div>
                                        </div>
                                        <div class="col-6">
                                            <label for="nama" class="form-label">Supplier</label>
                                            <select class="nama_suppliers form-control" name="id_supplier" id="id_supplier">
                                                <option value="" selected disabled>Pilih Supplier</option>
                                            </select>

                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" placeholder="Tanggal"
                                                    id="tanggal" name="tanggal">

                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-2">
                                            <div class="text-start">
                                                <button type="submit" class="btn btn-primary">Buat Receiving</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Receiving</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped mt-0 datatable" id="datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ILC</th>
                                            <th>Tanggal</th>
                                            <th>Persen</th>
                                            <th></th>
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
    </div>
@endsection

@push('scripts')
    <!--select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/select2.init.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const myDataTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                // lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Internal Lot Code",
                },

                ajax: "{{ route('receiving.getAll') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                    },
                    {
                        data: 'ilc',
                        name: 'ilc',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'checking',
                        name: 'checking',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: 'Bftp',
                // dom: 'Bftip',
            });
        });


        document.getElementById('receivingForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('{{ route('receiving.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.errors) {
                    const errorMessages = document.getElementById('error-messages');
                    errorMessages.innerHTML = '';
                    errorMessages.classList.remove('d-none');

                    Object.keys(data.errors).forEach(key => {
                        const messages = data.errors[key].join(' ');
                        errorMessages.innerHTML += `<li>${messages}</li>`;
                    });
                } else {
                    const errorMessages = document.getElementById('error-messages');
                    errorMessages.innerHTML = '';
                    errorMessages.classList.add('d-none');

                    form.reset();
                    $('.datatable').DataTable().ajax.reload();
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });

        document.addEventListener('DOMContentLoaded', async () => {
            const response = await fetch('{{ route('supplier.get') }}');
            const suppliers = await response.json();

            const selectElement = document.getElementById('id_supplier');

            suppliers.forEach(supplier => {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.text = supplier.nama_supplier;
                selectElement.appendChild(option);
            });

            $('.nama_suppliers').select2();
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
