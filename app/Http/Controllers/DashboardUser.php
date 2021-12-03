<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardUser extends Controller
{
    //

    public function index()
    {
        /**
         * menghitung jumlah data event berdasarkan id user login
         */
        $event = Event::where('id_user',Auth::user()->id)->count();
        /**
         * menghitung data juri dimana kolom refs == id user login
         * dan roles == JURI
         */
        $jury = User::where('refs',Auth::user()->id)->where('roles','JURI')->count();
        return view('user.index',[
            'event' => $event,
            'jury' => $jury
        ]);
    }

}
