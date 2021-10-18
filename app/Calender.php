<?php

namespace App;

use Carbon\Carbon;

class Calender{

    public function getCalendarDates($request)
    {
        $year = $request->year;
        $month = $request->month;

        if($month - 1 <= 0){
            $previousYear = $year -1;
            $previousMonth = 12;
        }else{
            $previousYear = $year;
            $previousMonth = $month - 1;
        }

        if($month + 1 >= 13){
            $nextYear = $year + 1;
            $nextMonth = 1;
        }else{
            $nextYear = $year;
            $nextMonth = $month + 1;
        }

        $date = new Carbon("{$year}-{$month}-01");
        $addDay = ($date->copy()->endOfMonth()->isSunday()) ? 7 : 0;
        $date->subDay($date->dayOfWeek);

        $count = 31 + $addDay + $date->dayOfWeek;
        $count = ceil($count / 7) * 7;
        $dates = [];

        for($i = 0; $i < $count; $i++, $date->addDay()){
            $dates[] = $date->copy();
        }
        return [$dates, $year, $month, $previousYear, $previousMonth, $nextYear, $nextMonth];
    }
}