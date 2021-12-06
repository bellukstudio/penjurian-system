<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index()
    {

        /**
         * fungsi untuk meredirect ketika tab di close
         * dan sudah login maka akan langsung mengarah
         * ke halaman sesuai role
         */
        if (Auth::user() == null) {
            return view('auth.register');
        } else if (Auth::user()->roles == "ADMIN") {
            return redirect()->route('dashboardAdmin');
        } else if (Auth::user()->roles == "USER") {
            return redirect()->route('dashboardUser');
        } else if (Auth::user()->roles == "JURI") {
            return redirect()->route('dashboardJuri');
        }
    }

    public function register(Request $request)
    {
        try {
            //validasi form input
            $request->validate([
                'fullName' => 'required|max:100',
                'email' => 'required|email:dns',
                'password' => 'required'
            ]);
            // menyimpan daata user baru dengan roles USER
            User::create([
                'name' => $request->fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => 'USER',
            ]);
            // di alihkan ke halaman login
            return redirect()->route('login')->with('success', 'Register Success, Please Login!');
        } catch (\Exception $error) {
            // jika gagal di alihkan ke halaman register kembali
            return back()->with('registerFailed', $error);
        }
    }
}
