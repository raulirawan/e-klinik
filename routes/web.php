<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/check-available', [HomeController::class, 'checkAvailable'])->name('check-available');
Route::post('/check-available', [HomeController::class, 'checkAvailablePost'])->name('check-available.post');

Route::post('/make-appointment', [HomeController::class, 'makeAppointment'])->name('makeAppointment')->middleware('auth');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUD Poin
    Route::get('point', [App\Http\Controllers\Admin\PointController::class, 'index'])->name('admin.point.index');
    Route::post('point/create', [App\Http\Controllers\Admin\PointController::class, 'store'])->name('admin.point.store');
    Route::post('point/update/{point}', [App\Http\Controllers\Admin\PointController::class, 'update'])->name('admin.point.update');
    Route::get('point/delete/{point}', [App\Http\Controllers\Admin\PointController::class, 'delete'])->name('admin.point.delete');

    // CRUD Pasien
    Route::get('pasien', [App\Http\Controllers\Admin\PasienController::class, 'index'])->name('admin.pasien.index');
    Route::post('pasien/create', [App\Http\Controllers\Admin\PasienController::class, 'store'])->name('admin.pasien.store');
    Route::post('pasien/update/{pasien}', [App\Http\Controllers\Admin\PasienController::class, 'update'])->name('admin.pasien.update');
    Route::get('pasien/delete/{pasien}', [App\Http\Controllers\Admin\PasienController::class, 'delete'])->name('admin.pasien.delete');

    // CRUD Dokter
    Route::get('dokter', [App\Http\Controllers\Admin\DokterController::class, 'index'])->name('admin.dokter.index');
    Route::get('dokter/create', [App\Http\Controllers\Admin\DokterController::class, 'create'])->name('admin.dokter.create');
    Route::post('dokter/create', [App\Http\Controllers\Admin\DokterController::class, 'store'])->name('admin.dokter.store');
    Route::get('dokter/edit/{dokter}', [App\Http\Controllers\Admin\DokterController::class, 'edit'])->name('admin.dokter.edit');
    Route::post('dokter/update/{dokter}', [App\Http\Controllers\Admin\DokterController::class, 'update'])->name('admin.dokter.update');
    Route::get('dokter/delete/{dokter}', [App\Http\Controllers\Admin\DokterController::class, 'delete'])->name('admin.dokter.delete');

    // CRUD Medicine
    Route::get('medicine', [App\Http\Controllers\Admin\MedicineController::class, 'index'])->name('admin.medicine.index');
    Route::post('medicine/create', [App\Http\Controllers\Admin\MedicineController::class, 'store'])->name('admin.medicine.store');
    Route::post('medicine/update/{medicine}', [App\Http\Controllers\Admin\MedicineController::class, 'update'])->name('admin.medicine.update');
    Route::get('medicine/delete/{medicine}', [App\Http\Controllers\Admin\MedicineController::class, 'delete'])->name('admin.medicine.delete');

    // CRUD Medicine
    Route::get('stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stock.index');
    Route::post('stock/create', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('admin.stock.store');
    Route::post('stock/update/{stock}', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('admin.stock.update');
    Route::get('stock/delete/{stock}', [App\Http\Controllers\Admin\StockController::class, 'delete'])->name('admin.stock.delete');


    Route::get('transaction', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transaction.index');
    Route::get('transaction/detail/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'detail'])->name('admin.transaction.detail');
    Route::post('transaction/update/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('admin.transaction.update');
});


// for dokter
Route::prefix('dokter')->middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Dokter\DashboardController::class, 'index'])->name('dokter.dashboard');
});


// for apoteker
Route::prefix('apoteker')->middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Apoteker\DashboardController::class, 'index'])->name('apoteker.dashboard');

    // CRUD Medicine
    Route::get('medicine', [App\Http\Controllers\Apoteker\MedicineController::class, 'index'])->name('apoteker.medicine.index');
    Route::post('medicine/create', [App\Http\Controllers\Apoteker\MedicineController::class, 'store'])->name('apoteker.medicine.store');
    Route::post('medicine/update/{medicine}', [App\Http\Controllers\Apoteker\MedicineController::class, 'update'])->name('apoteker.medicine.update');
    Route::get('medicine/delete/{medicine}', [App\Http\Controllers\Apoteker\MedicineController::class, 'delete'])->name('apoteker.medicine.delete');


    // CRUD Medicine
    Route::get('stock', [App\Http\Controllers\Apoteker\StockController::class, 'index'])->name('apoteker.stock.index');
    Route::post('stock/create', [App\Http\Controllers\Apoteker\StockController::class, 'store'])->name('apoteker.stock.store');
    Route::post('stock/update/{stock}', [App\Http\Controllers\Apoteker\StockController::class, 'update'])->name('apoteker.stock.update');
    Route::get('stock/delete/{stock}', [App\Http\Controllers\Apoteker\StockController::class, 'delete'])->name('apoteker.stock.delete');
});




// for apoteker
Route::prefix('pasien')->middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Pasien\DashboardController::class, 'index'])->name('pasien.dashboard');

    Route::get('transaction', [App\Http\Controllers\Pasien\TransactionController::class, 'index'])->name('pasien.transaction.index');
    Route::get('transaction/detail/{transaction}', [App\Http\Controllers\Pasien\TransactionController::class, 'detail'])->name('pasien.transaction.detail');

    Route::post('transaction/payment/{transaction}', [App\Http\Controllers\Pasien\TransactionController::class, 'payment'])->name('pasien.transaction.payment');

});


Route::prefix('ajax')->group(function () {
    Route::post('/get-medicine', [App\Http\Controllers\Admin\MedicineController::class, 'getMedicine']);
    Route::post('/get-medicine-detail', [App\Http\Controllers\Admin\MedicineController::class, 'getMedicineDetail']);
});

Auth::routes();
