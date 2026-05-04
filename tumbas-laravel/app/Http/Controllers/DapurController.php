<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DapurController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status_pelayanan', ['Menunggu', 'Diproses', 'Siap'])
            ->orderByRaw("FIELD(status_pelayanan, 'Menunggu', 'Diproses', 'Siap')")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('dapur', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|integer|exists:orders,id',
            'status' => 'required|in:Diproses,Siap,Selesai',
        ]);

        $order = Order::findOrFail($request->id);
        $order->status_pelayanan = $request->status;
        $order->save();

        return redirect()->route('dapur');
    }
}
