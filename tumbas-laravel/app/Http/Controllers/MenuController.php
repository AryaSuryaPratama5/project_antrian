<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $categories = [
            'Makanan' => '🥘 Makanan',
            'Minuman' => '🍹 Minuman Segar'
        ];

        $menusByCategory = [];
        foreach ($categories as $key => $label) {
            $menus = Menu::where('kategori', $key)->orderBy('id')->get();
            if ($menus->count() > 0) {
                $menusByCategory[$key] = [
                    'label' => $label,
                    'items' => $menus
                ];
            }
        }

        return view('menu', compact('menusByCategory'));
    }
}
