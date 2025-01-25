<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\ProductLogController;
use App\Http\Controllers\RetouchingController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\CuttingGradingController;

Route::get('/', [ReceivingController::class, 'index'])->middleware('auth');

Route::get('login', [UserController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::get('register', [UserController::class, 'showRegistrationForm'])->name('register')->middleware('guest');

Route::post('login', [UserController::class, 'login'])->middleware('guest');

Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::prefix('order')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index')->name('order.index');
    Route::get('/getAll', 'getAll')->name('order.getAll');
    Route::get('/checkout', 'checkout')->name('checkout.index');
    Route::get('/detail-order/{po_number}', 'detailOrder');
    Route::post('/update/{po_number}', 'update');

    // admin or super admin
    Route::get('/list-order', 'listOrder')->name('order.getOne');
    Route::get('/get-all-list-order', 'getAllListOrder')->name('order.getAllListOrder');
    Route::get('/getAllOrderInPo', 'getAllOrderInPo')->name('order.getAllOrderInPo');
    Route::post('/update-status-order/{id}', 'updateStatusOrder');
    Route::delete('/destroy/{id}', 'destroy')->name('order.destroy');
});

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/store', 'store')->name('cart.store');
    Route::get('/findOne', 'findOne')->name('cart.findOne');
    Route::post('/decrease/{id}', 'decrease')->name('cart.decrease');
    Route::post('/increase/{id}', 'increase')->name('cart.increase');
    Route::delete('/destroy/{id}', 'destroy')->name('cart.destroy');
});

Route::prefix('customer')->controller(CustomerController::class)->group(function () {
    // Route::get('/', 'index')->name('costumer.index');
    Route::post('/update/{id}', 'update')->name('costumer.update');
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::get('/customers', 'userCustomers')->name('user-customers.index');
    Route::post('/register', 'register');
    Route::post('/customer/register', 'customerRegister');
    Route::get('/findById/{id}', 'findById');
    Route::post('/update', 'update');
    Route::delete('/destroy/{id}', 'destroy');

    Route::get('/profile', 'profile');
    Route::get('/edit-profile', 'editProfile');
})->middleware('auth');

Route::prefix('packing')->controller(PackingController::class)->group(function () {
    // Users costumer enkripsi 
    // Route::get('/kode-po', 'processQRCode')->name('process-qr-code');

    Route::get('/', 'index')->name('packing.index');
    Route::post('/store', 'store')->name('packing.store');
    Route::post('/update', 'update');
    Route::get('/getAllDatatable', 'getAllDatatable')->name('packing.getAllDatatable');
    Route::get('/customer-produk/{id_customer}/{id_produk}', 'customerProduk');
    Route::delete('/{id}', 'destroy')->name('packing.destroy');

    Route::get('/getAllDataProductLog', 'getAllDataProductLog')->name('get-all-product-log');
});

Route::prefix('roles')->controller(RoleController::class)->group(function () {
    Route::get('/', 'index')->name('roles.index');
    Route::post('/store', 'store');
    Route::get('/findById/{id}', 'findById');
    Route::post('/update', 'update');
    Route::delete('/destroy/{id}', 'destroy');
});

Route::prefix('receiving')->controller(ReceivingController::class)->middleware(['auth', 'role:super_admin|admin'])->group(function () {
    Route::get('/', 'index')->name('receiving.index');
    Route::get('/getAll', 'getAll')->name('receiving.getAll');
    Route::post('/store', 'store')->name('receiving.store');
    Route::delete('/{id}/{ilc}', 'destroy')->name('receiving.destroy');
});


Route::prefix('raw-material')->controller(RawMaterialController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('raw_material.index');
    Route::get('/getAll', 'getAll')->name('raw_material.getAll');
    Route::get('/{ilc}', 'getOneRawWithILC')->name('raw_material.grading');
    Route::post('/store', 'store')->name('raw_material.store');
    Route::delete('/{id}', 'destroy');
    Route::delete('/{id}/{ilc}', 'destroy')->name('raw_material.destroy');
    Route::get('/findManyWithILC/{ilc}', 'findManyWithILC');
    Route::get('/nextNumber/{ilc}', 'nextNumber')->name('raw_material.nextNumber');
    Route::get('/calculateTotalWeight/{ilc}', 'calculateTotalWeight');
    Route::get('/getNoIkan/{ilc}', 'getNoIkan')->name('raw_material.get_no_ikan');

    Route::get('/grading/get/{ilc}', 'gradingGet')->name('rawmaterial.grading.get');
    Route::post('/grading/store', 'gradingStore')->name('rawmaterial.grading.store');
});

Route::prefix('cutting-grading')->controller(CuttingGradingController::class)->middleware('auth')->group(function () {
    Route::get('/{ilc_cutting}', 'index')->name('cutting-grading.index');
    Route::post('/store', 'store')->name('cutting-grading.store');
    Route::get('/getAll/{ilc_cutting}', 'getAll')->name('cutting-grading.getAll');
    Route::delete('/{id}', 'destroy')->name('cutting-grading.destroy');
    Route::get('/calculateTotalWeight/{ilc}', 'calculateTotalWeight')->name('cutting-grading.calculateTotalWeight');

    // Route::get('/nextNumber/{ilc_cutting}/{no_ikan}', 'nextNumber')->name('cutting-grading.nextNumber');
});

Route::prefix('cutting')->controller(CuttingController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('cutting.index');
    Route::get('/getAllReceiving', 'getAllReceiving')->name('cutting.getAllReceiving');
    Route::post('/store', 'store')->name('cutting.store');
    Route::get('/getAll', 'getAll')->name('cutting.getAll');
    Route::delete('/{id}/{ilc}', 'destroy');
});



Route::prefix('supplier')->controller(SupplierController::class)->group(function () {
    Route::get('/', 'index')->name('supplier.index');
    Route::get('/add', 'add')->name('supplier.add');
    Route::get('/getAllData', 'getAllData')->name('supplier.getAllData');
    Route::delete('/{id}', 'destroy')->name('supplier.destroy');
    Route::post('/store', 'store')->name('supplier.store');

    // select2
    Route::get('/get', 'get')->name('supplier.get');
});

Route::prefix('grades')->controller(GradesController::class)->group(function () {
    Route::get('/', 'index')->name('grades.index');
    Route::post('/store', 'store')->name('grades.store');
    Route::get('/getAll', 'getAll')->name('grades.getAll');
    Route::get('/getAllData', 'getAllData')->name('grades.getAllData');
    Route::delete('/{id}', 'destroy')->name('grades.destroy');
})->middleware('auth');

Route::prefix('retouching')->controller(RetouchingController::class)->group(function () {
    Route::get('/', 'index')->name('retouching.index');
    Route::get('/getAll', 'getAll')->name('retouching.getAll');
    Route::post('/', 'store')->name('retouching.store');
    Route::delete('/{id}', 'destroy')->name('retouching.destroy');
    Route::get('/getAllCutting', 'getAllCutting')->name('retouching.getAllCutting');

    Route::get('/getNumberLoinCutting/{ilc_cutting}', 'getNumberLoinCutting');

    Route::get('/getNumberLoinRetouching/{ilc_cutting}', 'getNumberLoinRetouching');
    Route::get('/getBerat/{ilc}/{no_loin}', 'getBerat');


    Route::get('/calculateLoin/{ilc_cutting}/{no_loin}', 'calculateLoin');
})->middleware('auth');

Route::prefix('product-log')->controller(ProductLogController::class)->group(function () {
    Route::get('/{ilc}', 'index')->name('product_log.index');
    Route::post('/store', 'store');
    Route::delete('/{id}', 'destroy')->name('product-log.destroy');

    // Route::get('/getAllDataProductLog', 'getAllDataProductLog');
})->middleware('auth');

Route::prefix('product')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('product.index');
    Route::post('/store', 'store')->name('product.store');
    Route::post('/saveImage', 'saveImage')->name('product.saveImage');
    Route::delete('/{id}', 'destroy')->name('product.destroy');
    Route::get('/getAllData', 'getAllData')->name('product.getAllData');
    Route::get('/productWithCustomerGroup', 'productWithCustomerGroup');

    // pindahkan ke sini
    Route::get('/getAllDataProductLog', 'getAllDataProductLog');

    // untuk customer
    Route::get('list-product', 'listProduct');
});

// Route::get('/', function () {
//     return view('welcome');
// });
