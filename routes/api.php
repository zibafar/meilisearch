<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::withoutMiddleware('api')->any(
    'course',
    CourseController::class
)->name('course');

Route::withoutMiddleware('api')->any(
    'forum',
    ForumController::class
)->name('forum');

Route::withoutMiddleware('api')->any(
    'video',
    VideoController::class
)->name('video');

Route::withoutMiddleware('api')->any(
    'assign',
    AssignmentController::class
)->name('assign');

Route::withoutMiddleware('api')->any(
    'quiz',
    QuizController::class
)->name('quiz');

Route::withoutMiddleware('api')->any(
    'search',
    SearchController::class
)->name('search');


//Route::withoutMiddleware('api')->get(
//    'post',
//    PostController::class
//)->name('post');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
