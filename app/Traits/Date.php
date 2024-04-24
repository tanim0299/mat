<?php
namespace App\Traits;

trait Date{
    public static function getOriginalDate($sign, $date)
    {
        $explode = explode($sign, $date);

        $original_date = $explode[2].'/'.$explode[1].'/'.$explode[0];

        return $original_date;
    }

    public static function getDateWithMonth($sign, $date)
    {
        $explode = explode($sign, $date);
        $month_name;
        if($explode[1] == 1)
        {
            $month_name = __('common.january');
        }
        elseif($explode[1] == 2)
        {
            $month_name = __('common.february');
        }
        elseif($explode[1] == 3)
        {
            $month_name = __('common.march');
        }
        elseif($explode[1] == 4)
        {
            $month_name = __('common.april');
        }
        elseif($explode[1] == 5)
        {
            $month_name = __('common.may');
        }
        elseif($explode[1] == 6)
        {
            $month_name = __('common.june');
        }
        elseif($explode[1] == 7)
        {
            $month_name = __('common.july');
        }
        elseif($explode[1] == 8)
        {
            $month_name = __('common.august');
        }
        elseif($explode[1] == 9)
        {
            $month_name = __('common.september');
        }
        elseif($explode[1] == 10)
        {
            $month_name = __('common.october');
        }
        elseif($explode[1] == 11)
        {
            $month_name = __('common.november');
        }
        elseif($explode[1] == 12)
        {
            $month_name = __('common.december');
        }

        $original_date = $explode[2].' '.$month_name.' '.$explode[0];

        return $original_date;
    }

}
