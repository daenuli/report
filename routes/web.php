<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/auth', [AuthController::class, 'auth']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('home', function () {
        return view('home.index');
    })->name('home');

    Route::get('subject/data', [SubjectController::class, 'data'])->name('subject.data');
    Route::resource('subject', SubjectController::class);

    Route::get('kelas/data', [KelasController::class, 'data'])->name('kelas.data');
    Route::resource('kelas', KelasController::class);

    Route::get('student/data', [StudentController::class, 'data'])->name('student.data');
    Route::resource('student', StudentController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});