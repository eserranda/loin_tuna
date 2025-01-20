@extends('layouts.master')
@push('head_component')
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
    <style>
        .card {
            padding: 8px;
            /* Jarak di dalam card */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* Memastikan konten dalam card terdistribusi */
            height: 100%;
            /* Menjaga tinggi card tetap seragam */
            min-height: 350px;
            /* Pastikan semua card memiliki tinggi minimum */
            box-sizing: border-box;
            /* Untuk menghindari padding mempengaruhi tinggi */
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
@endpush
@section('title')
    <h4 class="mb-sm-0">List Product</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        @foreach ($product as $p)
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <img class="img-fluid rounded" src="{{ asset($p->image ?? '/uploads/images/no-image.jpg') }}"
                        alt="Product Image">
                    <div class="card-body">
                        <h4 class="mb-2">{{ $p->nama }} <span class="text-muted h5">({{ $p->berat }}Kg)</span>
                        </h4>
                        <p class="fw-bold h4  mb-3">{{ formatRupiah($p->harga) }}</p>
                        {{-- <span class="text-muted h5">/Kg</span> --}}
                        <a href="javascript:void(0);" class="link-success">Detail
                            <i class="ri-arrow-right-s-line ms-1 align-middle lh-1"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="addTocard({{ $p->id }})"
                            class="btn btn-outline-info btn-sm btn-icon waves-effect waves-light float-end">
                            <i class="ri-shopping-basket-line"></i>
                        </a>
                        {{-- <button type="button"
                            class="btn btn-outline-info btn-sm btn-icon waves-effect waves-light float-end">
                            <i class="ri-shopping-basket-line"></i></button> --}}
                    </div>


                </div><!-- end card -->
            </div><!-- end col -->
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        async function getCart() {
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

                updateCartUI(cartData.data);
            } catch (error) {
                console.error('Error:', error);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            getCart();
        });

        async function addTocard(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('{{ route('cart.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json', // Pastikan tipe konten JSON
                    },
                    body: JSON.stringify({
                        id_product: id,
                        qty: 1
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    console.log(data);

                    getCart();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Fungsi untuk memperbarui UI keranjang (opsional) file ada di layout
        function updateCartUI(cartItems) {
            let totalQty = 0;
            let totalPrice = 0;

            const cartContainer = document.getElementById('cart-items-container');
            cartContainer.innerHTML = '';

            cartItems.forEach((row, index) => {
                totalQty += row.qty;
                totalPrice += row.qty * row.product.harga;

                const cards = `
                     <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                     <div class="d-flex align-items-center">
                      <img src="${row.product.image || '/uploads/images/no-image.jpg'}"
                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                        <div class="flex-1">
                             <h6 class="mt-0 mb-1 fs-14">
                          <a href="apps-ecommerce-product-details.html" class="text-reset"> ${row.product.nama}</a>
                            </h6>
                         <p class="mb-0 fs-12 text-muted">
                                Quantity: <span>${row.qty} x ${formatToRupiah(row.product.harga)}</span>
                            </p>
                            </div>
                                <div class="px-2">
                               <h5 class="m-0 fw-normal"><span class="cart-item-price">${formatToRupiah(row.product.harga)}</span>
                                </h5>
                                    </div>
                                        <div class="ps-2">
                                            <button type="button"
                                                class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                    class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>
                `;
                cartContainer.insertAdjacentHTML('beforeend', cards);
            });

            // Perbarui elemen total quantity
            document.getElementById('total_qty').textContent = totalQty;
            document.getElementById('total_qty_product').textContent = totalQty;

            // Perbarui elemen total harga
            document.getElementById('cart-item-total').textContent = formatToRupiah(totalPrice);
        }

        function formatToRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0 // Tidak menampilkan angka desimal
            }).format(number);
        }
    </script>
@endpush
