<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\CompanyAdminController;
use Illuminate\Support\Facades\Route;

// AUTH JALUR UTAMA
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// GRUP SUPERADMIN (role_id = 4)
Route::prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/add-company', [SuperadminController::class, 'showRegisterCompanyForm'])->name('superadmin.add_company');
    Route::post('/add-company', [SuperadminController::class, 'storeCompany'])->name('superadmin.store_company');
});

// GRUP ADMIN COMPANY (role_id = 1)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/add-staff', [CompanyAdminController::class, 'showAddStaffForm'])->name('admin.add_staff');
    Route::post('/add-staff', [CompanyAdminController::class, 'storeStaff'])->name('admin.store_staff');
    
    // ROUTE BARU: Jalur eksekusi cetak PDF
    Route::get('/download-report', [CompanyAdminController::class, 'downloadPdfReport'])->name('admin.download_report');
});