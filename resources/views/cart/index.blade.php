@extends('layouts.master')
@push('head_component')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
@endpush

@section('title')
    <h4 class="mb-sm-0">Cart</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Cart</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@push('head_component')
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
    <div class="row mb-3">
        <div class="col-xl-8">
            <div class="row align-items-center gy-3 mb-3">
                <div class="col-sm">
                    <div>
                        <h5 class="fs-14 mb-0" id="total_items"></h5>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <a href="/product/list-product" class="link-primary text-decoration-underline">Tambah Item</a>
                </div>
            </div>

            <div class="cart-container" id="cart-container">

            </div>

        </div>

        <div class="col-xl-4">
            <div class="sticky-side-div">
                <div class="card mb-2">
                    <div class="card-header border-bottom-dashed">
                        <h5 class="card-title mb-0">Detail Order</h5>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td>Total Qty :</td>
                                        <td class="text-end" id="cart-total-qty"></td>
                                    </tr>
                                    <tr>
                                        <td>Sub Total :</td>
                                        <td class="text-end" id="cart-subtotal"></td>
                                    </tr>
                                    <tr>
                                        <td>Pajak (12%) : </td>
                                        <td class="text-end" id="cart-tax"></td>
                                    </tr>
                                    <tr class="table-active">
                                        <th>Total (Rp) :</th>
                                        <td class="text-end">
                                            <span class="fw-semibold" id="cart-total">

                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>

                </div>

                <div class="text-end">
                    <a href="apps-ecommerce-checkout.html" class="btn btn-success btn-label right ms-auto"><i
                            class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i> Checkout</a>
                </div>


            </div>
            <!-- end stickey -->

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function getCart() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const cartResponse = await fetch('{{ route('cart.findOne') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    }
                });

                if (!cartResponse.ok) {
                    throw new Error('Gagal mengambil data keranjang.');
                }

                const cartData = await cartResponse.json();

                console.log(cartData.data);

                updateListCartUI(cartData.data);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            getCart();
        });

        function updateListCartUI(cartData) {
            let totalQty = 0;
            let totalPrice = 0;

            const listCartContainer = document.getElementById('cart-container');
            listCartContainer.innerHTML = '';

            if (cartData.length === 0) {
                listCartContainer.innerHTML = '<p>Keranjang Anda kosong.</p>';
                document.getElementById('total_items').textContent = '';
                document.getElementById('cart-total-qty').textContent = 0;
                document.getElementById('cart-subtotal').textContent = 'Rp ' + 0;
                document.getElementById('cart-tax').textContent = 'Rp ' + 0;
                document.getElementById('cart-total').textContent = 'Rp ' + 0;

                return;
            }

            // Iterasi data keranjang dan buat elemen HTML untuk setiap item
            cartData.forEach(items => {
                totalQty += items.qty;
                totalPrice += items.qty * items.product.harga;

                const listCard = `
            <div class="card product">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-sm-auto">
                            <div class="avatar-lg bg-light rounded p-1">
                                <img src="${items.product.image || '/uploads/images/no-image.jpg'}" alt="" class="img-fluid d-block">
                            </div>
                        </div>
                        <div class="col-sm">
                            <h5 class="fs-14 text-truncate">
                                <a href="ecommerce-product-detail.html" class="text-dark">${items.product.nama}</a>
                            </h5>
                            <ul class="list-inline text-muted">
                                <li class="list-inline-item">Berat: 
                                    <span class="fw-medium">${items.product.berat} Kg</span>
                                </li>
                            </ul>

                            <div class="input-step">
                                <button type="button" class="minus" onClick="decreaseQuantity(${items.id})">-</button>
                                <input type="number" class="product-quantity" value="${items.qty}" min="0" max="100">
                                <button type="button" class="plus" onClick="increaseQuantity(${items.id})">+</button>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="text-lg-end">
                                <p class="text-muted mb-1">Item Price:</p>
                                <h5 class="fs-14">
                                    <span class="product-price">${formatToRupiah(items.product.harga)}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card body -->
                <div class="card-footer">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <div class="d-flex flex-wrap my-n1">
                                <div>
                                    <a href="javascript:void(0);" onclick="removeCart(${items.id})"
                                        class="d-block text-body p-1 px-2">
                                        <i class="ri-delete-bin-fill text-muted align-bottom me-1"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <div>Total :</div>
                                <h5 class="fs-14 mb-0">
                                    <span class="product-line-price">${formatToRupiah(items.total_amount)}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                listCartContainer.insertAdjacentHTML('beforeend', listCard); // Tambahkan kartu ke dalam kontainer
            });

            document.getElementById('total_items').textContent = "Keranjang Anda : (" + cartData.length + " Items)";
            document.getElementById('cart-total-qty').textContent = totalQty;
            document.getElementById('cart-subtotal').textContent = formatToRupiah(totalPrice);
            document.getElementById('cart-tax').textContent = formatToRupiah(totalPrice * 0.12);
            document.getElementById('cart-total').textContent = formatToRupiah(totalPrice + (totalPrice * 0.12));
        }

        async function decreaseQuantity(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content');
            fetch('/cart/decrease/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    getCart();
                    getListCart();
                } else {
                    console.error('Error:', response.status);
                }
            });
        }

        async function increaseQuantity(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content');
            fetch('/cart/increase/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    getCart();
                    getListCart();
                } else {
                    console.error('Error:', response.status);
                }
            });
        }

        async function removeCart(id) {
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
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    fetch('/cart/destroy/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            getCart();
                            getListCart();
                        } else {
                            alert('Terjadi kesalahan saat menghapus item dari keranjang');
                        }
                    });
                }

            })
        }
    </script>
@endpush
