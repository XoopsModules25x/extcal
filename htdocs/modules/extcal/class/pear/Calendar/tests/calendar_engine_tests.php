<?php
// $Id: calendar_engine_tests.php 1511 2011-09-01 20:56:07Z jjdai $

require_once 'simple_include.php';
require_once 'calendar_include.php';

/**
 * Class CalendarEngineTests
 */
class CalendarEngineTests extends GroupTest
{
    function CalendarEngineTests()
    {
        $this->GroupTest('Calendar Engine Tests');
        $this->addTestFile('peardate_engine_test.php');
        $this->addTestFile('unixts_engine_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new CalendarEngineTests();
    $test->run(new HtmlReporter());
}
