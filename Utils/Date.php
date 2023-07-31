<?php

namespace PN\ServiceBundle\Utils;

class Date
{

    public static $timezoneOffset;

    const DATE_FORMAT1 = 'Y-m-d H:i:s';
    const DATE_FORMAT2 = 'Y-m-d';
    const DATE_FORMAT3 = 'd/m/Y';
    const DATE_FORMAT4 = 'Y-m';
    const DATE_FORMAT5 = 'm/Y';
    const DATE_FORMAT6 = 'd/m/Y h:i A';
    const DATE_FORMAT7 = 'd M Y';
    const DATE_FORMAT_D = 'd';
    const DATE_FORMAT_M = 'm';
    const DATE_FORMAT_Y = 'Y';
    const DATE_FORMAT_TIME = 'h:i A';

    public function __construct()
    {
        self::open();
    }

    private static function open()
    { // This Functions for initialization
        return;
    }

    private static function setDefaultTimezone($country)
    {
        return date_default_timezone_set($country);
    }

    /**
     * Set Offset between GMT and
     * @param type $country
     * @return boolean
     */
    private static function setTimezoneOffset($country = null)
    {
        $origin_dtz = new \DateTimeZone($country);
        $remote_dtz = new \DateTimeZone("Etc/GMT");
        $origin_dt = new \DateTime("now", $origin_dtz);
        $remote_dt = new \DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        static::$timezoneOffset = $offset;
    }

    public function getTimezoneOffset()
    {
        return static::$timezoneOffset;
    }

    /**
     *
     * @param type $year
     * @param type $month
     * @param type $day
     * @param type $timeFormat
     * @param type $separator
     * @return type
     */
    public static function getDatetimeNow(
        $year = false,
        $month = false,
        $day = false,
        $timeFormat = false,
        $separator = '-'
    )
    {
        self::open();

        if (!$year and !$month and !$day and !$timeFormat and $separator == '-') {
            return date(self::DATE_FORMAT1);
        }


        if ($year and $month and $day) {
            $date_string = self::DATE_FORMAT_Y . $separator . self::DATE_FORMAT_M . $separator . self::DATE_FORMAT_D;
        } elseif ($year and $month) {
            $date_string = self::DATE_FORMAT_Y . $separator . self::DATE_FORMAT_M;
        } elseif ($year and !self::DATE_FORMAT_M and !$day and !$timeFormat) {
            $date_string = self::DATE_FORMAT_Y;
        } elseif ($year and !$month and !$day and !$timeFormat) {
            $date_string = self::DATE_FORMAT_Y;
        } elseif (!$year and $month and !$day and !$timeFormat) {
            $date_string = self::DATE_FORMAT_M;
        } elseif (!$year and !$month and $day and !$timeFormat) {
            $date_string = self::DATE_FORMAT_D;
        } elseif (!$year and $month and $day and !$timeFormat) {
            $date_string = self::DATE_FORMAT_M . $separator . self::DATE_FORMAT_D;
        } elseif (!$year and !$month and !$day and $timeFormat) {
            $date_string = $timeFormat;
        } else {
            $date_string = self::DATE_FORMAT_Y . $separator . self::DATE_FORMAT_M . $separator . self::DATE_FORMAT_D . " " . $timeFormat;
        }


        return date($date_string);
    }

    public static function createDateRangeArray($strDateFrom, $strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2),
            substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date(self::DATE_FORMAT2, $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date(self::DATE_FORMAT2, $iDateFrom));
            }
        }

        return $aryRange;
    }

    public static function getWeekNow()
    {

        switch (WEEK_START_DAY_NAME) {
            case 'Sat':
            case 'Saturday':
                $offset = "+2";
                break;
            case 'Sun':
            case 'Sunday':
                $offset = "+1";
                break;
            case 'Mon':
            case 'Monday':
                $offset = "0";
                break;
            case 'Tue':
            case 'Tuesday':
                $offset = "-1";
                break;
            case 'Wed':
            case 'Wednesday':
                $offset = "-2";
                break;
            case 'Thu':
            case 'Thursday':
                $offset = "-3";
                break;
            case 'Friday':
                $offset = "-4";
                break;
        }

        return date("W", strtotime("$offset day"));
    }

    public static function getMonthLenght($month = false, $year = false): int
    {
        $monthNum = ($month) ? ltrim($month, 0) : ltrim(self::getDatetimeNow(false, true), 0);

        $year = ($year) ? $year : self::getDatetimeNow(true);

        $gregorian_leap_flag = !(($year) % 4);

        if (!$gregorian_leap_flag) {
            $gregorian_months_length = [
                "1" => 31,
                "2" => 28,
                "3" => 31,
                "4" => 30,
                "5" => 31,
                "6" => 30,
                "7" => 31,
                "8" => 31,
                "9" => 30,
                "10" => 31,
                "11" => 30,
                "12" => 31,
            ];
        } else {
            $gregorian_months_length = [
                "1" => 31,
                "2" => 29,
                "3" => 31,
                "4" => 30,
                "5" => 31,
                "6" => 30,
                "7" => 31,
                "8" => 31,
                "9" => 30,
                "10" => 31,
                "11" => 30,
                "12" => 31,
            ];
        }

        return $gregorian_months_length[$monthNum];
    }

    /**
     * @return string Formatted interval string like Facebook.
     * @author Peter Nassef <peter.nassef@gmail.com>
     *
     */
    public static function dateTimeDiffLikeFacebook($date, $locale = "en"): string
    {

        if (empty($date)) {
            return "No date provided";
        }

        switch ($locale) {
            case 'ar':
                $periods = array("ثواني", "دقيقة", "ساعة", "يوم", "أسبوع", "شهر", "سنة", "عقد");
                break;
            case 'en':
                $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
                break;
        }
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        $now = strtotime(Date::getDatetimeNow());
        $unix_date = strtotime($date);
        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }
        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            switch ($locale) {
                case 'ar':
                    $tense = "منذ";
                    break;
                case 'en':
                    $tense = "ago";
                    break;
            }
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        //        if ($difference != 1) {
        //            $periods[$j].= "s";
        //        }
        switch ($locale) {
            case 'ar':
                return "{$tense} $difference $periods[$j]";
            case 'en':
                return "$difference $periods[$j] {$tense}";
        }
        return "";
    }

    public static function addTodate(
        $year = false,
        $month = false,
        $day = false,
        $format = false,
        $time = false,
        $operation = false
    )
    {
        $currentDate = self::getDatetimeNow();

        $operation = (!$operation) ? '+' : '-';
        if (!$format) {

            $time = ($time) ? ' h:i:s' : '';

            $format = self::DATE_FORMAT2 . $time;
        }

        if (is_numeric($year)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($currentDate)) . " " . $operation . $year . " years");
        }

        if (is_numeric($month)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($currentDate)) . " " . $operation . $month . " month");
        }

        if (is_numeric($day)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($day)) . " " . $operation . $day . " days");
        }


        return date($format, $newDate);
    }

    public static function addDaysTodate(
        $date,
        $year = false,
        $month = false,
        $day = false,
        $format = false,
        $time = false,
        $operation = false
    )
    {
        $operation = (!$operation) ? '+' : '-';
        if (!$format) {
            $time = ($time) ? ' h:i:s' : '';
            $format = self::DATE_FORMAT2 . $time;
        }

        if (is_numeric($year)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($date)) . " " . $operation . $year . " years");
        }

        if (is_numeric($month)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($date)) . " " . $operation . $month . " month");
        }

        if (is_numeric($day)) {
            $newDate = strtotime(date(self::DATE_FORMAT1, strtotime($date)) . " " . $operation . $day . " days");
        }

        return date($format, $newDate);
    }

    /**
     * if get difference between tow dates
     * @param $date1
     * @param $date2
     * @return false|float (Days only not hour or minute)
     * @author Peter Nassef <peter.nassef@gmail.com>
     */
    public static function dateDiffByDays($date1, $date2)
    {
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $seconds_diff = $ts2 - $ts1;

        return floor($seconds_diff / 3600 / 24);
    }

    /**
     * @param $now
     * @param $expiryDate
     * @return bool
     * @author Peter Nassef <peter.nassef@gmail.com>
     */
    public static function dateDiff($now, $expiryDate)
    {
        $today = strtotime($now);
        $expiration_date = strtotime($expiryDate);

        if ($today > $expiration_date) {
            $valid = false;
        } else {
            $valid = true;
        }

        return $valid;
    }

    public static function getMonthNameByNumber($monthNum)
    {
        return date("F", mktime(0, 0, 0, $monthNum, 10));//output: May
    }

    /**
     * @param $date
     * @param $fromFormat
     * @param $toFormat
     * @return string (ex.  21-03-2010)
     * @author Peter Nassef <peter.nassef@gmail.com>
     * @example Date::convertDateFormat('21/03/2010', 'd/m/Y', 'd-m-Y')
     */
    public static function convertDateFormat($date, $fromFormat, $toFormat): string
    {
        $date = trim($date);

        return \DateTime::createFromFormat($fromFormat, $date)->format($toFormat);
    }

    public static function convertTimeToGMTIsoFormate(\DateTimeInterface $date): string
    {
        $date->setTimezone(new \DateTimeZone('GMT'));

        return $date->format("H:i:sO");
    }

    /*
     * @deprecated use convertDateToGMTAtomFormat() instead of convertDateToGMTIsoFormate()
     */
    public static function convertDateToGMTIsoFormate(\DateTimeInterface $date): string
    {
        return self::convertDateToGMTIsoFormat($date);
    }

    /*
     * @deprecated use convertDateToGMTAtomFormat() instead of convertDateToGMTIsoFormat()
     */
    public static function convertDateToGMTIsoFormat(\DateTimeInterface $date): string
    {
        $date->setTimezone(new \DateTimeZone('GMT'));

        return $date->format(\DateTimeInterface::ISO8601);
    }

    public static function convertDateToGMTAtomFormat(\DateTimeInterface $date): string
    {
        $date->setTimezone(new \DateTimeZone('GMT'));

        return $date->format(\DateTimeInterface::ATOM);
    }

    public static function convertDateFormatToDateTime($date, $fromFormat)
    {
        $date = trim($date);

        return \DateTime::createFromFormat($fromFormat, $date);
    }

    public static function timeDiffInMins(\DateTime $newDate, \DateTime $oldDate)
    {
        $interval = $newDate->diff($oldDate);
        $minutes = $interval->days * 24 * 60;
        $minutes += $interval->h * 60;
        $minutes += $interval->i;

        return $minutes;
    }
}