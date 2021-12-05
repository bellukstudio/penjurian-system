<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Jury;
use App\Models\Participant;
use App\Models\RedemToken;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManageEventByUser extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * ambil koleksi data event dimana id_user == id user login
         * dan dilempar ke halaman index
         */

        $dataEvent = Event::where('id_user', Auth::user()->id)->latest()->get();
        return view('user.manage_event.index', [
            'event' => $dataEvent
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
         * menampikan halaman form input event
         */
        return view('user.manage_event.create');
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
            'name_person_responsible' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        try {
            // cek dika form input id user tidak sama dengan id user login
            // akan menampilkan halaman 404
            if ($request->id_user != Auth::user()->id) {
                return abort(404);
            }
            /**
             * menyimpan data acara baru
             */
            Event::create([
                'name' => $request->name,
                'name_person_responsible' => $request->name_person_responsible,
                'id_user' => $request->id_user,
                'address' => $request->address,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'token' => Str::random(50)
            ]);

            return redirect()->route('manageEvent.index')->with('success', 'Data Saved');
        } catch (\Exception $error) {
            return back()->with('eventFailed', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**
         * ambil data tunggal event berdasarkan id
         * cek jika idUser pada tabel event != id user login
         * maka akan menampikan 404
         */
        $event = Event::findOrFail($id);
        if ($event->id_user != Auth::user()->id) {
            return abort(404);
        }
        return view('user.manage_event.edit', [
            'item' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * validasi form input update
         */
        $request->validate([
            'name' => 'required|max:100',
            'name_person_responsible' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        try {
            /**
             * ambil data tunggal event berdasarkan id
             * cek jika idUser pada tabel event != id user login
             * maka akan menampikan 404
             */
            $event = Event::findOrFail($id);
            if ($event->id_user != Auth::user()->id) {
                return abort(404);
            }
            /**
             * mengubah data baris sesuai form input
             */
            $event->name = $request->name;
            $event->name_person_responsible = $request->name_person_responsible;
            $event->address = $request->address;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->update();

            return redirect()->route('manageEvent.index')->with('success', 'Data Updated');
        } catch (\Exception $error) {
            return back()->with('eventFailed', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * ambil data tunggal event berdasarkan id
         * cek jika idUser pada tabel event != id user login
         * maka akan menampikan 404
         */
        $event = Event::findOrFail($id);
        if ($event->id_user != Auth::user()->id) {
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
                // ambil data tunggal contest dimana id event == id yang di ambil
                $contest = Contest::where('id_event', $id)->firstOrFail();
                // cek jika data score tidak 0
                if (count($checkScore) > 0) {
                    $score = Score::where('id_contest', $contest->id);
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
            return redirect()->route('manageEvent.index')->with('success', 'Data Deleted');
        } catch (\Exception $error) {
            return back()->with('juriFailed', $error);
        }
    }
}
