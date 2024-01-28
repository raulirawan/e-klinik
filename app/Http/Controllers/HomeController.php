<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\DayWork;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function checkAvailable(Request $request)
    {
        $date = $request->date;

        $day = Helper::dayName(Carbon::parse($date)->format('D'));
        $bookingDate = Carbon::parse($date)->format('Y-m-d');

        $dayWork = DayWork::with('dokter')->whereJsonContains('day_work', ['day' => $day])->get();

        return view('check-available', compact('dayWork', 'day', 'bookingDate'));
    }

    public function checkAvailablePost(Request $request)
    {
        $time  = $request->time;
        $day = $request->day;
        $bookingDate = $request->bookingDate;
        $timestamps = [];

        foreach ($time as $value) {
            if ($value['day'] == $day) {
                $start = new DateTime($value['time_start']);
                $end = new DateTime($value['time_end']);

                $interval = new DateInterval('PT1H'); // 1 Hour interval

                $range = new DatePeriod($start, $interval, $end);

                foreach ($range as $timestamp) {
                    $time = $timestamp->format('H:i');
                    // check time
                    $transaction = Transaction::where(['time' => $time, 'booking_date' => $bookingDate])->where('status', '!=', 'CANCEL')->first();
                    if (!$transaction) {
                        $timestamps[] = [
                            'value' => $timestamp->format('H'),
                            'text' => $timestamp->format('H:i') . ' - ' . $timestamp->add(new DateInterval('PT1H'))->format('H:i')
                        ];
                    }
                }
            }
        }
        return response($timestamps);
    }

    public function makeAppointment(Request $request)
    {
        // create transaction
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->dokter_id = $request->dokter_id;
        $transaction->booking_date = $request->booking_date;
        $transaction->day = $request->day;
        $transaction->time = $request->time . ':00';
        $transaction->code = Str::upper(Str::random(5));
        $transaction->status = 'PENDING';
        $transaction->save();

        if ($transaction) {
            Alert::success('Success', 'Berhasil Membuat Jadwal Konsultasi!');
            return redirect()->route('pasien.transaction.index');
        } else {
            Alert::error('Error', 'Gagal Membuat Jadwal Konsultasi, Coba Lagi!');
            return redirect()->back();
        }
    }
}
