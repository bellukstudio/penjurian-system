<?php

use App\Http\Controllers\Admin\ContestController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\JuryController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardAdmin;
use App\Http\Controllers\DashboardJuri;
use App\Http\Controllers\DashboardUser;
use App\Http\Controllers\Jury\JuriController;
use App\Http\Controllers\PDF\PDFController;
use App\Http\Controllers\User\Report\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ManageContest;
use App\Http\Controllers\User\ManageEventByUser;
use App\Http\Controllers\User\ManageParticipants;
use App\Http\Controllers\User\ManageJuriByUser;
use App\Http\Controllers\Admin\Report\ReportAdminController;

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

Route::get('/', [LoginController::class, 'authRedirect']);

// Auth
Route::get('/landing', [LoginController::class, 'landing'])->name('landing');
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

Route::get('/register', [RegisterController::class, 'index'])->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('registerPost');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// End Auth

// admin route
Route::middleware('checkRoles:ADMIN')->group(function () {
    //dashboard
    Route::get('/admin', [DashboardAdmin::class, 'index'])->name('dashboardAdmin');
    // manage user
    Route::resource('/admin/manageUser', UserController::class);
    //manage juri
    Route::resource('/admin/manageJury', JuryController::class);
    // menampilkan data juri sesuai lomba
    Route::get('/admin/manageJury/{id_contest}/contest', [JuryController::class, 'showJuryInContest'])->name('manageJury.showJuryInContest');
    // form input juri untuk lomba
    Route::get('/admin/manageJury/{id_contest}/contest/{id_event}/create', [JuryController::class, 'createJuryInContest'])->name('manageJury.createJuryInContest');
    // simpan data ke table jury
    Route::post('/admin/manageJury/contest/create/store', [JuryController::class, 'storeJuryForContest'])->name('manageJury.storeJuryForContest');
    // manage acara
    Route::resource('/admin/manageEvents', EventController::class);
    // manage participant
    Route::resource('/admin/manageParticipants', ParticipantController::class);
    // menampilkan data participant
    Route::get('/admin/manageContests/{id_contest}/manageParticipants', [ParticipantController::class, 'indexParticipant'])->name('manageParticipants.indexParticipants');
    Route::get('/admin/manageContests/{id_contest}/manageParticipants/create', [ParticipantController::class, 'createParticipants'])->name('manageParticipants.createParticipants');
    // manage contest
    Route::resource('/admin/manageContests', ContestController::class);

    // report
    Route::get('/admin/report/dataUser', [ReportAdminController::class, 'dataUser'])->name('manageReportAdmin.dataUser');
    Route::post('/admin/report/dataUser', [ReportAdminController::class, 'dataUser'])->name('manageReportAdmin.dataUser');
    Route::get('/admin/report/dataEvent', [ReportAdminController::class, 'dataEvent'])->name('manageReportAdmin.dataEvent');
    Route::post('/admin/report/dataEvent', [ReportAdminController::class, 'dataEvent'])->name('manageReportAdmin.dataEvent');
    Route::get('/admin/report/dataContest', [ReportAdminController::class, 'dataContest'])->name('manageReportAdmin.dataContest');
    Route::post('/admin/report/dataContest', [ReportAdminController::class, 'dataContest'])->name('manageReportAdmin.dataContest');
    Route::get('/admin/report/dataParticipant', [ReportAdminController::class, 'dataParticipant'])->name('manageReportAdmin.dataParticipant');
    Route::post('/admin/report/dataParticipant', [ReportAdminController::class, 'dataParticipant'])->name('manageReportAdmin.dataParticipant');
    Route::get('/admin/report/dataAssessment', [ReportAdminController::class, 'dataAssessment'])->name('manageReportAdmin.dataAssessment');
    Route::post('/admin/report/dataAssessment', [ReportAdminController::class, 'dataAssessment'])->name('manageReportAdmin.dataAssessment');
});
// end admin route

// user route


Route::middleware('checkRoles:USER')->group(function () {
    //dashboard
    Route::get('/user', [DashboardUser::class, 'index'])->name('dashboardUser');
    //manage event
    Route::resource('/user/manageEvent', ManageEventByUser::class);
    //manage juri
    Route::resource('/user/manageJuri', ManageJuriByUser::class);
    // form simpan juri di data lomba
    Route::get('/user/manageJuri/jury/contest/{id}/create', [ManageJuriByUser::class, 'saveJury'])->name('manageJuri.saveJury');
    // form edit juri di data lomba
    Route::get('/user/manageJuri/jury/contest/{id}/edit', [ManageJuriByUser::class, 'editJury'])->name('manageJuri.editJury');
    // simpan juri untuk lomba
    Route::post('/user/manageJuri/jury/storeJuryToContest', [ManageJuriByUser::class, 'storeJuryToContest'])->name('manageJuri.storeJuryToContest');
    // update juri data lomba
    Route::put('/user/manageJuri/jury/updateJuryToContest/{id}', [ManageJuriByUser::class, 'updateJury'])->name('manageJuri.updateJury');
    // hapus juri data lomba
    Route::delete('/user/manageJuri/jury/deleteJuryToContest/{id}', [ManageJuriByUser::class, 'destroyJury'])->name('manageJuri.destroyJury');
    // mnage participant
    Route::resource('/user/manageParticipant', ManageParticipants::class);
    //form simpan peserta lomba
    Route::get('/user/manageParticipant/{id}/create/{id_event}', [ManageParticipants::class, 'saveParticipant'])->name('manageParticipant.saveParticipant');
    // form edit peserta lomba
    Route::get('/user/manageParticipant/{id}/edit/{id_event}', [ManageParticipants::class, 'editParticipant'])->name('manageParticipant.editParticipant');
    // menampilkan data peserta lomba
    Route::get('/user/manageParticipant/{id}/show/{id_event}', [ManageParticipants::class, 'showParticipant'])->name('manageParticipant.showParticipant');
    //manage contest
    Route::resource('/user/manageContest', ManageContest::class);
    // form simpan data lomba per acara
    Route::get('/user/manageContest/{id}/new', [ManageContest::class, 'saveContest'])->name('manageContest.saveContest');

    // report
    Route::get('/user/manageReport/dataAssessment', [ReportController::class, 'dataAssessment'])->name('manageReport.dataAssessment');
    Route::post('/user/manageReport/dataAssessment', [ReportController::class, 'dataAssessment'])->name('exportPdf.dataAssessment');
    Route::get('/user/manageReport/dataContest', [ReportController::class, 'dataContest'])->name('manageReport.dataContest');
    Route::post('/user/manageReport/dataContest', [ReportController::class, 'dataContest'])->name('manageReport.dataContest');
    Route::get('/user/manageReport/dataEvent', [ReportController::class, 'dataEvent'])->name('manageReport.dataEvent');
    Route::post('/user/manageReport/dataEvent', [ReportController::class, 'dataEvent'])->name('manageReport.dataEvent');
    Route::get('/user/manageReport/dataParticipant', [ReportController::class, 'dataParticipant'])->name('manageReport.dataParticipant');
    Route::post('/user/manageReport/dataParticipant', [ReportController::class, 'dataParticipant'])->name('manageReport.dataParticipant');
});
// // end user route

// // juri route
Route::middleware('checkRoles:JURI')->group(function () {
    //dashboard
    Route::get('/juri', [DashboardJuri::class, 'index'])->name('dashboardJuri');
    // memvalidasi token dan menyimpan token
    Route::post('/juri/validateToken', [DashboardJuri::class, 'validateToken'])->name('dashboardJuri.validateToken');
    /// menghapus token
    Route::delete('/juri/removeToken/{token}', [DashboardJuri::class, 'destroyToken'])->name('dashboardJuri.removeToken');
    // menampilkan acara yang akan di jurikan
    Route::get('/juri/assessmentJury/event/{token}', [JuriController::class, 'indexJury'])->name('juriAssessment.indexJury');
    // menampilkan data lomba yang akan di nilai
    Route::get('/juri/startAssessment/{token}', [JuriController::class, 'startAssessment'])->name('juryAssessment.startAssessment');
    // form penilaian peserta
    Route::get('/juri/createAssessment/{id}/create', [JuriController::class, 'createAssessment'])->name('juryAssessment.createAssessment');
    // menyinpan data penilaian peserta
    Route::post('/juri/saveAssessment/{id}/store/{id_participant}', [JuriController::class, 'saveAssessment'])->name('juryAssessment.saveAssessment');
    // menampilkan data penilaian
    Route::get('/juri/showAssessment/{id}/show', [JuriController::class, 'dataAssessment'])->name('juryAssessment.showAssessment');
    // menampilkan data lomba yang pernah di lakukan juri
    Route::get('/juri/indexAssessment', [JuriController::class, 'indexAssessment'])->name('juryAssessment.indexAssessment');
    // menampilkan data penilaian peserta yang pernah dilakukan juri
    Route::get('/juri/indexAssessment/{id}/show', [JuriController::class, 'dataAssessmentIndex'])->name('juryAssessment.dataAssessmentIndex');

    // export pdf
    Route::get('/juri/exportPdf/Assessment/{id}', [PDFController::class, 'exportPdfAssesmentJury'])->name('exportPdf.exportPdfAssesmentJury');
});


// end juri route
