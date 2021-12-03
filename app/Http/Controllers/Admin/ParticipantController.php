<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
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
        // ambil data peserta dengan relasi contest dan event 
        //[latest] mengurutkan data terakhir di input agar tampil di awal
        $participant = Participant::with(['contest', 'event'])->latest()->get();
        // halaman index peserta
        return view('admin.manageParticipant.index', [
            'participant' => $participant
        ]);
    }

    public function indexParticipant($id)
    {
        $participant = Participant::with(['event', 'contest'])->where('id_contest', $id)->latest()->get();
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // ambil data lomba berdasarkan id
        $contest = Contest::findOrFail($id);

        return view('admin.manageParticipant.index', [
            'participant' => $participant,
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
    }
    public function createParticipants($id)
    {
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        // ambil data lomba berdasarkan id
        $contest = Contest::with(['acara'])->where('id', $id)->firstOrFail();
        /// membuat id sesuai data peserta berdasarkan id contest
        $generate_id = Participant::where('id_contest', $id)->count();
        $serial_number = $generate_id + 1;
        // ke halaman form input peserta
        return view('admin.manageParticipant.create', [
            'contest' => $contest,
            'serial_number' => $serial_number,
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
        //validasi form input
        $request->validate([
            'id_event' => 'required',
            'nameEvent' => 'required',
            'id_contest' => 'required',
            'nameContest' => 'required',
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'serial_number' => 'required',
            'gender' => 'required'
        ]);
        // check jika yang mengakses url edit bukan role admin
        if (Auth::user()->roles != "ADMIN") {
            return abort(404);
        }
        try {
            //simpan data peserta
            Participant::create([
                'name' => $request->name,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'id_contest' => $request->id_contest,
                'serial_number' => $request->serial_number,
                'id_event' => $request->id_event,
            ]);
            // ke halaman index peserta
            return redirect()->route('manageParticipants.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('participantFailed', $error);
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

        // ambil data peserta berdasarkan id
        $participant = Participant::findOrFail($id);
        // ambil data lomba berdasarkan id_contest pada data peserta yang dipilih
        $contest = Contest::findOrFail($participant->id_contest);
        // ambil semua data contest dengan id_event dari data contest 
        // dan di tampilkan berdasarkan data terbaru / terakhir di input
        // ke halaman edit
        // cari acara jika klik tombol cari
        $search = Contest::where('id_event', $contest->id_event)->latest();
        if (request('searchContest')) {
            $search->where('name', 'like', '%' . request('searchContest') . '%');
        }
        // mengambil semua data acara dengan membawa data
        $dataContest = $search->paginate(10);

        return view('admin.manageParticipant.edit', [
            'participant' => $participant,
            'getContest' => $contest,
            'dataContest' => $dataContest
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
        // validasi form input
        $request->validate([
            'id_contest' => 'required',
            'nameContest' => 'required',
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'gender' => 'required'
        ]);
        try {
            // update data lomba berdasarkan id peserta
            $participant = Participant::findOrFail($id);
            $participant->name = $request->name;
            $participant->phone = $request->phone;
            $participant->address = $request->address;
            $participant->gender = $request->gender;
            $participant->id_contest = $request->id_contest;
            $participant->update();
            return redirect()->route('manageParticipants.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('participantFailed', $error);
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
            /**
             * mengambil data peserta berdasarkan id data yang di ambil
             * dan menghapus datanya
             */
            $event = Participant::find($id);
            $event->delete();
            return back()->with('success', 'Data Participant Deleted');
        } catch (\Exception $error) {
            return back()->with('participantFailed', $error);
        }
    }
}
