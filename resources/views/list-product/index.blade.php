@extends('layouts.master')
@push('head_component')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>

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
        // const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        // async function getListCart() {
        //     try {
        //         const cartResponse = await fetch('{{ route('cart.findOne') }}', {
        //             method: 'GET',
        //             headers: {
        //                 'X-CSRF-TOKEN': csrfToken,
        //                 'Content-Type': 'application/json',
        //             }
        //         });

        //         if (!cartResponse.ok) {
        //             throw new Error('Gagal mengambil data keranjang.');
        //         }

        //         const cartData = await cartResponse.json();

        //         console.log(cartData.data);

        //         updateCartUI(cartData.data);
        //     } catch (error) {
        //         console.error('Error:', error);
        //     }
        // };

        // document.addEventListener('DOMContentLoaded', function() {
        //     getListCart();
        // });

        async function addTocard(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('{{ route('cart.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_product: id,
                        qty: 1
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    console.log(data);

                    getListCart();
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
    </script>
@endpush
