<?php

namespace App\Services;

use Carbon\Carbon;

class VacationCalculator
{
  public static function diffInBussinessDays($startDate, $endDate)
  {
    return Carbon::parse($startDate)->diffInDaysFiltered(function(Carbon $date) {
        return !$date->isWeekend();
    }, $endDate);
  }

  public static function vacationAvailable($startDate, $endDate, $vacations)
  {
    foreach ($vacations as $vacation) {
        if ($startDate > $vacation->end_of_vac) {
            continue;
        } else {
            if ($endDate < $vacation->start_of_vac) {
                continue;
            } else {
                return false;
            }
        }
    }

    return true;
  }
}