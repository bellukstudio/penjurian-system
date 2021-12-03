<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dataAssessment()
    {
        /**
         * mengambil data score dan di kelompokan sesuai id_contest, id participant , id_jury
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
        // $score = Score::groupBy('id_participant', 'id_jury', 'id_contest')->with(['lomba', 'peserta', 'user'])->selectRaw('sum(scores) as score,id_participant,id_jury,id_contest')->orderBy('score', 'DESC')->latest();
        /**
         * search data lomba    
         */
        
        $search = Score::join('contests', 'scores.id_contest', '=', 'contests.id')->join('events','contests.id_event','=','events.id')
            ->join('users', 'scores.id_jury', '=', 'users.id')->join('participants', 'scores.id_participant', '=', 'participants.id')
            ->select(DB::raw('SUM(scores.score) as total_score'),DB::raw('AVG(scores.score) as average'), 'contests.assessment_aspect as aspek', 'contests.name as name_contest','participants.name as name_participants','participants.id as id_participants','events.name as name_event')->groupBy('scores.id_participant','scores.id_contest')->latest('scores.created_at');
        if (request('searchContestName') && request('searchEventName')) {
            $search->where('contests.name', 'like', '%' . request('searchContestName') . '%')->where('events.name','like','%'.request('searchEventName').'%');
        }
        $score = $search->paginate(10);
        return view('user.report.dataAssessment', [
            'data' => $score
        ]);
    }
}
