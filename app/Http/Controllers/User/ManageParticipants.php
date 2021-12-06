<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Contest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class ManageParticipants extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $event = Event::where('id_user', Auth::user()->id)->latest()->get();
        return view('user.manage_event.index', [
            'event' => $event
        ]);
    }

    /**r
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function saveParticipant($id, $idEvent)
    {
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        $contest = Contest::findOrFail($id);
        $event = Event::where('id', $idEvent)->firstOrFail();
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        /**
         * menghitung semua data peserta dan membuat id sesuai jumlah data peserta berdasarkan id_contest
         */
        $generate_id = Participant::where('id_contest', $id)->count();
        $serial_number = $generate_id + 1; // menambah angka 1 setiap jumlah yang di dapat kan dari data peserta
        return view('user.manageParticipant.create', [
            'contest' => $contest,
            'event' => $event,
            'serial_number' => $serial_number
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
        /**
         * validasi form input participant
         */
        $request->validate([
            'idContest' => 'required|numeric',
            'idEvent' => 'required|numeric',
            'phone' => 'required|numeric',
            'name' => 'required|max:100',
            'address' => 'required',
            'gender' => 'required',
            'serial_number' => 'required'
        ]);
        try {

            /**
             * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
             * jika id user != id user login 
             * akan di alikan ke halaman 404 not found
             */
            $event = Event::where('id', $request->idEvent)->firstOrFail();
            if ($event->id_user != Auth::user()->id) {
                return abort(404);
            }
            /**
             * menyimpan data peserta
             */
            Participant::create([
                'name' => $request->name,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'id_contest' => $request->idContest,
                'serial_number' => $request->serial_number,
                'id_event' => $request->idEvent,
            ]);
            return redirect()->route('manageParticipant.showParticipant', [$request->idContest, $request->idEvent])->with('success', 'Data Saved');
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
    }

    public function showParticipant($id, $idEvent)
    {
        /**
         * mengambil data peserta dengan relasi event dimana id_contest == id data yang di ambil
         */
        $participant = Participant::with(['event'])->where('id_contest', $id)->get();
        /**
         * mengambil data tunggal contest berdasarkan id yang di ambil
         */
        $contest = Contest::findOrFail($id);
        /**
         * mengambil data tunggal event berdasarkan id yang di ambil
         */
        $event = Event::findOrFail($idEvent);
        /**
         * ambil data tunggal pada tabel event dimana id event == id data contest yang di ambil
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manageParticipant.index', [
            'participant' => $participant,
            'contest' => $contest,
            'event' => $event
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }
    public function editParticipant($id, $idEvent)
    {
        /**
         * mengambil data tunggal contest dimana id_event == id data yang di ambil
         */
        $contestCheck = Contest::where('id_event', $idEvent)->firstOrFail();
        /**
         * mengambil data tunggal event dimana id event == id event dari data contest
         */
        $event = Event::where('id', $contestCheck->id_event)->firstOrFail();
        /**
         * jika id user != id user login 
         * akan di alikan ke halaman 404 not found
         */
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        $participant = Participant::findOrFail($id);
        return view('user.manageParticipant.edit', [
            'participant' => $participant,
            'contest' => $contestCheck,
            'event' => $event
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
        /**
         * validasi form input update
         */
        $request->validate([
            'id_contest' => 'required',
            'id_event' => 'required',
            'phone' => 'required|numeric',
            'name' => 'required|max:100',
            'address' => 'required',
            'gender' => 'required'
        ]);
        try {
            /**
             * mengambil data peserta berdasarkan id data yang di ambil
             * dan mengubah data sesuai value form input
             */
            $participant = Participant::findOrFail($id);
            $participant->name = $request->name;
            $participant->phone = $request->phone;
            $participant->address = $request->address;
            $participant->gender = $request->gender;
            $participant->id_contest = $request->id_contest;
            $participant->update();
            return redirect()->route('manageParticipant.showParticipant', [$request->id_contest, $request->id_event])->with('success', 'Data Updated');
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
            $participant = Participant::find($id);
            $participant->delete();
            return back()->with('success', 'Data Participant Deleted');
        } catch (\Exception $error) {
            return back()->with('participantFailed', $error);
        }
    }
}
