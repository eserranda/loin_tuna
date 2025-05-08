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

@push('head_component')
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="row">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-3">
                                <h4>Detail Receiving</h4>
                            </div>
                            <!--end card-header-->
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">ILC</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->ilc)
                                                {{ $receiving->ilc ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Tanggal Receiving</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->tanggal)
                                                {{ \Carbon\Carbon::parse($receiving->created_at)->format('d-m-Y') ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Supplier</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->supplier->nama_supplier)
                                                {{ $receiving->supplier->nama_supplier ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inspection</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->inspection)
                                                {{ $receiving->inspection ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
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
                                    <table class="table text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <thead>
                                                <tr class="table-active">
                                                    <th scope="col" style="width: 50px;">No</th>
                                                    <th scope="col">Nomor Loin</th>
                                                    <th scope="col">Grade</th>
                                                    <th scope="col">Berat (Kg)</th>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            @foreach ($raw_materials as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->no_loin }}</td>
                                                    <td>{{ $item->grade }}</td>
                                                    <td>{{ $item->berat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-3">
                                <h4>Detail Cutting/Trimming</h4>
                            </div>
                            <!--end card-header-->
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">ILC Cutting</p>
                                        <h5 class="fs-14 mb-0">{{ $cutting->ilc_cutting ?? '-' }}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Tanggal Trimming</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->created_at)
                                                {{ \Carbon\Carbon::parse($receiving->created_at)->format('d-m-Y') ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inspection</p>
                                        <h5 class="fs-14 mb-0"> {{ $cutting->inspection ?? '-' }}</h5>
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
                                    <table class="table text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">No</th>
                                                <th scope="col">Nomor Loin</th>
                                                <th scope="col">Grade</th>
                                                <th scope="col">Berat (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cutting_grading as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->no_loin }}</td>
                                                    <td>{{ $item->grade }}</td>
                                                    <td>{{ $item->berat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-3">
                                <h4>Detail Retouching/Timbang Ulang</h4>
                            </div>
                            <!--end card-header-->
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">ILC Retouching</p>
                                        <h5 class="fs-14 mb-0">{{ $retouching->ilc ?? '-' }}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Tanggal Retouching</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($retouching && $retouching->created_at)
                                                {{ \Carbon\Carbon::parse($retouching->created_at)->format('d-m-Y') }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inspection</p>
                                        <h5 class="fs-14 mb-0"> {{ $retouching->inspection ?? '-' }}</h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-6">
                            <div class="card-body p-4">
                                <h4 class="card-title py-2">Hasil Timbang Ulang</h4>
                                <div class="table-responsive">
                                    <table class="table text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">No</th>
                                                <th scope="col">Nomor Loin</th>
                                                <th scope="col">Berat (Kg)</th>
                                                <th scope="col">Sisa Berat (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($retouchings as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->no_loin }}</td>
                                                    <td>{{ $item->berat }}</td>
                                                    <td>{{ $item->sisa_berat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-4">
                                <h4 class="card-title py-2">Hasil Produk</h4>
                                <div class="table-responsive">
                                    <table class="table text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">No</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col">Nomor Loin</th>
                                                <th scope="col">Berat (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product_logs as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->produk->nama }}</td>
                                                    <td>{{ $item->no_loin }}</td>
                                                    <td>{{ $item->berat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-3">
                                <h4>Detail Packing</h4>
                            </div>
                            <!--end card-header-->
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">ILC Cutting</p>
                                        <h5 class="fs-14 mb-0">{{ $cutting->ilc_cutting ?? '-' }}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Tanggal Trimming</p>
                                        <h5 class="fs-14 mb-0">
                                            @if ($receiving && $receiving->created_at)
                                                {{ \Carbon\Carbon::parse($receiving->created_at)->format('d-m-Y') ?? '-' }}
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inspection</p>
                                        <h5 class="fs-14 mb-0"> {{ $cutting->inspection ?? '-' }}</h5>
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
                                    <table class="table text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">No</th>
                                                <th scope="col">Nomor Loin</th>
                                                <th scope="col">Grade</th>
                                                <th scope="col">Berat (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cutting_grading as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->no_loin }}</td>
                                                    <td>{{ $item->grade }}</td>
                                                    <td>{{ $item->berat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end table-->
                                </div>
                                <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                    <a href="javascript:window.print()" class="btn btn-success"><i
                                            class="ri-printer-line align-bottom me-1"></i> Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
