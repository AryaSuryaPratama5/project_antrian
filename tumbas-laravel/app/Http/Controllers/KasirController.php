<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->fs) {
            $query->where('status_pelayanan', $request->fs);
        }
        if ($request->fb) {
            $query->where('status_bayar', $request->fb);
        }
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', '%' . $request->q . '%')
                  ->orWhere('nomor_meja', 'like', '%' . $request->q . '%')
                  ->orWhere('detail_pesanan', 'like', '%' . $request->q . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $menus  = Menu::orderBy('kategori')->orderBy('nama_item')->get();

        $stats = [
            'total'           => Order::count(),
            'menunggu'        => Order::where('status_pelayanan', 'Menunggu')->count(),
            'diproses'        => Order::where('status_pelayanan', 'Diproses')->count(),
            'belum_bayar'     => Order::where('status_bayar', 'Belum Bayar')->count(),
            'pendapatan_hari' => Order::whereDate('created_at', today())->sum('total_harga'),
        ];

        $maxId = Order::max('id') ?? 0;

        return view('kasir', compact('orders', 'menus', 'stats', 'maxId'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|integer|exists:orders,id',
            'status' => 'required|in:Diproses,Siap,Selesai',
        ]);

        Order::findOrFail($request->id)->update(['status_pelayanan' => $request->status]);
        return redirect()->route('kasir');
    }

    public function toggleBayar(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:orders,id']);

        $order = Order::findOrFail($request->id);
        $order->status_bayar = ($order->status_bayar === 'Belum Bayar') ? 'Sudah Bayar' : 'Belum Bayar';
        $order->save();

        return redirect()->route('kasir');
    }

    public function toggleStok(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:menus,id']);

        $menu = Menu::findOrFail($request->id);
        $menu->status_tersedia = ($menu->status_tersedia === 'Tersedia') ? 'Habis' : 'Tersedia';
        $menu->save();

        return redirect()->route('kasir');
    }

    public function hapus($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('kasir');
    }

    public function checkNewOrders(Request $request)
    {
        $lastId = (int) $request->get('last_id', 0);
        $newOrders = Order::where('id', '>', $lastId)->get();

        return response()->json([
            'count'  => $newOrders->count(),
            'max_id' => Order::max('id') ?? $lastId,
        ]);
    }
}
