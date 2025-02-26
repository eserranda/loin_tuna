@extends('layouts.master')
@push('head_component')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
@endpush
@section('title')
    <h4 class="mb-sm-0">Customers</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Check Out</a></li>
            <li class="breadcrumb-item active">items</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body checkout-tab">

                    <div class="step-arrow-nav mt-n3 mx-n3 mb-3">

                        <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fs-15 p-3 active" id="pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-bill-info" type="button" role="tab"
                                    aria-controls="pills-bill-info" aria-selected="true">
                                    <i
                                        class="ri-user-2-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                    Personal Info
                                </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link fs-15 p-3" id="pills-bill-address-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-bill-address" type="button" role="tab"
                                        aria-controls="pills-bill-address" aria-selected="false">
                                        <i
                                            class="ri-truck-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                        Shipping Info
                                    </button>
                                </li> --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fs-15 p-3" id="pills-payment-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-payment" type="button" role="tab"
                                    aria-controls="pills-payment" aria-selected="false">
                                    <i
                                        class="ri-bank-card-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                    Payment
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fs-15 p-3" id="pills-finish-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-finish" type="button" role="tab"
                                    aria-controls="pills-finish" aria-selected="false">
                                    <i
                                        class="ri-checkbox-circle-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                    Status Pesanan
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-bill-info" role="tabpanel"
                            aria-labelledby="pills-bill-info-tab">
                            <div>
                                <h5 class="mb-1">Informasi Pesanan <span class="fw-bold">
                                        {{ $order->po_number }}</span></h5>
                                <p class="text-muted mb-4">Pastikan semua informasi di bawah ini sudah benar</p>
                            </div>

                            <div>
                                <form id="customerForm">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control bg-light" id="name"
                                                    name="name" placeholder="Nama Lengkap"
                                                    value="{{ $order->user->name }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username <span
                                                        class="text-muted">(Optional)</span></label>
                                                <input type="text" class="form-control bg-light" id="username"
                                                    name="username" placeholder="Username"
                                                    value="{{ $order->user->username }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email </label>
                                                <input type="email" class="form-control bg-light" id="email"
                                                    name="email" placeholder="Email" value="{{ $order->user->email }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">No. Hp</label>
                                                <input type="number" class="form-control" id="phone" name="phone"
                                                    placeholder="No. Hp"
                                                    value="{{ $order->phone ? $order->phone : $customer->phone ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jalan" class="form-label">Jalan</label>
                                        <textarea class="form-control" id="jalan" name="jalan" placeholder="Jalan" rows="3">{{ $order->jalan ? $order->jalan : $customer->jalan ?? '' }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="negara" class="form-label">Negara</label>
                                                <input type="text" class="form-control" id="negara" name="negara"
                                                    placeholder="Negara"
                                                    value="{{ $order->negara ? $order->negara : $customer->negara ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="provinsi" class="form-label">Provinsi</label>
                                                    <input type="text" class="form-control" id="provinsi"
                                                        name="provinsi" placeholder="Provinsi"
                                                        value="{{ $order->provinsi ? $order->provinsi : $customer->provinsi ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
                                                    <input type="text" class="form-control" id="kabupaten"
                                                        name="kabupaten" placeholder="Kabupaten"
                                                        value="{{ $order->kabupaten ? $order->kabupaten : $customer->kabupaten ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                                    <input type="text" class="form-control" id="kecamatan"
                                                        name="kecamatan" placeholder="Kecamatan"
                                                        value="{{ $order->kecamatan ? $order->kecamatan : $customer->kecamatan ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                                <input type="text" class="form-control" id="kode_pos"
                                                    name="kode_pos" placeholder="Kode Pos"
                                                    value="{{ $order->kode_pos ? $order->kode_pos : $customer->kode_pos ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">

                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-info">Save</button>
                                            {{-- <button type="submit" class="btn btn-info"
                                                {{ $order->status == 'confirmed' || $order->status == 'rejected' ? 'disabled' : '' }}>Save</button> --}}

                                            <button type="button" class="btn btn-primary btn-label  nexttab"
                                                data-nexttab="pills-finish-tab">
                                                <i class="ri-truck-line label-icon align-middle fs-16 ms-2"></i>
                                                Cek Status
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                            aria-labelledby="pills-payment-tab">
                            <div>
                                <h5 class="mb-1">Informasi Pembayaran</h5>
                                <p class="text-muted mb-4">Silakan melakukan pembayaran dengan memilih salah salah satu
                                    bank tujuan di bawah</p>
                            </div>
                            <div class="row gy-3 mb-2">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-check card-radio">
                                        <input id="bank_01" name="bank" type="radio" class="form-check-input">
                                        <label class="form-check-label" for="bank_01" onclick="bank_01('BCA')">
                                            <span class="mb-4 fw-semibold d-block text-muted text-uppercase">BCA</span>
                                            <span class="fs-14 mb-2 d-block">1234-1244-1234</span>
                                            <span class="text-muted fw-normal mb-1 d-block">
                                                CV. FARIS INDO SEAFOOD
                                            </span>
                                            {{-- <span class="text-muted fw-normal d-block">Mo. 012-345-6789</span> --}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-check card-radio">
                                        <input id="bank_02" name="bank" type="radio" class="form-check-input">
                                        <label class="form-check-label" for="bank_02" onclick="bank_01('BRI')">
                                            <span class="mb-4 fw-semibold d-block text-muted text-uppercase">BRI</span>
                                            <span class="fs-14 mb-2 d-block">9494-0101-2121-2112</span>
                                            <span class="text-muted fw-normal mb-1 d-block">CV. FARIS INDO
                                                SEAFOOD
                                            </span>
                                            {{-- <span class="text-muted fw-normal d-block">Mo. 012-345-6789</span> --}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <p class="mb-0">Total Pembayaran :</p>
                            <h4>{{ formatRupiah($total_amount) }}</h4>

                            <form id="pembayaranForm">
                                <div class="collapse show" id="paymentmethodCollapse">
                                    <div class="card p-4 border shadow-none mb-0 mt-4">
                                        <div class="row gy-3">
                                            <div class="col-md-6">
                                                <label for="nama" class="form-label">Nama Pengirim</label>
                                                <input type="hidden" class="form-control" id="bank"
                                                    name="bank">
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    placeholder="Nama Lengkap">
                                                <small class="text-muted">Nama lengkap sesuai pemilik akun bank</small>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="bukti_bayar" class="form-label">Upload Bukti
                                                    Pembayaran</label>
                                                <input type="file" class="form-control"
                                                    data-buttonname="btn-secondary" name="receipt_image"
                                                    id="receipt_image" class="form-control">
                                                <small class="text-muted">Pastikan foto bukti pembayaran terlihat
                                                    jelas</small>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="text-muted mt-2 fst-italic">
                                        <i data-feather="lock" class="text-muted icon-xs"></i> Your transaction is
                                        secured
                                        with SSL encryption
                                    </div> --}}
                                </div>

                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                        data-previous="pills-bill-address-tab"><i
                                            class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back</button>

                                    <button type="submit" class="btn btn-primary btn-label right ms-auto">
                                        <i class="ri-checkbox-circle-line label-icon align-middle fs-16 ms-2"></i>
                                        Upload Bukti Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end tab pane -->

                        <div class="tab-pane fade" id="pills-finish" role="tabpanel" aria-labelledby="pills-finish-tab">
                            <div class="text-center py-5">

                                <div class="mb-4">
                                    {{-- <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                            colors="primary:#0ab39c,secondary:#405189"
                                            style="width:120px;height:120px"></lord-icon> --}}
                                    <lord-icon src="https://cdn.lordicon.com/warimioc.json" trigger="loop" stroke="bold"
                                        state="loop-oscillate" colors="primary:#66d7ee,secondary:#242424"
                                        style="width:120px;height:120px">
                                    </lord-icon>
                                </div>
                                <h5>Terimakasih! Pasanan kamu akan segera diproses!</h5>
                                {{-- <p class="text-muted">Kami akan menghubungi kamu melalui email.</p> --}}

                                <h3 class="fw-semibold">Order ID: <span class="fw-bold">{{ $order->po_number }}</span>
                                </h3>
                                <h3 class="fw-semibold">Status: <span class="fw-bold">{{ $order->status }}</span>
                                </h3>
                            </div>
                        </div>
                        <!-- end tab pane -->
                    </div>
                    <!-- end tab content -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Order Summary</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th style="width: 90px;" scope="col">Product</th>
                                    <th scope="col">Product Info</th>
                                    <th scope="col" class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_orders as $row)
                                    <tr>
                                        <td>
                                            <div class="avatar-md bg-light rounded p-1">
                                                <img src="{{ asset($row->product->image ?? '/uploads/images/no-image.jpg') }}"
                                                    alt="product image" class="img-fluid d-block">
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-14"><a href="apps-ecommerce-product-details.html"
                                                    class="text-dark">{{ $row->product->nama }}</a></h5>
                                            <p class="text-muted mb-0">{{ formatRupiah($row->product->harga) }} x
                                                {{ $row->qty }}
                                            </p>
                                        </td>
                                        <td class="text-end">{{ formatRupiah($row->total_price) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="fw-semibold" colspan="2">Sub Total :</td>
                                    <td class="fw-semibold text-end">{{ formatRupiah($sub_total) }}</td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="2">Shipping Charge :</td>
                                    <td class="text-end">$ 24.99</td>
                                </tr> --}}
                                <tr>
                                    <td colspan="2">PPN (12%): </td>
                                    <td class="text-end">{{ formatRupiah($pajak) }}</td>
                                </tr>
                                <tr class="table-active">
                                    <th colspan="2">Total (+ppn) :</th>
                                    <td class="text-end">
                                        <span class="fw-semibold">
                                            {{ formatRupiah($total_amount) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@push('scripts')
    <script>
        function bank_01(bank_name) {
            document.getElementById('bank').value = bank_name;
        }

        function bank_02(bank_name) {
            document.getElementById('bank').value = bank_name;
        }

        document.getElementById('pembayaranForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const po_number = "{{ $order->po_number }}";
            const form = event.target;
            const formData = new FormData(form);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('/order/payment/' + po_number, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

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
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }

        });

        document.getElementById('customerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const po_number = "{{ $order->po_number }}";
            const form = event.target;
            const formData = new FormData(form);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('/order/update/' + po_number, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

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
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });
    </script>

    <script src="{{ asset('assets') }}/js/pages/ecommerce-product-checkout.init.js"></script>
@endpush
