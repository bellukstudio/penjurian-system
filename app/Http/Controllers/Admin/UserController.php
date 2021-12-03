<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Jury;
use App\Models\Participant;
use App\Models\RedemToken;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // menampilkan data role user
        $dataUser = User::where('roles', 'USER')->latest()->paginate(10);
        return view('admin.manageUser.index', [
            'user' => $dataUser
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        //menampilkan form tambah user
        return view('admin.manageUser.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi form
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        try {
            /// insert data user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => 'USER',
                'refs' => Auth::user()->id
            ]);
            // halaman index manageUser dengan menampilkan pesan
            return redirect()->route('manageUser.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            // kembali ke halaman sebelumnya dengan menampilkan pesan
            return back()->with('userFailed', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ambil data user dengan id
        $user = User::findOrFail($id);

        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // mengirim data ke form edit user
        return view('admin.manageUser.edit', ['item' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validasi form
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email:dns',
            'password' => 'required',

        ]);
        try {
            // check jika yang mengakses url edit bukan role admin
            if (Auth::user()->roles != "ADMIN") {
                return abort(404);
            }

            //update data user berdasarkan nama input text
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();
            // halaman index manageUser dengan menampilkan pesan
            return redirect()->route('manageUser.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            // kembali ke halaman sebelumnya dengan menampilkan pesan

            return back()->with('userFailed', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }

        try {
            $dataEvent = Event::where('id_user', $id)->get();
            // jika data acara 0
            if (count($dataEvent) == 0) {
                $user = User::find($id);
                $user->delete();
                return redirect()->route('manageUser.index')->with('success', 'Data Deleted');
            }
            //cek apakah data acara tidak 0
            if (count($dataEvent) > 0) {
                // ambil data acara berdasarkan id user
                $getAcara = Event::where('id_user', $id)->firstOrFail();
                $dataContest = Contest::all();
                $dataJuri = Jury::all();
                $dataPeserta = Participant::all();
                $dataToken = RedemToken::all();
                $dataScore = Score::all();
                $dataUser = User::all();
                //cek apakah data peserta tidak 0
                if (count($dataPeserta) > 0) {
                    // hapus data peserta
                    $peserta = Participant::where('id_event', $getAcara->id);
                    $peserta->delete();
                }

                // hapus data user dengan roles JURI dan refs id user yang  di hapus
                if (count($dataUser) > 0) {
                    $user = User::where('refs', $id);
                    $user->delete();
                }
                //cek apakah data juri tidak 0
                if (count($dataJuri) > 0) {
                    // hapus data juri
                    $juri = Jury::where('id_event', $getAcara->id);
                    $juri->delete();
                }
                // //cek apakah data token tidak 0
                if (count($dataToken) > 0) {
                    // hapus data redem token

                    $token = RedemToken::where('id_event', $getAcara->id);
                    $token->delete();
                }
                // //cek apakah data lomba tidak 0
                if (count($dataContest) > 0) {
                    // ambil data lomba berdasarkan id acara
                    $getLomba = Contest::where('id_event', $getAcara->id)->firstOrFail();
                    //cek apakah data score tidak 0
                    if (count($dataScore) > 0) {
                        // hapus data score
                        $score = Score::where('id_contest', $getLomba->id);
                        $score->delete();
                    }
                    //     //hapus data lomba
                    $lomba = Contest::where('id_user', $id);
                    $lomba->delete();
                }
                //hapus data acara
                $event = Event::where('id_user', $id);
                $event->delete();
                // hapus user
                $user = User::find($id);
                $user->delete();
                // halaman index manageUser dengan menampilkan pesan
                return redirect()->route('manageUser.index')->with('success', 'Data Deleted');
            }
            // kembali ke halaman sebelumnya dengan menampilkan pesan
            return back()->with('userFailed', 'DeleteFailed');
        } catch (\Exception $error) {
            return back()->with('userFailed', $error);
        }
    }
}
