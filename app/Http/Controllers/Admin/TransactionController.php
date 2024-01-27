<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query();
            $query->with(['pasien','dokter']);
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
                    } elseif ($row->status == 'PAID') {
                        return '<span class="badge bg-success">PAID</span>';
                    } else {
                        return '<span class="badge bg-danger">CANCEL</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('admin.transaction.detail', $row->id).'" class="btn btn-info btn-sm"><i class="bi bi-trash"></i> Detail</a>
                    ';

                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.transaction.index');
    }

    public function detail(Transaction $transaction)
    {
        return view('admin.transaction.detail', compact('transaction'));
    }
}
