<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with(['category', 'brand'])->get();

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json($articles, 200);
        }

        return view('admin.articles', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
{
    try {
        $data = $request->all();

        // Asignamos el ID del usuario del Token de Sanctum a brand_id
        if ($request->user()) {
            $data['brand_id'] = $request->user()->id;
            $data['user_id'] = $request->user()->id; // Por si usas ambas columnas
        }

        $article = Article::create($data);

        if ($request->has('schedules')) {
            $schedules = is_string($request->schedules) 
                ? json_decode($request->schedules, true) 
                : $request->schedules;

            if (is_array($schedules)) {
                foreach ($schedules as $scheduleData) {
                    $article->schedules()->create($scheduleData);
                }
            }
        }

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json([
                'message' => 'Experiencia creada con éxito',
                'article' => $article->load('schedules')
            ], 201);
        }

        return redirect('/admin/articles');

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error de BD: ' . $e->getMessage()
        ], 500);
    }
}

    public function show(Request $request, $id)
    {
        $article = Article::with(['category', 'brand'])->find($id);

        if (!$article) {
            if ($request->wantsJson() || $request->segment(1) === 'api') {
                return response()->json(['message' => 'No encontrada'], 404);
            }
            return back()->withErrors(['message' => 'No encontrada']);
        }

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json($article, 200);
        }

        return view('admin.articles.show', compact('article'));
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
        if ($article) {
            $article->update($request->all());
        }

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json([
                'message' => 'Actualizado exitosamente',
                'article' => $article
            ], 200);
        }

        return redirect('/admin/articles')->with('success', 'Actualizado');
    }

    public function destroy(Request $request, $id)
    {
        Article::destroy($id);

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json(['message' => 'Eliminado exitosamente'], 200);
        }

        return redirect('/admin/articles');
    }

    public function myExperiences(Request $request)
    {
        $userId = auth()->id();
        $articles = Article::where('brand_id', $userId)->get();

        if ($request->wantsJson() || $request->segment(1) === 'api') {
            return response()->json($articles, 200);
        }

        return view('admin.articles', compact('articles'));
    }

}
