<?php

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Jury;
use App\Models\Contest;
use App\Models\Participant;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class JuriController extends Controller
{

    public function indexJury($token)
    {
        /**
         * mengambil url dan membuang tanda (/) menggunakan fungsi explode
         */
        $url = explode('/', url()->current());
        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        /**
         * mengambil data tunggal event dimana token == data url pada index ke 6
         */
        $tokenCheck = Event::where('token', $url[6])->firstOrFail();
        $startDate = date('Y-m-d', strtotime($tokenCheck->start_date));
        $endDate = date('Y-m-d', strtotime($tokenCheck->end_date));
        //checkExpired token
        /**
         * jika tanggal sekarang lebih dari tanggal berakhirnya acara
         * atau
         * jika tanggal sekarang kurang dari tanggal mulainya acara
         * maka akan kembali ke form input dengan pesan
         */
        if ($currentDate > $endDate || $currentDate < $startDate) {
            return abort(419);
        }

        // check jury
        /**
         * mengmabil data event dimana id == id dari data variabel tokenCheck
         * dimana id event ada pada tabel juri dimana id_jury == id user login
         */
        $checkJury = Event::where('id', $tokenCheck->id)->whereIn('id', function ($query) {
            $query->select('id_event')->from('juries')->where('id_jury', Auth::user()->id);
        })->get();

        // jika data 0
        if (count($checkJury) == 0) {
            return abort(404);
        }
        $event = Event::where('token', $token)->get();
        return view('juri.assesment.index', [
            'event' => $event
        ]);
    }

    public function startAssessment($token)
    {
        /**
         * mengambil url dan membuang tanda (/) menggunakan fungsi explode
         */
        $url = explode('/', url()->current());
        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        /**
         * mengambil data tunggal event dimana token == data url pada index ke 5
         */
        $tokenCheck = Event::where('token', $url[5])->firstOrFail();
        $startDate = date('Y-m-d', strtotime($tokenCheck->start_date));
        $endDate = date('Y-m-d', strtotime($tokenCheck->end_date));
        /**
         * jika tanggal sekarang lebih dari tanggal berakhirnya acara
         * atau
         * jika tanggal sekarang kurang dari tanggal mulainya acara
         * maka akan kembali ke form input dengan pesan
         */
        if ($currentDate > $endDate || $currentDate < $startDate) {
            return abort(419);
        }
        // check jury
        /**
         * mengmabil data event dimana id == id dari data variabel tokenCheck
         * dimana id event ada pada tabel juri dimana id_jury == id user login
         */
        $checkJury = Event::where('id', $tokenCheck->id)->whereIn('id', function ($query) {
            $query->select('id_event')->from('juries')->where('id_jury', Auth::user()->id);
        })->get();
        // jika data 0

        if (count($checkJury) == 0) {
            return abort(404);
        }
        // mengambil data juri dengan relasi contest dimana id Event == id event dari data variabel tokenCheck dan dimana id_jury == id user login
        $contest = Jury::with(['contest'])->where('id_event', $tokenCheck->id)->where('id_jury', Auth::user()->id)->get();

        return view('juri.assesment.start_assessment', [
            'contest' => $contest,
            'event' => $tokenCheck
        ]);
    }
    public function indexAssessment()
    {
        $checkJury = Jury::where('id_jury', Auth::user()->id)->get();
        if (count($checkJury) == 0) {
            return redirect()->route('dashboardJuri')->with('tokenFailed', 'Tidak bisa membuka halaman karena belum menjadi salah satu juri lomba');
        }
        /**
         * mengambil data contest dimana id_jury == id user login
         */
        $contest = Jury::with(['event','contest'])->where('id_jury', Auth::user()->id)->latest()->get();
        return view('juri.data_assessment.index', [
            'contest' => $contest,
        ]);
    }

    public function createAssessment($id)
    {
        // select participant where id participant and id_race not in table scores
        /**
         * mengambil data peserta dimana id contest == id data yang di ambil
         * dimana id peserta tidak ada pada tabel score dimana id jury == id user login
         * dan di tampilkan 1 data perhalaman
         */
        $participant = Participant::where('id_contest', $id)->whereNotIn('id', function ($query) {
            $query->select('id_participant')->from('scores')->where('id_jury', Auth::user()->id);
        })->paginate(1);
        // ambil data contest dimana id == id data yang di ambil
        $contest = Contest::where('id', $id)->firstOrFail();
        // check jury
        /**
         * mengmabil data event dimana id == id dari data variabel tokenCheck
         * dimana id event ada pada tabel juri dimana id_jury == id user login
         */
        $checkJury = Event::where('id', $contest->id_event)->whereIn('id', function ($query) {
            $query->select('id_event')->from('juries')->where('id_jury', Auth::user()->id);
        })->get();
        // jika data == 0
        if (count($checkJury) == 0) {
            return abort(404);
        }
        return view('juri.assesment.create_assessment', [
            'participant' => $participant,
            'contest' => $contest,

        ]);
    }
    public function saveAssessment(Request $request, $id, $idParticipant)
    {
        /**
         * mengambil data tunggal contest di mana id == id data yang di ambil
         * dan kemudian mengambil kolom assessment_aspect
         * lalu menghilangkan tanda spasi dan mengubahnya ke huruf kecil
         * kemudian data pada variabel aspect di hilangkan tanda (,) dan dimasukan ke dalam collection
         */
        $contest = Contest::where('id', $id)->firstOrFail();
        $aspect = str_replace(' ', '_', strtolower($contest->assessment_aspect));
        $collect = collect(explode(',', $aspect))->all();

        try {
            /**
             * lalu dilakukan perulangan dimana
             * data variabel a dimasukan sebagai nama form input text
             * dan menyimpannya ke dalam tabel score
             */
            foreach ($collect as $a) {
                $request->validate([
                    $a => 'required|numeric'
                ]);
                Score::create([
                    'id_contest' => $id,
                    'id_participant' => $idParticipant,
                    'id_jury' => Auth::user()->id,
                    'score' => $request->$a,
                    'assessment_aspect' => $a,
                ]);
            }
            return back()->with('success', 'Data Saved');
        } catch (\Exception $e) {
            return back()->with('assessmentFailed', $e);
        }
    }

    public function dataAssessment($id)
    {
        /**
         * mengambil data contest dimana id == id data yang diambil
         */
        $contest = Contest::where('id', $id)->firstOrFail();
        // check jury
        // check jury
        /**
         * mengmabil data event dimana id == id dari data variabel tokenCheck
         * dimana id event ada pada tabel juri dimana id_jury == id user login
         */
        $checkJury = Event::where('id', $contest->id_event)->whereIn('id', function ($query) {
            $query->select('id_event')->from('juries')->where('id_jury', Auth::user()->id);
        })->get();
        /**
         * jika data == 0
         */
        if (count($checkJury) == 0) {
            return abort(404);
        }

        /**
         * mengambil data score dan di kelompokan sesuai id_contest dan id participant
         * dengan dengan relasi lonba,peserta,user
         * dan mengambil baris
         * jumlah kolom score,
         * id participant
         * dan id jury
         * 
         * lalu di tampilkan dari data terbesar ke kecil
         * dimana id jury == id login user
         * id contest == id data yang diambil
         */
        $score = Score::groupBy('id_contest', 'id_participant')->with(['lomba', 'peserta', 'user'])->selectRaw('sum(score) as score,id_participant,id_jury')->orderBy('score', 'DESC')->where('id_contest', $id)->where('id_jury', Auth::user()->id)->get();
        return view('juri.assesment.data_assessment', [
            'data' => $score
        ]);
    }
    public function dataAssessmentIndex($id)
    {
        /**
         * mengambil data score dan di kelompokan sesuai id_contest dan id participant
         * dengan dengan relasi lonba,peserta,user
         * dan mengambil baris
         * jumlah kolom score,
         * id participant
         * dan id jury
         * 
         * lalu di tampilkan dari data terbesar ke kecil
         * dimana id jury == id login user
         * id contest == id data yang diambil
         */
        $score = Score::groupBy('id_contest', 'id_participant')->with(['lomba', 'peserta', 'user'])->selectRaw('sum(score) as score,id_participant,id_jury')->orderBy('score', 'DESC')->where('id_contest', $id)->where('id_jury', Auth::user()->id)->get();
        return view('juri.data_assessment.data_assessment', [
            'data' => $score,
            'idContest' => $id
        ]);
    }
}
