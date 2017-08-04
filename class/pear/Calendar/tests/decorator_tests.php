<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class DecoratorTests.
 */
class DecoratorTests extends GroupTest
{
    /**
     * DecoratorTests constructor.
     */
    public function __construct()
    {
        parent::__construct('Decorator Tests');
        $this->addTestFile('decorator_test.php');
        $this->addTestFile('decorator_textual_test.php');
        $this->addTestFile('decorator_uri_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new DecoratorTests();
    $test->run(new HtmlReporter());
}
