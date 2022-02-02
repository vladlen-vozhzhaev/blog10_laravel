<?php

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
function base64_to_image($base64_string){
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );
    $image_info = getimagesize($base64_string);
    $extension = (isset($image_info["mime"]) ? explode('/', $image_info["mime"] )[1]: "");

    $output_file = 'storage/contentImage/'.time().'.'.$extension;
    $ifp = fopen( $output_file, 'xb' );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp );

    return '/'.$output_file;
}

Route::get('/', function () {
    $articles = Article::all();
    foreach ($articles as $article){
        $html = new simple_html_dom();
        $content = $article->content;
        $html->load($content);
        $article->content =  mb_substr($html->plaintext, 0, 90).'...';
    }
    return view('pages.mainPage', ['articles'=>$articles]);
})->name('main');

Route::get('/blog/{id}', function (Request $request){
    $articleId = $request->id;
    $article = Article::where('id', $articleId)->first();
    return view('pages.article', ['article'=>$article]);
});

Route::get('/addArticle', function(){
   return view('pages.addArticle');
})->middleware('auth');

Route::post('/addArticle', function (Request $request){
    $content = $request->input('content');
    $html = new simple_html_dom();
    $html->load($content);
    foreach ($html->find('img') as $img){
        if(!strripos($img->src, 'base64')) continue;
        $img->src = base64_to_image($img->src);
    }
    $content = $html->save();
    $article = null;
    if(empty($request->id)){
        $article = new Article();
    }else{
        $article = Article::where('id', $request->id)->first();
    }
    $article->title = $request->title;
    $article->content = $content;
    $article->author = $request->author;
    $article->save();
    return json_encode(['result'=>'success']);
})->middleware('auth');

Route::get('/updateArticle/{id}', function (Request $request){
    $articleId = $request->id;
    $article = Article::where('id', $articleId)->first();
   return view('pages.addArticle', ['article'=>$article]);
});
Route::get('/deleteArticle/{id}', function (Request $request){
    Article::where('id', $request->id)->delete();
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $articles = Article::all();
    return view('pages.cmsArticles', ['articles'=>$articles]);
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
