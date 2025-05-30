@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Invoice Pembelian Bahan baku</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan Pembelian</a></li>
            <li class="breadcrumb-item active">invoice</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    {{-- <img src="assets/images/logo-dark.png" class="card-logo card-logo-dark" alt="logo dark"
                                        height="17">
                                    <img src="assets/images/logo-light.png" class="card-logo card-logo-light"
                                        alt="logo light" height="17"> --}}
                                    <h4>CV. Faris Indo Seafood</h4>
                                    <div class="mt-sm-5 mt-4">
                                        <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                                        <p class="text-muted mb-1" id="address-details">Pattene Business Park Blok K No.2
                                        </p>
                                        <p class="text-muted mb-1" id="address-details">Makassar, Indonesia</p>
                                        <p class="text-muted mb-0" id="zip-code"><span>Zip-code:</span>
                                            90551
                                        </p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    {{-- <h6><span class="text-muted fw-normal">Legal Registration
                                            No:</span><span id="legal-register-no">987654</span></h6> --}}
                                    <h6>
                                        <span class="text-muted fw-normal">Email:</span>
                                        <span id="email">fis@dipomelo.com</span>
                                    </h6>
                                    <h6>
                                        <span class="text-muted fw-normal">Website:</span>
                                        <a href="https://tuna.dipomelo.com" class="link-primary" target="_blank"
                                            id="website">www.tuna.dipomelo.com</a>
                                    </h6>
                                    <h6 class="mb-0">
                                        <span class="text-muted fw-normal">Contact :</span>
                                        <span id="contact-no"> +(62) 823-9649-9875</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">No. Nota</p>
                                    <h5 class="fs-14 mb-0">{{ $invoice_number }}</h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Tanggal</p>
                                    <h5 class="fs-14 mb-0"><span id="invoice-date">{{ $tanggal }}</span></h5>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Status Pemayaran
                                    </p>
                                    <span class="badge badge-soft-success fs-11" id="payment-status">Lunas</span>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Pembayaran
                                    </p>
                                    <h5 class="fs-14 mb-0">Rp<span id="total-amount">{{ $total_harga }}</span></h5>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->

                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;">#</th>
                                            <th scope="col">Product Details</th>
                                            <th scope="col">Rate</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-list">
                                        @foreach ($raw_material as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->no_loin }}</td>
                                                <td> {{ $item->grade }}</td>
                                                <td>{{ $item->berat }} Kg</td>
                                                <td class="text-end">
                                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table><!--end table-->
                            </div>
                            <div class="border-top border-top-dashed mt-2">
                                <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                    style="width:250px">
                                    <tbody>
                                        {{-- <tr>
                                            <td>Sub Total</td>
                                            <td class="text-end">Rp{{ $total_harga }}</td>
                                        </tr> --}}
                                        <tr class="border-top border-top-dashed fs-15">
                                            <th scope="row">Total Harga</th>
                                            <td class="text-end">Rp{{ $total_harga }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>
                            <div class="mt-3">
                                <div class="row p-3 mt-4 border-top border-top-dashed">
                                    <div class="col-md-6"></div>

                                    <!-- Kolom kanan -->
                                    <div class="col-md-6 text-end">
                                        <!-- Tampilan tanda tangan -->
                                        <div id="signatureDisplay">
                                            <p class="text-uppercase fw-semibold mb-5" id="dateText"
                                                onclick="editSignature()">Makassar, 22 Sep 2022</p>
                                            <p class="text-uppercase fw-semibold mb-0" id="nameText"
                                                onclick="editSignature()">Nama</p>
                                            <small class="text-muted" id="positionText"
                                                onclick="editSignature()">(Jabatan)</small>
                                        </div>

                                        <!-- Form edit (disembunyikan awalnya) -->
                                        <form id="signatureForm" class="d-none text-start mt-3"
                                            onsubmit="saveSignature(event)">
                                            <div class="mb-2">
                                                <label class="form-label">Tempat & Tanggal</label>
                                                <input type="text" id="dateInput" class="form-control" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Nama</label>
                                                <input type="text" id="nameInput" class="form-control" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Jabatan</label>
                                                <input type="text" id="positionInput" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">OK</button>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="mt-4">
                                <div class="alert alert-info">
                                    <p class="mb-0"><span class="fw-semibold">NOTES:</span>
                                        <span id="note">All accounts are to be paid within 7 days from receipt of
                                            invoice. To be paid by cheque or
                                            credit card or direct payment online. If account is not paid within 7
                                            days the credits details supplied as confirmation of work undertaken
                                            will be charged the agreed quoted fee noted above.
                                        </span>
                                    </p>
                                </div>
                            </div> --}}
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <a href="javascript:window.print()" class="btn btn-success"><i
                                        class="ri-printer-line align-bottom me-1"></i> Print</a>
                                {{-- <a href="javascript:void(0);" class="btn btn-primary"><i
                                        class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
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
        function editSignature() {
            // Isi nilai form dengan teks saat ini
            document.getElementById('dateInput').value = document.getElementById('dateText').textContent;
            document.getElementById('nameInput').value = document.getElementById('nameText').textContent;
            document.getElementById('positionInput').value = document.getElementById('positionText').textContent.replace(
                /[()]/g, '');

            // Tampilkan form, sembunyikan display
            document.getElementById('signatureDisplay').classList.add('d-none');
            document.getElementById('signatureForm').classList.remove('d-none');
        }

        function saveSignature(event) {
            event.preventDefault();

            // Perbarui tampilan
            document.getElementById('dateText').textContent = document.getElementById('dateInput').value;
            document.getElementById('nameText').textContent = document.getElementById('nameInput').value;
            document.getElementById('positionText').textContent = `(${document.getElementById('positionInput').value})`;

            // Tampilkan kembali display, sembunyikan form
            document.getElementById('signatureForm').classList.add('d-none');
            document.getElementById('signatureDisplay').classList.remove('d-none');
        }
    </script>
@endpush
