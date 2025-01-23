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
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="assets/images/profile-bg.jpg" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row ">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ $customer->image ?? '/uploads/images/no-img-profile.png' }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $customer->kode ?? $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i> Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama" name="name"
                                                placeholder="Enter your firstname" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Enter your lastname" value="{{ $user->username }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter your email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    @if (auth()->check() &&
                                            auth()->user()->hasRole(['customer']))
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">No.hp</label>
                                                <input type="number" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter your phone number"
                                                    value="{{ $customer->phone ?? '' }}">
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="negara" class="form-label">Negara</label>
                                                <input type="text" class="form-control" id="negara" name="negara"
                                                    placeholder="Negara" value="{{ $customer->negara ?? '' }}" />
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <input type="text" class="form-control" id="provinsi"
                                                    name="provinsi" placeholder="Provinsi"
                                                    value="{{ $customer->provinsi ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="kabupaten" class="form-label">Kabupaten</label>
                                                <input type="text" class="form-control" id="kabupaten"
                                                    name="kabupaten" placeholder="Kabupaten"
                                                    value="{{ $customer->kabupaten ?? '' }}" />
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                                <input type="text" class="form-control" id="kecamatan"
                                                    name="kecamatan" placeholder="Kecamatan"
                                                    value="{{ $customer->kecamatan ?? '' }}" />
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                                <input type="text" class="form-control" minlength="5" maxlength="6"
                                                    id="kode_pos" name="kode_pos" placeholder="Kode Pos"
                                                    value="{{ $customer->kode_pos ?? '' }}">
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div class="mb-3 pb-2">
                                                <label for="jalam" class="form-label">Jalan</label>
                                                <textarea class="form-control" id="jalan" name="jalan" placeholder="Enter your description" rows="3">{{ $customer->jalan ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        {{-- <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="javascript:void(0);">
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                            <input type="password" class="form-control" id="oldpasswordInput"
                                                placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New Password*</label>
                                            <input type="password" class="form-control" id="newpasswordInput"
                                                placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                placeholder="Confirm password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot Password ?</a>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
@endsection


@push('scripts')
    <script>
        document.getElementById('profileForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const userId = {{ $user->id }};

            const form = event.target;
            const formData = new FormData(form);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await fetch('/customer/update/' + userId, {
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
@endpush
