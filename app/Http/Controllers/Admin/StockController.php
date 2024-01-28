<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Stock::query();
            $query->with('medicine');
            return DataTables::of($query)
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d F Y H:i:s');
            })
                ->editColumn('stock', function ($row) {
                    return number_format($row->stock);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'MASUK') {
                        return '<span class="badge bg-success">MASUK</span>';
                    };
                    return '<span class="badge bg-danger">KELUAR</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.stock.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // find obat
        $obat = Medicine::where('id', $data['medicine_id'])->first();

        if($data['status'] == 'KELUAR') {
            if ($obat->stock < $data['stock']) {
                Alert::error("Error", 'Stock Kurang, Coba Lagi!');
                return redirect()->route('admin.stock.index');
            }
        }

        if (Stock::create($data)) {
            // add table stock
            if ($data['status'] == 'MASUK') {
                $obat->stock = $obat->stock + $data['stock'];
                $obat->save();
            } else {
                $obat->stock = $obat->stock - $data['stock'];
                $obat->save();
            }
            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('admin.stock.index');
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'name' => 'unique:stocks,id,' . $stock->id
        ]);
        $data = $request->all();
        unset($data['stock']);
        if ($stock->update($data)) {
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('admin.stock.index');
    }

    // public function delete(Stock $stock)
    // {
    //     try {
    //         if ($stock->stockStock()->delete()) {
    //             $stock->delete();
    //             Alert::success("Success", 'Data Berhasil Di Delete!');
    //         } else {
    //             Alert::error("Error", 'Data Gagal Di Delete!');
    //         }
    //         return redirect()->route('admin.stock.index');
    //     } catch (\Throwable $th) {
    //         Log::error($th);
    //         Alert::error("Error", 'Data Gagal Di Delete, Ada Error Server!');
    //         return redirect()->route('admin.stock.index');
    //     }
    // }
}
