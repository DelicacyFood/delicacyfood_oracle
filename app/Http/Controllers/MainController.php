<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    // Controller Main Page
    public function index()
    {
        return view('start');
    }

    public function menu()
    {
        $menu = DB::table('menu')->get();
        return view('menu', compact('menu'));
    }

    // Controller Modules
    public function sweetalert()
    {
        return view('modules.sweet-alert');
    }

    // Controller Pages
    public function dashboard()
    {
        $customer = DB::selectOne("select totalCustomers as value from dual");
        // $waiter = DB::table('menu')->get();
        $sales = DB::selectOne("select numberOfOrders as value from dual");
        // $users = DB::table('users')->get();
        return view('pages.dashboard', compact('customer', 'sales'));
    }

    public function topup()
    {
        return view('pages.topup');
    }

    public function ordermenu()
    {
        return view('pages.ordermenu');
    }

    public function orderlist()
    {
        return view('pages.orderlist');
    }

    public function home()
    {
        // $user = DB::selectOne("select ('orderlist') as value from dual");
        // $totalBayar = DB::selectOne("select hitungTotalBayar(1) as value from dual");
        // $users = DB::table('users')->get();
        $users = Users::all();
        return view('pages.home', compact('users'));
    }

    public function credits()
    {
        return view('pages.credits');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function history_topup()
    {
        return view('pages.history_topup');
    }

    public function jumlah_order()
    {
        return view('pages.jumlah_order');
    }
}
