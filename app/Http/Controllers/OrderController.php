<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'experience'])->get();
        return view('admin.orders', compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->update(['status' => $request->status]);
        return redirect('/admin/orders');
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->update(['status' => 'cancelled']);
        return redirect('/admin/orders');
    }
}