<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Jury;
use App\Models\Contest;
use App\Models\RedemToken;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageJuriByUser extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * ambil data user dimana kolom refs == id user login
         */
        $juri = User::where('refs', Auth::user()->id)->latest()->get();
        return view('user.manage_juri.index', [
            'juri' => $juri
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * ke halaman form input juri
         */
        return view('user.manage_juri.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * validasi form input
         */
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        try {
            // menyimpan data user dengan roles JURI
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => 'JURI',
                'refs' => Auth::user()->id
            ]);
            return redirect()->route('manageJuri.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('juriFailed', $error);
        }
    }

    public function storeJuryToContest(Request $request)
    {
        /**
         * fungsi untuk menyimpan juri ke sesuai data lomba
         * validasi form input
         */
        $request->validate([
            'id_juri' => 'required',
            'id_contest' => 'required',
            'id_event' => 'required',
            'nama_juri' => 'required'
        ]);
        try {
            /**
             * cek id juri dimana id_jury == value form input id _juri
             */
            $check = Jury::where('id_jury', $request->id_juri)->where('id_contest', $request->id_contest)->get();
            /**
             * jika data juri dengan id jury tidak 0
             */
            if (count($check) > 0) {
                return back()->with('juryFailed', 'Duplicated entry');
            }
            /**
             * ambil data tunggal dimana id == value form input id event
             * dan cek jika id user pada data event yang di ambil != id user login
             * akan menampilkan halaman 404
             */
            $event = Event::where('id', $request->id_event)->firstOrFail();
            if ($event->id_user != Auth::user()->id) {
                return abort(404);
            }
            // menyimpan data juri untuk lomba
            Jury::create([
                'id_jury' => $request->id_juri,
                'id_contest' => $request->id_contest,
                'id_event' => $request->id_event,
            ]);
            return redirect()->route('manageJuri.show', $request->id_contest)->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * ambil data koleksi juri dengan relasi user, contest, event dimana
         * id_contest pada table juri == id data yang di ambil
         */
        $data = Jury::with(['user', 'contest', 'event'])->where('id_contest', $id)->latest()->get();
        /**
         * ambil data tunggal contest dimana id contest == id data yang di ambil
         */
        $contest = Contest::where('id', $id)->firstOrFail();

        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        $event = Event::where('id', $contest->id_event)->firstOrFail();
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manageContest.juri.index', [
            'jury' => $data,
            'contest' => $contest
        ]);
    }

    public function saveJury($id)
    {
        /**
         * fungsi untuk ke halaman simpan juri untuk data contest
         */
        $contest = Contest::where('id', $id)->firstOrFail();
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        $event = Event::where('id', $contest->id_event)->firstOrFail();
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }

        // search data juri dimana roles == JURI dan refs == id user dari data event yang di ambil
        // dan di tampilkan berdasarkan data yang terbaru
        // ambil nama input text
        $search = User::where('roles', 'JURI')->where('refs', $event->id_user)->latest();
        if (request('searchJuri')) {
            $search->where('name', 'like', '%' . request('searchJuri') . '%');
        }
        $jury = $search->paginate(10);
        return view('user.manageContest.juri.create', [
            'contest' => $contest,
            'juri' => $jury
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ambil data tunggal user sesuai id data yang di ambil
        $juri = User::findOrFail($id);
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika refs != id user login 
         * akan di alikan ke halaman 404 not found
         */
        if ($juri->refs != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manage_juri.edit', ['item' => $juri]);
    }

    public function editJury($id)
    {
        /**
         * fungsi edit data juri pada data contest
         *  
         * ambil data contest dimana id contest  == id data yang di ambil
         */
        $contest = Contest::where('id', $id)->firstOrFail();
        // ambil data event dimana id == id event dari data contest yang di ambil
        $event = Event::where('id', $contest->id_event)->firstOrFail();
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        // ambil data tunggal jury dimana id_contest == id data yang diambil
        $data = Jury::where('id_contest', $id)->firstOrFail();
        // ambil data user tunggal dimana id == id jury dari tabel juri
        $user = User::where('id', $data->id_jury)->firstOrFail();
        // search data juri dimana roles == JURI dan refs == id user dari data event yang di ambil
        // dan di tampilkan berdasarkan data yang terbaru
        // ambil nama input text
        $search = User::where('roles', 'JURI')->where('refs', $event->id_user)->latest();
        if (request('searchJuri')) {
            $search->where('name', 'like', '%' . request('searchJuri') . '%');
        }
        // menampilkan 10 data perhalaman
        $jury = $search->paginate(10);
        return view('user.manageContest.juri.edit', [
            'data' => $data,
            'juri' => $jury,
            'contest' => $contest,
            'user' => $user
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * valdasi form input juri
         */
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email:dns',
            'password' => 'required',

        ]);
        try {
            /**
             * ambil data tunggal user dimana id user == id data yang diambil
             */
            $juri = User::findOrFail($id);
            /**
             * jika refs != id user login 
             * akan di alikan ke halaman 404 not found
             */
            if ($juri->refs != Auth::user()->id) {
                return abort(404);
            }
            /**
             * update data user sesuai value input text
             */
            $juri->password = Hash::make($request->password);
            $juri->name = $request->name;
            $juri->email = $request->email;
            $juri->update();
            return redirect()->route('manageJuri.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('juriFailed', $error);
        }
    }

    public function updateJury(Request $request, $id)
    {
        /**
         * validasi form input halaman edit juri pada data contest
         */
        $request->validate([
            'id_jury' => 'required',
            'id_contest' => 'required',
            'id_event' => 'required'
        ]);

        try {
            /**
             * ambil data juri dimana id _jury == value input text id jury
             * dan id contest == value input text id contest
             * 
             * jika data yang di input sudah ada maka akan mucul pesan
             */
            $check = Jury::where('id_jury', $request->id_jury)->where('id_contest', $request->id_contest)->get();
            if (count($check) > 0) {
                return back()->with('juryFailed', 'Duplicated entry');
            }
            // ambil data jury idmana id == id data yang di ambil
            $juri = Jury::findOrFail($id);
            // ambil data event dimana id event == id event dari data juri yang di ambil
            $event = Event::where('id', $juri->id_event)->firstOrFail();
            /**
             * jika iduser != id user login 
             * akan di alikan ke halaman 404 not found
             */
            if ($event->id_user != Auth::user()->id) {
                return abort(404);
            }
            /**
             * update data juri pada contest
             */
            $juri->id_jury = $request->id_jury;
            $juri->id_contest = $request->id_contest;
            $juri->id_event = $request->id_event;
            $juri->update();
            return redirect()->route('manageJuri.show', $request->id_contest)->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('juryFailed', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * ambil data tunggal pada tabel user dimana id user  == id data user yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        $juri = User::findOrFail($id);
        if ($juri->refs != Auth::user()->id) {
            return abort(404);
        }
        try {
            // ambil semua data tabel
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
            // hapus data user dengan id == id data yang di ambil
            $juri = User::find($id);
            $juri->delete();
            return redirect()->route('manageJuri.index')->with('success', 'Data Deleted');
        } catch (\Exception $error) {
            return back()->with('juriFailed', $error);
        }
    }

    public function destroyJury($id)
    {
        $juri = Jury::find($id);
        $event = Event::where('id', $juri->id_event)->firstOrFail();
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        try {
            // hapus data juri pada tabel juri
            $juri->delete();
            return back()->with('success', 'Data Deleted');
        } catch (\Exception $error) {
            return back()->with('juriFailed', $error);
        }
    }
}
