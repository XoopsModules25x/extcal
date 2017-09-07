<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./calendar_test.php';

/**
 * Class TestOfSecond.
 */
class TestOfSecond extends TestOfCalendar
{
    /**
     * TestOfSecond constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Second');
    }

    public function setUp()
    {
        $this->cal = new Calendar_Second(2003, 10, 25, 13, 32, 43);
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
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfSecond();
    $test->run(new HtmlReporter());
}
