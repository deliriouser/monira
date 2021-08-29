<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
Use Alert;
use Session;
use Illuminate\Support\Facades\Cache;


class LoginController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('authentication.login-two');
    }

    public function postlogin(Request $request)
    {
        $remember_me = $request->has('remember') ? true : false;
        if (Auth::attempt(array('email' => $request->post('username'),'password' => $request->post('password'),'active' => 1),$remember_me)) {
            switch (Auth::user()->level_id) {
                case 3:
                    $status = 1;
                    return $status;
                    break;
                case 2:
                case 4:
                case 5:
                    $status = 1;
                    return $status;
                    break;
                default:
                    return redirect('/login');
            }
        }
        return redirect('/login')->with('warning', 'User ' . $request->username . ' gagal login, periksa username dan password');
    }

    public function logout()
    {
        Session::flush();
        Cache::flush();
        Auth::logout();
        return redirect('/login');
    }
}
