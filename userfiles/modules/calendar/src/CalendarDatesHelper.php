<?php

use \Recurr\Rule;

class CalendarDatesHelper
{

    function getDatesOfMonthByDayName($timeZone, $date, $dayName)
    {

        $dayName = $this->convertDayNamesToAbr($dayName);

        $startDate = new \DateTime($date, new \DateTimeZone($timeZone));

        $rule = (new \Recurr\Rule)
            ->setStartDate($startDate)
            ->setTimezone($timeZone)
            ->setFreq('DAILY')
            ->setByDay([$dayName])
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . $this->__daysInMonth( $startDate->format('m'), $startDate->format('Y'))));

        $transformer = new \Recurr\Transformer\ArrayTransformer();

        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);

        return $transformer->transform($rule);
    }


    function getDatesOfMonth($timeZone, $date)
    {
        $startDate = new \DateTime($date, new \DateTimeZone($timeZone));

        $rule = (new \Recurr\Rule)
            ->setStartDate($startDate)
            ->setTimezone($timeZone)
            ->setFreq('DAILY')
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . $this->__daysInMonth( $startDate->format('m'), $startDate->format('Y'))));

        $transformer = new \Recurr\Transformer\ArrayTransformer();

        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);

        return $transformer->transform($rule);
    }

    function getDatesOfMonthWithInterval($timeZone, $date, $interval)
    {

        $startDate = new \DateTime($date, new \DateTimeZone($timeZone));

        $rule = (new \Recurr\Rule)
            ->setStartDate($startDate)
            ->setTimezone($timeZone)
            ->setFreq('DAILY')
            ->setInterval($interval)
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . $this->__daysInMonth( $startDate->format('m'), $startDate->format('Y'))));

        $transformer = new \Recurr\Transformer\ArrayTransformer();

        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);

        return $transformer->transform($rule);
    }

    function convertDayNamesToAbr($dayName)
    {

        $dayName = str_replace('Monday', 'MO', $dayName);
        $dayName = str_replace('Tuesday', 'TU', $dayName);
        $dayName = str_replace('Wednesday', 'WE', $dayName);
        $dayName = str_replace('Thursday', 'TH', $dayName);
        $dayName = str_replace('Friday', 'FR', $dayName);
        $dayName = str_replace('Saturday', 'SA', $dayName);
        $dayName = str_replace('Sunday', 'SU', $dayName);

        return $dayName;
    }


    /**
     * Returns the number of week in a month for the specified date.
     *
     * @param string $date
     * @return int
     */
    function getWeekOfMonth($date)
    {
        // estract date parts
        list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

        // current week, min 1
        $w = 1;

        // for each day since the start of the month
        for ($i = 1; $i <= $d; ++$i) {
            // if that day was a sunday and is not the first day of month
            if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
                // increment current week
                ++$w;
            }
        }

        // now return
        return $w;
    }


    private function __daysInMonth($month)
    {

        $date = new DateTime('last day of '.$month. ' month');
        $numDaysOfCurrentMonth = $date->format('d');
        return $numDaysOfCurrentMonth;
    }
}
