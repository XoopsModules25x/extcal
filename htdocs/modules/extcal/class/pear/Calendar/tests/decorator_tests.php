<?php
// $Id: decorator_tests.php 1511 2011-09-01 20:56:07Z jjdai $

require_once 'simple_include.php';
require_once 'calendar_include.php';

/**
 * Class DecoratorTests
 */
class DecoratorTests extends GroupTest
{
    function DecoratorTests()
    {
        $this->GroupTest('Decorator Tests');
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
