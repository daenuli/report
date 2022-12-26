<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\UserController;

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
    return view('front.index');
});

Route::post('store', [StudentController::class, 'store_api'])->name('store');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/auth', [AuthController::class, 'auth']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('home', function () {
        return view('home.index');
    })->name('home');
    
    Route::get('subject/data', [SubjectController::class, 'data'])->name('subject.data');
    Route::resource('subject', SubjectController::class);
    
    Route::get('extra/data', [ExtracurricularController::class, 'data'])->name('extra.data');
    Route::resource('extra', ExtracurricularController::class);

    Route::get('kelas/data', [KelasController::class, 'data'])->name('kelas.data');
    Route::resource('kelas', KelasController::class);

    Route::get('student/data', [StudentController::class, 'data'])->name('student.data');
    Route::resource('student', StudentController::class);

    Route::get('user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);

    Route::post('periode/switch_status', [PeriodeController::class, 'switch_status'])->name('periode.switch.status');
    Route::get('periode/data', [PeriodeController::class, 'data'])->name('periode.data');
    Route::resource('periode', PeriodeController::class);
    
    Route::get('report/data', [ReportController::class, 'data'])->name('report.data');
    Route::get('report/detail/{kelas_id}/{period_id}/{student_id}/{id}', [ReportController::class, 'detail_student'])->name('report.student.show');
    Route::get('report/print/{id}', [ReportController::class, 'print'])->name('report.print');
    // Route::get('report/print/{kelas_id}/{period_id}/{student_id}/{id}', [ReportController::class, 'detail_student'])->name('report.student.show');
    Route::get('report/subject/data/{id}', [ReportController::class, 'data_subject_student'])->name('report.student.subject');

    Route::get('report/extra/data/{id}', [ReportController::class, 'data_extra_student'])->name('report.student.extra');
    Route::get('report/extra/edit/{id}', [ReportController::class, 'extra_edit'])->name('report.student.extra.edit');
    Route::post('report/extra/update', [ReportController::class, 'extra_update'])->name('report.student.extra.update');
    Route::get('report/extra/delete/{id}', [ReportController::class, 'extra_delete'])->name('report.student.extra.delete');


    Route::get('report/subject/edit/{id}', [ReportController::class, 'subject_edit'])->name('report.student.subject.edit');
    Route::post('report/subject/update', [ReportController::class, 'subject_update'])->name('report.student.subject.update');
    Route::get('report/subject/delete/{id}', [ReportController::class, 'subject_delete'])->name('report.student.subject.delete');
    Route::get('report/student/{kelas_id}', [ReportController::class, 'student'])->name('report.student');
    
    Route::get('report/student-list/{kelas_id}', [ReportController::class, 'student_list'])->name('report.student.list');
    Route::post('report/student-select/{kelas_id}', [ReportController::class, 'select_student'])->name('report.student.select');
    
    Route::get('report/teacher-list/{kelas_id}', [ReportController::class, 'teacher_list'])->name('report.teacher.list');
    Route::post('report/teacher-select/{kelas_id}', [ReportController::class, 'select_teacher'])->name('report.teacher.select');

    Route::get('report/teacher-check/{kelas_id}', [ReportController::class, 'check_teacher'])->name('report.teacher.check');


    Route::get('report/{kelas_id}', [ReportController::class, 'show'])->name('report.show');
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::post('report', [ReportController::class, 'store'])->name('report.store');
    Route::post('report/store', [ReportController::class, 'store'])->name('report.store.mapel');

    Route::post('report/store/extra', [ReportController::class, 'store_extra'])->name('report.store.extra');
    Route::post('report/attendance/{student_id}/{id}', [ReportController::class, 'attendance'])->name('report.attendance');
    
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});