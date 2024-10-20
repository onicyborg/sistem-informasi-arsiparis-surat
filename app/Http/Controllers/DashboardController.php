<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\OutcomingLetter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $dates = [];
            $incoming = [];
            $outcoming = [];

            // Mendapatkan tanggal 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);  // Mengambil tanggal 7 hari terakhir
                $formattedDate = $date->format('d-m-Y'); // Format: "YYYY-MM-DD"
                $dates[] = $formattedDate;

                // Menghitung total surat masuk (incoming) berdasarkan tanggal yang sudah diformat
                $incomingCount = IncomingLetter::whereDate('tanggal_diterima', $date->format('Y-m-d'))->count();
                $incoming[] = $incomingCount;

                // Menghitung total surat keluar (outcoming) berdasarkan tanggal yang sudah diformat
                $outcomingCount = OutcomingLetter::whereDate('tanggal_dikirim', $date->format('Y-m-d'))->count();
                $outcoming[] = $outcomingCount;
            }

            $today = Carbon::now()->format('Y-m-d');

            $incomingCountToDay = IncomingLetter::whereDate('tanggal_diterima', $today)->count();
            $outcomingCountToDay = OutcomingLetter::whereDate('tanggal_dikirim', $today)->count();
            $incomingAll = IncomingLetter::all()->count();
            $outcomingAll = OutcomingLetter::all()->count();

            // dd($incoming);
            // Kirim data ke view
            return view('dashboard', [
                'dates' => $dates,
                'incoming' => $incoming,
                'outcoming' => $outcoming,
                'outcomingtoday' => $outcomingCountToDay,
                'incomingtoday' => $incomingCountToDay,
                'outcomingall' => $outcomingAll,
                'incomingall' => $incomingAll
            ]);
        }
    }
}
