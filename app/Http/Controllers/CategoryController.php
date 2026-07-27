<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Retorna solo las categorías activas de la BD (Tours, Experiencias, Naturaleza, etc.)
        return response()->json(Category::where('active', true)->get());
    }

    public function store(Request $request)
    {
        Category::create($request->all());
        return redirect('/admin/categories');
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->all());
        return redirect('/admin/categories');
    }

    public function destroy($id)
    {
    	try {
        	\App\Models\Category::destroy($id);
        	return redirect('/admin/categories')->with('success', 'Categoría eliminada correctamente.');
    	} catch (\Illuminate\Database\QueryException $e) {
        	return redirect('/admin/categories')->with('error', 'No se puede eliminar la categoría porque tiene experiencias asociadas.');
        }
    }


}
