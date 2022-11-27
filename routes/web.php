<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ReportController;

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

    Route::post('periode/switch_status', [PeriodeController::class, 'switch_status'])->name('periode.switch.status');
    Route::get('periode/data', [PeriodeController::class, 'data'])->name('periode.data');
    Route::resource('periode', PeriodeController::class);
    
    Route::get('report/data', [ReportController::class, 'data'])->name('report.data');
    Route::get('report/detail/{kelas_id}/{period_id}/{student_id}/{id}', [ReportController::class, 'detail_student'])->name('report.student.show');
    Route::get('report/detail/data/{kelas_id}/{period_id}/{student_id}', [ReportController::class, 'data_detail_student'])->name('report.student.data');
    Route::get('report/student/{kelas_id}', [ReportController::class, 'student'])->name('report.student');
    Route::get('report/{kelas_id}', [ReportController::class, 'show'])->name('report.show');
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::post('report', [ReportController::class, 'store'])->name('report.store');
    Route::post('report/store', [ReportController::class, 'store'])->name('report.store.mapel');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});