<?php

namespace App\Http\Controllers\Pasien;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaction::where('user_id', Auth::user()->id)->count();
        $transaksiSuccess = Transaction::where('user_id', Auth::user()->id)->where('status','PAID')->count();
        $transaksiPending = Transaction::where('user_id', Auth::user()->id)->whereIn('status', ['MENUNGGU PEMBAYARAN','PENDING'])->count();
        $transaksiCancel = Transaction::where('user_id', Auth::user()->id)->where('status','CANCEL')->count();
        return view('pasien.dashboard', compact('totalTransaksi','transaksiSuccess','transaksiPending','transaksiCancel'));
    }
}
