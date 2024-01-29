<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Medicine;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalObat = Medicine::count();
        $totalTransaksi = Transaction::count();
        $totalDokter = User::where('roles','DOKTER')->count();
        $totalPasien = User::where('roles','PASIEN')->count();
        return view('admin.dashboard', compact('totalObat','totalTransaksi','totalDokter','totalPasien'));
    }
}
