<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Ordermenu;
use Illuminate\Http\Request;
use App\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdermenuController extends Controller
{
    public function ordermenu()
    {
        return view('pages.ordermenu');
    }

    public function jumlah_order($menu_id)
    {
        $menu = Menu::find($menu_id);
        return view('pages.jumlah_order', compact('menu'));
    }

    // public function save_jumlah_order(Request $request, $menu_id)
    // {

    //     $ordermenu = new Ordermenu;
    //     $ordermenu->jumlah_order = $request->jumlah_order;
    //     $ordermenu->orderlist_id = NULL;
    //     $ordermenu->menu_id = $menu_id;
    //     $ordermenu->save();

    //     return redirect('/menu');
    // }

    public function save_jumlah_order(Request $request, $menu_id)
    {
        $menu = Menu::find($menu_id);
        $jumlah_order = $request->jumlah_order;
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($menu, $menu->menu_id, $jumlah_order);

        $request->session()->put('cart', $cart);
        // dd($request->session()->get('cart');
        return redirect()->route('ordermenu');
    }

    public function getCart()
    {
        if (!session()->has('cart')) {
            return view('menu');
        }
        $oldCart = session()->get('cart');
        $cart = new Cart($oldCart);
        return view('pages.ordermenu', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice, 'totalQty' => $cart->totalQty]);
    }

    public function cancelOrdermenu()
    {
        session()->forget('cart');
    }
}