<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DayWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DokterController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();
            $query->where('roles', 'DOKTER');
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a class="btn btn-info btn-sm" href="' . route('admin.dokter.edit', $row->id) . '"><i class="bi bi-power"></i> Edit</a>
                    <a href="' . route('admin.dokter.delete', $row->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(' . "'Yakin ?'" . ')"><i class="bi bi-trash"></i> Delete</a>
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
        return view('admin.dokter.index');
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'unique:users'
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['roles'] = 'DOKTER';
        if ($data = User::create($data)) {
            // create day work
            $dayWork = [];
            foreach ($request->day as $key => $day) {
                if ($request->time_start[$key] != '00:00' && $request->time_end[$key] != '00:00') {
                    $dayWork[] = [
                        'day' => $day,
                        'time_start' => $request->time_start[$key],
                        'time_end' => $request->time_end[$key],
                    ];
                }
            }
            $dayWork = json_encode($dayWork);
            $work = new DayWork();
            $work->dokter_id = $data->id;
            $work->day_work = $dayWork;
            $work->save();
            Alert::success("Success", 'Data Berhasil Di Simpan!');
        } else {
            Alert::error("Error", 'Data Gagal Di Simpan!');
        }
        return redirect()->route('admin.dokter.index');
    }

    public function edit(User $dokter)
    {
        return view('admin.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, User $dokter)
    {
        $request->validate([
            'email' => 'unique:users,id,' . $dokter->id
        ]);
        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        if ($dokter->update($data)) {
            $dayWork = [];
            foreach ($request->day as $key => $day) {
                if ($request->time_start[$key] != '00:00' && $request->time_end[$key] != '00:00') {
                    $dayWork[] = [
                        'day' => $day,
                        'time_start' => $request->time_start[$key],
                        'time_end' => $request->time_end[$key],
                    ];
                }
            }
            $dayWork = json_encode($dayWork);
            $work = DayWork::where('dokter_id', $dokter->id)->first();
            $work->day_work = $dayWork;
            $work->save();
            Alert::success("Success", 'Data Berhasil Di Update!');
        } else {
            Alert::error("Error", 'Data Gagal Di Update!');
        }
        return redirect()->route('admin.dokter.index');
    }

    public function delete(User $dokter)
    {
        try {
            if ($dokter->dayWork->delete()) {
                $dokter->delete();
                Alert::success("Success", 'Data Berhasil Di Delete!');
            } else {
                Alert::error("Error", 'Data Gagal Di Delete!');
            }
            return redirect()->route('admin.dokter.index');
        } catch (\Throwable $th) {
            Log::error($th);
            Alert::error("Error", 'Data Gagal Di Delete, Ada Error Server!');
            return redirect()->route('admin.dokter.index');
        }
    }
}
