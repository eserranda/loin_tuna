<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Register - Tuna App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets') }}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Masukkan semua data dengan benar</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form id="registerForm" action="index.html">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Masukan Nama" name="name">
                                            <div class="invalid-feedback">

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username"
                                                placeholder="Masukkan username" name="username">
                                            <div class="invalid-feedback">

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Masukkan Email" value="test@gmail.com">
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>



                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input"
                                                    placeholder="Masukkan password" name="password" id="password">
                                                <div class="invalid-feedback">

                                                </div>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>

                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Ulangi Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input"
                                                    placeholder="Ulangi password" name="password_confirmation"
                                                    id="password_confirmation">
                                                <div class="invalid-feedback">

                                                </div>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>

                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Register</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Sudah memiliki akun? <a href="/login"
                                    class="fw-semibold text-primary text-decoration-underline"> Login </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->


    </div>
    <!-- JAVASCRIPT -->

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch('/users/customer/register', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData,
                });

                const data = await response.json();
                console.log(data);
                if (!data.success) {
                    Object.keys(data.messages).forEach(fieldName => {
                        const inputField = document.getElementById(fieldName);
                        if (inputField) {
                            inputField.classList.add('is-invalid');
                            if (inputField.nextElementSibling) {
                                inputField.nextElementSibling.textContent = data.messages[
                                    fieldName][0];
                            }
                        }
                    });

                    // hapus error message jika form sudah di isi
                    const validFields = document.querySelectorAll('.is-invalid');
                    validFields.forEach(validField => {
                        const fieldName = validField.id;
                        if (!data.messages[fieldName]) {
                            validField.classList.remove('is-invalid');
                            if (validField.nextElementSibling) {
                                validField.nextElementSibling.textContent = '';
                            }
                        }
                    });

                }

                if (data.success) {
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

                    window.location.href = '/login';
                }
            } catch (error) {
                console.error(error);
            }
        });
    </script>
    <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('assets') }}/libs/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{ asset('assets') }}/js/plugins.js"></script>

    <!-- particles js -->
    <script src="{{ asset('assets') }}/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="{{ asset('assets') }}/js/pages/particles.app.js"></script>
    <!-- validation init -->
    {{-- <script src="{{ asset('assets') }}/js/pages/form-validation.init.js"></script> --}}
    <!-- password create init -->
    <script src="{{ asset('assets') }}/js/pages/passowrd-create.init.js"></script>

</body>

</html>
