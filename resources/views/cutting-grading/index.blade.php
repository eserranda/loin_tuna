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
    <h4 class="mb-sm-0">Cutting</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Cutting</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-8">
            <div class="d-flex flex-column h-100">
                <div class="row mb-0">
                    <div class="col-md-6">
                        <div class="card mb-1">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-sm-6 mb-2">
                                        ILC : <span class="fw-bold">{{ $data->ilc }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        Ekspor : <span class="fw-bold"> {{ $data->ekspor }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        ILC Cutting : <span class="fw-bold"> {{ $data->ilc_cutting }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        Tanggal : <span class="fw-bold">{{ $tanggal }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        Total Berat : <span class="fw-bold" id="total_berat_receiving"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Buat Produk Cutting Baru</h4>
                            </div>
                            <div class="card-body">
                                <form id="cuttingGradingForm">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="error_combination" class="alert alert-danger d-none"></div>
                                        </div>
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
                                                <select class="form-select mb-3" id="no_loin" name="no_loin">
                                                </select>
                                            </div>
                                        </div>

                                        <p class="form-label">Grade</p>
                                        <div class="col-12 mb-3">
                                            <div class="gap-4" id="gradesContainer">

                                            </div>
                                        </div>
                                        <input type="hidden" id="selectedGrade" name="grade" value="">

                                        <div class="col-lg-12 mt-2">
                                            <div class="text-start">
                                                <button type="submit" class="btn btn-primary">Buat Cutting</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-0">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Data Cutting</h4>
                            </div>
                            <div class="card-body">
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
                                <hr>
                                <table class="table table-striped mt-0 datatable" id="datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Berat</th>
                                            <th>No Loin</th>
                                            <th>Grade</th>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const ilc_cutting = "{{ $data->ilc_cutting }}";
            const datatable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    "search": "",
                    "searchPlaceholder": "Cari Nomor Ikan atau Grade",
                },
                ajax: "{{ url('cutting-grading/getAll') }}/" + ilc_cutting,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'berat',
                        name: 'berat',
                        orderable: false
                    },
                    {
                        data: 'no_loin',
                        name: 'no_loin',
                        orderable: false
                    },
                    {
                        data: 'grade',
                        name: 'grade',
                        orderable: false
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
        const ilc = "{{ $data->ilc }}";
        // const no_ikan = document.getElementById('no_ikan').value;
        // var nomorIkan = '';

        document.addEventListener('DOMContentLoaded', function() {
            populateNoIkan(ilc);
            calculateTotalWeight(ilc);
            grade();

            // const autoNumberStatus = localStorage.getItem('auto_number') || 'on';
            // const autoNumberSwitch = document.getElementById('auto_number_switch');

            // if (autoNumberStatus === 'on') {
            //     autoNumberSwitch.checked = true;
            //     const noLoinSelect = document.getElementById('no_loin');
            //     const noIkanValue = noLoinSelect.value;

            //     if (!noIkanValue) {
            //         console.log("no ikan belum dipilih");
            //     } else {
            //         nextNumber(ilc_cutting, noIkanValue).then(() => {
            //             document.getElementById('no_loin').readOnly = true;
            //         });
            //     }
            // } else {
            //     autoNumberSwitch.checked = false;
            //     document.getElementById('no_loin').readOnly = false;
            // }

            // autoNumberSwitch.addEventListener('change', function(event) {
            //     const isChecked = event.target.checked;
            //     localStorage.setItem('auto_number', isChecked ? 'on' : 'off');

            //     if (isChecked) {
            //         const noLoinSelect = document.getElementById('no_ikan');
            //         const noIkanValue = noLoinSelect.value;
            //         if (!noIkanValue) {
            //             console.log("no ikan belum dipilih");
            //         } else {
            //             nextNumber(ilc_cutting, noIkanValue).then(() => {
            //                 document.getElementById('no_loin').readOnly = true;
            //             });
            //         }
            //     } else {
            //         document.getElementById('no_loin').readOnly = false;
            //         document.getElementById('no_loin').value = '';
            //     }
            // });

            // document.getElementById('no_ikan').addEventListener('change', function(event) {
            //     const noIkanValue = event.target.value;
            //     nomorIkan = noIkanValue;
            //     if (!noIkanValue) {
            //         console.log("no ikan belum dipilih");
            //         return;
            //     }

            //     if (autoNumberSwitch.checked) {
            //         nextNumber(ilc_cutting, noIkanValue);
            //     }
            // });

            document.getElementById('cuttingGradingForm').addEventListener('submit', async (event) => {
                event.preventDefault();

                const form = event.target;
                const formData = new FormData(form);
                formData.append('ilc_cutting', ilc_cutting);
                formData.append('ilc', ilc);

                const noLoinSelect = document.getElementById('no_loin');
                const noIkanValue = noLoinSelect.value;
                if (!noIkanValue) {
                    console.log("no ikan belum dipilih");
                    // return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const response = await fetch('{{ route('cutting-grading.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok) {
                        calculateTotalWeight(ilc);
                        const invalidInputs = document.querySelectorAll('.is-invalid');
                        const errorCombination = document.getElementById('error_combination');
                        errorCombination.innerHTML = '';
                        errorCombination.classList.add('d-none');

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
                        // const autoNumberStatus = localStorage.getItem('auto_number');
                        // if (autoNumberStatus === 'on') {
                        //     nextNumber(ilc_cutting, noIkanValue);
                        // } else {
                        //     autoNumberSwitch.checked = false;
                        //     document.getElementById('no_loin').readOnly = false;
                        // }
                    } else {
                        if (data.errors && data.errors.unique_combination) {
                            const errorCombination = document.getElementById('error_combination');
                            errorCombination.innerHTML = '';
                            errorCombination.classList.remove('d-none');
                            errorCombination.innerHTML += `<li>${data.errors.unique_combination}</li>`;
                        } else if (data.errors) {
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
                    }
                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                }
            });
        });

        async function kodeILC(ilc) {
            document.getElementById('ilc').value = ilc;
        }

        function calculateTotalWeight(ilc) {
            fetch("/cutting-grading/calculateTotalWeight/" + ilc)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total_berat').textContent = `${data.totalBerat} Kg`;
                    document.getElementById('total_berat_receiving').textContent = `${data.totalBeratReceiving} Kg`;
                    document.getElementById('sisa_berat').textContent = `${data.totalSisaBerat} Kg`;
                    document.getElementById('persentasePenggunaan').textContent = `${data.persentasePenggunaan} %`;
                })
                .catch(error => {
                    console.error('Error fetching next no_ikan:', error);
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
                        url: '/cutting-grading/' + id,
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
                                // const autoNumberStatus = localStorage.getItem('auto_number');
                                // const noLoinSelect = document.getElementById('no_ikan');
                                // const noIkanValue = noLoinSelect.value;

                                // if (noLoinSelect.value == "") {
                                //     console.log("no ikan belum dipilih");
                                //     return;
                                // } else {
                                //     if (autoNumberStatus === 'on') {
                                //         nextNumber(ilc_cutting, nomorIkan);
                                //     } else {
                                //         autoNumberSwitch.checked = false;
                                //         document.getElementById('no_loin').readOnly = false;
                                //     }
                                // }
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

        function populateNoIkan(ilc) {
            fetch("/raw-material/getNoIkan/" + ilc)
                .then(response => response.json())
                .then(data => {
                    const noLoinSelect = document.getElementById('no_loin');
                    noLoinSelect.innerHTML = '<option value="" selected disabled>Pilih Nomor Loin</option>';
                    data.forEach(no_ikan => {
                        const option = document.createElement('option');
                        option.value = no_ikan;
                        option.textContent = no_ikan;
                        noLoinSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching no_ikan:', error);
                });
        }

        // function nextNumber(ilc_cutting, no_loin) {
        //     return fetch("/cutting-grading/nextNumber/" + ilc_cutting + "/" + no_loin)
        //         .then(response => response.json())
        //         .then(data => {
        //             document.getElementById('no_loin').value = data.next_no_loin;
        //         })
        //         .catch(error => {
        //             console.error('Error fetching next no_ikan:', error);
        //         });
        // }
    </script>
@endpush
