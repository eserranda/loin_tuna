@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Produk</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Produk</a></li>
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
        <div class="col-xxl-12 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Data Produk</h4>
                        {{-- <div class="flex-shrink-0">
                            <a href={{ route('produk.add') }} class="btn btn-info ">Tambah Produk</a>
                        </div> --}}
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">Tambah Data</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table dataProduk">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Berat (Kg)</th>
                                    <th>customer Group</th>
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

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>

                <form id="addForm" action="{{ route('product.store') }}" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-lable">Input File Foto</label>
                                <input type="file" class="filestyle" data-buttonname="btn-secondary" name="image"
                                    id="product_image" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <img class="img-thumbnail" id="photoPreview" src="" alt="Photo Preview"
                                    style="max-width: 200px; max-height: 200px;">
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode Produk</label>
                                    <input type="text" id="kode" name="kode" class="form-control"
                                        placeholder="Kode" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Produk</label>
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="Nama Produk" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" placeholder="Harga" id="harga"
                                        name="harga">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Berat (kg)</label>
                                    <input type="number" class="form-control" placeholder="Berat" id="berat"
                                        name="berat" step="0.01">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="customer_group" class="form-label">Customer Group</label>
                                    <select class="form-control" name="customer_group" id="customer_group">
                                        <option value="" selected disabled>Pilih Customer Group</option>
                                        <option value="USA">USA</option>
                                        <option value="EROPA">EROPA</option>
                                        <option value="JEPANG">JEPANG</option>
                                        <option value="LOCAL">LOCAL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Tambah</button>
                            <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Tambah Image Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>

                <form id="addImageForm">
                    <div class="modal-body">
                        <input type="hidden" id="id_product" name="id" />
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-lable">Input File Foto</label>
                                <input type="file" class="filestyle" data-buttonname="btn-secondary" name="image"
                                    id="image" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <img class="img-thumbnail" id="photoProductPreview" src="" alt="Photo Preview"
                                    style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Tambah</button>
                            <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('addImageForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch('{{ route('product.saveImage') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        form.reset();
                        $('.dataProduk').DataTable().ajax.reload();
                        $('#addImageModal').modal('hide');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silahkan coba lagi.');
                });
        });

        async function showModalAddImage(id) {
            $('#addImageModal').modal('show');
            document.getElementById('id_product').value = id;
        }

        document.getElementById('product_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoProductPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('addForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('{{ route('product.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        form.reset();
                        $('.dataProduk').DataTable().ajax.reload();
                        $('#addModal').modal('hide');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silahkan coba lagi.');
                });
        });


        $(document).ready(function() {
            const datatable = $('.dataProduk').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data",
                },
                ajax: "{{ route('product.getAllData') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'images',
                        name: 'images',
                        orderable: false,
                    },
                    {
                        data: 'kode',
                        name: 'kode',
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'berat',
                        name: 'berat',
                    },
                    {
                        data: 'customer_group',
                        name: 'customer_group',
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
                        url: '/product/' + id,
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
                                $('.dataProduk').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data guru.',
                                    'error'
                                );
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data guru.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
