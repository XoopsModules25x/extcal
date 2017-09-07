<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class TestOfCalendar.
 */
class TestOfCalendar extends UnitTestCase
{
    public $cal;

    /**
     * @param string $name
     */
    public function __construct($name = 'Test of Calendar')
    {
        parent::__construct($name);
    }

    public function setUp()
    {
        $this->cal = new Calendar(2003, 10, 25, 13, 32, 43);
    }

    public function tearDown()
    {
        unset($this->cal);
    }

    public function testPrevYear()
    {
        $this->assertEqual(2002, $this->cal->prevYear());
    }

    public function testPrevYear_Array()
    {
        $this->assertEqual([
                               'year'   => 2002,
                               'month'  => 1,
                               'day'    => 1,
                               'hour'   => 0,
                               'minute' => 0,
                               'second' => 0,
                           ], $this->cal->prevYear('array'));
    }

    public function testThisYear()
    {
        $this->assertEqual(2003, $this->cal->thisYear());
    }

    public function testNextYear()
    {
        $this->assertEqual(2004, $this->cal->nextYear());
    }

    public function testPrevMonth()
    {
        $this->assertEqual(9, $this->cal->prevMonth());
    }

    public function testPrevMonth_Array()
    {
        $this->assertEqual([
                               'year'   => 2003,
                               'month'  => 9,
                               'day'    => 1,
                               'hour'   => 0,
                               'minute' => 0,
                               'second' => 0,
                           ], $this->cal->prevMonth('array'));
    }

    public function testThisMonth()
    {
        $this->assertEqual(10, $this->cal->thisMonth());
    }

    public function testNextMonth()
    {
        $this->assertEqual(11, $this->cal->nextMonth());
    }

    public function testPrevDay()
    {
        $this->assertEqual(24, $this->cal->prevDay());
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

    public function testThisDay()
    {
        $this->assertEqual(25, $this->cal->thisDay());
    }

    public function testNextDay()
    {
        $this->assertEqual(26, $this->cal->nextDay());
    }

    public function testPrevHour()
    {
        $this->assertEqual(12, $this->cal->prevHour());
    }

    public function testThisHour()
    {
        $this->assertEqual(13, $this->cal->thisHour());
    }

    public function testNextHour()
    {
        $this->assertEqual(14, $this->cal->nextHour());
    }

    public function testPrevMinute()
    {
        $this->assertEqual(31, $this->cal->prevMinute());
    }

    public function testThisMinute()
    {
        $this->assertEqual(32, $this->cal->thisMinute());
    }

    public function testNextMinute()
    {
        $this->assertEqual(33, $this->cal->nextMinute());
    }

    public function testPrevSecond()
    {
        $this->assertEqual(42, $this->cal->prevSecond());
    }

    public function testThisSecond()
    {
        $this->assertEqual(43, $this->cal->thisSecond());
    }

    public function testNextSecond()
    {
        $this->assertEqual(44, $this->cal->nextSecond());
    }

    public function testSetTimeStamp()
    {
        $stamp = mktime(13, 32, 43, 10, 25, 2003);
        $this->cal->setTimestamp($stamp);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }

    public function testGetTimeStamp()
    {
        $stamp = mktime(13, 32, 43, 10, 25, 2003);
        $this->assertEqual($stamp, $this->cal->getTimestamp());
    }

    public function testIsToday()
    {
        $stamp = time();
        $this->cal->setTimestamp($stamp);
        $this->assertTrue($this->cal->isToday());

        $stamp += 1000000000;
        $this->cal->setTimestamp($stamp);
        $this->assertFalse($this->cal->isToday());
    }
}
