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
                        <p class="fw-bold h4 harga mb-3" data-harga="{{ $p->harga }}">
                            {{ formatRupiah($p->harga) }} <!-- Harga awal dalam Rupiah -->
                        </p>
                        <button class="btn btn-sm btn-info" onclick="detail({{ $p->id }})">Detail</button>
                        <button class="btn btn-sm btn-warning float-end" onclick="addTocard({{ $p->id }})">+
                            Keranjang</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        async function getExchangeRate() {
            try {
                const response = await fetch('https://api.exchangerate-api.com/v4/latest/IDR');
                const data = await response.json();
                return data.rates.JPY; // Ambil nilai tukar IDR ke JPY
            } catch (error) {
                console.error("Gagal mengambil nilai tukar: ", error);
                return null;
            }
        }

        // Fungsi untuk mengonversi semua harga ke Yen Jepang
        async function convertAllPricesToYen() {
            const exchangeRate = await getExchangeRate();
            if (!exchangeRate) return;

            // Ambil semua elemen harga
            document.querySelectorAll(".harga").forEach(hargaElement => {
                let hargaIDR = parseFloat(hargaElement.getAttribute("data-harga"));

                if (!isNaN(hargaIDR)) {
                    let hargaJPY = hargaIDR * exchangeRate;
                    // let formattedJPY = `≈ ¥ ${hargaJPY.toFixed(1)}`; // Batasi 1 angka desimal
                    let formattedJPY = `¥ ${hargaJPY.toFixed(1)}`; // Batasi 1 angka desimal
                    hargaElement.innerText += ` (${formattedJPY})`; // Gabungkan harga Rupiah & Yen
                }
            });
        }

        // Panggil fungsi konversi saat halaman dimuat
        convertAllPricesToYen();

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
