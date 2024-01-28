<?php

namespace App\Http\Controllers\Apoteker;

use Carbon\Carbon;
use App\Models\Medicine;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query();
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
                    $btn = '<a href="' . route('apoteker.transaction.detail', $row->id) . '" class="btn btn-info btn-sm"><i class="bi bi-trash"></i> Detail</a>
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
        $dataObat = [];
        $dataLayanan = [];

        $indexObat = 0;
        $indexLayanan = 0;

        foreach ($transaction->detailMedicine as $value) {
            $dataObat[] = [
                'index' => $indexObat,
                'medicine_price' => $value->price,
                'medicine_qty' => $value->qty,
            ];
            $indexObat++;
        }

        foreach ($transaction->detailService as $value) {
            $dataLayanan[] = [
                'index' => $indexLayanan,
                'layanan_service_name' => $value->service_name,
                'layanan_qty' => $value->qty,
                'layanan_price' => $value->price,
            ];
            $indexLayanan++;
        }
        return view('admin.transaction.detail', compact('transaction', 'dataObat', 'dataLayanan'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $dataTransactionDetails = [];

        $data = $request->all();
        // for layanan
        foreach ($data['medicine_id'] as $key => $value) {
            // check obat
            $medicineId = explode('-', $data['medicine_id'][$key])[0];
            $medicine = Medicine::find($medicineId);
            if ($data['medicine_qty'][$key] > $medicine->stock) {
                Alert::error("Error", 'Stock Obat ' . $medicine->name . ' Tidak Cukup!, Coba Lagi!');
                return redirect()->route('dokter.transaction.detail', $transaction->id);
            }
            $dataTransactionDetails[] = [
                'transaction_id' => $transaction->id,
                'medicine_id' => $medicineId,
                'service_name' => NULL,
                'qty' => $data['medicine_qty'][$key],
                'price' => $data['medicine_price'][$key],
            ];
        }

        foreach ($data['layanan_service_name'] as $key => $value) {
            $dataTransactionDetails[] = [
                'transaction_id' => $transaction->id,
                'medicine_id' => NULL,
                'service_name' => $data['layanan_service_name'][$key],
                'qty' => $data['layanan_qty'][$key],
                'price' => $data['layanan_price'][$key],
            ];
        }

        if ($transaction->detailMedicine->isNotEmpty()) {
            $transaction->detailMedicine()->delete();
        }
        if ($transaction->detailService->isNotEmpty()) {
            $transaction->detailService()->delete();
        }
        $insert = DB::table('transaction_details')->insert($dataTransactionDetails);
        if ($insert) {
            // update price transaction
            $transaction->total_price = $data['total_price'];
            $transaction->medical_record = $data['medical_records'];
            $transaction->save();
            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('dokter.transaction.detail', $transaction->id);
    }

    public function updateStatus(Transaction $transaction)
    {
        $transaction->status = 'MENUNGGU PEMBAYARAN';

        if ($transaction->save()) {
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('apoteker.transaction.detail', $transaction->id);
    }
}
