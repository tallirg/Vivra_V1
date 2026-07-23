<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BrandController extends Controller
{
    public function index()
    {
        // Busca usuarios cuyo rol sea 'provider' (inglés) o 'prestador' (español)
        $prestadores = User::whereIn('role', ['provider', 'prestador'])
            ->orWhereRaw('LOWER(TRIM(role)) LIKE ?', ['%provider%'])
            ->orWhereRaw('LOWER(TRIM(role)) LIKE ?', ['%prestador%'])
            ->get();

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