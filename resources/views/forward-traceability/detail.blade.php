@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Forward Traceability</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Forward Traceability</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

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
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            {{-- <h5 class="mb-3">Custom Tabs Bordered</h5> --}}
            <div class="card">
                <div class="card-body">
                    {{-- <p class="text-muted">Use <code>nav-tabs-custom</code> class to create custom tabs with borders.</p> --}}
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#receiving" role="tab"
                                onclick="getReceiving()">
                                Receiving
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#cutting" role="tab" onclick="getCutting()">
                                Cutting
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#retouching" role="tab">
                                Retouching
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#packing" role="tab">
                                Packing
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="receiving" role="tabpanel">
                            <div class="d-flex">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-checkbox-multiple-blank-fill text-success"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-2">

                                    <h4 class="card-title mb-3 flex-grow-1">Detail Receiving</h4>

                                    <div class="col-sm-6 mb-1">
                                        <div class="row align-items-start">
                                            <div class="col-sm-6 mb-2">
                                                ILC : <span class="fw-bold">{{ $receiving->ilc }}</span>
                                            </div>

                                            <div class="col-sm-6">
                                                Supplier :
                                                <span class="fw-bold">
                                                    {{ $receiving->supplier->nama_supplier }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                Tanggal : <span class="fw-bold"> {{ $receiving->tanggal }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                Inspection :
                                                <span class="fw-bold">
                                                    {{ $receiving->inspection ?? '-' }}

                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- <h4 class="card-title mb-3 flex-grow-1">Detail Receiving</h4> --}}
                                    <div class="col-sm-6 mb-1">
                                        <div class="card ">
                                            <table class="table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">No</th>
                                                        <th scope="col">No.Loin</th>
                                                        <th scope="col">Grade</th>
                                                        <th scope="col">Berat(Kg)</th>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="cutting" role="tabpanel">
                            <div class="d-flex">
                                <div class="flex-grow-1 ms-2">
                                    <h4 class="card-title mb-3 flex-grow-1">Detail Cutting</h4>

                                    <div class="col-sm-6 mb-1">
                                        <div class="row align-items-start">
                                            <div class="col-sm-6 mb-2">
                                                ILC : <span class="fw-bold">{{ $cutting->ilc ?? '-' }}</span>
                                            </div>

                                            <div class="col-sm-6">
                                                Supplier :
                                                <span class="fw-bold">
                                                    {{ $receiving->supplier->nama_supplier }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                ILC Cutting :
                                                <span class="fw-bold">
                                                    {{ $cutting->ilc_cutting ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                Tanggal : <span class="fw-bold"> {{ $cutting->created_at ?? '-' }}</span>
                                            </div>

                                            <div class="col-sm-6">
                                                Inspection :
                                                <span class="fw-bold">
                                                    {{ $cutting->inspection ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- <h4 class="card-title mb-3 flex-grow-1">Detail Receiving</h4> --}}
                                    <div class="col-sm-6 mb-1">
                                        <div class="card ">
                                            <table class="table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">No</th>
                                                        <th scope="col">No.Loin</th>
                                                        <th scope="col">Grade</th>
                                                        <th scope="col">Berat(Kg)</th>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="retouching" role="tabpanel">
                            <div class="d-flex">
                                <div class="flex-grow-1 ms-2">
                                    <h4 class="card-title mb-3 flex-grow-1">Detail Retouching</h4>

                                    <div class="col-sm-6 mb-1">
                                        <div class="row align-items-start">
                                            <div class="col-sm-6 mb-2">
                                                ILC : <span class="fw-bold">{{ $retouching->ilc ?? '-' }}</span>
                                            </div>

                                            <div class="col-sm-6">
                                                Supplier :
                                                <span class="fw-bold">
                                                    {{ $receiving->supplier->nama_supplier }}
                                                </span>
                                            </div>
                                            {{-- <div class="col-sm-6 mb-2">
                                                ILC Cutting :
                                                <span class="fw-bold">
                                                    {{ $retouching->ilc_cutting ?? '-' }}
                                                </span>
                                            </div> --}}
                                            <div class="col-sm-6 mb-2">
                                                Tanggal :
                                                <span class="fw-bold">
                                                    {{ $retouching->created_at ?? '-' }}
                                                </span>
                                            </div>

                                            <div class="col-sm-6">
                                                Inspection :
                                                <span class="fw-bold">
                                                    {{ $retouching->inspection ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-6 mb-1">
                                            <div class="card ">
                                                <h4 class="card-title m-2">Data Retouching</h4>
                                                <table class="table table-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No</th>
                                                            <th scope="col">No.Loin</th>
                                                            <th scope="col">Berat (Kg)</th>
                                                            <th scope="col">Sisa Berat(Kg)</th>
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
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-1">
                                            <div class="card ">
                                                <h4 class="card-title m-2">Data Product</h4>
                                                <table class="table table-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No</th>
                                                            <th scope="col">Nama Product</th>
                                                            <th scope="col">No.Loin</th>
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
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="packing" role="tabpanel">
                            <div class="d-flex">
                                <div class="flex-grow-1 ms-2">
                                    <h4 class="card-title ms-2 mb-2">Detail Packing</h4>

                                    <div class="col-sm-6 mb-1">
                                        <div class="row align-items-start">
                                            <div class="col-sm-6 mb-2">
                                                ILC : <span class="fw-bold">{{ $retouching->ilc ?? '-' }}</span>
                                            </div>

                                            <div class="col-sm-6">
                                                Supplier :
                                                <span class="fw-bold">
                                                    {{ $receiving->supplier->nama_supplier }}
                                                </span>
                                            </div>
                                            {{-- <div class="col-sm-6 mb-2">
                                                ILC Cutting :
                                                <span class="fw-bold">
                                                    {{ $retouching->ilc_cutting ?? '-' }}
                                                </span>
                                            </div> --}}
                                            <div class="col-sm-6 mb-2">
                                                Tanggal :
                                                <span class="fw-bold">
                                                    {{ $retouching->created_at ?? '-' }}
                                                </span>
                                            </div>

                                            <div class="col-sm-6">
                                                Inspection :
                                                <span class="fw-bold">
                                                    {{ $retouching->inspection ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- <h4 class="card-title mb-3 flex-grow-1">Data Retouching</h4> --}}
                                    <div class="col-sm-6 mb-1">
                                        <div class="card ">
                                            <table class="table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">No</th>
                                                        <th scope="col">No.Loin</th>
                                                        <th scope="col">Berat (Kg)</th>
                                                        <th scope="col">Sisa Berat(Kg)</th>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // function getCutting() {
        //     alert('Cutting');
        // }

        // function getReceiving() {
        //     alert('Receiving');
        // }
    </script>
@endpush
