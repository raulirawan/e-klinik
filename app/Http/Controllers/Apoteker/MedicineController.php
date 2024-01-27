<?php

namespace App\Http\Controllers\Apoteker;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Medicine::query();
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button class="btn btn-info btn-sm" id="edit" data-bs-toggle="modal"
                    data-bs-target="#modal-create"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-stock="' . $row->stock . '"
                    data-harga="' . $row->harga . '"
                    ><i class="bi bi-power"></i> Edit</button>
                    <a href="' . route('admin.medicine.delete', $row->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(' . "'Yakin ?'" . ')"><i class="bi bi-trash"></i> Delete</a>
                    ';

                    return $btn;
                })
                ->editColumn('stock', function ($row) {
                    return number_format($row->stock);
                })
                ->editColumn('harga', function ($row) {
                    return number_format($row->harga);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.medicine.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'unique:medicines'
        ]);
        $data = $request->all();
        if ($medicine = Medicine::create($data)) {
            // add table stock
            $stock = new Stock();
            $stock->medicine_id = $medicine->id;
            $stock->stock = $data['stock'];
            $stock->stock = $medicine->stock;
            $stock->status = 'MASUK';
            $stock->keterangan = 'STOCK AWAL';
            $stock->save();

            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('admin.medicine.index');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'unique:medicines,id,' . $medicine->id
        ]);
        $data = $request->all();
        unset($data['stock']);
        if ($medicine->update($data)) {
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('admin.medicine.index');
    }

    public function delete(Medicine $medicine)
    {
        try {
            if ($medicine->stockMedicine()->delete()) {
                $medicine->delete();
                Alert::success("Success", 'Data Berhasil Di Delete!');
            } else {
                Alert::error("Error", 'Data Gagal Di Delete!');
            }
            return redirect()->route('admin.medicine.index');
        } catch (\Throwable $th) {
            Log::error($th);
            Alert::error("Error", 'Data Gagal Di Delete, Ada Error Server!');
            return redirect()->route('admin.medicine.index');
        }
    }

    public function getMedicine(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $medicines = [];
        } else {
            $medicines = Medicine::select('id','name')->where('name', 'like', '%' . $search . '%')->limit(5)->get();
        }
        $response = [];
        foreach ($medicines as $item) {
            $response[] = [
                "id" => $item->id,
                "text" => $item->name,
            ];
        }
        return response()->json($response);
    }
}
