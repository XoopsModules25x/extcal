<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfMonthWeeks.
 */
class TestOfMonthWeeks extends TestOfCalendar
{
    /**
     * TestOfMonthWeeks constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Month Weeks');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Month_Weeks(2003, 10);
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
 * Class TestOfMonthWeeksBuild.
 */
class TestOfMonthWeeksBuild extends TestOfMonthWeeks
{
    /**
     * TestOfMonthWeeksBuild constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Month_Weeks::build()');
    }

    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(5, $this->cal->size());
    }

    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            ++$i;
        }
        $this->assertEqual(5, $i);
    }

    /* Recusive dependency issue with SimpleTest
        function testFetchAll()
        {
            $this->cal->build();
            $children = array();
            $i = 1;
            while ( $Child = $this->cal->fetch() ) {
                $children[$i]=$Child;
                ++$i;
            }
            $this->assertEqual($children,$this->cal->fetchAll());
        }
    */
    public function testSelection()
    {
        require_once CALENDAR_ROOT . 'Week.php';
        $selection = [new Calendar_Week(2003, 10, 12)];
        $this->cal->build($selection);
        $i        = 1;
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 3 : 2;
        while ($Child = $this->cal->fetch()) {
            if ($i == $expected) {
                //12-10-2003 is in the 2nd week of the month if firstDay is Monday,
                //in the 3rd if firstDay is Sunday
                break;
            }
            ++$i;
        }
        $this->assertTrue($Child->isSelected());
    }

    public function testEmptyDaysBefore_AfterAdjust()
    {
        $this->cal = new Calendar_Month_Weeks(2004, 0);
        $this->cal->build();
        $expected = (CALENDAR_FIRST_DAY_OF_WEEK == 0) ? 1 : 0;
        $this->assertEqual($expected, $this->cal->tableHelper->getEmptyDaysBefore());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfMonthWeeks();
    $test->run(new HtmlReporter());
    $test = new TestOfMonthWeeksBuild();
    $test->run(new HtmlReporter());
}
