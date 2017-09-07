<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class TestOfUnixTsEngine.
 */
class TestOfUnixTsEngine extends UnitTestCase
{
    public $engine;

    /**
     * TestOfUnixTsEngine constructor.
     */
    public function __construct()
    {
        parent::__construct('Test of Calendar_Engine_UnixTs');
    }

    public function setUp()
    {
        $this->engine = new Calendar_Engine_UnixTS();
    }

    public function testGetSecondsInMinute()
    {
        $this->assertEqual($this->engine->getSecondsInMinute(), _EXTCAL_TS_MINUTE);
    }

    public function testGetMinutesInHour()
    {
        $this->assertEqual($this->engine->getMinutesInHour(), _EXTCAL_TS_MINUTE);
    }

    public function testGetHoursInDay()
    {
        $this->assertEqual($this->engine->getHoursInDay(), 24);
    }

    public function testGetFirstDayOfWeek()
    {
        $this->assertEqual($this->engine->getFirstDayOfWeek(), 1);
    }

    public function testGetWeekDays()
    {
        $this->assertEqual($this->engine->getWeekDays(), [0, 1, 2, 3, 4, 5, 6]);
    }

    public function testGetDaysInWeek()
    {
        $this->assertEqual($this->engine->getDaysInWeek(), 7);
    }

    public function testGetWeekNInYear()
    {
        $this->assertEqual($this->engine->getWeekNInYear(2003, 11, 3), 45);
    }

    public function testGetWeekNInMonth()
    {
        $this->assertEqual($this->engine->getWeekNInMonth(2003, 11, 3), 2);
    }

    public function testGetWeeksInMonth0()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 11, 0), 6); //week starts on sunday
    }

    public function testGetWeeksInMonth1()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 11, 1), 5); //week starts on monday
    }

    public function testGetWeeksInMonth2()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 2, 6), 4); //week starts on saturday
    }

    public function testGetWeeksInMonth3()
    {
        // Unusual cases that can cause fails (shows up with example 21.php)
        $this->assertEqual($this->engine->getWeeksInMonth(2004, 2, 1), 5);
        $this->assertEqual($this->engine->getWeeksInMonth(2004, 8, 1), 6);
    }

    public function testGetDayOfWeek()
    {
        $this->assertEqual($this->engine->getDayOfWeek(2003, 11, 18), 2);
    }

    public function testGetFirstDayInMonth()
    {
        $this->assertEqual($this->engine->getFirstDayInMonth(2003, 10), 3);
    }

    public function testGetDaysInMonth()
    {
        $this->assertEqual($this->engine->getDaysInMonth(2003, 10), 31);
    }

    public function testGetMinYears()
    {
        $test = strpos(PHP_OS, 'WIN') >= 0 ? 1970 : 1902;
        $this->assertEqual($this->engine->getMinYears(), $test);
    }

    public function testGetMaxYears()
    {
        $this->assertEqual($this->engine->getMaxYears(), 2037);
    }

    public function testDateToStamp()
    {
        $stamp = mktime(0, 0, 0, 10, 15, 2003);
        $this->assertEqual($this->engine->dateToStamp(2003, 10, 15, 0, 0, 0), $stamp);
    }

    public function testStampToSecond()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToSecond($stamp), 45);
    }

    public function testStampToMinute()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToMinute($stamp), 30);
    }

    public function testStampToHour()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToHour($stamp), 13);
    }

    public function testStampToDay()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToDay($stamp), 15);
    }

    public function testStampToMonth()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToMonth($stamp), 10);
    }

    public function testStampToYear()
    {
        $stamp = mktime(13, 30, 45, 10, 15, 2003);
        $this->assertEqual($this->engine->stampToYear($stamp), 2003);
    }

    public function testIsToday()
    {
        $stamp = time();
        $this->assertTrue($this->engine->isToday($stamp));
        $stamp += 1000000000;
        $this->assertFalse($this->engine->isToday($stamp));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfUnixTsEngine();
    $test->run(new HtmlReporter());
}
