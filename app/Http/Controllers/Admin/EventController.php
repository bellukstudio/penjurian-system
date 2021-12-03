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
use Illuminate\Support\Str;

class EventController extends Controller
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
        // mengambil semua data acara dengan membawa data
        $event = Event::latest()->get();
        return view('admin.manageEvent.index', [
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
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // cari user
        $search = User::where('roles', 'USER')->latest();
        if (request('search')) {
            $search->where('name', 'like', '%' . request('search') . '%');
        }
        $user = $search->paginate(10);
        // menuju form acara
        return view(
            'admin.manageEvent.create',
            [
                'user' => $user
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi input form
        $request->validate([
            'id_user' => 'required|numeric',
            'name' => 'required|max:100',
            'name_person_responsible' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        try {
            // menyimpan data berdasarkan nama input text
            Event::create([
                'name' => $request->name,
                'name_person_responsible' => $request->name_person_responsible,
                'id_user' => $request->id_user,
                'address' => $request->address,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'token' => Str::random(50)
            ]);
            // ke halaman index dengan pesan
            return redirect()->route('manageEvents.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('eventFailed', $error);
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
        // mengambil data acara berdasarkan id
        $event = Event::findOrFail($id);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        return view('admin.manageEvent.edit', [
            'item' => $event
        ]);
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
        /// validasi form input text
        $request->validate([
            'name' => 'required|max:100',
            'name_person_responsible' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        try {
            // check jika yang mengakses url edit bukan role admin
            if (Auth::user()->roles != "ADMIN") {
                return abort(404);
            }
            // menghubah data acara berdasarkan id
            $event = Event::findOrFail($id);
            $event->name = $request->name;
            $event->name_person_responsible = $request->name_person_responsible;
            $event->address = $request->address;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->update();
            // ke halaman index dengan membawa pesan
            return redirect()->route('manageEvents.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            // kembali ke halaman sebelumnya dengan menampilkan pesan
            return back()->with('eventFailed', $error);
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
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        try {
            // ambil semua data
            $checkContest = Contest::all();
            $participantCheck = Participant::all();
            $checkJuri = Jury::all();
            $checkRedemToken = RedemToken::all();
            $checkScore = Score::all();
            /// cek jika data lomba tidak 0
            if (count($checkContest) > 0) {
                // ambil data lomba berdasarkan id event
                $getContest = Contest::where('id_event', $id)->firstOrFail();
                $contest = Contest::where('id_event', $id);
                if (count($checkScore) > 0) {
                    //hapus data score
                    $score = Score::where('id_contest', $getContest->id);
                    $score->delete();
                }
                $contest->delete();
            }
            // cek jika data peserta tidak 0
            if (count($participantCheck) > 0) {
                $participant = Participant::where('id_event', $id);
                $participant->delete();
            }
            // cek jika data juri tidak 0
            if (count($checkJuri) > 0) {
                $juri = Jury::where('id_event', $id);
                $juri->delete();
            }
            // cek jika data token tidak 0
            if (count($checkRedemToken) > 0) {
                $token = RedemToken::where('id_event', $id);
                $token->delete();
            }
            // ambil data acara berdasarkan id'
            // dan menghapusnya
            $event = Event::find($id);
            $event->delete();

            // ke halaman index dengan membawa pesan
            return redirect()->route('manageEvents.index')->with('success', 'Data Deleted');
        } catch (\Exception $error) {
            // kembali ke halaman sebelumnya dengan menampilkan pesan
            return back()->with('juriFailed', $error);
        }
    }
}
