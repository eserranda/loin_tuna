<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Tambah Data Users</h5>

                <button type="button" class="btn-close" onclick="closeModalEdit()">

                </button>
            </div>

            <form id="editForm">
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="name">Nama Lengkap</label>
                                <input type="hidden" class="form-control" id="edit_id" name="id">
                                <input type="text" class="form-control" id="edit_name" name="edit_name"
                                    placeholder="Nama Lengkap">
                                <div class="invalid-feedback"> </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="edit_username" name="edit_username"
                                    placeholder="Username">
                                <div class="invalid-feedback"> </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="password">Password</label>
                                <input type="text" class="form-control" id="password" name="password"
                                    placeholder="Password" disabled value="*********">
                                <div class="invalid-feedback"> </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="password_confirmation">Ulangi Password</label>
                                <input type="text" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm Password" readonly disabled
                                    value="*********">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="email">Email</label>
                                <input type="text" class="form-control" id="edit_email" name="edit_email"
                                    placeholder="Email">
                                <div class="invalid-feedback"></div>

                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label class="form-label" for="role">Role</label>
                                @foreach (App\Models\Role::all() as $role)
                                    <div class="form-check ">
                                        <input class="form-check-input" type="checkbox" name="edit_roles[]"
                                            id="role-checkbox-{{ $role->id }}" value="{{ $role->name }}">
                                        <label class="form-check-label"
                                            for="role-checkbox-{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                                <div class="invalid-feedback"> </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" onclick="closeModalEdit()">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success" id="add-btn">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeModalEdit() {
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

        const form = document.getElementById('editForm');
        form.reset();
        $('#editModal').modal('hide');
    }

    document.getElementById('editForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const response = await fetch('/users/update', {
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

            } else {
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

                $('#datatable').DataTable().ajax.reload();
                $('#editModal').modal('hide');
            }


        } catch (error) {
            console.error(error);
        }
    });
</script>
