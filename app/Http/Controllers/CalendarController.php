<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $events = [];

        /**
         * 1. Ambil data Hari Libur dari database
         */
        $holidays = Holiday::all();
        foreach ($holidays as $holiday) {
            $events[] = [
                'id'    => 'h-' . $holiday->id,
                'title' => $holiday->description,
                'start' => Carbon::parse($holiday->date)->format('Y-m-d'),
                'allDay' => true,
                'backgroundColor' => '#EF4444', // merah
                'borderColor' => '#EF4444',
                'extendedProps' => [ 'type' => 'holiday' ]
            ];
        }

        /**
         * 2. Ambil data Ulang Tahun Karyawan
         */
        $usersWithBirthday = User::whereHas('profile', function ($query) {
            $query->whereNotNull('birth_date');
        })->with('profile')->get();
        
        $currentYear = Carbon::now()->year;

        foreach ($usersWithBirthday as $user) {
            if (
                $user->profile &&
                $user->profile->birth_date &&
                $user->profile->birth_date instanceof Carbon
            ) {
                // Set ulang tahun ke tahun berjalan
                $birthdayThisYear = $user->profile->birth_date->copy()->setYear($currentYear);

                // Ambil nama depan
                $firstName = explode(' ', $user->name)[0];

                $events[] = [
                    'title' => '🎂 Ultah ' . $firstName,
                    'start' => $birthdayThisYear->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => '#FCA311', // oranye
                    'borderColor' => '#FCA311',
                    'extendedProps' => [ 'type' => 'birthday' ]
                ];
            }
        }

        return response()->json($events);
    }
}
