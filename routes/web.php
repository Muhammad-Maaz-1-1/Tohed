<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\rishtaController;
use App\Http\Controllers\visitorController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// visitors route
Route::get('/', [visitorController::class, 'index'])->name('masjid');
Route::get('/masjid', [visitorController::class, 'masjid'])->name('masjid_list');
Route::get('/masjid/detail/{id}', [visitorController::class, 'masjidDetail'])->name('masjid_detail');
Route::post('/masjid/submit', [visitorController::class, 'masjidFormSubmit'])->name('masjid_form_submit');
Route::post('/fetchmasjid', [visitorController::class, 'fetchMasjid'])->name('fetch_masjid');
Route::get('/rishta', [rishtaController::class, 'rishtaList'])->name('rishta_list');
Route::get('/rishta/detail/{id}', [rishtaController::class, 'rishtaDetail'])->name('rishta_detail');
Route::get('/rishta/form', [rishtaController::class, 'rishtaForm'])->name('rishta_form');
Route::post('/rishta/form/submit', [rishtaController::class, 'rishtaFormSubmit'])->name('rishta_form_submit');
Route::post('/fetchrishta', [rishtaController::class, 'fetchRishtaMasjid'])->name('fetch_rishta_masjid');
// Route::get('/add-records', [VisitorController::class, 'records'])->name('records');



// login
Route::get('/login', [adminController::class, 'login'])->name('login');
Route::post('/login/submit', [adminController::class, 'loginSubmit'])->name('login_admin');

// admin route
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [adminController::class, 'index'])->name('dashboard');
    Route::get('/admin/rishta', [adminController::class, 'rishta'])->name('rishta_listing');
    Route::get('/admin/rishta/approved', [adminController::class, 'rishtaApproved'])->name('rishta_approved');
    Route::get('/admin/rishta/pending', [adminController::class, 'rishtaPending'])->name('rishta_pending');
    Route::get('/admin/rishta/form', [adminController::class, 'rishtaForm'])->name('rishta_form');
    Route::get('/admin/rishta/edit/{id}', [adminController::class, 'rishtaEdit'])->name('edit_rishta');
    Route::get('/admin/rishta/approve/{id}', [adminController::class, 'rishtaApproval'])->name('rishta_approval');
    Route::get('/admin/rishta/delete/{id}', [adminController::class, 'rishtaDelete'])->name('delete_rishta');
    Route::post('/admin/rishta/update', [adminController::class, 'rishtaUpdate'])->name('rishta_form_update');
    Route::get('/admin/masjid', [adminController::class, 'masjid'])->name('courses');
    Route::get('/admin/masjid/approve/{id}', [adminController::class, 'approval'])->name('approval');
    Route::get('/admin/masjid/delete/{id}', [adminController::class, 'delete'])->name('delete_masjid');
    Route::get('/admin/masjid/edit/{id}', [adminController::class, 'edit'])->name('edit_masjid');
    Route::post('/admin/masjid/update', [adminController::class, 'update'])->name('masjid_form_update');
    Route::get('/admin/masjid/form', [adminController::class, 'masjidForm'])->name('masjid_form');
    Route::get('/admin/masjid/approved', [adminController::class, 'approvedListings'])->name('approved_listing');
    Route::get('/admin/masjid/pending', [adminController::class, 'pendingListings'])->name('pending_listing');
});
