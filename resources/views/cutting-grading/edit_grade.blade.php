<!-- Modal -->
<div class="modal fade" id="updateGradeModal" tabindex="-1" aria-labelledby="updateGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateGradeModalLabel">Update Grade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk update data -->
                <form id="updateGradeForm">
                    <!-- Basic Input -->
                    <div class="col-xxl-12 col-md-12 mb-3">
                        <div>
                            <label class="form-label">Pilih Grade Baru</label>
                            <input type="hidden" id="id" name="id">
                            <select class="form-select" id="grade" name="grade">
                                <option selected disabled>- Pilih grade -</option>
                                @foreach (App\Models\Grades::all() as $grades)
                                    <option value="{{ $grades->grade }}">{{ $grades->grade }}</option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label class="form-label">Berat</label>
                            <input type="number" class="form-control" name="berat" id="berat"
                                placeholder="Berat baru" step="0.01">

                            <div class="invalid-feedback"></div>
                        </div>

                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('updateGradeForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        id = document.getElementById('id').value;
        try {
            const response = await fetch('/cutting-grading/updateGrade/' + id, {
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

                const form = document.getElementById('updateGradeForm');
                form.reset();

                $('#datatable').DataTable().ajax.reload();
                $('#updateGradeModal').modal('hide');
            }
        } catch (error) {
            console.error(error);
        }
    });
</script>
