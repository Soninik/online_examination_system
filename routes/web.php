<?php

use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'studentRegister')->name('register_view');
    Route::post('register_store', 'storeRegister')->name('store_register');
    Route::get('login', 'studentLogin')->name('login');
    Route::post('login_store', 'storeLogin')->name('store_login');
    Route::get('logout', 'logout')->name('logout');
    Route::get('forget_password', 'forgetPassword')->name('forgetPassword');
    Route::post('forget_password_store', 'forgetPasswordStore')->name('forget_password_store');
    Route::get('reset_password', 'resetPassword')->name('resetPassword');
    Route::post('reset_password_store', 'resetPasswordStore')->name('reset_password_store');
});



Route::middleware(['auth', 'student'])->group(function () {
    Route::controller(StudentController::class)->group(function () {
        Route::get('student', 'index')->name('student_home');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('admin', 'index')->name('admin_home');
    });

    Route::resource('subject', SubjectController::class);
});
