<?php
// $Id: calendar_tests.php 1511 2011-09-01 20:56:07Z jjdai $

require_once 'simple_include.php';
require_once 'calendar_include.php';

/**
 * Class CalendarTests
 */
class CalendarTests extends GroupTest
{
    function CalendarTests()
    {
        $this->GroupTest('Calendar Tests');
        $this->addTestFile('calendar_test.php');
        $this->addTestFile('year_test.php');
        $this->addTestFile('month_test.php');
        $this->addTestFile('day_test.php');
        $this->addTestFile('hour_test.php');
        $this->addTestFile('minute_test.php');
        $this->addTestFile('second_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new CalendarTests();
    $test->run(new HtmlReporter());
}
