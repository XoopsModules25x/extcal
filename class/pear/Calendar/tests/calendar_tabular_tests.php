<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class CalendarTabularTests.
 */
class CalendarTabularTests extends GroupTest
{
    /**
     * CalendarTabularTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Calendar Tabular Tests');
        $this->addTestFile('month_weekdays_test.php');
        $this->addTestFile('month_weeks_test.php');
        $this->addTestFile('week_test.php');
        //$this->addTestFile('week_firstday_0_test.php'); //switch with the above
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new CalendarTabularTests();
    $test->run(new HtmlReporter());
}
