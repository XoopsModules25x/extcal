<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class TableHelperTests.
 */
class TableHelperTests extends GroupTest
{
    /**
     * TableHelperTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Table Helper Tests');
        $this->addTestFile('helper_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TableHelperTests();
    $test->run(new HtmlReporter());
}
