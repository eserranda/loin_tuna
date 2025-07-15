<div id="editModal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0">Edit Data Grade</h4>
                <button type="button" class="btn-close" onclick="closeModalEdit()">
            </div>

            <div class="modal-body">
                <form id="editForm" action="{{ route('grades.store') }}" method="POST">
                    <div class="mb-3">
                        <label for="Grade" class="form-label">Garde</label>
                        <input type="hidden" class="form-control" id="edit_id" name="id">
                        <input type="text" class="form-control" id="edit_grade" name="edit_grade">
                        <div class="invalid-feedback"> </div>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_description" name="edit_description" rows="4"></textarea>
                        {{-- <textarea name="edit_description" id="edit_description" cols="30" rows="10"></textarea> --}}
                        <div class="invalid-feedback"> </div>
                    </div>

                    <div class="text-end">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" onclick="closeModalEdit()">
                                Close
                            </button>
                            <button type="submit" class="btn btn-success" id="add-btn">Update</button>
                        </div>

                    </div>
                </form>
            </div>
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
            const response = await fetch('/grades/update', {
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
