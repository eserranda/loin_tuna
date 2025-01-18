<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\ProductLogController;
use App\Http\Controllers\RetouchingController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\CuttingGradingController;

Route::get('/', [ReceivingController::class, 'index']);

Route::prefix('raw-material')->controller(RawMaterialController::class)->group(function () {
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

Route::prefix('cutting-grading')->controller(CuttingGradingController::class)->group(function () {
    Route::get('/{ilc_cutting}', 'index')->name('cutting-grading.index');
    Route::post('/store', 'store')->name('cutting-grading.store');
    Route::get('/getAll/{ilc_cutting}', 'getAll')->name('cutting-grading.getAll');
    Route::delete('/{id}', 'destroy')->name('cutting-grading.destroy');
    Route::get('/calculateTotalWeight/{ilc}', 'calculateTotalWeight')->name('cutting-grading.calculateTotalWeight');

    // Route::get('/nextNumber/{ilc_cutting}/{no_ikan}', 'nextNumber')->name('cutting-grading.nextNumber');
});

Route::prefix('cutting')->controller(CuttingController::class)->group(function () {
    Route::get('/', 'index')->name('cutting.index');
    Route::get('/getAllReceiving', 'getAllReceiving')->name('cutting.getAllReceiving');
    Route::post('/store', 'store')->name('cutting.store');
    Route::get('/getAll', 'getAll')->name('cutting.getAll');
    Route::delete('/{id}/{ilc}', 'destroy');
});

Route::prefix('receiving')->controller(ReceivingController::class)->group(function () {
    Route::get('/', 'index')->name('receiving.index');
    Route::get('/getAll', 'getAll')->name('receiving.getAll');
    Route::post('/store', 'store')->name('receiving.store');
    Route::delete('/{id}/{ilc}', 'destroy')->name('receiving.destroy');
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

    Route::get('/getNumberLoin/{ilc_cutting}', 'getNumberLoin');
    Route::get('/getBerat/{ilc}/{no_loin}', 'getBerat');


    Route::get('/calculateLoin/{ilc_cutting}/{no_loin}', 'calculateLoin');
})->middleware('auth');

Route::prefix('product-log')->controller(ProductLogController::class)->group(function () {
    Route::get('/{ilc}', 'index')->name('product_log.index');
    Route::post('/store', 'store');
    Route::delete('/{id}', 'destroy')->name('grades.destroy');

    // Route::get('/getAllDataProductLog', 'getAllDataProductLog');
})->middleware('auth');

Route::prefix('product')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('product.index');
    Route::post('/store', 'store')->name('product.store');
    Route::delete('/{id}', 'destroy')->name('product.destroy');
    Route::get('/getAllData', 'getAllData')->name('product.getAllData');
    Route::get('/productWithCustomerGroup/{customer_group}', 'productWithCustomerGroup');

    // pindahkan ke sini
    Route::get('/getAllDataProductLog', 'getAllDataProductLog');
});

// Route::get('/', function () {
//     return view('welcome');
// });
