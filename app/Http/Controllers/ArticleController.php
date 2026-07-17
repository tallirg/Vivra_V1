<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'brand'])->get();
        return view('admin.articles', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        Article::create($request->all());
        return redirect('/admin/articles');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $article = Article::find($id);
    $articles = Article::with(['category', 'brand'])->get();
    return view('admin.articles', compact('articles', 'article'));
    }

    public function update(Request $request, $id)
    {
       $article = Article::find($id);
    $article->update($request->all());
    return redirect('/admin/articles')->with('success', 'Actualizado'); 
    }

    public function destroy($id)
    {
        Article::destroy($id);
        return redirect('/admin/articles');
    }
}