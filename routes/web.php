<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\AnfragenController;
use App\Http\Controllers\EventplanungController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\VacationController;
use App\Models\VacationType;
use App\Http\Controllers\InternalEmailController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DeckelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RabattController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AusbildungController;
use App\Http\Controllers\AusbildungAkteController;
use App\Http\Controllers\EquipmentController;

require __DIR__.'/auth.php';



Route::middleware('auth')->group(function () {
    // Role routes
    Route::get('/admin/member/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/admin/member/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/admin/member/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/admin/member/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/admin/member/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/attendance/toggle', [AttendanceController::class, 'toggle'])->name('attendance.toggle');

    Route::prefix('admin/verwaltung')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/vacations', [VacationController::class, 'adminIndex'])->name('vacations.index');
        Route::post('/vacations', [VacationController::class, 'adminStore'])->name('vacations.store');
        Route::get('/vacations/{vacation}/edit', [VacationController::class, 'adminEdit'])->name('vacations.edit');
        Route::put('/vacations/{vacation}', [VacationController::class, 'adminUpdate'])->name('vacations.update');
    });

    Route::prefix('admin/verwaltung')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('/rabatte', RabattController::class);
    });

    // Suggestion routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('suggestions', SuggestionController::class);
        Route::post('suggestions/{suggestion}/vote', [SuggestionController::class, 'vote'])
            ->name('suggestions.vote');
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('equipment/{id}/history', [EquipmentController::class, 'history'])->name('equipment.history');
        Route::resource('equipment', EquipmentController::class);
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        // Ausbildung routes
        Route::resource('ausbildungen', AusbildungController::class);
        
        // Ausbildung Akte routes
        Route::get('ausbildungen_akte/{id}', [AusbildungAkteController::class, 'show'])
            ->name('ausbildungen_akte.show');
        Route::put('ausbildungen_akte/{id}/content', [AusbildungAkteController::class, 'updateContent'])
            ->name('ausbildungen_akte.updateContent');
        Route::post('ausbildungen_akte/upload-image', [AusbildungAkteController::class, 'uploadImage'])
             ->name('ausbildungen_akte.uploadImage');
    });

    Route::prefix('admin/verwaltung')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/deckel', [DeckelController::class, 'index'])->name('deckel.index');
        Route::post('/deckel/delete-location', [DeckelController::class, 'deleteLocation'])->name('deckel.delete-location');
        
        // Rabatt routes
        Route::get('/rabatt', [RabattController::class, 'index'])->name('rabatt.index');
    });

    Route::prefix('admin/verwaltung')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::get('vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::post('vehicles/{vehicle}/fuel', [VehicleController::class, 'fuel'])->name('vehicles.fuel');
    });

    Route::prefix('admin/finance')->name('admin.finance.')->middleware(['auth'])->group(function () {
        Route::get('/salaries', [SalaryController::class, 'index'])->name('salaries.index');
        Route::get('/salaries/{id}/history', [SalaryController::class, 'getHistory'])->name('salaries.history');
        Route::post('/salaries/entry', [SalaryController::class, 'storeEntry'])->name('salaries.entry');
        Route::post('/salaries/payout', [SalaryController::class, 'processPayout'])->name('salaries.payout');
    });

    // Finance routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::post('/finance/store', [FinanceController::class, 'store'])->name('finance.store');
    });

    // User routes
    Route::resource('users', UserController::class);
    Route::get('/admin/member/team', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::prefix('admin/member/training')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('training.index');
        Route::post('/', [TrainingController::class, 'store'])->name('training.store');
        Route::post('/{id}/register', [TrainingController::class, 'register'])->name('training.register');
        Route::post('/{id}/unregister', [TrainingController::class, 'unregister'])->name('training.unregister');
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/announcements', [DashboardController::class, 'getAnnouncements'])->name('dashboard.announcements');
        Route::post('/dashboard/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    Route::prefix('admin/member/urlaub')->middleware(['auth'])->group(function () {
        Route::get('/', [VacationController::class, 'index'])->name('vacation.index');
        Route::post('/store', [VacationController::class, 'store'])->name('vacation.store');
        Route::delete('/{id}', [VacationController::class, 'destroy'])->name('vacation.destroy');
        Route::patch('/{id}/status', [VacationController::class, 'updateStatus'])->name('vacation.status');
    });

    // Anfragen routes
    Route::get('/admin/planung/anfragen', [AnfragenController::class, 'index'])->name('anfragen.index');
    Route::post('/admin/planung/anfragen', [AnfragenController::class, 'store'])->name('anfragen.store');
    Route::post('/admin/planung/anfragen/update-status', [AnfragenController::class, 'updateStatus'])->name('anfragen.updateStatus');

    // Eventplanung routes with consistent prefix
    Route::get('/admin/planung/eventplanung', [EventplanungController::class, 'index'])
        ->name('eventplanung.index');
    Route::get('/admin/planung/eventplanung/{id}', [EventplanungController::class, 'show'])
        ->name('eventplanung.show');
    Route::post('/admin/planung/eventplanung/duplicate/{id}', [EventplanungController::class, 'duplicate'])
        ->name('eventplanung.duplicate');
    Route::delete('/admin/planung/eventplanung/{id}', [EventplanungController::class, 'destroy'])
        ->name('eventplanung.delete');
    Route::post('/admin/planung/eventplanung/update-content/{id}', [EventplanungController::class, 'updateContent'])
        ->name('eventplanung.updateContent');
    Route::post('/admin/planung/eventplanung/upload-image', [EventplanungController::class, 'uploadImage'])
        ->name('eventplanung.uploadImage');
    Route::post('/admin/planung/eventplanung/{id}/update-teams', [EventplanungController::class, 'updateTeams'])
        ->name('eventplanung.updateTeams');
    Route::post('/admin/planung/eventplanung/{id}/register', [EventplanungController::class, 'register'])
        ->name('eventplanung.register');
    Route::delete('/admin/planung/eventplanung/unregister/{id}', [EventplanungController::class, 'unregister'])
        ->name('eventplanung.unregister');
    Route::post('/admin/planung/eventplanung/{id}/save-times', [EventplanungController::class, 'saveTimes'])
        ->name('eventplanung.saveTimes');
    Route::get('/admin/planung/eventplanung/{id}/total-hours', [EventplanungController::class, 'getTotalHours'])
        ->name('eventplanung.getTotalHours');

    // Employee routes
    Route::get('/admin/member/profile/{id}', [EmployeeController::class, 'show'])->name('employee.show');
    Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::put('/employee/{id}/rank', [EmployeeController::class, 'updateRank'])->name('employee.updateRank');

    // Document routes
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::post('/documents/delete', [DocumentController::class, 'delete'])->name('documents.delete');

    // Note routes
    Route::post('/notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.delete');

    // Other routes
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('/home', fn() => view('dashboards.analytics'))->name('home');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});