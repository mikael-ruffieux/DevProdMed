<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $nbArticlesParPage = 4;

    public function __construct() {
        $this->middleware('auth', ['except'=>'index']);
        $this->middleware('admin', ['only'=>'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=Article::with('user')
            ->orderBy('articles.created_at','desc')
            ->paginate($this->nbArticlesParPage);
        $links=$articles->render();
        return view('view_articles', compact('articles','links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('view_ajoute_article');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request) {
        $inputs=array_merge($request->all(), ['user_id'=>$request->user()->id]);
        Article::create($inputs);
        return redirect(route('articles.index'));
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Article::findOrFail($id)->delete();  
        return redirect()->back();
    }
}