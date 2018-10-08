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
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . cal_days_in_month(CAL_GREGORIAN, $startDate->format('m'), $startDate->format('Y'))));

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
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . cal_days_in_month(CAL_GREGORIAN, $startDate->format('m'), $startDate->format('Y'))));

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
            ->setUntil(new \DateTime($startDate->format('Y-m') . '-' . cal_days_in_month(CAL_GREGORIAN, $startDate->format('m'), $startDate->format('Y'))));

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
}
