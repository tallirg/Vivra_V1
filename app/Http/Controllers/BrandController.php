<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands', compact('brands'));
    }

    public function store(Request $request)
    {
        Brand::create($request->all());
        return redirect('/admin/brands');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $brand->update($request->all());
        return redirect('/admin/brands');
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return redirect('/admin/brands');
    }
}