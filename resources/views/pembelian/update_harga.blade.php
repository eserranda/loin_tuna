<!-- Modal -->
<div class="modal fade" id="updateHargaModal" tabindex="-1" aria-labelledby="updateHargaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateHargaModalLabel">Masukkan harga beli Per Loin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk update data -->
                <form id="updateHargaForm">
                    <!-- Basic Input -->
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label class="form-label">Masukkan Harga</label>
                            <input type="hidden" id="id" name="id">
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Masukkan harga beli per Loin">

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('updateHargaForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        id = document.getElementById('id').value;
        try {
            const response = await fetch('/pembelian/update-harga/' + id, {
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

                const form = document.getElementById('updateHargaForm');
                form.reset();

                $('#detailPembelian').DataTable().ajax.reload();
                $('#updateHargaModal').modal('hide');
            }
        } catch (error) {
            console.error(error);
        }
    });
</script>
