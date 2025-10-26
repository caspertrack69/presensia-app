<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Employee\LeaveRequestController as EmployeeLeaveRequestController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\LeaveApprovalController;
use App\Http\Controllers\Manager\ReportController as ManagerReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

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
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Employee Routes
Route::middleware(['auth', 'role:employee,manager,admin'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    
    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/scanner', [AttendanceController::class, 'scanner'])->name('scanner');
        Route::post('/verify-qr', [AttendanceController::class, 'verifyQr'])->name('verify-qr');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');
    });

    Route::resource('leave-requests', EmployeeLeaveRequestController::class)
        ->except(['edit', 'update', 'destroy']);
});

// Manager Routes
Route::middleware(['auth', 'role:manager,admin'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/leave-requests', [LeaveApprovalController::class, 'index'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/approve', [LeaveApprovalController::class, 'approve'])
        ->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveApprovalController::class, 'reject'])
        ->name('leave-requests.reject');

    Route::get('/reports/attendance', [ManagerReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/attendance/export', [ManagerReportController::class, 'export'])->name('reports.attendance.export');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // QR Code Management
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        Route::get('/', [QrCodeController::class, 'index'])->name('index');
        Route::get('/display', [QrCodeController::class, 'display'])->name('display');
        Route::post('/generate', [QrCodeController::class, 'generate'])->name('generate');
        Route::get('/current', [QrCodeController::class, 'current'])->name('current');
    });

    Route::resource('users', AdminUserController::class);
    Route::resource('departments', AdminDepartmentController::class)->except(['show']);

    Route::get('/leave-requests', [AdminLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/approve', [AdminLeaveRequestController::class, 'approve'])
        ->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [AdminLeaveRequestController::class, 'reject'])
        ->name('leave-requests.reject');

    Route::get('/reports/attendance', [AdminReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/attendance/export', [AdminReportController::class, 'export'])->name('reports.attendance.export');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Public attendance scan route (for QR redirect)
Route::get('/attendance/scan/{token}', function ($token) {
    return redirect()->route('employee.attendance.scanner', ['token' => $token]);
})->name('attendance.scan');
