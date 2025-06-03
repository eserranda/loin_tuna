<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Data Produk</h5>

                <button type="button" class="btn-close" onclick="closeModalEdit()"> </button>
            </div>

            <form id="editForm" action="{{ route('product.store') }}" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-lable">Input File Foto</label>
                            <input type="file" class="filestyle" data-buttonname="btn-secondary" name="edit_image"
                                id="edit_image" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <img class="img-thumbnail" id="photoPreview" src="" alt="Photo Preview"
                                style="max-width: 200px; max-height: 200px;">
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode Produk</label>
                                <input type="hidden" class="form-control" id="edit_id" name="id">
                                <input type="text" id="edit_kode" name="edit_kode" class="form-control"
                                    placeholder="Kode" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Produk</label>
                                <input type="text" id="edit_nama" name="edit_nama" class="form-control"
                                    placeholder="Nama Produk" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" class="form-control" placeholder="Harga" id="edit_harga"
                                    name="edit_harga">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Berat (kg)</label>
                                <input type="number" class="form-control" placeholder="Berat" id="edit_berat"
                                    name="edit_berat" step="0.01">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="edit_deskripsi" placeholder="Deskripsi Produk"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" onclick="closeModalEdit()">Close</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Update</button>
                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('edit_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

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
            const response = await fetch('/product/update', {
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
                $('.dataProduk').DataTable().ajax.reload();
                $('#editModal').modal('hide');
            }


        } catch (error) {
            console.error(error);
        }
    });
</script>
