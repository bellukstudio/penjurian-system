<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Jury;
use App\Models\RedemToken;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class JuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // menampilkan data role user
        $dataJuri = User::where('roles', 'JURI')->latest()->get();
        return view('admin.manageJury.index', [
            'juri' => $dataJuri
        ]);
    }

    public function showJuryInContest($id)
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // menampilkan data juri dengan relasi contest dan event
        // dimana id_contestnya sesuai data yang di klik
        $juri = Jury::with(['contest', 'event', 'user'])->where('id_contest', $id)->latest()->get();
        $contest = Contest::findOrFail($id);
        return view('admin.manageContest.showJury', [
            'juri' => $juri,
            'contest' => $contest
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataUser = User::where('roles', 'USER')->latest();
        // jika input text dengan nama searchUser di isi
        if (request('searchUser')) {
            $dataUser->where('name', 'like', '%' . request('searchUser') . '%');
        }
        // menampilkan 10 data perhalaman
        $user =  $dataUser->paginate(10);

        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        return view('admin.manageJury.create', [
            'user' => $user
        ]);
    }

    public function createJuryInContest($idContest, $idEvent)
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // ambil data contest berdarasrakan idContest
        $contest = Contest::where('id', $idContest)->firstOrFail();
        //ambil data event berdasarkan id
        $event = Event::where('id', $idEvent)->firstOrFail();
        // ambil semua data user dengan kolom refs == iduser pada event
        $search = User::where('refs', $event->id_user)->where('roles', 'JURI')->latest();
        if (request('searchJuri')) {
            $search->where('name', 'like', '%' . request('searchJuri') . '%');
        }
        $juri = $search->paginate(10);
        return view('admin.manageContest.createJury', [
            'juri' => $juri,
            'event' => $event,
            'contest' => $contest
        ]);
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
            'id_user' => 'required',
            'nameUser' => 'required'
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
                'roles' => 'JURI',
                'refs' => $request->id_user
            ]);
            // halaman index manageJury dengan menampilkan pesan
            return redirect()->route('manageJury.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
        }
    }

    public function storeJuryForContest(Request $request)
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        //validasi form
        $request->validate([
            'id_user' => 'required',
            'nameUser' => 'required',
            'nameContest' => 'required',
            'nameEvent' => 'required',
            'id_event' => 'required',
            'id_contest' => 'required',
        ]);
        try {
            //validasi data
            $checkJury = Jury::where('id_jury', $request->id_user)->where('id_contest', $request->id_contest)->get();
            if (count($checkJury) > 0) {
                return back()->with('juryFailed', 'Duplicated entry');
            }
            // simpan data ke table jury
            Jury::create([
                'id_jury' => $request->id_user,
                'id_contest' => $request->id_contest,
                'id_event' => $request->id_event
            ]);
            return redirect()->route('manageJury.showJuryInContest', [$request->id_contest, $request->id_event])->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
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
        return view('admin.manageJury.edit', ['item' => $user]);
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
            // halaman index manageJury dengan menampilkan pesan
            return redirect()->route('manageJury.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
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
            $dataJuri = Jury::all();
            $dataToken = RedemToken::all();
            $dataScore = Score::all();


            //cek apakah data juri tidak 0
            if (count($dataJuri) > 0) {
                // hapus data juri
                $juri = Jury::where('id_jury', $id);
                $juri->delete();
            }
            //cek apakah data token tidak 0
            if (count($dataToken) > 0) {
                // hapus data redem token

                $token = RedemToken::where('id_jury', $id);
                $token->delete();
            }
            //cek apakah data score tidak 0
            if (count($dataScore) > 0) {
                // hapus data score
                $score = Score::where('id_jury', $id);
                $score->delete();
            }
            // hapus user
            $user = User::find($id);
            $user->delete();
            return redirect()->route('manageJury.index')->with('success', 'Data Deleted');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
        }
    }
}
