<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
        // menampilkan form login
        return view('auth.login');
    }

    public function authRedirect()
    {
        /**
         * fungsi untuk meredirect ketika tab di close
         * dan sudah login maka akan langsung mengarah
         * ke halaman sesuai role
         */
        if(Auth::user() == null){
            return redirect()->route('login');
        }else if(Auth::user()->roles == "ADMIN"){
            return redirect()->route('dashboardAdmin');
        }else if(Auth::user()->roles == "USER"){
            return redirect()->route('dashboardUser');
        }else if(Auth::user()->roles == "JURI"){
            return redirect()->route('dashboardJuri');
        }
    }

    public function authenticate(Request $request)
    {
        // validasi form login
        $credentials =  $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        /**
         * fungsi untuk login jika form input valid
         * dan dilakukan percobaan login dengan email dan password yang di input
         * jika berhasil akan di cek data roles nya dan membuat session baru
         * dan akan di alihkan ke halaman sesuai role nya
         */
        if (Auth::attempt($credentials)) {
            if (Auth::user()->roles == 'USER') {
                $request->session()->regenerate();
                return redirect()->intended('/user');
            } else if (Auth::user()->roles == 'ADMIN') {
                $request->session()->regenerate();
                return redirect()->intended('/admin');
            } else if(Auth::user()->roles == 'JURI'){
                $request->session()->regenerate();
                return redirect()->intended('/juri');
            }else{
                return abort(404);
            }
        }

        return back()->with('loginFailed', 'Sign In Failed!');
    }

    public function logout(Request $request)
    {
        /**
         * logout
         */
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
