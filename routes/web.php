<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantWebController;
use App\Http\Controllers\DigitizerController;
use App\Http\Controllers\BallotController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicVoteController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Event Public Landing & Ranking
Route::get('/eventos/{slug}', [LandingController::class, 'show'])->name('events.landing');
Route::get('/eventos/{event:slug}/pantalla-gigante', \App\Livewire\Frontend\PantallaGigante::class)->name('events.stage');
Route::post('/public-vote', [PublicVoteController::class, 'store'])->name('public.vote');

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\JudgeDashboardController;
use App\Http\Controllers\ParticipantDashboardController;

use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminJudgeController;
use App\Http\Controllers\AdminBrandController;
use App\Http\Controllers\AdminParticipantController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCriterionController;
use App\Http\Controllers\AdminAuditController;

Route::middleware(['auth'])->group(function () {
    // Shared 
    Route::get('/mi-credencial', [ParticipantWebController::class, 'show'])->name('participant.profile');
    
    // SuperAdmin Dashboard
    Route::middleware(['role:SuperAdmin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Event CRUD
        Route::resource('/admin/events', AdminEventController::class)->names('admin.events');
        Route::post('/admin/events/{event}/toggle', [AdminEventController::class, 'toggle'])->name('admin.events.toggle');

        // Categories (Nested under Event)
        Route::get('/admin/events/{event}/categories', [AdminCategoryController::class, 'index'])->name('admin.events.categories.index');
        Route::post('/admin/events/{event}/categories', [AdminCategoryController::class, 'store'])->name('admin.events.categories.store');
        Route::post('/admin/events/{event}/categories/{category}/toggle', [AdminCategoryController::class, 'toggle'])->name('admin.events.categories.toggle');
        Route::delete('/admin/events/{event}/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.events.categories.destroy');

        // Criteria (Nested under Category)
        Route::get('/admin/events/{event}/categories/{category}/criteria', [AdminCriterionController::class, 'index'])->name('admin.events.categories.criteria.index');
        Route::post('/admin/events/{event}/categories/{category}/criteria', [AdminCriterionController::class, 'store'])->name('admin.events.categories.criteria.store');
        Route::post('/admin/events/{event}/categories/{category}/criteria/{criterion}/toggle', [AdminCriterionController::class, 'toggle'])->name('admin.events.categories.criteria.toggle');
        Route::delete('/admin/events/{event}/categories/{category}/criteria/{criterion}', [AdminCriterionController::class, 'destroy'])->name('admin.events.categories.criteria.destroy');

        // Staff CRUD (SuperAdmin/Digitador)
        Route::resource('/admin/users', AdminUserController::class)->names('admin.users');
        Route::post('/admin/users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('admin.users.toggle');

        // Judges CRUD (Special profile + Event Sync)
        Route::resource('/admin/judges', AdminJudgeController::class)->names('admin.judges');
        Route::post('/admin/judges/{judge}/toggle', [AdminJudgeController::class, 'toggle'])->name('admin.judges.toggle');

        // Participants CRUD
        Route::resource('/admin/participants', AdminParticipantController::class)->names('admin.participants');
        Route::post('/admin/participants/{participant}/toggle', [AdminParticipantController::class, 'toggle'])->name('admin.participants.toggle');
        Route::delete('/admin/participants/{participant}/photo', [AdminParticipantController::class, 'deletePhoto'])->name('admin.participants.deletePhoto');

        // Brands CRUD
        Route::resource('/admin/brands', AdminBrandController::class)->names('admin.brands');
        Route::post('/admin/brands/{brand}/toggle', [AdminBrandController::class, 'toggle'])->name('admin.brands.toggle');

        // Audit Module
        Route::get('/admin/audit', [AdminAuditController::class, 'index'])->name('admin.audit.index');
        Route::post('/admin/audit/{score}', [AdminAuditController::class, 'update'])->name('admin.audit.update');

        Route::get('/admin/events/{event}/print-ballots', [BallotController::class, 'print'])->name('admin.ballots.print');
        Route::get('/admin/events/{event}/control-tarima', \App\Livewire\Admin\TarimaControl::class)->name('admin.tarima.control');
        Route::get('/admin/events/{event}/tarima-settings', \App\Livewire\Admin\TarimaSettings::class)->name('admin.tarima.settings');
        Route::get('/admin/events/{event}/gallery', \App\Livewire\Admin\EventGallery::class)->name('admin.events.gallery');
        Route::get('/admin/ceremonia', \App\Livewire\Admin\TarimaIndex::class)->name('admin.tarima.index');
    });

    // Digitizer Dashboard
    Route::middleware(['role:Digitador|SuperAdmin'])->group(function () {
        Route::get('/digitizer', [DigitizerController::class, 'index'])->name('digitizer.index');
        Route::get('/digitizer/details', [DigitizerController::class, 'getCategoryDetails']);
        Route::get('/digitizer/check-existing', [DigitizerController::class, 'checkExisting']);
        Route::get('/digitizer/categories/{category}/criteria', [DigitizerController::class, 'getCriteria']);
        Route::post('/digitizer', [DigitizerController::class, 'store']);
    });

    // Judge Dashboard
    Route::middleware(['role:Juez|SuperAdmin'])->group(function () {
        Route::get('/juez/panel', [JudgeDashboardController::class, 'index'])->name('judge.dashboard');
        Route::get('/juez/evaluar/{event}', [JudgeDashboardController::class, 'evaluate'])->name('judge.evaluate');
    });

    // Participant Dashboard
    Route::middleware(['role:Participante|SuperAdmin'])->group(function () {
        Route::get('/mi-portal', [ParticipantDashboardController::class, 'index'])->name('participant.dashboard');
    });
});
