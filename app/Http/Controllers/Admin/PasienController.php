<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PasienController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();
            $query->where('roles', 'PASIEN');
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button class="btn btn-info btn-sm" id="edit" data-bs-toggle="modal"
                    data-bs-target="#modal-create"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-email="' . $row->email . '"
                    data-date_of_birth="' . $row->date_of_birth . '"
                    data-gender="' . $row->gender . '"
                    ><i class="bi bi-power"></i> Edit</button>
                    <a href="' . route('admin.pasien.delete', $row->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(' . "'Yakin ?'" . ')"><i class="bi bi-trash"></i> Delete</a>
                    ';

                    return $btn;
                })
                ->editColumn('date_of_birth', function ($row) {
                    $age = Carbon::parse($row->date_of_birth)->diff(Carbon::now())->format('%y Tahun, %m Bulan');
                    return $age;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.pasien.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'unique:users'
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        if (User::create($data)) {
            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('admin.pasien.index');
    }

    public function update(Request $request, User $pasien)
    {
        $request->validate([
            'email' => 'unique:users,id,' . $pasien->id
        ]);
        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        if ($pasien->update($data)) {
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('admin.pasien.index');
    }

    public function delete(User $pasien)
    {
        try {
            if ($pasien->delete()) {
                Alert::success("Success", 'Data Berhasil Di Delete!');
            } else {
                Alert::error("Error", 'Data Gagal Di Delete!');
            }
            return redirect()->route('admin.pasien.index');
        } catch (\Throwable $th) {
            Log::error($th);
            Alert::error("Error", 'Data Gagal Di Delete, Ada Error Server!');
            return redirect()->route('admin.pasien.index');
        }
    }
}
