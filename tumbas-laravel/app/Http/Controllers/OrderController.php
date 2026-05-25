<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meja' => 'required|integer',
            'nama' => 'required|string|max:255',
            'jenis_pesanan' => 'required|string',
            'metode_bayar' => 'required|string',
            'pilihan' => 'required|array',
        ]);

        $detail_text = "";
        $detail_items = [];
        $total = 0;

        foreach ($validated['pilihan'] as $id) {
            $menu = Menu::find($id);
            if (!$menu) continue;

            $qty = max(1, (int) $request->input('qty_' . $id, 1));
            $pedas = $request->input('pedas_' . $id, '-');
            $catatan = trim($request->input('catatan_' . $id, ''));
            $subtotal = $menu->harga * $qty;
            $total += $subtotal;

            $pedas_tag = ($pedas !== '-' && $pedas !== '') ? " [$pedas]" : '';
            $cat_tag = $catatan ? " (Catatan: $catatan)" : '';
            $detail_text .= $menu->nama_item . " x$qty" . $pedas_tag . $cat_tag . ", ";

            $detail_items[] = [
                'id' => $menu->id,
                'nama' => $menu->nama_item,
                'kategori' => $menu->kategori,
                'qty' => $qty,
                'harga' => (int) $menu->harga,
                'subtotal' => $subtotal,
                'pedas' => $pedas,
                'catatan' => $catatan,
            ];
        }

        if (empty($detail_items)) {
            return redirect()->back()->with('error', 'Silakan pilih menu terlebih dahulu.');
        }

        $detail_text = rtrim($detail_text, ', ');
        $jml_items = array_sum(array_column($detail_items, 'qty'));
        $estimasi = min(60, 10 + ($jml_items * 3));
        $status_bayar = ($validated['metode_bayar'] === 'Tunai') ? 'Belum Bayar' : 'Sudah Bayar';

        $order = Order::create([
            'nomor_meja' => $validated['meja'],
            'nama_pelanggan' => $validated['nama'],
            'jenis_pesanan' => $validated['jenis_pesanan'],
            'detail_pesanan' => $detail_text,
            'detail_json' => $detail_items,
            'total_harga' => $total,
            'metode_bayar' => $validated['metode_bayar'],
            'status_bayar' => $status_bayar,
            'status_pelayanan' => 'Menunggu',
            'estimasi_menit' => $estimasi,
        ]);

        if ($validated['metode_bayar'] !== 'Tunai') {
            return redirect()->route('order.qris', ['id' => $order->id]);
        }

        return redirect()->route('order.track', ['id' => $order->id]);
    }

    public function track($id)
    {
        $order = Order::findOrFail($id);
        return view('tracking', compact('order'));
    }

    public function qris($id)
    {
        $order = Order::findOrFail($id);
        return view('qris', compact('order'));
    }
}
