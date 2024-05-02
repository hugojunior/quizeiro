<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::get('/quizzes/list', [QuizController::class, 'list'])->name('api.quizzes.list');
    Route::get('/quizzes/find/{slug}', [QuizController::class, 'find'])->name('api.quizzes.find');
    Route::get('/quizzes/{username}', [QuizController::class, 'user'])->name('api.quizzes.user');
    Route::get('/quizzes/{username}/{slug}', [QuizController::class, 'show'])->name('api.quizzes.show');
    Route::post('/quizzes/{username}/{slug}', [QuizController::class, 'store'])->name('api.quizzes.store');
    Route::get('/quizzes/{username}/{slug}/score', [QuizController::class, 'score'])->name('api.quizzes.score');
});
