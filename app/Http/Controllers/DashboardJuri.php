<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Jury;
use App\Models\RedemToken;
use Illuminate\Support\Facades\Auth;

class DashboardJuri extends Controller
{
    //
    public function index()
    {
        /**
         * cek data pada tabel redem token di mana id_jury == id user yang login
         * jika datanya  ada
         * kemudian ambil data tunggal di tabel redem token dimana id_juty == id user yang login
         *  dan di alihkan ke halaman indexJury dengan membawa data token
         * jika kosong akan dialhikan ke halaman index
         */
        $checkRedemToken = RedemToken::where('id_jury', Auth::user()->id)->get();
        if (count($checkRedemToken) > 0) {
            $getToken = RedemToken::where('id_jury', Auth::user()->id)->firstOrFail();
            return redirect()->route('juriAssessment.indexJury', [
                'token' => $getToken->token
            ]);
        }
        return view('juri.index');
    }
    public function validateToken(Request $request)
    {
        // valiasi form input token
        $request->validate([
            'token' => 'required|string'
        ]);
        try {
            /**
             * ambil koleksi data event dimana kolom token == form input token
             * jika token salah / tidak ada
             * maka akan di alikan ke form input kembali dengan membawa pesan token undefined
             */
            $event = Event::where('token', $request->token)->get();
            if (count($event) == 0) {
                return back()->with('tokenFailed', 'Token undefined');
            }
            // check expired token
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            // mengambil data tunggal event dimana kolom token == form input token
            $token = Event::where('token', $request->token)->firstOrFail();
            // ambil data tunggal event dengan kolom start_date
            $startDate = date('Y-m-d', strtotime($token->start_date));
            // ambil data tunggal event dengan kolom end_date
            $endDate = date('Y-m-d', strtotime($token->end_date));
            /**
             * jika tanggal sekarang lebih dari tanggal berakhirnya acara
             * atau
             * jika tanggal sekarang kurang dari tanggal mulainya acara
             * maka akan kembali ke form input dengan pesan
             */
            if ($currentDate > $endDate || $currentDate < $startDate) {
                return back()->with('tokenFailed', "Token expired or The event hasn't started");
            }
            /**
             * ambil koleksi data juri dimana id_event  == id event dari data tunggal tabel event di atas
             */
            $juri = Jury::where('id_event', $token->id)->where('id_jury', Auth::user()->id)->get();
            /**
             * jika data 0 / tidak ada
             * maka akan kembali ke form input
             */
            if (count($juri) == 0) {
                return back()->with('tokenFailed', "Can't access event");
            }
            // jika semua validasi benar
            // data token di simpan
            RedemToken::create([
                'id_jury' => Auth::user()->id,
                'id_event' => $token->id,
                'token' => $request->token
            ]);
            // di arahkan ke index jury dengan membawa token
            return redirect()->route('juriAssessment.indexJury', [
                'token' => $request->token
            ]);
        } catch (\Exception $error) {
            // jika gagal di alihkan ke halaman form input token dengan pesan error
            return back()->with('tokenFailed', $error, 404);
        }
    }

    public function destroyToken($token)
    {
        /**
         * fungsi untuk menghapus token pada tabel redem token dimana id_token == parameter token
         * dan id_jury == id user login
         */
        try {
            $getToken = RedemToken::where('token', $token)->where('id_jury', Auth::user()->id);
            $getToken->delete();
            return redirect()->route('dashboardJuri');
        } catch (\Exception $error) {
            return redirect()->route('dashboardJuri')->with('tokenFailed', $error, 404);
        }
    }
}
