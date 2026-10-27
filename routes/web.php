<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Models\Employee;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Analytics: receives views / scroll / clicks from public/js/track.js
Route::post('/track', [AnalyticsController::class, 'track'])
    ->middleware('throttle:120,1')
    ->name('track');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

// ---- Auth ----
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ---- Admin ----
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        $postCount = Post::count();
        $employeeCount = Employee::count();
        $projectCount = Project::count();
        return view('admin.dashboard', compact('postCount', 'employeeCount', 'projectCount'));
    })->name('dashboard');

    Route::get('analytics', [AnalyticsController::class, 'dashboard'])->name('analytics');

    Route::resource('posts', PostController::class)->except('show');
    Route::resource('employees', EmployeeController::class)->except('show');
    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/mode', [SettingController::class, 'mode'])->name('settings.mode');
});