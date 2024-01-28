<?php

namespace App\Http\Controllers\Pasien;

use Carbon\Carbon;
use Midtrans\Snap;
use Exception;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function detail(Request $request,$transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)->firstOrFail();
        if($request->transaction_status == 'settlement') {
            Alert::success("Success","Pembayaran Berhasil!");
        }
        return view('pasien.transaction.detail', compact('transaction'));
    }

    public function payment(Request $request, Transaction $transaction)
    {
      
        if(!empty($transaction->payment_url)) {
            return redirect($transaction->payment_url);
        }
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

         // kirim ke midtrans
         $midtrans_params = [
            'transaction_details' => [
                'order_id' => $transaction->code,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'callbacks' => [
                'finish' => route('pasien.transaction.detail', $transaction->id),
            ],
            'enable_payments' => ['bca_va','permata_va','bni_va','bri_va','gopay'],
            'vtweb' => [],
        ];

        try {
            //ambil halaman payment midtrans

            $paymentUrl = Snap::createTransaction($midtrans_params)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->total_point_exchanged = $request->total_point;
            $transaction->save();

            return redirect($paymentUrl);
            //reditect halaman midtrans
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
