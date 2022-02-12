<?php

use App\Http\Controllers\ArticleController;
use App\Models\Article;
use App\Supports\simple_html_dom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|   h9eW&Y77
*/


Route::get('/', [ArticleController::class, 'showArticles'])->name('main');
Route::get('/blog/{id}',  [ArticleController::class, 'showArticle']);
Route::get('/addArticle', [ArticleController::class, 'showAddArticle'])->middleware('verified');
Route::post('/addArticle', [ArticleController::class, 'addArticle'])->middleware('verified');
Route::get('/updateArticle/{id}', [ArticleController::class, 'showUpdateArticle']);
Route::get('/deleteArticle/{id}', [ArticleController::class, 'deleteArticle']);

Route::get('/dashboard', function () {
    $articles = Article::all();
    return view('pages.cmsArticles', ['articles'=>$articles]);
})->middleware(['verified'])->name('dashboard');

require __DIR__.'/auth.php';
