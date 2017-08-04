<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class UtilTests.
 */
class UtilTests extends GroupTest
{
    /**
     * UtilTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Util Tests');
        $this->addTestFile('util_uri_test.php');
        $this->addTestFile('util_textual_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new UtilTests();
    $test->run(new HtmlReporter());
}
