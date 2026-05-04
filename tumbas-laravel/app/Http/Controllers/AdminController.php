<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // --- MENU CRUD ---
    public function menuIndex()
    {
        $menus = Menu::orderBy('kategori')->orderBy('nama_item')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function menuCreate()
    {
        return view('admin.menus.create');
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'nama_item' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_item', 'harga', 'kategori', 'deskripsi']);
        $data['status_tersedia'] = 'Tersedia';

        if ($request->hasFile('gambar')) {
            $imageName = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('img'), $imageName);
            $data['gambar'] = $imageName;
        } else {
            $data['gambar'] = 'default.jpg';
        }

        Menu::create($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function menuEdit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.edit', compact('menu'));
    }

    public function menuUpdate(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $request->validate([
            'nama_item' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_item', 'harga', 'kategori', 'deskripsi', 'status_tersedia']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika bukan default
            if ($menu->gambar != 'default.jpg' && file_exists(public_path('img/'.$menu->gambar))) {
                unlink(public_path('img/'.$menu->gambar));
            }
            $imageName = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('img'), $imageName);
            $data['gambar'] = $imageName;
        }

        $menu->update($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function menuDelete($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->gambar != 'default.jpg' && file_exists(public_path('img/'.$menu->gambar))) {
            unlink(public_path('img/'.$menu->gambar));
        }
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    // --- USER CRUD ---
    public function userIndex()
    {
        $users = User::orderBy('role')->get();
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:4',
            'nama' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'username' => 'required|unique:users,username,'.$id,
            'nama' => 'required',
            'role' => 'required',
        ]);

        $data = [
            'username' => $request->username,
            'nama' => $request->nama,
            'role' => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function userDelete($id)
    {
        if (session('staff_id') == $id) {
            return back()->with('error', 'Tidak bisa menghapus diri sendiri.');
        }
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    // --- ORDER CRUD ---
    public function orderIndex(Request $request)
    {
        $query = \App\Models\Order::query();

        if ($request->q) {
            $query->where('nama_pelanggan', 'like', '%'.$request->q.'%')
                  ->orWhere('id', 'like', '%'.$request->q.'%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDelete($id)
    {
        \App\Models\Order::findOrFail($id)->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
