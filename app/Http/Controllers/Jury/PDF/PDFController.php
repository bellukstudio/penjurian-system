<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// menggunakan library  barryvdh/laravel-dompdf
use PDF;

class PDFController extends Controller
{
    public function exportPdfAssesmentJury($id)
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
        $score = Score::groupBy('id_contest', 'id_participant')->with(['lomba', 'peserta', 'user'])->selectRaw('sum(scores) as score,id_participant,id_jury')->orderBy('score', 'DESC')->where('id_contest', $id)->where('id_jury', Auth::user()->id)->get();
        // melempar data ke tampilan
        view()->share('data', $score);
        // memuat file html data assessment content
        $pdf = PDF::loadView('juri.pdf.export_dataAssessment_contest');
        // diberi nama dan di download
        return $pdf->download('assessmentJury.pdf');
    }
}
      // $score =  Score::join('contests', 'scores.id_contest', '=', 'contests.id')
        //     ->join('users', 'scores.id_jury', '=', 'users.id')->join('participants', 'scores.id_participant', '=', 'participants.id')
        //     ->select('users.id as id_jury', 'users.name as user_name', DB::raw('SUM(scores.score) as total_score'), 'contests.assessment_aspect as aspek', 'contests.name as name_contest', 'participants.name as name_participants', 'participants.id as id_participants')->groupBy('scores.id_participant', 'scores.id_jury', 'scores.id_contest')->latest('scores.created_at');
        // // melempar data ke tampilan
        // view()->share('data', $score);