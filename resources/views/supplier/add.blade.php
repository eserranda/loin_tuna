@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Supplier</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Supplier</a></li>
            <li class="breadcrumb-item active">data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12 col-lg-12">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Input nama supplier</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <form action="{{ route('supplier.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                {{-- <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Batch</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('kode_batch') ? 'is-invalid' : '' }}"
                                            placeholder="Contoh : JKW" name="kode_batch" id="kode_batch"
                                            value="{{ old('kode_batch') }}">
                                        @if ($errors->has('kode_batch'))
                                            <div class="text-danger">{{ $errors->first('kode_batch') }}</div>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Supplier</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('kode_supplier') ? 'is-invalid' : '' }}"
                                            placeholder="Contoh : MRC" name="kode_supplier" id="kode_supplier"
                                            value="{{ old('kode_supplier') }}">
                                        @if ($errors->has('kode_supplier'))
                                            <div class="text-danger">{{ $errors->first('kode_supplier') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Supplier</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('nama_supplier') ? 'is-invalid' : '' }}"
                                            placeholder="Nama Supplier" name="nama_supplier" id="nama_supplier"
                                            value="{{ old('nama_supplier') }}">
                                        @if ($errors->has('nama_supplier'))
                                            <div class="text-danger">{{ $errors->first('nama_supplier') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone/No.Hp</label>
                                        <input type="number"
                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                            placeholder="Nomor Hp" name="phone" id="phone"
                                            value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jalan</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('Jalan') ? 'is-invalid' : '' }}"
                                            placeholder="Jalan" name="jalan" id="jalan">
                                        @if ($errors->has('Jalan'))
                                            <div class="text-danger">{{ $errors->first('Jalan') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('provinsi') ? 'is-invalid' : '' }}"
                                            placeholder="Provinsi" name="provinsi" id="provinsi" value="Sulawesi-Selatan">
                                        @if ($errors->has('provinsi'))
                                            <div class="text-danger">{{ $errors->first('provinsi') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kabupaten</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('kabupaten') ? 'is-invalid' : '' }}"
                                            placeholder="Kabupaten" name="kabupaten" id="kabupaten"
                                            value="{{ old('kabupaten') }}">
                                        @if ($errors->has('kabupaten'))
                                            <div class="text-danger">{{ $errors->first('kabupaten') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('kecamatan') ? 'is-invalid' : '' }}"
                                            placeholder="Kecamatan" name="kecamatan" id="kecamatan"
                                            value="{{ old('kecamatan') }}">
                                        @if ($errors->has('kecamatan'))
                                            <div class="text-danger">{{ $errors->first('kecamatan') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Buat Supplier</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
