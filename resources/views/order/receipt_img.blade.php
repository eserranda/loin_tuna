<div class="modal fade" id="receiptImgModal" tabindex="-1" aria-labelledby="receiptImgModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptImgModal">Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Responsive Images -->
                <img src="" class="img-fluid rounded" alt="Receipt Image" id="receipt_img">
                <input type="hidden" id="id" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="updateStatusPaid('rejected')">Tolak</button>
                <button type="button" class="btn btn-success"
                    onclick="updateStatusPaid('confirmed')">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function updateStatusPaid(status) {
        const id = document.getElementById('id').value;
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch('/order/update-status-paid/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: status
                })
            });

            const data = await response.json();
            console.log(data);
            if (!data.success) {
                alert(data.message);
            } else {
                $('.dataOrder').DataTable().ajax.reload();
                $('#receiptImgModal').modal('hide');
            }
        } catch (error) {
            console.error(error);
        }
    }
</script>
