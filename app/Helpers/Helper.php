<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Helper
{
    public static function dayName($day)
    {
        if ($day == 'Sun') {
            $day = 'Minggu';
        } elseif ($day == 'Mon') {
            $day = 'Senin';
        }elseif ($day == 'Tue') {
            $day = 'Selasa';
        }elseif ($day == 'Wed') {
            $day = 'Rabu';
        }elseif ($day == 'Thu') {
            $day = 'Kamis';
        }elseif ($day == 'Fri') {
            $day = 'Jumat';
        }elseif ($day == 'Sat') {
            $day = 'Sabtu';
        }else {
            $day = 'Minggu';
        }

        return $day;
    }
}
