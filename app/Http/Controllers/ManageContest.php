<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Event;
use App\Models\Jury;
use App\Models\Participant;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageContest extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * ambil data koleksi event dimana id user == id user login
         */
        $event = Event::where('id_user', Auth::user()->id)->latest()->get();
        return view('user.manage_event.index', [
            'event' => $event
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function saveContest($id)
    {
        /**
         * ambil data tunggal di mana id event == parameter id
         */
        $event = Event::findOrFail($id);
        /**
         * cek jika id user pada event tidak sama dengan id user login
         * maka akan muncul halaman 404 / not found
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manageContest.create', [
            'event' => $event
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
        // validasi form input
        $request->validate([
            'id_user' => 'required|numeric',
            'name' => 'required|max:100',
            'type' => 'required',
            'id_event' => 'required',
            'assessment_aspect' => 'required|max:255'
        ]);
        /**
         * cek jika id user pada form input dengan id user login
         * maka akan muncul halaman 404 / not found
         */
        if ($request->id_user != Auth::user()->id) {
            return abort(404);
        }
        try {
            /**
             * menyimpan data lomba
             */
            Contest::create([
                'name' => $request->name,
                'type' => $request->type,
                'id_event' => $request->id_event,
                'id_user' => $request->id_user,
                'assessment_aspect' => $request->assessment_aspect
            ]);
            return redirect()->route('manageContest.show', $request->id_event)->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('raceFailed', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Race  $race
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * mengambil data koleksi dengan relasi acara dimana id_event == parameter id
         */
        $contest = Contest::with(['acara'])->where('id_event', $id)->latest()->get();
        /**
         * ambil data tunggal event dimana id event == paramter id
         */
        $event = Event::findOrFail($id);
        /**
         * cek data jika data event id user tidak sama dengan id user login
         * akan menampilkan halaman 404
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manageContest.index', [
            'contest' => $contest,
            'event' => $event
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Race  $race
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**
         * ambil data tunggal dimana id contest == paramter id
         */
        $contest = Contest::findOrFail($id);
        /**
         * ambil data tunggal event dimana id event == data kolom id_event pada tabel contest yang di ambil
         */
        $event = Event::where('id', $contest->id_event)->firstOrFail();
        /**
         * cek data jika data event id user tidak sama dengan id user login
         * akan menampilkan halaman 404
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manageContest.edit', [
            'contest' => $contest,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Race  $race
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * validasi form input
         */
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'id_event' => 'required',
            'assessment_aspect' => 'required|max:255'

        ]);
        // ambil data event dimana id event == form input id event
        $event = Event::where('id', $request->id_event)->firstOrFail();
        /**
         * cek data jika data event id user tidak sama dengan id user login
         * akan menampilkan halaman 404
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        try {
            /**
             * ambil data tunggal contest berdasarkan parameter id
             * dan mengubah data sesuai nama baris
             */
            $contest = Contest::findOrFail($id);
            $contest->name = $request->name;
            $contest->type = $request->type;
            $contest->assessment_aspect = $request->assessment_aspect;
            $contest->update();
            return redirect()->route('manageContest.show', $request->id_event)->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('raceFailed', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Race  $race
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // ambil data tunggal contest berdasarkan id yang di ambil pada parameter
            $contest = Contest::find($id);
            /**
             * cek data jika data event id user tidak sama dengan id user login
             * akan menampilkan halaman 404
             */
            $event = Event::where('id', $contest->id_event)->firstOrFail();
            if ($event->id_user != Auth::user()->id) {
                return abort(404);
            }
            // hapus data contest
            $contest->delete();
            // mengambil semua data peserta
            $checkParticipant = Participant::all();
            // mengambil semua data pada tabel juri
            $checkJury = Jury::all();
            // mengambil semua data pada table score
            $checkScore = Score::all();
            /**
             * cek jika tabel peserta tidak kosong
             */
            if (count($checkParticipant) > 0) {
                /**
                 * ambil data peserta dimana kolom id_contest == id contest yang di ambil
                 * dan kemudian di hapus
                 */
                $participant = Participant::where('id_contest', $id);
                $participant->delete();
            }
            /**
             * cek jika tabel juri tidak kosong
             */
            if (count($checkJury) > 0) {
                /**
                 * ambil data juri dimana id _contest == id contest yang di ambil
                 * dan kemudian di hapus
                 */
                $jury = Jury::where('id_contest', $id);
                $jury->delete();
            }
            /**
             * cek jika tabel score tidak 0
             */
            if (count($checkScore) > 0) {
                /**
                 * ambil data score dimana id_contest == id contest yang di ambil
                 * dan kemudian di hapus
                 */
                $score = Score::where('id_contest', $id);
                $score->delete();
            }
            return back()->with('success', 'Data Contest Deleted');
        } catch (\Exception $error) {
            return back()->with('raceFailed', $error);
        }
    }
}
