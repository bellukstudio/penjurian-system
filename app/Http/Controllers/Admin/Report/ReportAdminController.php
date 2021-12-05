<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportAdminController extends Controller
{
    public function dataUser(Request $request)
    {
        $search = DB::table('users');

        // search role
        if ($request->isMethod('POST')) {

            // jika button di klik
            if ($request->has('searchBtn')) {
                $search->where('roles', request('searchRoles'));
            }

            // jika button unduh di klik
            if ($request->has('exportPdf')) {
                $request->validate([
                    'searchRoles' => 'required'
                ]);
                $data = $search->where('roles', request('searchRoles'))->get();
                $pdf = PDF::loadview('admin.report.pdf.export_data_user', [
                    'data' => $data,
                ])->setPaper('a4', 'landscape');
                // diberi nama dan di download
                return $pdf->download('export_user_roles_' . request('searchRoles') . '.pdf');
            }

            $data = $search->paginate(10);
            return view('admin.report.data_user', [
                'data' => $data
            ]);
        }
        $data = $search->paginate(10);

        return view('admin.report.data_user', [
            'data' => $data
        ]);
    }

    public function dataEvent(Request $request)
    {
        $search = Event::join('users', 'events.id_user', '=', 'users.id')
            ->select('events.name as name_event', 'events.name_person_responsible as pr', 'events.address as address_event', 'events.start_date as start_event', 'events.end_date as end_event', 'users.name as user_name')->where('roles', 'USER');

        if ($request->isMethod('POST')) {

            // klik tombol searchBtn
            if ($request->has('searchBtn')) {
                $search->where('users.name', 'like', '%' . request('search') . '%');
            }

            // export pdf
            if ($request->has('exportPdf')) {
                $data =   $search->where('users.name', 'like', '%' . request('search') . '%')->get();
                $pdf = PDF::loadview('admin.report.pdf.export_data_event', [
                    'data' => $data,
                ])->setPaper('a4', 'landscape');
                // diberi nama dan di download
                return $pdf->download('export_event_user_' . request('search') . '.pdf');
            }

            // data
            $data = $search->paginate(10);
            return view('admin.report.data_event', [
                'data' => $data
            ]);
        }
        // data
        $data = $search->paginate(10);
        return view('admin.report.data_event', [
            'data' => $data
        ]);
    }

    public function dataContest(Request $request)
    {
        // ambil data lomba di mana id user == id user login
        $search = Contest::join('events', 'contests.id_event', '=', 'events.id')->join('users', 'events.id_user', '=', 'users.id')
            ->select('contests.id as id_contest', 'contests.name as contest_name', 'contests.type as type_contest', 'contests.assessment_aspect as contest_aspect', 'events.name as event_name', 'users.name as user_name');
        // jika ada permintaan post
        if ($request->isMethod('POST')) {
            // jika tombol searchBtn di klik
            if ($request->has('searchBtn')) {
                $search->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('users.name', 'like', '%' . request('searchUserName') . '%');
            }
            // export pdf
            if ($request->has('exportPdf')) {
                //validasi form
                $request->validate([
                    'searchEventName' => 'required',
                    'searchUserName' => 'required',
                ]);
                $data = $search->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('users.name', 'like', '%' . request('searchUserName') . '%')->get();
                $pdf = PDF::loadview('admin.report.pdf.export_data_contest', [
                    'data' => $data,
                    'nameEvent' => request('searchEventName'),
                    'nameUser' => request('searchUserName'),
                ])->setPaper('a4', 'landscape');
                // diberi nama dan di download
                return $pdf->download('export_contest_' . request('searchEventName') . '_' . request('searchUserName') . '.pdf');
            }

            // 
            $contest = $search->latest('contests.created_at')->paginate(10);
            return view('admin.report.data_contest', [
                'data' => $contest
            ]);
        }
        $contest = $search->latest('contests.created_at')->paginate(10);
        return view('admin.report.data_contest', [
            'data' => $contest
        ]);
    }
    public function dataAssessment(Request $request)
    {
        /**
         * mengambil data score dan di kelompokan sesuai id_contest, id participant , id_jury
         * dan mengambil baris
         * jumlah kolom score,
         * id participant
         * dan id jury
         * 
         */
        /**
         * search data lomba dengan join table
         * jika terdapat permintaan post
         * 
         * dan jika tombol search btn di klik dan input text dengan nama
         * searchContestName dan searchEventName
         * maka akan mencari data yang mengandung nilai yang terdapat pada ke 2 input text tersebut
         * 
         * jika tombol exportPdf di klik
         * maka akan
         */
        $search = Score::join('contests', 'scores.id_contest', '=', 'contests.id')->join('events', 'contests.id_event', '=', 'events.id')
            ->join('users', 'events.id_user', '=', 'users.id')->join('participants', 'scores.id_participant', '=', 'participants.id')
            ->select('users.email as email_user', DB::raw('SUM(scores.score) as total_score'), DB::raw('AVG(scores.score) as average'), 'contests.assessment_aspect as aspek', 'contests.name as name_contest', 'participants.name as name_participants', 'participants.id as id_participants', 'events.name as name_event');

        if ($request->isMethod('POST')) {

            if ($request->has('searchBtn')) {
                $search->groupBy('scores.id_participant', 'scores.id_contest', 'scores.id_jury')->where('contests.name', 'like', '%' . request('searchContestName') . '%')
                    ->where('events.name', 'like', '%' . request('searchEventName') . '%')
                    ->where('users.email', 'like', '%' . request('searchEmailUser') . '%')->latest('scores.created_at');
            }
            if ($request->has('exportPdf')) {
                $request->validate([
                    'searchContestName' => 'required',
                    'searchEventName' => 'required',
                    'searchEmailUser' => 'required|email:dns'
                ]);
                // query serach
                /**
                 * mengambil data score dan di kelompokan sesuai id_contest, id participant , id_jury
                 * dan mengambil baris
                 * jumlah kolom score,
                 * id participant
                 * 
                 */
                $querySearch = $search->where('contests.name', 'like', '%' . request('searchContestName') . '%')->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('users.email', 'like', '%' . request('searchEmailUser') . '%');
                $data = $querySearch->groupBy('scores.id_participant', 'scores.id_contest')->orderBy('average', 'DESC')->get();

                // rata rata tertinggi
                $maxAverage =  Score::join('contests', 'scores.id_contest', '=', 'contests.id')
                    ->join('events', 'contests.id_event', '=', 'events.id')
                    ->join('participants', 'scores.id_participant', '=', 'participants.id')
                    ->join('users', 'events.id_user', '=', 'users.id')
                    ->select(DB::raw('SUM(scores.score) as total_score'), DB::raw('AVG(scores.score) as average'), 'participants.name as name_participants',)
                    ->where('contests.name', 'like', '%' . request('searchContestName') . '%')
                    ->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('users.email', 'like', '%' . request('searchEmailUser') . '%')
                    ->groupBy('scores.id_participant', 'scores.id_contest')->orderBy('average', 'DESC')
                    ->limit(1)->firstOrFail();
                // rata rata terendah
                $minAverage = Score::join('contests', 'scores.id_contest', '=', 'contests.id')
                    ->join('events', 'contests.id_event', '=', 'events.id')
                    ->join('participants', 'scores.id_participant', '=', 'participants.id')
                    ->join('users', 'events.id_user', '=', 'users.id')
                    ->select(DB::raw('SUM(scores.score) as total_score'), DB::raw('AVG(scores.score) as average'), 'participants.name as name_participants',)
                    ->where('contests.name', 'like', '%' . request('searchContestName') . '%')
                    ->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('users.email', 'like', '%' . request('searchEmailUser') . '%')
                    ->groupBy('scores.id_participant', 'scores.id_contest')->orderBy('average', 'ASC')
                    ->limit(1)->firstOrFail();

                //mengirim data ke halaman export pdf assement dengan membawa data
                $pdf = PDF::loadView('user.report.pdf.export_pdf_assessment', [
                    'data' => $data,
                    'nameContest' => request('searchContestName'),
                    'nameEvent' => request('searchEventName'),
                    'maxAverage' => $maxAverage,
                    'minAverage' => $minAverage
                ])->setPaper('a4', 'landscape');
                // diberi nama dan di download
                return $pdf->download('pdf_export_assessment_' . request('searchEventName') . '_' . request('searchContestName') . '.pdf');
            }
            $search->groupBy('scores.id_participant', 'scores.id_contest', 'scores.id_jury')->latest('scores.created_at');
            $score = $search->paginate(10);
            return view('admin.report.data_asssessment', [
                'data' => $score
            ]);
        }

        $search->groupBy('scores.id_participant', 'scores.id_contest', 'scores.id_jury')->latest('scores.created_at');
        $score = $search->paginate(10);
        return view('admin.report.data_asssessment', [
            'data' => $score
        ]);
    }
    public function dataParticipant(Request $request)
    {
        // ambil data peserta dengan join tabel events dan participants
        /** 
         * nama peserta,
         * jenis kelamin,
         * nomor telepon
         * alamat,
         * nama acara,
         * nama lomba
         * 
         * dimnana id user pada tabel event == id user login
         */
        $search = Participant::join('events', 'participants.id_event', '=', 'events.id')
            ->join('contests', 'participants.id_contest', '=', 'contests.id')
            ->select('participants.name as name_participant', 'participants.gender as gender_participant', 'participants.phone as phone_participant', 'participants.address as address_participant', 'events.name as name_event', 'contests.name as name_contest');
        if ($request->isMethod('POST')) {
            //jika klik tombol search btn
            if ($request->has('searchBtn')) {
                $search->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('contests.name', 'like', '%' . request('searchContestName') . '%');
            }
            // export pdf
            if ($request->has('exportPdf')) {
                $request->validate([
                    'searchContestName' => 'required',
                    'searchEventName' => 'required'
                ]);
                $data = $search->where('events.name', 'like', '%' . request('searchEventName') . '%')->where('contests.name', 'like', '%' . request('searchContestName') . '%')->get();
                $pdf = PDF::loadview('admin.report.pdf.export_data_participant', [
                    'data' => $data,
                    'nameEvent' => request('searchEventName'),
                    'nameContest' => request('searchContestName'),
                ])->setPaper('a4', 'landscape');
                // diberi nama dan di download
                return $pdf->download('export_participants_' . request('searchEventName') . '_' . request('searchContestName') . '.pdf');
            }
            // menampilkan data yang dicari
            $participant = $search->latest('participants.created_at')->paginate(10);
            return view('admin.report.data_participant', [
                'data' => $participant,
            ]);
        }
        // menampilkan data yang dicari
        $participant = $search->latest('participants.created_at')->paginate(10);
        return view('admin.report.data_participant', [
            'data' => $participant,
        ]);
    }
}
