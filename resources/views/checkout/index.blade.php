@extends('layouts.master')
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
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fs-15 p-3" id="pills-payment-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-payment" type="button" role="tab"
                                        aria-controls="pills-payment" aria-selected="false">
                                        <i
                                            class="ri-bank-card-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                        Payment Info
                                    </button>
                                </li> --}}
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
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Nama Lengkap" value="{{ $order->user->name }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    placeholder="Enter last name" value="{{ $order->user->username }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-muted">(Optional)</span></label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter email" value="{{ $order->user->email }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">No. Hp</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter phone no." value="{{ $customer->phone ?? '' }} ">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jalan" class="form-label">Jalan</label>
                                        <textarea class="form-control" id="jalan" name="jalan" placeholder="Jalan" rows="3">{{ $customer->jalan ?? '' }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="negara" class="form-label">Negara</label>
                                                <input type="text" class="form-control" id="negara" name="negara"
                                                    placeholder="Negara" value="{{ $customer->negara ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="provinsi" class="form-label">Provinsi</label>
                                                    <input type="text" class="form-control" id="provinsi"
                                                        name="provinsi" placeholder="Provinsi"
                                                        value="{{ $customer->provinsi ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="kabupaten" class="form-label">Kabupaten</label>
                                                    <input type="text" class="form-control" id="kabupaten"
                                                        name="kabupaten" placeholder="Kabupaten"
                                                        value="{{ $customer->kabupaten ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                                    <input type="text" class="form-control" id="kecamatan"
                                                        name="kecamatan" placeholder="Kecamatan"
                                                        value="{{ $customer->kecamatan ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                                <input type="text" class="form-control" id="kode_pos"
                                                    name="kode_pos" placeholder="Kode Pos"
                                                    value="{{ $customer->kode_pos ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">

                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-info">Save</button>

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
                        <!-- end tab pane -->

                        {{-- <div class="tab-pane fade" id="pills-bill-address" role="tabpanel"
                                aria-labelledby="pills-bill-address-tab">
                                <div>
                                    <h5 class="mb-1">Shipping Information</h5>
                                    <p class="text-muted mb-4">Please fill all information below</p>
                                </div>

                                <div class="mt-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-0">Saved Address</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-success mb-3"
                                                data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                                Add Address
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row gy-3">
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-check card-radio">
                                                <input id="shippingAddress01" name="shippingAddress" type="radio"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label" for="shippingAddress01">
                                                    <span class="mb-4 fw-semibold d-block text-muted text-uppercase">Home
                                                        Address</span>

                                                    <span class="fs-14 mb-2 d-block">Marcus Alfaro</span>
                                                    <span class="text-muted fw-normal text-wrap mb-1 d-block">4739
                                                        Bubby
                                                        Drive Austin, TX 78729</span>
                                                    <span class="text-muted fw-normal d-block">Mo. 012-345-6789</span>
                                                </label>
                                            </div>
                                            <div class="d-flex flex-wrap p-2 py-1 bg-light rounded-bottom border mt-n1">
                                                <div>
                                                    <a href="#" class="d-block text-body p-1 px-2"
                                                        data-bs-toggle="modal" data-bs-target="#addAddressModal"><i
                                                            class="ri-pencil-fill text-muted align-bottom me-1"></i>
                                                        Edit</a>
                                                </div>
                                                <div>
                                                    <a href="#" class="d-block text-body p-1 px-2"
                                                        data-bs-toggle="modal" data-bs-target="#removeItemModal"><i
                                                            class="ri-delete-bin-fill text-muted align-bottom me-1"></i>
                                                        Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-check card-radio">
                                                <input id="shippingAddress02" name="shippingAddress" type="radio"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="shippingAddress02">
                                                    <span class="mb-4 fw-semibold d-block text-muted text-uppercase">Office
                                                        Address</span>

                                                    <span class="fs-14 mb-2 d-block">James Honda</span>
                                                    <span class="text-muted fw-normal text-wrap mb-1 d-block">1246
                                                        Virgil
                                                        Street Pensacola, FL 32501</span>
                                                    <span class="text-muted fw-normal d-block">Mo. 012-345-6789</span>
                                                </label>
                                            </div>
                                            <div class="d-flex flex-wrap p-2 py-1 bg-light rounded-bottom border mt-n1">
                                                <div>
                                                    <a href="#" class="d-block text-body p-1 px-2"
                                                        data-bs-toggle="modal" data-bs-target="#addAddressModal"><i
                                                            class="ri-pencil-fill text-muted align-bottom me-1"></i>
                                                        Edit</a>
                                                </div>
                                                <div>
                                                    <a href="#" class="d-block text-body p-1 px-2"
                                                        data-bs-toggle="modal" data-bs-target="#removeItemModal"><i
                                                            class="ri-delete-bin-fill text-muted align-bottom me-1"></i>
                                                        Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h5 class="fs-14 mb-3">Shipping Method</h5>

                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-check card-radio">
                                                    <input id="shippingMethod01" name="shippingMethod" type="radio"
                                                        class="form-check-input" checked>
                                                    <label class="form-check-label" for="shippingMethod01">
                                                        <span
                                                            class="fs-20 float-end mt-2 text-wrap d-block fw-semibold">Free</span>
                                                        <span class="fs-14 mb-1 text-wrap d-block">Free Delivery</span>
                                                        <span class="text-muted fw-normal text-wrap d-block">Expected
                                                            Delivery 3 to 5 Days</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-check card-radio">
                                                    <input id="shippingMethod02" name="shippingMethod" type="radio"
                                                        class="form-check-input" checked>
                                                    <label class="form-check-label" for="shippingMethod02">
                                                        <span
                                                            class="fs-20 float-end mt-2 text-wrap d-block fw-semibold">$24.99</span>
                                                        <span class="fs-14 mb-1 text-wrap d-block">Express
                                                            Delivery</span>
                                                        <span class="text-muted fw-normal text-wrap d-block">Delivery
                                                            within 24hrs.</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                        data-previous="pills-bill-info-tab"><i
                                            class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to
                                        Personal Info</button>
                                    <button type="button" class="btn btn-primary btn-label right ms-auto nexttab"
                                        data-nexttab="pills-payment-tab"><i
                                            class="ri-bank-card-line label-icon align-middle fs-16 ms-2"></i>Continue
                                        to
                                        Payment</button>
                                </div>
                            </div> --}}
                        <!-- end tab pane -->

                        {{-- <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                                aria-labelledby="pills-payment-tab">
                                <div>
                                    <h5 class="mb-1">Payment Selection</h5>
                                    <p class="text-muted mb-4">Please select and enter your billing information</p>
                                </div>

                                <div class="row g-4">
                                    <div class="col-lg-4 col-sm-6">
                                        <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse.show"
                                            aria-expanded="false" aria-controls="paymentmethodCollapse">
                                            <div class="form-check card-radio">
                                                <input id="paymentMethod01" name="paymentMethod" type="radio"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="paymentMethod01">
                                                    <span class="fs-16 text-muted me-2"><i
                                                            class="ri-paypal-fill align-bottom"></i></span>
                                                    <span class="fs-14 text-wrap">Paypal</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse"
                                            aria-expanded="true" aria-controls="paymentmethodCollapse">
                                            <div class="form-check card-radio">
                                                <input id="paymentMethod02" name="paymentMethod" type="radio"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label" for="paymentMethod02">
                                                    <span class="fs-16 text-muted me-2"><i
                                                            class="ri-bank-card-fill align-bottom"></i></span>
                                                    <span class="fs-14 text-wrap">Credit / Debit Card</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6">
                                        <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse.show"
                                            aria-expanded="false" aria-controls="paymentmethodCollapse">
                                            <div class="form-check card-radio">
                                                <input id="paymentMethod03" name="paymentMethod" type="radio"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="paymentMethod03">
                                                    <span class="fs-16 text-muted me-2"><i
                                                            class="ri-money-dollar-box-fill align-bottom"></i></span>
                                                    <span class="fs-14 text-wrap">Cash on Delivery</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse show" id="paymentmethodCollapse">
                                    <div class="card p-4 border shadow-none mb-0 mt-4">
                                        <div class="row gy-3">
                                            <div class="col-md-12">
                                                <label for="cc-name" class="form-label">Name on card</label>
                                                <input type="text" class="form-control" id="cc-name"
                                                    placeholder="Enter name">
                                                <small class="text-muted">Full name as displayed on card</small>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="cc-number" class="form-label">Credit card number</label>
                                                <input type="text" class="form-control" id="cc-number"
                                                    placeholder="xxxx xxxx xxxx xxxx">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="cc-expiration" class="form-label">Expiration</label>
                                                <input type="text" class="form-control" id="cc-expiration"
                                                    placeholder="MM/YY">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="cc-cvv" class="form-label">CVV</label>
                                                <input type="text" class="form-control" id="cc-cvv"
                                                    placeholder="xxx">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted mt-2 fst-italic">
                                        <i data-feather="lock" class="text-muted icon-xs"></i> Your transaction is
                                        secured
                                        with SSL encryption
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                        data-previous="pills-bill-address-tab"><i
                                            class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to
                                        Shipping</button>
                                    <button type="button" class="btn btn-primary btn-label right ms-auto nexttab"
                                        data-nexttab="pills-finish-tab"><i
                                            class="ri-shopping-basket-line label-icon align-middle fs-16 ms-2"></i>Complete
                                        Order</button>
                                </div>
                            </div> --}}
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
        document.getElementById('customerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const po_number = {{ $order->po_number }}

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
                    alert(data.message);
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
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });
    </script>

    <script src="{{ asset('assets') }}/js/pages/ecommerce-product-checkout.init.js"></script>
@endpush
