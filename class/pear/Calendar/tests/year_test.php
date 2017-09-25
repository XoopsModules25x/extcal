<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfYear.
 */
class TestOfYear extends TestOfCalendar
{
    /**
     * TestOfYear constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Year');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Year(2003);
    }

    public function testPrevYear_Object()
    {
        $this->assertEqual(new Calendar_Year(2002), $this->cal->prevYear('object'));
    }

    public function testThisYear_Object()
    {
        $this->assertEqual(new Calendar_Year(2003), $this->cal->thisYear('object'));
    }

    public function testPrevMonth()
    {
        $this->assertEqual(12, $this->cal->prevMonth());
    }

    public function testPrevMonth_Array()
    {
        $this->assertEqual([
                               'year'   => 2002,
                               'month'  => 12,
                               'day'    => 1,
                               'hour'   => 0,
                               'minute' => 0,
                               'second' => 0,
                           ], $this->cal->prevMonth('array'));
    }

    public function testThisMonth()
    {
        $this->assertEqual(1, $this->cal->thisMonth());
    }

    public function testNextMonth()
    {
        $this->assertEqual(2, $this->cal->nextMonth());
    }

    public function testPrevDay()
    {
        $this->assertEqual(31, $this->cal->prevDay());
    }

    public function testPrevDay_Array()
    {
        $this->assertEqual([
                               'year'   => 2002,
                               'month'  => 12,
                               'day'    => 31,
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
        $stamp = mktime(0, 0, 0, 1, 1, 2003);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }
}

/**
 * Class TestOfYearBuild.
 */
class TestOfYearBuild extends TestOfYear
{
    /**
     * TestOfYearBuild constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Year::build()');
    }

    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(12, $this->cal->size());
    }

    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            ++$i;
        }
        $this->assertEqual(12, $i);
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
        require_once CALENDAR_ROOT . 'Month.php';
        $selection = [new Calendar_Month(2003, 10)];
        $this->cal->build($selection);
        $i = 1;
        while ($Child = $this->cal->fetch()) {
            if (10 == $i) {
                break;
            }
            ++$i;
        }
        $this->assertTrue($Child->isSelected());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfYear();
    $test->run(new HtmlReporter());
    $test = new TestOfYearBuild();
    $test->run(new HtmlReporter());
}
