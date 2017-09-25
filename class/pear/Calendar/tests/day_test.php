<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfDay.
 */
class TestOfDay extends TestOfCalendar
{
    /**
     * TestOfDay constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Day');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Day(2003, 10, 25);
    }

    public function testPrevDay_Array()
    {
        $this->assertEqual([
                               'year'   => 2003,
                               'month'  => 10,
                               'day'    => 24,
                               'hour'   => 0,
                               'minute' => 0,
                               'second' => 0,
                           ], $this->cal->prevDay('array'));
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
        $stamp = mktime(0, 0, 0, 10, 25, 2003);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }
}

/**
 * Class TestOfDayBuild.
 */
class TestOfDayBuild extends TestOfDay
{
    /**
     * TestOfDayBuild constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Day::build()');
    }

    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(24, $this->cal->size());
    }

    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            ++$i;
        }
        $this->assertEqual(24, $i);
    }

    public function testFetchAll()
    {
        $this->cal->build();
        $children = [];
        $i        = 0;
        while ($Child = $this->cal->fetch()) {
            $children[$i] = $Child;
            ++$i;
        }
        $this->assertEqual($children, $this->cal->fetchAll());
    }

    public function testSelection()
    {
        require_once CALENDAR_ROOT . 'Hour.php';
        $selection = [new Calendar_Hour(2003, 10, 25, 13)];
        $this->cal->build($selection);
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            if (13 == $i) {
                break;
            }
            ++$i;
        }
        $this->assertTrue($Child->isSelected());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfDay();
    $test->run(new HtmlReporter());
    $test = new TestOfDayBuild();
    $test->run(new HtmlReporter());
}
