<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfHour.
 */
class TestOfHour extends TestOfCalendar
{
    /**
     * TestOfHour constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Hour');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Hour(2003, 10, 25, 13);
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
        $stamp = mktime(13, 0, 0, 10, 25, 2003);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }
}

/**
 * Class TestOfHourBuild.
 */
class TestOfHourBuild extends TestOfHour
{
    /**
     * TestOfHourBuild constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Hour::build()');
    }

    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(_EXTCAL_TS_MINUTE, $this->cal->size());
    }

    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            ++$i;
        }
        $this->assertEqual(_EXTCAL_TS_MINUTE, $i);
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
        require_once CALENDAR_ROOT . 'Minute.php';
        $selection = [new Calendar_Minute(2003, 10, 25, 13, 32)];
        $this->cal->build($selection);
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            if (32 == $i) {
                break;
            }
            ++$i;
        }
        $this->assertTrue($Child->isSelected());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfHour();
    $test->run(new HtmlReporter());
    $test = new TestOfHourBuild();
    $test->run(new HtmlReporter());
}
