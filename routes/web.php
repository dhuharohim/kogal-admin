<?php

use App\Http\Controllers\FormInputController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserAccessManagementController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Rute-rute yang hanya bisa diakses oleh admin
Route::middleware('admin')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');

    Route::get('item/{id}', [ItemController::class, 'getItem'])->name('get.item');

    // CMS
    Route::prefix('cms')->name('cms.')->group(function () {
    });

    Route::prefix('user-access-management')->name('user-access-management.')->group(function () {
        Route::get('/', [UserAccessManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserAccessManagementController::class, 'create'])->name('create');
        Route::post('/store', [UserAccessManagementController::class, 'store'])->name('store');
        Route::post('/update/{id}', [UserAccessManagementController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [UserAccessManagementController::class, 'delete'])->name('delete');
        Route::get('/view/{id}', [UserAccessManagementController::class, 'view'])->name('view');
    });
});

Route::middleware(['warehouse', 'admin'])->group(function () {
    // warehouse
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::resource('', WarehouseController::class)->except([
            'show',
            'delete',
            'edit',
            'update',
        ]);
        Route::get('show/{id}', [WarehouseController::class, 'show'])->name('show');
        Route::post('delete/{id}', [WarehouseController::class, 'destroy'])->name('delete');
        Route::get('edit/{id}', [WarehouseController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [WarehouseController::class, 'update'])->name('update');

        Route::prefix('items')->name('items.')->group(function () {
            Route::get('/', [ItemController::class, 'index'])->name('index');
            Route::post('change', [ItemController::class, 'change'])->name('change');
        });

        Route::prefix('request-shipment')->name('request-shipment.')->group(function () {
            Route::get('/', [WarehouseController::class, 'requestShipment'])->name('index');
            Route::get('/{shipment_number}', [WarehouseController::class, 'showRequestShipment'])->name('show');
            Route::post('{shipment_number}/shipment-confirmation', [WarehouseController::class, 'shipmentConfirmation'])->name('approve');
            Route::post('{shipment_number}/shipment-picked-up', [WarehouseController::class, 'pickedUp'])->name('picked.up');
        });
    });
});

Route::middleware('shipment')->group(function () {

     //shipment route
     Route::prefix('shipment')->name('shipment.')->group(function () {
        Route::get('master', [ShipmentController::class, 'getAjax'])->name('master');
        Route::resource('', ShipmentController::class)->except([
            'show',
            'delete',
            'edit',
            'update'
        ]);
        Route::post('update/{id}', [ShipmentController::class, 'update'])->name('update');
        Route::post('publish/{id}', [ShipmentController::class, 'publish'])->name('publish');
        Route::get('show/{id}', [ShipmentController::class, 'show'])->name('show');
        Route::post('delete/{id}', [ShipmentController::class, 'destroy'])->name('delete');
        Route::get('edit/{id}', [ShipmentController::class, 'edit'])->name('edit');
        Route::get('manage-status', [ShipmentController::class, 'manageStatus'])->name('manage.status');
        Route::get('ajax-items', [ShipmentController::class, 'ajaxItems'])->name('ajax.items');

        Route::post('add-history/{id}', [ShipmentController::class, 'addHistory'])->name('add.history');
        Route::post('edit-history/{history_id}', [ShipmentController::class, 'editHistory'])->name('edit.history');
        Route::post('delete-history/{history_id}', [ShipmentController::class, 'deleteHistory'])->name('delete.history');

        // Form input
        Route::prefix('form-input')->name('form-input.')->group(function () {
            Route::get('/', [FormInputController::class, 'index'])->name('index');
            Route::post('/save-shipment', [FormInputController::class, 'storeShipmentForm'])->name('saveShipment');
            Route::post('/save-item-changes', [FormInputController::class, 'saveItem'])->name('saveItem');
        });

        // shipment invoices
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoicesController::class, 'index'])->name('index');
            Route::get('/create', [InvoicesController::class, 'create'])->name('create');
            Route::get('/{invoice_id}', [InvoicesController::class, 'show'])->name('show');
            Route::get('/data-shipment/{shipment_id}', [InvoicesController::class, 'dataShipment'])->name('data.shipment');
            Route::post('/multiple-creation', [InvoicesController::class, 'multipleCreation'])->name('multiple.creation');
            Route::get('/print/{invoice_id}', [InvoicesController::class, 'print'])->name('print');
            Route::post('/update/{invoice_id}', [InvoicesController::class, 'update'])->name('update');
        });
    });

    Route::get('item/{id}', [ItemController::class, 'getItem'])->name('get.item');
});


// API
Route::get('getshipment/details/{shipment_number}', [ShipmentController::class, 'requestAPI'])->name('request.api');
Route::get('api-invoice/{shipment_number}', [InvoicesController::class, 'getInvoice'])->name('api.invoices');
