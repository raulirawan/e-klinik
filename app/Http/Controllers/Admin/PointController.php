<?php

namespace App\Http\Controllers\Admin;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PointController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Point::query();
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button class="btn btn-info btn-sm" id="edit" data-bs-toggle="modal"
                    data-bs-target="#modal-create"
                    data-id="' . $row->id . '"
                    data-min_transaction="' . $row->min_transaction . '"
                    data-point="' . $row->point . '"
                    ><i class="bi bi-power"></i> Edit</button>
                    <a href="' . route('admin.point.delete', $row->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(' . "'Yakin ?'" . ')"><i class="bi bi-trash"></i> Delete</a>
                    ';

                    return $btn;
                })
                ->editColumn('min_transaction', function ($row) {
                    return number_format($row->min_transaction);
                })
                ->editColumn('point', function ($row) {
                    return number_format($row->point);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.point.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (Point::create($data)) {
            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('admin.point.index');
    }

    public function update(Request $request, Point $point)
    {
        $data = $request->all();
        if ($point->update($data)) {
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('admin.point.index');
    }

    public function delete(Point $point)
    {
        try {
            if ($point->delete()) {
                Alert::success("Success", 'Data Berhasil Di Delete!');
            } else {
                Alert::error("Error", 'Data Gagal Di Delete!');
            }
            return redirect()->route('admin.point.index');
        } catch (\Throwable $th) {
            Log::error($th);
            Alert::error("Error", 'Data Gagal Di Delete, Ada Error Server!');
            return redirect()->route('admin.point.index');
        }
    }
}
