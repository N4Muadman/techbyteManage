<?php

use App\Events\TestBroadcastEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\employeeController;
use App\Http\Controllers\attendanceController;
use App\Http\Controllers\hrtransferController;
use App\Http\Controllers\salaryController;
use App\Http\Controllers\branchController;
use App\Http\Controllers\Customer_manageController;
use App\Http\Controllers\recruitmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\leaveController;
use App\Http\Controllers\work_scheduleController;
use App\Http\Controllers\evaluationAndRewardsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\RoleManageController;
use App\Http\Controllers\Support_complaintController;
use App\Http\Controllers\work_shiftController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    return redirect()->route('home.admin');
});

Route::get('no-access', function () {return view('home.noAccess');});

Route::get('admin/dang-nhap', [LoginController::class, 'index'])->name('login.index');
Route::post('admin/login', [LoginController::class, 'login'])->name('login');
Route::get('admin/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('download-file-employee-must-reading', [LoginController::class, 'download'])->name('download-file');

    Route::get('thong-ke-khach-hang-doanh-nghiep', [Customer_manageController::class, 'BusinessCustomerStatistics'])->name('businessCustomerStatistics');
Route::middleware('is_admin')->prefix('admin')->group(function () {
    Route::get('home', function () {
        return view('home.banner');
    })->name('home.admin');

    Route::middleware('is_access:1,1,2,3,4,5')->prefix('quan-ly-nhan-vien')->group(function () {
        Route::get('/', [employeeController::class, 'index'])->name('employee.index.admin');
        Route::get('/chi-tiet-nhan-vien/{id}', [employeeController::class, 'detail'])->name('employee.detail.admin');
        Route::post('/create', [employeeController::class, 'create'])->name('employee.create.admin');
        Route::post('/update/{id}', [employeeController::class, 'update'])->name('employee.update.admin');
        Route::post('/delete/{id}', [employeeController::class, 'delete'])->name('employee.delete.admin');
        Route::get('/chinh-sua-nhan-vien/{id}', [employeeController::class, 'edit'])->name('employee.edit.admin');
        Route::post('/empower/{id}', [employeeController::class, 'empower'])->name('employee.empower.admin');
    });

    Route::get('/thong-tin-ca-nhan', [employeeController::class, 'profile'])->name('employee.profile.admin');
    Route::put('/thong-tin-ca-nhan', [employeeController::class, 'updateProfile'])->name('employee.profile.update');


    Route::middleware('is_access:2,3,4')->prefix('quan-ly-tien-luong')->group(function () {
        Route::get('/', [salaryController::class, 'index'])->name('salary.index.admin');
        Route::post('/create', [salaryController::class, 'create'])->name('salary.create.admin');
        Route::post('/update/{id}', [salaryController::class, 'update'])->name('salary.update.admin');
        Route::get('/export', [salaryController::class, 'export'])->name('salary.export.admin');
    });
    Route::middleware('is_access:3,1,2,3,4')->prefix('quan-ly-chi-nhanh')->group(function () {
        Route::get('/', [branchController::class, 'index'])->name('branch.index.admin');
        Route::post('/create', [branchController::class, 'create'])->name('branch.create.admin');
        Route::post('/update/{id}', [branchController::class, 'update'])->name('branch.update.admin');
        Route::post('/delete/{id}', [branchController::class, 'delete'])->name('branch.delete.admin');
    });
    Route::middleware('is_access:4,1,2,3,4')->prefix('quan-ly-tuyen-dung')->group(function () {
        Route::get('/', [recruitmentController::class, 'index'])->name('recruitment.index.admin');
    });

    Route::middleware('is_access:5,1,3,4,6')->prefix('quan-ly-nghi-phep')->group(function () {
        Route::get('/', [leaveController::class, 'index'])->name('leave.index.admin');
        Route::post('/approve/{id}', [leaveController::class, 'approve'])->name('leave.approve.admin');
        Route::post('/refuse/{id}', [leaveController::class, 'refuse'])->name('leave.refuse.admin');
        Route::post('/delete/{id}', [leaveController::class, 'delete'])->name('leave.delete.admin');
        Route::post('/create', [leaveController::class, 'create'])->name('leave.create.admin');
    });

    Route::middleware('is_access:6,1,2,3,4')->prefix('quan-ly-ca-lam')->group(function () {
        Route::get('/', [work_shiftController::class, 'index'])->name('work_shift.index.admin');
        Route::post('/create', [work_shiftController::class, 'create'])->name('work_shift.create.admin');
        Route::post('/update/{id}', [work_shiftController::class, 'update'])->name('work_shift.update.admin');
        Route::post('/delete/{id}', [work_shiftController::class, 'delete'])->name('work_shift.delete.admin');
    });

    Route::middleware('is_access:7,1,2,3')->prefix('danh-gia-khen-thuong')->group(function () {
        Route::get('/', [evaluationAndRewardsController::class, 'index'])->name('evaluation.index.admin');
        Route::post('/create', [evaluationAndRewardsController::class, 'create'])->name('evaluation.create.admin');
        Route::post('/update/{id}', [evaluationAndRewardsController::class, 'update'])->name('evaluation.update.admin');
    });

    Route::middleware('is_access:8,1,2,3,4,7')->prefix('khach-hang-tu-dau')->group(function () {
        Route::get('/', [Customer_manageController::class, 'newCustomer'])->name('newCustomer');
        Route::get('khach-hang/show/{id}', [Customer_manageController::class, 'show'])->name('showCustomer');
        Route::post('khach-hang/create', [Customer_manageController::class, 'create'])->name('createCustomer');
        Route::put('khach-hang/update/{id}', [Customer_manageController::class, 'update'])->name('updateCustomer');
        Route::delete('khach-hang/delete/{id}', [Customer_manageController::class, 'delete'])->name('deleteCustomer');
        Route::post('khach-hang/deal/{id}', [Customer_manageController::class, 'deal'])->name('dealCustomer');
    });

    Route::get('khach-hang-tu-dau/thong-ke', [Customer_manageController::class, 'statistics'])->middleware('is_access:9,3')->name('customerStatistics');

    Route::get('khach-hang-doanh-nghiep', [Customer_manageController::class, 'businessCustomer'])->middleware('is_access:10,2,3,4,8')->name('businessCustomer');
    Route::get('thong-ke-doanh-nghiep', [Customer_manageController::class, 'businessStatistics'])->middleware('is_access:11,3')->name('businessStatistics');
    Route::get('thong-ke-khach-hang-doanh-nghiep', [Customer_manageController::class, 'BusinessCustomerStatistics'])->middleware('is_access:11,3')->name('businessCustomerStatistics');

    Route::middleware('is_access:12,1,2,3,4')->group(function () {
        Route::resource('quan-ly-du-an', ProjectController::class)->names('projects')->parameters(['quan-ly-du-an' => 'project']);
        Route::post('quan-ly-du-an/{id}/add-members', [ProjectController::class, 'addMember'])->name('projects.add-member');
        Route::resource('quan-ly-du-an.tasks', ProjectTaskController::class)->shallow()->names('project_tasks');
    });

    Route::middleware('is_access:13,1,3,4')->prefix('quan-ly-cham-cong')->group(function () {
        Route::get('/', [attendanceController::class, 'index'])->name('attendance.index.admin');
        Route::post('/create', [attendanceController::class, 'create'])->name('attendance.create.admin');
        Route::post('/update/{id}', [attendanceController::class, 'update'])->name('attendance.update.admin');
        Route::post('/delete/{id}', [attendanceController::class, 'delete'])->name('attendance.delete.admin');
        Route::post('/approve/{id}', [attendanceController::class, 'approve'])->name('attendance.approve.admin');
        Route::get('/export', [attendanceController::class, 'export'])->name('attendance.export.admin');
    });

    Route::middleware('is_access')->prefix('dieu-dong-nhan-su')->group(function () {
        Route::get('/', [hrtransferController::class, 'index'])->name('hrtransfer.index.admin');
        Route::post('/approve/{id}', [hrtransferController::class, 'approve'])->name('hrtransfer.approve.admin');
        Route::post('/create', [hrtransferController::class, 'create'])->name('hrtransfer.create.admin');
    });

    Route::middleware('is_access')->prefix('quan-ly-lich-lam-viec')->group(function () {
        Route::get('/', [work_scheduleController::class, 'index'])->name('workschedule.index.admin');
        Route::post('/create', [work_scheduleController::class, 'create'])->name('workschedule.create.admin');
        Route::post('/create-work-schedule-by-file', [work_scheduleController::class, 'importFile'])->name('workschedule.importfile.admin');
        Route::get('/employee-of-branch/{id}', [work_scheduleController::class, 'employeeOfBranch'])->name('workschedule.employeeOfBranch');
        Route::post('edit', [work_scheduleController::class, 'edit'])->name('workschedule.edit');
        Route::post('update/{id}', [work_scheduleController::class, 'update'])->name('workschedule.update');
        Route::get('/get-work-shedule-by-employee/{id}', [work_scheduleController::class, 'getWorkScheduleByEmployee'])->name('workschedule.getWorkSche');
    });

    Route::middleware('is_access:14,1,3,6')->prefix('ho-tro-khieu-nai')->group(function () {
        Route::get('/', [Support_complaintController::class, 'index'])->name('complaint.index.admin');
        Route::post('/create', [Support_complaintController::class, 'create'])->name('complaint.create.admin');
        Route::post('/approve/{id}', [Support_complaintController::class, 'approve'])->name('complaint.approve.admin');
    });

    Route::get('/qrcode-checkin', [attendanceController::class, 'generateCheckinQrCode'])->name('qrcode-checkin');
    Route::post('/checkin', [attendanceController::class, 'checkin'])->name('checkin');
    Route::post('/checkout', [attendanceController::class, 'checkout'])->name('checkout');

    Route::get('/doi-mat-khau', [employeeController::class, 'formChangePassword'])->name('form-change-password');
    Route::post('/doi-mat-khau', [employeeController::class, 'changePassword'])->name('change-password');

    Route::get('get-feedback/{id}', [Customer_manageController::class, 'getFeedback'])->name('getFeedback');
    Route::post('upload-feedback', [Customer_manageController::class, 'uploadFeedback'])->name('uploadFeedback');
    Route::get('get-contract/{id}', [Customer_manageController::class, 'getContract'])->name('getContract');
    Route::post('upload-feedback/{id}', [Customer_manageController::class, 'updateContract'])->name('updateContract');

    Route::get('cong-no-doanh-nghiep', [Customer_manageController::class, 'debtContract'])->name('debtContract');

    Route::resource('quan-ly-vai-tro', RoleManageController::class)->names('roles');
    Route::get('quyen-cua-vai-tro', [PermissionController::class, 'index'])->name('permission.index');
    Route::post('change-status-permission/{id}', [PermissionController::class, 'changeStatus'])->name('permission.change.status');
});
