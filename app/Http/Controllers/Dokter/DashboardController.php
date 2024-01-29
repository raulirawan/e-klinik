<?php

namespace App\Http\Controllers\Dokter;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasien = Transaction::where('dokter_id', Auth::user()->id)->count();
        return view('dokter.dashboard', compact('totalPasien'));
    }
}
