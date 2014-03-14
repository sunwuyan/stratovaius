<?php
function parseDate($dateStr){
        $year=substr($dateStr,0,4);
        $month=substr($dateStr,4,2);
        $day=substr($dateStr,6,2);
        $hour=substr($dateStr,8,2);
        $min=substr($dateStr,10,2);
        $sec=substr($dateStr,-2);
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':'.$sec;
    }
