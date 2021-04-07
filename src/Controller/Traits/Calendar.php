<?php

namespace App\Controller\Traits;

trait Calendar{
    
    public function getMonthsDays($days)
    {
        $monthsDays = [
            // 12 months max
            [], [], [], [], [], [], [], [], [], [], [], [], []
        ];
        $i = 0;
        foreach ($days as $key => $day) {
            $dayMonth = date('Ym', $day->getDate()->getTimestamp());

            if (isset($prevDayMonth) && $dayMonth > $prevDayMonth) {
                $i++;
            }

            array_push($monthsDays[$i], $day);
            $prevDayMonth = $dayMonth;
        };
        return $monthsDays;
    }

}