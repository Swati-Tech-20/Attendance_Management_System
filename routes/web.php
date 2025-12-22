<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\UserlistController;
use App\Http\Controllers\User\LeaveUserController;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\User\BreaksUserController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminBreaksController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\AttendanceUserController;
use App\Http\Controllers\User\FingerDevicesControlller;
use App\Http\Controllers\User\ManageemployeeController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AttendanceMonthSheetController;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
// Route::view('/mobile-error', 'mobile-error')->name('mobile.error');

Route::middleware('admin')->group(function () {
    Route::get('/reset/update', [AdminLoginController::class, 'showPasswordForm'])->name('admin.auth.reset');
    Route::put('/reset/update/{id}', [AdminLoginController::class, 'updatePassword'])->name('reset.update');
    });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index']);
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
   Route::get('/home', [AdminController::class, 'index'])
        ->name('index')->middleware('admin');
});


    Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [RegisterController::class, 'register'])->name('admin.register.post');
    Route::get('/admin/Userlist', [UserlistController::class, 'index'])->name('admin.userlist');
    Route::put('/Userlist/update/{id}', '\App\Http\Controllers\Admin\UserlistController@update')->name('admin.userlist.update');
    Route::delete('/Userlist/{id}', '\App\Http\Controllers\Admin\UserlistController@destroy')->name('admin.userlist.destroy');
   
// Route::get('attended/{user_id}', '\App\Http\Controllers\User\AttendanceController@attended' )->name('attended');
// Route::get('attended-before/{user_id}', '\App\Http\Controllers\User\AttendanceController@attendedBefore' )->name('attendedBefore');
// Auth::routes(['register' => false, 'reset' => false]);

Route::group(['middleware' => ['admin'], 'roles' => ['admin']], function () {
    Route::get('/admin/employee', '\App\Http\Controllers\Admin\EmployeeController@index')->name('admin.employee');
    Route::post('/employee/store', '\App\Http\Controllers\Admin\EmployeeController@store')->name('admin.employee.store');
    Route::put('/employee/update/{id}', '\App\Http\Controllers\Admin\EmployeeController@update')->name('admin.employee.update');
    Route::delete('/employee/{id}', '\App\Http\Controllers\Admin\EmployeeController@destroy')->name('admin.employee.destroy');
    Route::get('/check', [AdminAttendanceController::class, 'index'])->name('admin.check');
    Route::get('/break-details/{attendanceId}', [AdminAttendanceController::class, 'getBreakDetails'])->name('admin.breakDetails');
    Route::post('/punch-out/{id}', [AdminAttendanceController::class, 'punchOut'])->name('admin.punchOut');
    Route::resource('/schedule', '\App\Http\Controllers\Admin\ScheduleController');
    Route::get('/attendance_month_sheet',[AttendanceMonthSheetController::class,'index'])->name('admin.attendance_month_sheet');
    Route::get('/attendance_month_sheet_report',[AttendanceMonthSheetController::class,'sheetReport'])->name('admin.attendance_month_sheet_report');
    Route::post('check-store',[AttendanceMonthSheetController::class,'CheckStore'])->name('admin.check_store');



   //Fingerprint Devices
    Route::resource('/finger_device', '\App\Http\Controllers\BiometricDeviceController');

    Route::delete('finger_device/destroy', '\App\Http\Controllers\BiometricDeviceController@massDestroy')->name('finger_device.massDestroy');
    Route::get('finger_device/{fingerDevice}/employees/add', '\App\Http\Controllers\BiometricDeviceController@addEmployee')->name('finger_device.add.employee');
    Route::get('finger_device/{fingerDevice}/get/attendance', '\App\Http\Controllers\BiometricDeviceController@getAttendance')->name('finger_device.get.attendance');
    // Temp Clear Attendance route
    Route::get('finger_device/clear/attendance', function () {
     $midnight = \Carbon\Carbon::createFromTime(23, 50, 00);
        $diff = now()->diffInMinutes($midnight);
        dispatch(new ClearAttendanceJob())->delay(now()->addMinutes($diff));
        toast("Attendance Clearance Queue will run in 11:50 P.M}!", "success");
      return back();
 })->name('finger_device.clear.attendance');
});



Route::middleware('auth')->group(function () {
Route::get('/passwordreset/update', [UserLoginController::class, 'showPasswordForm'])->name('user.auth.passwordreset');
Route::put('/passwordreset/update/{id}', [UserLoginController::class, 'updatePassword'])->name('passwordreset.update');
});

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserLoginController::class, 'index']);
    Route::post('/login', [UserLoginController::class, 'login'])->name('login');
 
    Route::get('/home', [HomeController::class, 'index'])
        ->name('index')
        ->middleware('user');
});


Route::middleware(['auth', 'user'])->group(function () {
    // Route::get('/latetime', '\App\Http\Controllers\AttendanceController@indexLatetime')->name('indexLatetime');
    Route::get('/leave', '\App\Http\Controllers\User\LeaveUserController@index')->name('user.leave');
    Route::get('/leave/create', '\App\Http\Controllers\User\LeaveUserController@create')->name('user.leave.create');
    Route::post('/leave/store', '\App\Http\Controllers\User\LeaveUserController@store')->name('user.leave.store');
    // Route::get('/user/leave/{id}/edit', [LeaveUserController::class, 'edit'])->name('user.leave.edit');
    Route::patch('/user/leave/{id}/update', [LeaveUserController::class, 'update'])->name('user.leave.update');
    Route::get('/user/leave/{id}/show', [LeaveUserController::class, 'show'])->name('user.leave.show');
    // Route::get('/overtime', '\App\Http\Controllers\User\LeaveController@indexOvertime')->name('indexOvertime');

});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/attendance', [AttendanceUserController::class, 'index'])->name('user.check');
    Route::post('/attendance', [AttendanceUserController::class, 'create'])->name('user.check.create');
    Route::get('/attendance/status', [AttendanceUserController::class, 'status'])->name('user.check.status');
    Route::get('/break', [BreaksUserController::class, 'index'])->name('break.index');
    Route::post('/break/create', [BreaksUserController::class, 'create'])->name('break.create');
    Route::get('/break/status', [BreaksUserController::class, 'status'])->name('break.status');
});

Route::get('/attendancereport', [ManageemployeeController::class, 'showStatusPage'])->name('user.attendancereport');
Route::get('/user/attendancereport/{attendanceId}', [ManageEmployeeController::class, 'getBreakDetails']);



 Route::get('/admin/leave', [LeaveController::class, 'index'])->name('admin.leave');
 Route::post('/admin/leave/{leave}/status', [LeaveController::class, 'updateStatus'])->name('admin.leave.updateStatus');
 Route::get('/admin/User-status','\App\Http\Controllers\Admin\UserstatusController@index')->name('admin.userstatus');

    
// Route::get('/attendance/assign', function () {
//     return view('attendance_leave_login');
// })->name('attendance.login');

// Route::post('/attendance/assign', '\App\Http\Controllers\AttendanceController@assign')->name('attendance.assign');


// Route::get('/leave/assign', function () {
//     return view('attendance_leave_login');
// })->name('leave.login');

// Route::post('/leave/assign', '\App\Http\Controllers\LeaveController@assign')->name('leave.assign');


// Route::get('{any}', 'App\http\controllers\VeltrixController@index');