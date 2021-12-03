<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdmin extends Controller
{
    //

    public function index()
    {
        // menampilkan jumlah data user
        $dataUser = User::where('roles', 'USER')->count();
        // menampilkan jumlah data juri
        $dataJuri = User::where('roles', 'JURI')->count();
        // menampilkan jumlah data lomba
        $dataLomba = Contest::count();
        // menampilkan jumlah data acara
        $dataAcara = Event::count();

        // mengirim data ke halaman index admin
        return view('admin.index', compact('dataUser', 'dataJuri', 'dataLomba', 'dataAcara'));
    }
}
