<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Controller Auth
    public function login()
    {
        if (session()->has('hasLogin')) {
            echo "<script>alert('You already signed in')</script>";
            return view('pages.dashboard');
        }
        return view('auth.customer.login');
    }

    public function register()
    {
        return view('auth.customer.register');
    }

    public function update_password()
    {
        return view('auth.update_password');
    }

    public function edit_profile()
    {
        return view('auth.edit_profile');
    }

    // Authenticate
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $query = DB::select('select users.user_id, users.username, users.password from users inner join customer on customer.user_id = users.user_id where users.username = ?', [$credentials['username']]);
        // dd($query);
        // Session User_id, Username, hasLogin, and Role
        if (!$query) {
            return back()->with('loginError', 'Login Failed!');
        }
        $request->session()->put('username', $credentials['username']);
        $username_user = $credentials['username'];
        $role_user = DB::selectOne("select getRoleUser('$username_user') as value from dual")->value;
        $user_id = DB::selectOne("select getUserId('$username_user') as value from dual")->value;
        $address_customer = DB::selectOne("select getAddressUser('$user_id') as value from dual")->value;

        $isCustomer = Customer::where('user_id', $query[0]->user_id)->first();
        if ($isCustomer && Hash::check($credentials['password'], $query[0]->password)) {
            $request->session()->put('hasLogin', 'true');
            $request->session()->put('role', $role_user);
            $request->session()->put('username', $username_user);
            $request->session()->put('customer_address', $address_customer);
            $request->session()->put('user_id', $user_id);
            return redirect()->intended('pages/dashboard');
        } else {
            return back()->with('loginError', 'Login Failed!');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('start');
    }

    // Register
    public function store(Request $request)
    {
        // Users
        $users = new Users;
        $temp1 = DB::selectOne("select getNewId('users') as value from dual");
        $users->user_id = $temp1->value;
        $users->password = Hash::make($request->password);
        $users->username = $request->username;
        $users->save();

        // Customer
        $customer = new Customer;
        $customer->user_id = $temp1->value;
        $customer->address = $request->alamat;
        $customer->saldo = $request->saldo;

        $customer->save();

        return redirect('auth/login')->with('success', 'Registration Success! Please Login');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }







    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //// Register dan Login Waiter
    public function login_waiter()
    {
        if (session()->has('hasLogin')) {
            echo "<script>alert('You already signed in')</script>";
            return view('pages.dashboard');
        }
        return view('auth.waiter.login_waiter');
    }


    public function register_waiter()
    {
        return view('auth.waiter.register_waiter');
    }


    public function store_waiter(Request $request)
    {
        // Users
        $users = new Users;
        $temp1 = DB::selectOne("select getNewId('users') as value from dual");
        $users->user_id = $temp1->value;
        $users->password = Hash::make($request->password);
        $users->username = $request->username;
        $users->save();

        // Waiter
        $waiter = new Waiter;
        $waiter->user_id = $temp1->value;
        $waiter->address = $request->alamat;
        $waiter->phone = $request->phone;

        $waiter->save();

        return redirect('auth/login_waiter')->with('success', 'Registration Success! Please Login');
    }

    // Authenticate Waiter
    public function authenticate_waiter(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $query = DB::select('select users.user_id, users.username, users.password from users inner join waiter on waiter.user_id = users.user_id where users.username = ?', [$credentials['username']]);
        // dd($query);
        // Session User_id, Username, hasLogin, and Role
        if (!$query) {
            return back()->with('loginError', 'Login Failed!');
        }
        $request->session()->put('username', $credentials['username']);
        $username_user = $credentials['username'];
        $role_user = DB::selectOne("select getRoleUser('$username_user') as value from dual")->value;
        $user_id = DB::selectOne("select getUserId('$username_user') as value from dual")->value;

        $isWaiter = Waiter::where('user_id', $query[0]->user_id)->first();
        if ($isWaiter && Hash::check($credentials['password'], $query[0]->password)) {
            $request->session()->put('hasLogin', 'true');
            $request->session()->put('role', $role_user);
            $request->session()->put('username', $username_user);
            $request->session()->put('user_id', $user_id);
            return redirect()->intended('pages/dashboard');
        } else {
            return back()->with('loginError', 'Login Failed!');
        }
    }







    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //// Register dan Login Driver

    public function login_driver()
    {
        if (session()->has('hasLogin')) {
            echo "<script>alert('You already signed in')</script>";
            return view('pages.dashboard');
        }
        return view('auth.driver.login_driver');
    }


    public function register_driver()
    {
        return view('auth.driver.register_driver');
    }


    public function store_driver(Request $request)
    {
        $temp1 = DB::selectOne("select getNewId('driver') as value from dual");

        // Driver
        $driver = new Driver;
        $driver->driver_id = $temp1->value;
        $driver->driver_name = $request->username;
        $driver->phone = $request->phone;
        $driver->city = $request->city;
        $driver->password = Hash::make($request->password);

        $driver->save();

        return redirect('auth/login_driver')->with('success', 'Registration Success! Please Login');
    }

    // Authenticate driver
    public function authenticate_driver(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Session User_id, Username, hasLogin, and Role
        $request->session()->put('username', $credentials['username']);
        $username_user = $credentials['username'];

        $isDriver = Driver::where('driver_name', $credentials['username'])->first();
        if ($isDriver && Hash::check($credentials['password'], $isDriver->password)) {
            $request->session()->put('hasLogin', 'true');
            $request->session()->put('role', 'driver');
            $request->session()->put('username', $username_user);
            $request->session()->put('user_id', $isDriver->driver_id);
            return redirect()->intended('pages/dashboard');
        } else {
            return back()->with('loginError', 'Login Failed!');
        }
    }
}
