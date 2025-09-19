<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\DoughController;
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
    Route::resource('dough', DoughController::class);
    Route::resource('users', UserController::class);
    Route::get('users/restore-password/{id}', [UserController::class, 'restorePassword'])->name('users.restore.password');

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
    Route::get('/tasks/user', [TaskController::class, 'dailyTaskListForUser'])->name('tasks.user');
    Route::post('/tasks/{id}/toggle-complete', [TaskController::class, 'toggleCompleteForUser'])->name('tasks.toggleComplete.user');
    // Logout Route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
