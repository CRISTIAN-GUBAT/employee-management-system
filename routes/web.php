<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest routes - REGISTER REMOVED
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Admin Profile routes
    Route::get('/admin/profile', [DashboardController::class, 'adminProfile'])->name('admin.profile');
    Route::put('/admin/profile/image', [DashboardController::class, 'updateAdminProfileImage'])->name('admin.update.profile.image');
    Route::put('/admin/profile/info', [DashboardController::class, 'updateAdminProfileInfo'])->name('admin.update.profile.info');
    
    // Employee Profile routes
    Route::get('/employee/profile', [DashboardController::class, 'employeeProfile'])->name('employee.profile');
    Route::put('/employee/profile/image', [DashboardController::class, 'updateEmployeeProfileImage'])->name('employee.update.profile.image');
    Route::put('/employee/profile/info', [DashboardController::class, 'updateEmployeeProfileInfo'])->name('employee.update.profile.info');
    Route::get('/employee/edit-profile', [DashboardController::class, 'editEmployeeProfile'])->name('employee.edit-profile');
    
    // Employee profile completion routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/complete-profile', [ProfileController::class, 'completeProfile'])->name('complete-profile');
        Route::post('/complete-profile', [ProfileController::class, 'storeProfile'])->name('profile.store');
        Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');
    });
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Employee routes - only index, create, store, show, destroy (no edit/update)
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        // Department routes - full CRUD
        Route::resource('departments', DepartmentController::class);
    });
});

// Home redirect
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Check if employee has completed profile
        $employee = Auth::user()->employee;
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('employee.complete-profile');
        }
        
        return redirect()->route('employee.dashboard');
    }
    return redirect()->route('login');
});