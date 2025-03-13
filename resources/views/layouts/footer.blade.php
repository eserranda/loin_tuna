<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © FIS.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    CV. FARIS INDO SEAFOOD
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<!-- end main content-->
</div>
<!-- END layout-wrapper -->

<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<script>
    async function getListCart() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        try {
            const cartResponse = await fetch('{{ route('cart.findOne') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            });

            if (!cartResponse.ok) {
                throw new Error('Gagal mengambil data keranjang.');
            }

            const cartData = await cartResponse.json();

            updateCartUI(cartData.data);
        } catch (error) {
            console.error('Error:', error);
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        getListCart();
    });


    // Fungsi untuk memperbarui UI keranjang (opsional) file ada di layout
    function updateCartUI(cartItems) {
        let totalQty = 0;
        let totalPrice = 0;

        const cartContainer = document.getElementById('cart-items-container');
        cartContainer.innerHTML = '';

        cartItems.forEach((row, index) => {
            totalQty += row.qty;
            totalPrice += row.qty * row.product.harga;

            const cards = `
                     <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                     <div class="d-flex align-items-center">
                      <img src="${row.product.image || '/uploads/images/no-image.jpg'}"
                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="product-pic">
                        <div class="flex-1">
                             <h6 class="mt-0 mb-1 fs-14">
                          <a href="#" class="text-reset"> ${row.product.nama}</a>
                            </h6>
                         <p class="mb-0 fs-12 text-muted">
                                Quantity: <span>${row.qty} x ${formatToRupiah(row.product.harga)}</span>
                            </p>
                            </div>
                                <div class="px-2">
                               <h5 class="m-0 fw-normal"><span class="cart-item-price">${formatToRupiah(row.product.harga)}</span>
                                </h5>
                                    </div>
                                        <div class="ps-2">
                                            <button type="button" onclick="removeCartItem(${row.id})"
                                                class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn">
                                                <i class="ri-close-fill text-danger fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>
                `;
            cartContainer.insertAdjacentHTML('beforeend', cards);
        });

        // Perbarui elemen total quantity
        document.getElementById('total_qty').textContent = totalQty;
        document.getElementById('total_qty_product').textContent = cartItems.length;

        // Perbarui elemen total harga
        document.getElementById('cart-item-total').textContent = formatToRupiah(totalPrice);
    }

    function removeCartItem($id) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content');
            fetch('/cart/destroy/' + $id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    getListCart();
                } else {
                    alert('Terjadi kesalahan saat menghapus item dari keranjang');
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function formatToRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0 // Tidak menampilkan angka desimal
        }).format(number);
    }
</script>
<!-- JAVASCRIPT -->
<script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets') }}/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('assets') }}/libs/node-waves/waves.min.js"></script>
<script src="{{ asset('assets') }}/libs/feather-icons/feather.min.js"></script>
<script src="{{ asset('assets') }}/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="{{ asset('assets') }}/js/plugins.js"></script>

<!-- App js -->
<script src="{{ asset('assets') }}/js/app.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
@stack('scripts')

</body>

</html>
