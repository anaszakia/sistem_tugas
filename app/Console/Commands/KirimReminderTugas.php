<?php

namespace App\Schedule;

use App\Models\Tugas;
use App\Helpers\FonteWa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Schedule\Schedule;

class ReminderTugasSchedule
{
    public function __invoke(Schedule $schedule): void
    {
        $schedule->call(function () {
            $besok = Carbon::tomorrow()->toDateString();

            $tugasList = Tugas::whereDate('deadline', $besok)->with('user')->get();

            foreach ($tugasList as $tugas) {
                $user = $tugas->user;
                if ($user && $user->no_wa) {
                    $message = "⏰ Reminder! Halo {$user->name}, tugas \"{$tugas->nama_tugas}\" akan berakhir besok ({$tugas->deadline}). Segera diselesaikan ya!";
                    FonteWa::send($user->no_wa, $message);
                    Log::info("Reminder terkirim ke {$user->no_wa}");
                }
            }
        })->dailyAt('12:00');
    }
}
