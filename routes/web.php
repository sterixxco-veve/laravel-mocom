<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController; // 🛠️ PERBAIKAN 1: Pastikan huruf 'A' besar
use App\Http\Controllers\CompanyAdminController;
use Illuminate\Support\Facades\Route;

// AUTH JALUR UTAMA
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// GRUP SUPERADMIN (role_id = 4)
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Tampilan Dashboard Utama
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // Form Registrasi Tenant Perusahaan Baru
    Route::get('/company/add', [SuperAdminController::class, 'create'])->name('add_company');
    
    // Proses Pengiriman Form (Submit Data)
    Route::post('/company/store', [SuperAdminController::class, 'store'])->name('store_company');
    Route::post('/company/add-staff-backdoor', [SuperAdminController::class, 'storeStaffByAdmin'])->name('store_staff_backdoor');
    Route::post('/company/{id}/deactivate', [SuperAdminController::class, 'deactivate'])->name('deactivate_company');
    Route::post('/company/{id}/activate', [SuperAdminController::class, 'activate'])->name('activate_company');
    Route::get('/company/{companyId}/staff', [SuperAdminController::class, 'getStaff'])->name('company_staff');
    Route::post('/staff/{id}/deactivate', [SuperAdminController::class, 'deactivateStaff'])->name('deactivate_staff');
    Route::post('/staff/{id}/activate', [SuperAdminController::class, 'activateStaff'])->name('activate_staff');
});

// GRUP ADMIN COMPANY (role_id = 1)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/add-staff', [CompanyAdminController::class, 'showAddStaffForm'])->name('admin.add_staff');
    Route::post('/add-staff', [CompanyAdminController::class, 'storeStaff'])->name('admin.store_staff');
    Route::get('/download-report', [CompanyAdminController::class, 'downloadPdfReport'])->name('admin.download_report');
});

Route::middleware(['auth.company'])->group(function () {
    Route::get('/admin/dashboard', [CompanyAdminController::class, 'dashboard'])->name('admin.dashboard');
});