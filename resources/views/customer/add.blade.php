@extends('layouts.master')
@section('title')
    <h4 class="mb-sm-0">Customer</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-8">
            <div class="d-flex flex-column h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Input nama Customer</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <form action="{{ route('customer.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                            placeholder="Nama" name="nama" id="nama" value="{{ old('nama') }}">
                                        @if ($errors->has('nama'))
                                            <div class="text-danger">{{ $errors->first('nama') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Customer</label>
                                        <input type="teks"
                                            class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}"
                                            placeholder="Kode customer" name="kode" id="kode"
                                            value="{{ old('kode') }}">
                                        @if ($errors->has('kode'))
                                            <div class="text-danger">{{ $errors->first('kode_customer') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Group</label>
                                        <select
                                            class="form-select  {{ $errors->has('customer_group') ? 'is-invalid' : '' }}"
                                            id="customer_group" name="customer_group">
                                            <option selected disabled>Pilih Ekspor</option>
                                            <option value="USA">USA</option>
                                            <option value="EROPA">EROPA</option>
                                            <option value="JEPANG">JEPANG</option>
                                            <option value="LOCAL">LOCAL</option>
                                        </select>

                                        @if ($errors->has('customer_group'))
                                            <div class="text-danger">{{ $errors->first('customer_group') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email"
                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <div class="text-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomot Telepon</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                            placeholder="Nomor Telepon" name="phone" id="phone"
                                            value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <input type="text"
                                            class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                                            placeholder="Alamat" name="alamat" id="alamat" value="{{ old('alamat') }}">
                                        @if ($errors->has('alamat'))
                                            <div class="text-danger">{{ $errors->first('alamat') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Buat Customer</button>
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
