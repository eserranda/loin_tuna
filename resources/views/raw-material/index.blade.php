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
@endpush

@section('title')
    <h4 class="mb-sm-0">Receiving</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Receving</a></li>
            <li class="breadcrumb-item active">Grading</li>
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
                                <h4 class="card-title mb-0 flex-grow-1">Data Receiving</h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-sm-6 mb-1">
                                        ILC : <span class="fw-bold">{{ $data->ilc }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        Tanggal : <span class="fw-bold"> {{ $data->tanggal }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- Supplier : <span class="fw-bold">{{ $data->supplier->nama_supplier }}</span> --}}
                                        Supplier : <span class="fw-bold">{{ $data->supplier->nama_supplier ?? '-' }}</span>

                                    </div>
                                    <div class="col-sm-6 mt-1">
                                        Hasil Checking : <span class="fw-bold">{{ $data->checking }}% </span>
                                    </div>
                                </div>
                                <hr>
                                <form id="rawMaterialLotsForm">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="berat" class="form-label">Berat</label>
                                            <input type="number" class="form-control" placeholder="Berat" id="berat"
                                                name="berat" step="0.01">
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="no_loin" class="form-label">Nomor Loin</label>
                                                <input type="number" class="form-control" placeholder="Nomor Loin"
                                                    id="no_loin" name="no_loin">
                                                <div class="invalid-feedback"></div>
                                                <span>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="auto_number_receiving">
                                                        <label class="form-check-label" for="auto_number_switch">Auto
                                                            Number</label>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>

                                        <p class="form-label">Grade</p>
                                        <div class="col-12 mb-3">
                                            <div class="gap-4" id="gradesContainer">

                                            </div>
                                        </div>
                                        <input type="hidden" id="selectedGrade" name="grade" value="">
                                        <div class="invalid-feedback">
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
                                <h4 class="card-title mb-0 flex-grow-1">Produk Receiving</h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-sm-6 mb-1">
                                        Total Berat : <span class="fw-bold" id="total_berat"> </span>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped mt-0 datatable" id="datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Loin</th>
                                            <th>Grade</th>
                                            <th>Berat (Kg)</th>
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
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            const ilc = "{{ $data->ilc }}";
            const datatable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Nomor Ikan",
                },

                ajax: "{{ url('/raw-material/findManyWithILC') }}/" + ilc,
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

        var ilc = "{{ $data->ilc }}";
        document.addEventListener('DOMContentLoaded', function() {
            const autoNumberStatus = localStorage.getItem('auto_number') || 'on';
            const autoNumberSwitch = document.getElementById('auto_number_receiving');

            if (autoNumberStatus === 'on') {
                autoNumberSwitch.checked = true;
                nextNumber(ilc).then(() => {
                    document.getElementById('no_loin').readOnly = true;
                });
            } else {
                autoNumberSwitch.checked = false;
                document.getElementById('no_loin').readOnly = false;
            }

            autoNumberSwitch.addEventListener('change', function(event) {
                const isChecked = event.target.checked;
                localStorage.setItem('auto_number', isChecked ? 'on' : 'off');

                if (isChecked) {
                    nextNumber(ilc).then(() => {
                        document.getElementById('no_loin').readOnly = true;
                    });
                } else {
                    document.getElementById('no_loin').readOnly = false;
                    document.getElementById('no_loin').value = '';
                }
            });

            calculateTotalWeight(ilc);
            grade();

            document.getElementById('rawMaterialLotsForm').addEventListener('submit', async (event) => {
                event.preventDefault();

                const ilc = "{{ $data->ilc }}";
                const form = event.target;
                const formData = new FormData(form);
                formData.append('ilc', ilc); // menambahkan ilc ke formData

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const response = await fetch('{{ route('raw_material.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    })

                    const data = await response.json();
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

                        $('.datatable').DataTable().ajax.reload();
                        calculateTotalWeight(ilc);

                        const autoNumberStatus = localStorage.getItem('auto_number');
                        if (autoNumberStatus === 'on') {
                            nextNumber(ilc);
                        } else {
                            autoNumberSwitch.checked = false;
                            document.getElementById('no_loin').readOnly = false;
                        }
                    }
                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                }
            });
        });

        function calculateTotalWeight(ilc) {
            fetch('/raw-material/calculateTotalWeight/' + ilc)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total_berat').textContent = `${data.totalBerat} Kg`;
                })
                .catch(error => {
                    console.error('Error fetching total berat:', error);
                });
        }

        function grade() {
            fetch('/grades/getAll')
                .then(response => response.json())
                .then(data => {
                    const gradesContainer = document.getElementById('gradesContainer');
                    const selectedGradeInput = document.getElementById('selectedGrade');
                    gradesContainer.innerHTML = '';

                    data.forEach(grade => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'btn btn-soft-secondary custom-toggle m-2';
                        button.setAttribute('data-bs-toggle', 'button');
                        button.innerHTML = `
                    <span class="icon-on mx-2">${grade}</span>
                    <span class="icon-off mx-2">${grade}</span>
                `;

                        button.addEventListener('click', () => {
                            // Remove active class from all buttons
                            const buttons = gradesContainer.querySelectorAll('button');
                            buttons.forEach(btn => btn.classList.remove('active'));

                            // Add active class to the clicked button
                            button.classList.add('active');

                            // Set the selected grade value
                            selectedGradeInput.value = grade;
                        });

                        gradesContainer.appendChild(button);
                    });
                })
                .catch(error => {
                    console.error('err saat mengambil data:', error);
                });
        }

        function nextNumber(ilc) {
            return fetch("/raw-material/nextNumber/" + ilc)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('no_loin').value = data.next_no_loin;
                })
                .catch(error => {
                    console.error('Error fetching next no_loin:', error);
                });
        }

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
                        url: '/raw-material/' + id,
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
                                calculateTotalWeight(ilc);
                                const autoNumberStatus = localStorage.getItem('auto_number');
                                if (autoNumberStatus === 'on') {
                                    nextNumber(ilc);
                                } else {
                                    autoNumberSwitch.checked = false;
                                    document.getElementById('no_loin').readOnly = false;
                                }
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
