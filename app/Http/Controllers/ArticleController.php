<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Supports\HelpMethods;
use App\Supports\simple_html_dom;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public static function showArticles(){
        $articles = Article::all();
        foreach ($articles as $article){
            $html = new simple_html_dom();
            $content = $article->content;
            $html->load($content);
            $article->content =  mb_substr($html->plaintext, 0, 90).'...';
        }
        return view('pages.mainPage', ['articles'=>$articles]);
    }
    public static function showAddArticle(){
        return view('pages.addArticle');
    }
    public static function showArticle(Request $request){
        $articleId = $request->id;
        $article = Article::where('id', $articleId)->first();
        return view('pages.article', ['article'=>$article]);
    }
    public static function showUpdateArticle(Request $request){
        $articleId = $request->id;
        $article = Article::where('id', $articleId)->first();
        return view('pages.addArticle', ['article'=>$article]);
    }
    public static function addArticle(Request $request){
        $content = $request->input('content');
        $html = new simple_html_dom();
        $html->load($content);
        foreach ($html->find('img') as $img){
            if(!strripos($img->src, 'base64')) continue;
            $img->src = HelpMethods::base64_to_image($img->src);
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
    }
    public static function deleteArticle(Request $request){
        Article::where('id', $request->id)->delete();
        return redirect()->route('dashboard');
    }
}
