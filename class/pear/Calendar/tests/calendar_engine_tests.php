<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class CalendarEngineTests.
 */
class CalendarEngineTests extends GroupTest
{
    /**
     * CalendarEngineTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Calendar Engine Tests');
        $this->addTestFile('peardate_engine_test.php');
        $this->addTestFile('unixts_engine_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new CalendarEngineTests();
    $test->run(new HtmlReporter());
}
