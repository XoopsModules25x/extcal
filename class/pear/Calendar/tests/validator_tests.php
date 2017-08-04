<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class ValidatorTests.
 */
class ValidatorTests extends GroupTest
{
    /**
     * ValidatorTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Validator Tests');
        $this->addTestFile('validator_unit_test.php');
        $this->addTestFile('validator_error_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new ValidatorTests();
    $test->run(new HtmlReporter());
}
