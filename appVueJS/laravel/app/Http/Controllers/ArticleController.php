<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Motcle;
use Illuminate\Support\Str;

class ArticleController extends Controller {

    protected $nbArticlesParPage = 10;

    public function __construct() {
        // pour tester la méthode 'articleAyantMotcle' sans authentification
        // $this->middleware('auth', ['except' => ['index', 'articlesAyantMotcle']]);
        $this->middleware('auth', ['except' => ['index', 'indexVue']]);
        $this->middleware('admin', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $articles = Article::with('user')
                ->orderBy('articles.created_at', 'desc')
                ->paginate($this->nbArticlesParPage);
        $links = $articles->render();
        return view('view_articles', compact('articles', 'links'));
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexVue() {
        $articles = Article::with('user')
                ->orderBy('articles.created_at', 'desc')
                ->get();
        return view('view_articles_vuejs', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('view_ajoute_article');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request) {
        $inputs = array_merge($request->all(), ['user_id' => $request->user()->id]);
        $article = Article::create($inputs);
        if (isset($inputs['motcles'])) {
            $tabMotcles = explode(',', $inputs['motcles']);
            foreach ($tabMotcles as $motcle) {
                // trim(...) enlève les espaces superflux en début et en fin de chaîne
                $motcle = trim($motcle);
                // Str::slug génère une nouvelle chaîne similaire à $motcle mais adaptée aus urls
                // adaptation des caractères accentués et/ou caractères spéciaux
                $mot_url = Str::slug($motcle);
                $mot_ref = Motcle::where('mot_url', $mot_url)->first();
                if (is_null($mot_ref)) {
                    $mot_ref = new Motcle([
                        'mot' => $motcle,
                        'mot_url' => $mot_url
                    ]);
                    $article->motcles()->save($mot_ref);
                } else {
                    $article->motcles()->attach($mot_ref->id);
                }
            }
        }
        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $article = Article::findOrFail($id);
        $article->motcles()->detach();
        $article->delete();
        return redirect()->back();
    }

    public function articlesAyantMotcle($motcle) {
        $articles = Article::with('user', 'motcles')
                        ->orderBy('articles.created_at', 'desc')
                        ->whereHas('motcles', function ($q) use ($motcle) {
                            $q->where('motcles.mot_url', $motcle);
                        })->paginate($this->nbArticlesParPage);
        //return $articles;  // pour tester rapidement que la méthode fonctionne
        $links = $articles->render();
        return view('view_articles', compact('articles', 'links'))
                        ->with('info', 'Résultats pour la recherche du mot-clé : ' . $motcle);
    }

}
