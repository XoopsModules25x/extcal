<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Contains the Calendar_Engine_UnixTS class.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE FREEBSD PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Date and Time
 *
 * @author    Harry Fuecks <hfuecks@phppatterns.com>
 * @copyright 2003-2007 Harry Fuecks
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *
 * @link      http://pear.php.net/package/Calendar
 */

/**
 * Performs calendar calculations based on the PHP date() function and
 * Unix timestamps (using PHP's mktime() function).
 *
 * @category  Date and Time
 *
 * @author    Harry Fuecks <hfuecks@phppatterns.com>
 * @copyright 2003-2007 Harry Fuecks
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *
 * @link      http://pear.php.net/package/Calendar
 */
class Calendar_Engine_UnixTS /* implements Calendar_Engine_Interface */
{
    /**
     * Makes sure a given timestamp is only ever parsed once
     * <pre>
     * array (
     *  [0] => year (e.g 2003),
     *  [1] => month (e.g 9),
     *  [2] => day (e.g 6),
     *  [3] => hour (e.g 14),
     *  [4] => minute (e.g 34),
     *  [5] => second (e.g 45),
     *  [6] => num days in month (e.g. 31),
     *  [7] => week in year (e.g. 50),
     *  [8] => day in week (e.g. 0 for Sunday)
     * )
     * </pre>
     * Uses a static variable to prevent date() being used twice
     * for a date which is already known.
     *
     * @param int $stamp Unix timestamp
     *
     * @return array
     */
    public function stampCollection($stamp)
    {
        static $stamps = [];
        if (!isset($stamps[$stamp])) {
            $date           = @date('Y n j H i s t W w', $stamp);
            $stamps[$stamp] = sscanf($date, '%d %d %d %d %d %d %d %d %d');
        }

        return $stamps[$stamp];
    }

    /**
     * Returns a numeric year given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int year (e.g. 2003)
     */
    public function stampToYear($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[0];
    }

    /**
     * Returns a numeric month given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int month (e.g. 9)
     */
    public function stampToMonth($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[1];
    }

    /**
     * Returns a numeric day given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int day (e.g. 15)
     */
    public function stampToDay($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[2];
    }

    /**
     * Returns a numeric hour given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int hour (e.g. 13)
     */
    public function stampToHour($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[3];
    }

    /**
     * Returns a numeric minute given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int minute (e.g. 34)
     */
    public function stampToMinute($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[4];
    }

    /**
     * Returns a numeric second given a timestamp.
     *
     * @param int $stamp Unix timestamp
     *
     * @return int second (e.g. 51)
     */
    public function stampToSecond($stamp)
    {
        $date = self::stampCollection($stamp);

        return (int)$date[5];
    }

    /**
     * Returns a timestamp.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (13)
     * @param int $h hour (13)
     * @param int $i minute (34)
     * @param int $s second (53)
     *
     * @return int Unix timestamp
     */
    public function dateToStamp($y, $m, $d, $h = 0, $i = 0, $s = 0)
    {
        static $dates = [];
        if (!isset($dates[$y][$m][$d][$h][$i][$s])) {
            $dates[$y][$m][$d][$h][$i][$s] = @mktime($h, $i, $s, $m, $d, $y);
        }

        return $dates[$y][$m][$d][$h][$i][$s];
    }

    /**
     * The upper limit on years that the Calendar Engine can work with.
     *
     * @return int (2037)
     */
    public function getMaxYears()
    {
        return 2037;
    }

    /**
     * The lower limit on years that the Calendar Engine can work with.
     *
     * @return int (1970 if it's Windows and 1902 for all other OSs)
     */
    public function getMinYears()
    {
        return $min = false === strpos(PHP_OS, 'WIN') ? 1902 : 1970;
    }

    /**
     * Returns the number of months in a year.
     *
     * @param int $y year
     *
     * @return int (12)
     */
    public function getMonthsInYear($y = null)
    {
        return 12;
    }

    /**
     * Returns the number of days in a month, given year and month.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     *
     * @return int days in month
     */
    public function getDaysInMonth($y, $m)
    {
        $stamp = self::dateToStamp($y, $m, 1);
        $date  = self::stampCollection($stamp);

        return $date[6];
    }

    /**
     * Returns numeric representation of the day of the week in a month,
     * given year and month.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     *
     * @return int from 0 to 6
     */
    public function getFirstDayInMonth($y, $m)
    {
        $stamp = self::dateToStamp($y, $m, 1);
        $date  = self::stampCollection($stamp);

        return $date[8];
    }

    /**
     * Returns the number of days in a week.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return int (7)
     */
    public function getDaysInWeek($y = null, $m = null, $d = null)
    {
        return 7;
    }

    /**
     * Returns the number of the week in the year (ISO-8601), given a date.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return int week number
     */
    public function getWeekNInYear($y, $m, $d)
    {
        $stamp = self::dateToStamp($y, $m, $d);
        $date  = self::stampCollection($stamp);

        return $date[7];
    }

    /**
     * Returns the number of the week in the month, given a date.
     *
     * @param int $y        year (2003)
     * @param int $m        month (9)
     * @param int $d        day (4)
     * @param int $firstDay first day of the week (default: monday)
     *
     * @return int week number
     */
    public function getWeekNInMonth($y, $m, $d, $firstDay = 1)
    {
        $weekEnd     = (0 == $firstDay) ? $this->getDaysInWeek() - 1 : $firstDay - 1;
        $end_of_week = 1;
        while (@date('w', @mktime(0, 0, 0, $m, $end_of_week, $y)) != $weekEnd) {
            ++$end_of_week; //find first weekend of the month
        }
        $w = 1;
        while ($d > $end_of_week) {
            ++$w;
            $end_of_week += $this->getDaysInWeek();
        }

        return $w;
    }

    /**
     * Returns the number of weeks in the month.
     *
     * @param int $y        year (2003)
     * @param int $m        month (9)
     * @param int $firstDay first day of the week (default: monday)
     *
     * @return int weeks number
     */
    public function getWeeksInMonth($y, $m, $firstDay = 1)
    {
        $FDOM = $this->getFirstDayInMonth($y, $m);
        if (0 == $FDOM) {
            $FDOM = $this->getDaysInWeek();
        }
        if ($FDOM > $firstDay) {
            $daysInTheFirstWeek = $this->getDaysInWeek() - $FDOM + $firstDay;
            $weeks              = 1;
        } else {
            $daysInTheFirstWeek = $firstDay - $FDOM;
            $weeks              = 0;
        }
        $daysInTheFirstWeek %= $this->getDaysInWeek();

        return (int)(ceil(($this->getDaysInMonth($y, $m) - $daysInTheFirstWeek) / $this->getDaysInWeek()) + $weeks);
    }

    /**
     * Returns the number of the day of the week (0=sunday, 1=monday...).
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return int weekday number
     */
    public function getDayOfWeek($y, $m, $d)
    {
        $stamp = self::dateToStamp($y, $m, $d);
        $date  = self::stampCollection($stamp);

        return $date[8];
    }

    /**
     * Returns a list of integer days of the week beginning 0.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return array (0,1,2,3,4,5,6) 1 = Monday
     */
    public function getWeekDays($y = null, $m = null, $d = null)
    {
        return [0, 1, 2, 3, 4, 5, 6];
    }

    /**
     * Returns the default first day of the week.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return int (default 1 = Monday)
     */
    public function getFirstDayOfWeek($y = null, $m = null, $d = null)
    {
        return 1;
    }

    /**
     * Returns the number of hours in a day.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     *
     * @return int (24)
     */
    public function getHoursInDay($y = null, $m = null, $d = null)
    {
        return 24;
    }

    /**
     * Returns the number of minutes in an hour.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     * @param int $h hour
     *
     * @return int (_EXTCAL_TS_MINUTE)
     */
    public function getMinutesInHour($y = null, $m = null, $d = null, $h = null)
    {
        return _EXTCAL_TS_MINUTE;
    }

    /**
     * Returns the number of seconds in a minutes.
     *
     * @param int $y year (2003)
     * @param int $m month (9)
     * @param int $d day (4)
     * @param int $h hour
     * @param int $i minute
     *
     * @return int (_EXTCAL_TS_MINUTE)
     */
    public function getSecondsInMinute($y = null, $m = null, $d = null, $h = null, $i = null)
    {
        return _EXTCAL_TS_MINUTE;
    }

    /**
     * Checks if the given day is the current day.
     *
     * @param mixed $stamp Any timestamp format recognized by Pear::Date
     *
     * @return bool
     */
    public function isToday($stamp)
    {
        static $today = null;
        if (is_null($today)) {
            $today_date = @date('Y n j');
            $today      = sscanf($today_date, '%d %d %d');
        }
        $date = self::stampCollection($stamp);

        return $date[2] == $today[2] && $date[1] == $today[1] && $date[0] == $today[0];
    }
}
