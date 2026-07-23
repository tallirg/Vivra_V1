<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BrandController extends Controller
{
    public function index()
    {
        // Acepta 'prestador', 'Prestador' o 'PRESTADOR'
        $prestadores = User::whereIn('role', ['prestador', 'Prestador', 'PRESTADOR'])->get();

        return view('admin.brands', compact('prestadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'prestador', // Asignamos el rol automáticamente
        ]);

        return redirect('/admin/brands');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect('/admin/brands');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/admin/brands');
    }
}