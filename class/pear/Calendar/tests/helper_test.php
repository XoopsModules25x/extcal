<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

Mock::generate('Calendar_Engine_Interface', 'Mock_Calendar_Engine');
Mock::generate('Calendar_Second', 'Mock_Calendar_Second');

/**
 * Class TestOfTableHelper.
 */
class TestOfTableHelper extends UnitTestCase
{
    public $mockengine;
    public $mockcal;

    /**
     * TestOfTableHelper constructor.
     */
    public function __construct()
    {
        parent::__construct('Test of Calendar_Table_Helper');
    }

    public function setUp()
    {
        $this->mockengine = new Mock_Calendar_Engine($this);
        $this->mockengine->setReturnValue('getMinYears', 1970);
        $this->mockengine->setReturnValue('getMaxYears', 2037);
        $this->mockengine->setReturnValue('getMonthsInYear', 12);
        $this->mockengine->setReturnValue('getDaysInMonth', 31);
        $this->mockengine->setReturnValue('getHoursInDay', 24);
        $this->mockengine->setReturnValue('getMinutesInHour', _EXTCAL_TS_MINUTE);
        $this->mockengine->setReturnValue('getSecondsInMinute', _EXTCAL_TS_MINUTE);
        $this->mockengine->setReturnValue('getWeekDays', [0, 1, 2, 3, 4, 5, 6]);
        $this->mockengine->setReturnValue('getDaysInWeek', 7);
        $this->mockengine->setReturnValue('getFirstDayOfWeek', 1);
        $this->mockengine->setReturnValue('getFirstDayInMonth', 3);
        $this->mockcal = new Mock_Calendar_Second($this);
        $this->mockcal->setReturnValue('thisYear', 2003);
        $this->mockcal->setReturnValue('thisMonth', 10);
        $this->mockcal->setReturnValue('thisDay', 15);
        $this->mockcal->setReturnValue('thisHour', 13);
        $this->mockcal->setReturnValue('thisMinute', 30);
        $this->mockcal->setReturnValue('thisSecond', 45);
        $this->mockcal->setReturnValue('getEngine', $this->mockengine);
    }

    public function testGetFirstDay()
    {
        for ($i = 0; $i <= 7; ++$i) {
            $Helper = new Calendar_Table_Helper($this->mockcal, $i);
            $this->assertEqual($Helper->getFirstDay(), $i);
        }
    }

    public function testGetDaysOfWeekMonday()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getDaysOfWeek(), [1, 2, 3, 4, 5, 6, 0]);
    }

    public function testGetDaysOfWeekSunday()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal, 0);
        $this->assertEqual($Helper->getDaysOfWeek(), [0, 1, 2, 3, 4, 5, 6]);
    }

    public function testGetDaysOfWeekThursday()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal, 4);
        $this->assertEqual($Helper->getDaysOfWeek(), [4, 5, 6, 0, 1, 2, 3]);
    }

    public function testGetNumWeeks()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getNumWeeks(), 5);
    }

    public function testGetNumTableDaysInMonth()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getNumTableDaysInMonth(), 35);
    }

    public function testGetEmptyDaysBefore()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getEmptyDaysBefore(), 2);
    }

    public function testGetEmptyDaysAfter()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getEmptyDaysAfter(), 33);
    }

    public function testGetEmptyDaysAfterOffset()
    {
        $Helper = new Calendar_Table_Helper($this->mockcal);
        $this->assertEqual($Helper->getEmptyDaysAfterOffset(), 5);
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfTableHelper();
    $test->run(new HtmlReporter());
}
