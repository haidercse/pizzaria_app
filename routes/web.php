<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\CheckoutController;
use App\Http\Controllers\admin\DayTaskController;
use App\Http\Controllers\admin\DoughController;
use App\Http\Controllers\admin\DoughMakingListController;
use App\Http\Controllers\admin\EmployeeAvailabilityController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\HolidayController;
use App\Http\Controllers\admin\PrepsController;
use App\Http\Controllers\admin\ShiftManagerController;
use App\Http\Controllers\admin\TaskController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;


// Login and Authentication Routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginAll'])->name('login.post');


Route::middleware('auth')->group(function () {

    // Redirect to Dashboard on '/'
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Main Dashboard Route
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Resource Routes
    Route::resource('dough', DoughController::class)->except(['create', 'edit', 'show']);
    Route::get('dough/{id}/get', [DoughController::class, 'getDough'])->name('dough.get');


    Route::resource('users', UserController::class);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset.password');

    //profile route
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('profile-update', [AuthController::class, 'profileUpdate'])->name('profile.update');


    // Task Routes
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    //  Task opening
    Route::get('/tasks/opening', [TaskController::class, 'taskOpening'])->name('tasks.opening.index');
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.opening.toggle');

    // Closing Checklist
    Route::get('/tasks/closing', [TaskController::class, 'taskClosingIndex'])
        ->name('tasks.closing.index');
    Route::post('/tasks/{task}/toggle-closing', [TaskController::class, 'toggleClosingComplete'])
        ->name('tasks.toggle.closing');

    // Monthly Matrix View
    Route::get('/tasks/monthly-matrix', [TaskController::class, 'monthlyMatrix'])
        ->name('tasks.monthly.matrix');

    // Daily Tasks View
    Route::get('/tasks/daily-tasks', [TaskController::class, 'dailyTasks'])->name('tasks.daily');
    Route::post('/tasks/daily-tasks', [TaskController::class, 'taskDailyStore'])->name('tasks.daily.store');
    Route::put('/tasks/daily-tasks/{id}', [TaskController::class, 'taskDailyUpdate'])->name('tasks.daily.update');
    Route::delete('/tasks/daily-tasks/{id}', [TaskController::class, 'taskDailyDestroy'])->name('tasks.daily.destroy');

    //task list for user and checklist
    Route::get('/tasks/checklist', [TaskController::class, 'checklist'])->name('tasks.checklist');
    Route::post('/tasks/checklist/update', [TaskController::class, 'updateChecklist'])->name('tasks.checklist.update');

    //task list for user and checklist
    Route::get('/user/tasks/checklist', [TaskController::class, 'userChecklist'])->name('user.tasks.checklist');
    Route::post('/user/tasks/checklist/update', [TaskController::class, 'updateUserChecklist'])->name('user.tasks.checklist.update');

    Route::get('/availability', [EmployeeAvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/availability/create', [EmployeeAvailabilityController::class, 'create'])->name('availability.create');
    Route::post('/availability/store', [EmployeeAvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/availability/{id}/edit', [EmployeeAvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability/{id}', [EmployeeAvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{id}', [EmployeeAvailabilityController::class, 'destroy'])->name('availability.destroy');

    // Shift Manager Routes

    Route::get('/manager', [ShiftManagerController::class, 'index'])->name('shift-manager.index');
    Route::get('/shift-manager/ajax/{date}', [ShiftManagerController::class, 'ajaxLoad'])->name('shift-manager.ajax');
    Route::post('/shift/save', [ShiftManagerController::class, 'save'])->name('shift.save');
    Route::get('/shift/view/{employee}', [ShiftManagerController::class, 'view'])->name('shift.view');
    Route::get('/shift/show', [ShiftManagerController::class, 'shiftShow'])->name('shift.show');
    Route::get('/shift/employee', [ShiftManagerController::class, 'employeeShifts'])->name('shift.employee');
    Route::get('/availability/history/{id}', [EmployeeAvailabilityController::class, 'history'])
        ->name('shift.history');
    Route::get('/shift/all_shifts', [ShiftManagerController::class, 'allShifts'])->name('all.shifts');
    // DayTask Routes
    Route::resource('day_tasks', DayTaskController::class);


    // Checkout Routes
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/create', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/update/{id}', [CheckoutController::class, 'update'])->name('checkout.update');
    Route::get('/checkout/monthly_overview', [CheckoutController::class, 'monthlyOverview'])->name('checkout.monthly_overview');


    // Holiday Routes
    Route::resource('holidays', HolidayController::class);

    // Prep Routes
    Route::prefix('preps')->group(function () {
        // List 
        Route::get('/list', [PrepsController::class, 'list'])->name('preps.list');
        // Create page
        Route::get('/create', [PrepsController::class, 'create'])->name('preps.create');
        // Store new prep
        Route::post('/', [PrepsController::class, 'store'])->name('preps.store');
        // Edit page
        Route::get('/{id}/edit', [PrepsController::class, 'edit'])->name('preps.edit');
        // Update prep
        Route::put('/{id}', [PrepsController::class, 'update'])->name('preps.update');
        // Show single prep (/{id} should be last!)
        Route::get('/{id}', [PrepsController::class, 'show'])->name('preps.show');
        // Index page (optional, could be first or last)
        Route::get('/', [PrepsController::class, 'index'])->name('preps.index');
        Route::delete('/{id}', [PrepsController::class, 'destroy'])->name('preps.destroy');
    });


    // Dough Making List Routes
    Route::get('/dough_making/yeast_salt_list', [DoughMakingListController::class, 'YeastSaltList'])->name('dough_making.yeast_salt_list');
    Route::post('/phase-table/update-inline', [DoughMakingListController::class, 'updateInline'])->name('phase.update.inline');

    //events route
    Route::prefix('admin')->group(function () {
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    });


    // Logout Route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
