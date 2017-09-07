<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfMonthWeekdays.
 */
class TestOfMonthWeekdays extends TestOfCalendar
{
    /**
     * TestOfMonthWeekdays constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Month Weekdays');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Month_Weekdays(2003, 10);
    }

    public function testPrevDay()
    {
        $this->assertEqual(30, $this->cal->prevDay());
    }

    public function testPrevDay_Array()
    {
        $this->assertEqual([
                               'year'   => 2003,
                               'month'  => 9,
                               'day'    => 30,
                               'hour'   => 0,
                               'minute' => 0,
                               'second' => 0,
                           ], $this->cal->prevDay('array'));
    }

    public function testThisDay()
    {
        $this->assertEqual(1, $this->cal->thisDay());
    }

    public function testNextDay()
    {
        $this->assertEqual(2, $this->cal->nextDay());
    }

    public function testPrevHour()
    {
        $this->assertEqual(23, $this->cal->prevHour());
    }

    public function testThisHour()
    {
        $this->assertEqual(0, $this->cal->thisHour());
    }

    public function testNextHour()
    {
        $this->assertEqual(1, $this->cal->nextHour());
    }

    public function testPrevMinute()
    {
        $this->assertEqual(59, $this->cal->prevMinute());
    }

    public function testThisMinute()
    {
        $this->assertEqual(0, $this->cal->thisMinute());
    }

    public function testNextMinute()
    {
        $this->assertEqual(1, $this->cal->nextMinute());
    }

    public function testPrevSecond()
    {
        $this->assertEqual(59, $this->cal->prevSecond());
    }

    public function testThisSecond()
    {
        $this->assertEqual(0, $this->cal->thisSecond());
    }

    public function testNextSecond()
    {
        $this->assertEqual(1, $this->cal->nextSecond());
    }

    public function testGetTimeStamp()
    {
        $stamp = mktime(0, 0, 0, 10, 1, 2003);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }
}

/**
 * Class TestOfMonthWeekdaysBuild.
 */
class TestOfMonthWeekdaysBuild extends TestOfMonthWeekdays
{
    /**
     * TestOfMonthWeekdaysBuild constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Month_Weekdays::build()');
    }

    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(35, $this->cal->size());
    }

    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            ++$i;
        }
        $this->assertEqual(35, $i);
    }

    public function testFetchAll()
    {
        $this->cal->build();
        $children = [];
        $i        = 1;
        while ($Child = $this->cal->fetch()) {
            $children[$i] = $Child;
            ++$i;
        }
        $this->assertEqual($children, $this->cal->fetchAll());
    }

    public function testSelection()
    {
        require_once CALENDAR_ROOT . 'Day.php';
        $selection = [new Calendar_Day(2003, 10, 25)];
        $this->cal->build($selection);
        $daysInPrevMonth = (0 == CALENDAR_FIRST_DAY_OF_WEEK) ? 3 : 2;
        $end             = 25 + $daysInPrevMonth;
        $i               = 1;
        while ($Child = $this->cal->fetch()) {
            if ($i == $end) {
                break;
            }
            ++$i;
        }
        $this->assertTrue($Child->isSelected());
        $this->assertEqual(25, $Child->day);
    }

    public function testEmptyCount()
    {
        $this->cal->build();
        $empty = 0;
        while ($Child = $this->cal->fetch()) {
            if ($Child->isEmpty()) {
                ++$empty;
            }
        }
        $this->assertEqual(4, $empty);
    }

    public function testEmptyCount2()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 3);
        $this->cal->build();
        $empty = 0;
        while ($Child = $this->cal->fetch()) {
            if ($Child->isEmpty()) {
                ++$empty;
            }
        }
        $this->assertEqual(4, $empty);
    }

    public function testEmptyCount3()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 6);
        $this->cal->build();
        $empty = 0;
        while ($Child = $this->cal->fetch()) {
            if ($Child->isEmpty()) {
                ++$empty;
            }
        }
        $this->assertEqual(5, $empty);
    }

    public function testEmptyDaysBefore_AfterAdjust()
    {
        $this->cal = new Calendar_Month_Weekdays(2004, 0);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 1 : 0;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysBefore());
    }

    public function testEmptyDaysBefore()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 3);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 1 : 0;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysBefore());
    }

    public function testEmptyDaysBefore2()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 6);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 2 : 1;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysBefore());
    }

    public function testEmptyDaysAfter()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 3);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 30 : 31;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysAfter());
    }

    public function testEmptyDaysAfter2()
    {
        $this->cal = new Calendar_Month_Weekdays(2010, 6);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 30 : 31;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysAfter());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfMonthWeekdays();
    $test->run(new HtmlReporter());
    $test = new TestOfMonthWeekdaysBuild();
    $test->run(new HtmlReporter());
}
