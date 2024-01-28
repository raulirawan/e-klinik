<?php

namespace App\Http\Controllers\Pasien;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query();
            $query->where('user_id', Auth::user()->id);
            $query->with(['pasien', 'dokter']);
            return DataTables::of($query)
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d F Y H:i:s');
                })
                ->editColumn('booking_date', function ($row) {
                    return Carbon::parse($row->booking_date)->format('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'PENDING') {
                        return '<span class="badge bg-warning">PENDING</span>';
                    } elseif ($row->status == 'MENUNGGU PEMBAYARAN') {
                        return '<span class="badge bg-warning">MENUNGGU PEMBAYARAN</span>';
                    } elseif ($row->status == 'PAID') {
                        return '<span class="badge bg-success">PAID</span>';
                    } else {
                        return '<span class="badge bg-danger">CANCEL</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pasien.transaction.detail', $row->id) . '" class="btn btn-info btn-sm"><i class="bi bi-trash"></i> Detail</a>
                    ';

                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('pasien.transaction.index');
    }

    public function detail($transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)->firstOrFail();
        return view('pasien.transaction.detail', compact('transaction'));
    }

    public function payment(Request $request)
    {

    }
}
