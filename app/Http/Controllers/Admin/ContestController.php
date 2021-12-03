<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Jury;
use App\Models\Participant;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContestController extends Controller
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
        // mengambil semua data lomba relasi acara
        // yang dibuat pada model contest
        $contest = Contest::with(['acara'])->latest()->get();
        return view('admin.manageContest.index', [
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
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // cari acara jika klik tombol cari
        $search = Event::latest();
        if (request('search')) {
            $search->where('name', 'like', '%' . request('search') . '%');
        }
        // mengambil semua data acara dengan membawa data
        $event = $search->paginate(10);
        // halaaman form  input lomba
        return view('admin.manageContest.create', [
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
        // cek validasi form
        $request->validate([
            'name' => 'required|max:100',
            'id_user' => 'required',
            'type' => 'required',
            'id_event' => 'required',
            'nameEvent' => 'required',
            'assessment_aspect' => 'required|max:255'
        ]);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        try {
            // simpan data 
            Contest::create([
                'name' => $request->name,
                'type' => $request->type,
                'id_event' => $request->id_event,
                'id_user' => $request->id_user,
                'assessment_aspect' => $request->assessment_aspect
            ]);
            // halaman index
            return redirect()->route('manageContests.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('contestFailed', $error);
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
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // cari acara jika klik tombol cari
        $search = Event::latest();
        if (request('search')) {
            $search->where('name', 'like', '%' . request('search') . '%');
        }
        // mengambil semua data acara dengan membawa data
        $event = $search->paginate(10);

        $contest = Contest::findOrFail($id);
        //ambil data event berdasarkan id event pada table contest
        $getEvent = Event::findOrFail($contest->id_event);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        return view('admin.manageContest.edit', [
            'contest' => $contest,
            'event' => $event,
            'getEvent' => $getEvent
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
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        //cek validasi form
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'id_event' => 'required',
            'assessment_aspect' => 'required|max:255'

        ]);
        try {
            //update data contest berdasarkan id contest
            $contest = Contest::findOrFail($id);
            $contest->name = $request->name;
            $contest->id_event = $request->id_event;
            $contest->type = $request->type;
            $contest->assessment_aspect = $request->assessment_aspect;
            $contest->update();
            // halaman index

            return redirect()->route('manageContests.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('contestFailed', $error);
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
        try {
            // ambil data contest berdasarkan id
            $contest = Contest::find($id);
            // check jika yang mengakses url edit bukan role admin
            if (Auth::user()->roles != "ADMIN") {
                return abort(404);
            }
            $contest->delete();
            $checkParticipant = Participant::all();
            $checkJury = Jury::all();
            $checkScore = Score::all();
            if (count($checkParticipant) > 0) {
                $participant = Participant::where('id_contest', $id);
                $participant->delete();
            }
            if (count($checkJury) > 0) {
                $jury = Jury::where('id_contest', $id);
                $jury->delete();
            }
            if (count($checkScore) > 0) {
                $score = Score::where('id_contest', $id);
                $score->delete();
            }
            return back()->with('success', 'Data Contest Deleted');
        } catch (\Exception $error) {
            return back()->with('contestFailed', $error);
        }
    }
}
