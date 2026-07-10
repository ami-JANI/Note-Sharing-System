<?php

use App\Http\Controllers\Admin\NoteController as AdminNoteController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PreviousQuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/semesters/{semester}', [SemesterController::class, 'show'])->name('semesters.show');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/download', [NoteController::class, 'download'])->name('notes.download');
    Route::post('/notes/{note}/unlock', [NoteController::class, 'unlock'])->name('notes.unlock');

    Route::post('/previous-questions', [PreviousQuestionController::class, 'store'])->name('previous-questions.store');
    Route::get('/previous-questions/{previousQuestion}/download', [PreviousQuestionController::class, 'download'])->name('previous-questions.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/credits/buy', [CreditController::class, 'create'])->name('credits.buy');
    Route::post('/credits/purchase', [CreditController::class, 'purchase'])->name('credits.purchase');
    Route::get('/credits/history', [CreditController::class, 'history'])->name('credits.history');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/notes/pending', [AdminNoteController::class, 'pending'])->name('notes.pending');
        Route::post('/notes/{note}/approve', [AdminNoteController::class, 'approve'])->name('notes.approve');
        Route::post('/notes/{note}/reject', [AdminNoteController::class, 'reject'])->name('notes.reject');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/unsuspend', [AdminUserController::class, 'unsuspend'])->name('users.unsuspend');
    });
});

Route::get('/users/{user}', [PublicProfileController::class, 'show'])->name('profiles.show');

require __DIR__.'/auth.php';
