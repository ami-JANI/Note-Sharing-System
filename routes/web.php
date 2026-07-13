<?php

use App\Http\Controllers\Admin\NoteController as AdminNoteController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PreviousQuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public — guests can browse approved notes (they're prompted to log in to unlock).
Route::get('/browse', [BrowseController::class, 'index'])->name('browse.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/semesters/{semester}', [SemesterController::class, 'show'])->name('semesters.show');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::get('/notes/{note}/download', [NoteController::class, 'download'])->name('notes.download');
    Route::post('/notes/{note}/unlock', [NoteController::class, 'unlock'])->name('notes.unlock');
    Route::post('/notes/{note}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/notes/{note}/visibility', [NoteController::class, 'toggleVisibility'])->name('notes.visibility');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    Route::post('/previous-questions', [PreviousQuestionController::class, 'store'])->name('previous-questions.store');
    Route::get('/previous-questions/{previousQuestion}/download', [PreviousQuestionController::class, 'download'])->name('previous-questions.download');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/credits/buy', [CreditController::class, 'create'])->name('credits.buy');
    Route::post('/credits/purchase', [CreditController::class, 'purchase'])->name('credits.purchase');
    Route::get('/credits/history', [CreditController::class, 'history'])->name('credits.history');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/notes/pending', [AdminNoteController::class, 'pending'])->name('notes.pending');
        Route::post('/notes/{note}/approve', [AdminNoteController::class, 'approve'])->name('notes.approve');
        Route::post('/notes/{note}/reject', [AdminNoteController::class, 'reject'])->name('notes.reject');

        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
        Route::post('/reviews/{review}/hide', [AdminReviewController::class, 'hide'])->name('reviews.hide');
        Route::post('/reviews/{review}/delete', [AdminReviewController::class, 'delete'])->name('reviews.delete');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/unsuspend', [AdminUserController::class, 'unsuspend'])->name('users.unsuspend');
    });
});

Route::get('/users/{user}', [PublicProfileController::class, 'show'])->name('profiles.show');

require __DIR__.'/auth.php';
