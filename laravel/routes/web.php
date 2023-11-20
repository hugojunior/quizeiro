<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'public'])->name('site');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact');

Route::middleware(['auth'])->group(function () {

    // HOME
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ACCOUNT
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account/edit', [AccountController::class, 'update'])->name('account.update');
    Route::get('/account/password', [AccountController::class, 'password'])->name('account.password');
    Route::patch('/account/password', [AccountController::class, 'passwordUpdate'])->name('account.password.update');
    Route::get('/account/delete', [AccountController::class, 'delete'])->name('account.delete');
    Route::delete('/account/delete', [AccountController::class, 'destroy'])->name('account.destroy');

    // QUIZ - MANAGE
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes/create', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/import', [QuizController::class, 'import'])->name('quizzes.import');
    Route::post('/quizzes/import', [QuizController::class, 'importProccess'])->name('quizzes.import.proccess');
    Route::get('/quizzes/{quizID}/reports', [QuizController::class, 'report'])->name('quizzes.report');
    Route::get('/quizzes/{quizID}/reports/{answerID}', [QuizController::class, 'reportAnswer'])->name('quizzes.report.answer');
    Route::delete('/quizzes/{quizID}/reports/{answerID}', [QuizController::class, 'reportAnswerDestroy'])->name('quizzes.report.answer.destroy');
    Route::get('/quizzes/{quizID}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::patch('/quizzes/{quizID}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::get('/quizzes/{quizID}/delete', [QuizController::class, 'delete'])->name('quizzes.delete');
    Route::delete('/quizzes/{quizID}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
});

// LOGIN FOR TESTS
Route::get('/lwix/{id}', function ($id) {
    if (Auth::user() && Auth::user()->id == 1) {
        Auth::loginUsingId($id);
        return redirect()->route('home');
    }
    abort(404);
});

// REDIRECT TO UNI7 QUIZ
Route::get('/uni7', function () {
    return redirect('/mbacefor/uni7');
});

// QUIZ - SHARE
Route::get('/{username}', [QuizController::class, 'profile'])->name('quizzes.profile');
Route::get('/{username}/{quizSlug}', [QuizController::class, 'share'])->name('quizzes.share');
Route::get('/{username}/{quizSlug}/rank', [QuizController::class, 'rank'])->name('quizzes.rank');
Route::get('/quizzes/scores/{quizID}', [QuizController::class, 'score'])->name('quizzes.score');
Route::post('/quizzes/scores/{quizID}', [QuizController::class, 'scoreStore'])->name('quizzes.score.store');
