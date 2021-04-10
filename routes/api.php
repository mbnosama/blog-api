<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommonQuestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*..............Articles...........................*/
Route::get('/articles',[ArticleController::class,'get_all']);
Route::get('/articles/{id}',[ArticleController::class,'get_article_by_id']);
Route::get('/articles/search/{word}',[ArticleController::class,'get_article_by_word']);
Route::post('/article/write',[ArticleController::class,'write_article'])->middleware("auth:api");
Route::put('/article/update/{id}',[ArticleController::class,'update_article'])->middleware("auth:api");;
Route::delete('/article/delete/{id}',[ArticleController::class,'destroy_article'])->middleware("auth:api");;
Route::get('article/{id}/comments',[ArticleController::class,'comments']);
/*......................................comments..................................................*/
Route::get('comments',[CommentController::class,'index'])->middleware('auth:api');
Route::post('comment/store',[CommentController::class,'store'])->middleware('auth:api');
Route::get('comment/{id}/show',[CommentController::class,'show']);
Route::post('comment/{id}/update',[CommentController::class,'update'])->middleware('auth:api');
Route::post('comment/{id}/remove',[CommentController::class,'remove'])->middleware('auth:api');

/*....................................CommonQuestion..................*/
Route::get('commonquestion',[CommonQuestionController::class,'index'])->middleware('auth:api');
Route::post('commonquestion/store',[CommonQuestionController::class,'store'])->middleware('auth:api');
Route::get('commonquestion/{id}/show',[CommonQuestionController::class,'show']);
Route::post('commonquestion/{id}/update',[CommonQuestionController::class,'update'])->middleware('auth:api');
Route::post('commonquestion/{id}/remove',[CommonQuestionController::class,'remove'])->middleware('auth:api');

/*....................for authors   ................*/
Route::post('register',[AuthorController::class,'register']);
Route::post('login',[AuthorController::class,'login']);
Route::post('logout',[AuthorController::class,'logout'])->middleware('auth:api');
