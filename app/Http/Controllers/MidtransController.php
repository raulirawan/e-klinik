<?php

namespace App\Http\Controllers;

use App\Models\User;
use Midtrans\Config;
use App\Models\Point;
use App\Models\Stock;
use App\Models\Medicine;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {

        //buat instance midtrans
        //assign ke variable untuk memudahkan coding

        $status = $request->body['transaction_status'];

        $transaksi = Transaction::where('code', $request->order_id)->first();
        // handler notification status midtrans
        if ($status == "settlement") {

            $transaksi->status = 'PAID';

            // kurangin stock barang
            if ($transaksi->detailMedicine->isNotEmpty()) {
                foreach ($transaksi->detailMedicine as $value) {
                    // find medicine id
                    $medicineId = $value->medicine_id;

                    $medicine = Medicine::find($medicineId);

                    $medicine->stock = $medicine->stock - $value->qty;

                    if ($medicine->save()) {
                        // insert to historystock
                        $stock = new Stock();
                        $stock->medicine_id = $medicine->id;
                        $stock->stock = $value->qty;
                        $stock->status = 'KELUAR';
                        $stock->keterangan = 'RESEP';
                        $stock->save();
                    }
                }
            }

            // jika coin tidak 0
            $user = User::find($transaksi->user_id);
            if ($transaksi->total_point_exchanged != 0) {
                $user->point = $user->point - $transaksi->total_point_exchanged;
                $user->save();
            }
            // mendapatkan koin jika selesai transaksi
            $totalPrice = $transaksi->total_price;

            // find coin
            $point = Point::where('min_transaction', '<=', $totalPrice)->orderBy('min_transaction','DESC')->first();

            if($point) {
                $user->point = $user->point + $point->point;
                $user->save();
                $transaksi->total_point_earned = $point->point;
            }

            $transaksi->save();
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Payment Success'
                ]
            ]);
        } else if ($status == "pending") {
            $transaksi->status = 'MENUNGGU PEMBAYARAN';
            $transaksi->save();
        } else if ($status == 'deny') {
            $transaksi->status = 'CANCEL';
            $transaksi->save();
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Payment Deny'
                ]
            ]);
        } else if ($status == 'expired') {
            $transaksi->status = 'CANCEL';
            $transaksi->save();
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Payment Expired'
                ]
            ]);
        } else if ($status == 'cancel') {
            $transaksi->status = 'CANCEL';
            $transaksi->save();
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Payment Cancel'
                ]
            ]);
        } else {
            $transaksi->status = 'CANCEL';
            $transaksi->save();
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'message' => 'Midtrans Payment Gagal'
                ]
            ]);
        }
    }
}
