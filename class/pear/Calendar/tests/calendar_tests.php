<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class CalendarTests.
 */
class CalendarTests extends GroupTest
{
    /**
     * CalendarTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Calendar Tests');
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
