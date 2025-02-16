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

    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
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
    <h4 class="mb-sm-0">Data Packing</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Packing</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="row">
                    <div class="col-md-5">
                        <div class="accordion mb-1" id="default-accordion-example">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Product order {{ $data_po->po_number }}
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#default-accordion-example">
                                    <div class="accordion-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table table-striped mt-0 list_product" id="list_product"
                                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Product</th>
                                                            <th>Qty</th>
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

                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Product Ready </h4>
                            </div>

                            <div class="card-body">
                                <table class="table table-striped mt-0 product_ready" id="product_ready"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Berat</th>
                                            <th>Qty</th>
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
                        <div class="card mb-2">
                            <div class="card-body">
                                <form id="packingProduk">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <label for="id_produk" class="form-label">Product</label>
                                            <input type="hidden" class="form-control bg-light" id="id_produk_log"
                                                name="id_produk_log" readonly>
                                            <input type="text" class="form-control bg-light" placeholder="Product"
                                                id="nama_produk" name="nama_produk" readonly>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="berat" class="form-label">Berat</label>
                                            <input type="text" class="form-control bg-light" placeholder="Berat"
                                                id="berat" name="berat" step="0.01" readonly>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-start">
                                                <button type="submit" class="btn btn-primary">Packing Product</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Packing Product</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped mt-0 packing_po" id="packing_po"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product</th>
                                            <th>Total QTY</th>
                                            <th>Total Berat</th>
                                            <th>Progres</th>
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
        async function setProduct(id, nama_produk, berat) {
            document.getElementById('id_produk_log').value = id;
            document.getElementById('nama_produk').value = nama_produk;
            document.getElementById('berat').value = berat;
        }

        $(document).ready(function() {
            const list_product = $('.list_product').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/packing-po/getAllDataProductOrder/" + '{{ $data_po->po_number }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                    },
                    {
                        data: 'product',
                        name: 'product',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                    },
                ],
                dom: 'Btp',
            });

            const product_ready = $('.product_ready').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data Product Ready",
                },
                ajax: "/packing-po/getAllProductLogs/" + '{{ $data_po->po_number }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                    },
                    {
                        data: 'id_produk',
                        name: 'id_produk',
                    },
                    {
                        data: 'berat',
                        name: 'berat',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        orderable: false,
                        searchable: false
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

            const packing_po = $('.packing_po').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Data Packing",
                },
                ajax: "/packing-po/getAllPackingPo/" + '{{ $data_po->po_number }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'id_produk',
                        name: 'id_produk',
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty',
                    },
                    {
                        data: 'total_weight',
                        name: 'total_weight',
                    },
                    {
                        data: 'progress',
                        name: 'progress',
                    },
                ],
                dom: 'Bftp',
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
                        url: '/cutting/' + id + '/' + ilc,
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
                                $('.list_product').DataTable().ajax.reload();
                                $('.receiving').DataTable().ajax.reload();
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




        document.getElementById('packingProduk').addEventListener('submit', async (event) => {
            event.preventDefault();
            const po_number = "{{ $data_po->po_number }}";
            const form = event.target;
            const formData = new FormData(form);
            formData.append('po_number', po_number);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('{{ route('packing-po.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();
                console.log(data.success);
                if (data.errors) {
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
                    form.reset();
                    $('.list_product').DataTable().ajax.reload();
                    $('.product_ready').DataTable().ajax.reload();
                    $('.packing_po').DataTable().ajax.reload();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: data.message,
                        });
                    }
                    from.reset();
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });
    </script>
@endpush
