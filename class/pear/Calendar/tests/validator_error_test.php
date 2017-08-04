<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

/**
 * Class TestOfValidationError.
 */
class TestOfValidationError extends UnitTestCase
{
    public $vError;

    /**
     * TestOfValidationError constructor.
     */
    public function __construct()
    {
        parent::__construct('Test of Validation Error');
    }

    public function setUp()
    {
        $this->vError = new Calendar_Validation_Error('foo', 20, 'bar');
    }

    public function testGetUnit()
    {
        $this->assertEqual($this->vError->getUnit(), 'foo');
    }

    public function testGetValue()
    {
        $this->assertEqual($this->vError->getValue(), 20);
    }

    public function testGetMessage()
    {
        $this->assertEqual($this->vError->getMessage(), 'bar');
    }

    public function testToString()
    {
        $this->assertEqual($this->vError->toString(), 'foo = 20 [bar]');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfValidationError();
    $test->run(new HtmlReporter());
}
