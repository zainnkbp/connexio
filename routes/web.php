<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AssignmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // --- Admin & Super Admin Route Group ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Devices
        Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
        Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
        Route::put('/devices/{serial_number}', [DeviceController::class, 'update'])->name('devices.update');
        Route::delete('/devices/{serial_number}', [DeviceController::class, 'destroy'])->name('devices.destroy');

        // Customers (Admin Management)
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::put('/customers/{id_pelanggan}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id_pelanggan}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // Approvals / ACC
        Route::get('/approvals', [AssignmentController::class, 'pendingApprovals'])->name('approvals.index');
        Route::post('/approvals/direct', [AssignmentController::class, 'storeDirectAssignment'])->name('approvals.direct');
        Route::post('/approvals/approve-deployment/{id}', [AssignmentController::class, 'approveDeployment'])->name('approvals.approve-deployment');
        Route::post('/approvals/approve-return/{id}', [AssignmentController::class, 'approveReturn'])->name('approvals.approve-return');
        Route::post('/approvals/approve-dismantle/{id}', [AssignmentController::class, 'approveDismantle'])->name('approvals.approve-dismantle');
        Route::post('/approvals/reject/{id}', [AssignmentController::class, 'rejectAssignment'])->name('approvals.reject');

        // Customer Search & Bypass (AJAX)
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::post('/customers/bypass', [CustomerController::class, 'bypassStore'])->name('customers.bypass');

        // Bulk Import
        Route::get('/import', [CustomerController::class, 'showImport'])->name('import.show');
        Route::post('/import/parse', [CustomerController::class, 'parseImport'])->name('import.parse');
        Route::post('/import/resolve', [CustomerController::class, 'resolveConflict'])->name('import.resolve');
    });

    // --- Technician Route Group ---
    Route::prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teknisiDashboard'])->name('dashboard');
        
        // Deployment
        Route::post('/request-deployment', [AssignmentController::class, 'storeDeploymentRequest'])->name('request-deployment');
        Route::post('/pickup/{id}', [AssignmentController::class, 'confirmPickup'])->name('pickup');
        Route::post('/pickup-group', [AssignmentController::class, 'confirmPickupGroup'])->name('pickup-group');
        Route::post('/complete/{id}', [AssignmentController::class, 'completeDeployment'])->name('complete');
        Route::post('/complete-group', [AssignmentController::class, 'completeDeploymentGroup'])->name('complete-group');

        // Return
        Route::post('/return', [AssignmentController::class, 'storeReturnRequest'])->name('return');
        Route::get('/active-devices', [AssignmentController::class, 'getActiveDevices'])->name('active-devices');

        // Dismantle
        Route::post('/dismantle', [AssignmentController::class, 'storeDismantleRequest'])->name('dismantle');
    });
});
