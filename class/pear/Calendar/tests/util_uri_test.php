<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

Mock::generate('Calendar_Day', 'Mock_Calendar_Day');
Mock::generate('Calendar_Engine_Interface', 'Mock_Calendar_Engine');

/**
 * Class TestOfUtilUri.
 */
class TestOfUtilUri extends UnitTestCase
{
    public $MockCal;

    /**
     * TestOfUtilUri constructor.
     */
    public function __construct()
    {
        parent::__construct('Test of Calendar_Util_Uri');
    }

    public function setUp()
    {
        $this->MockCal = new Mock_Calendar_Day($this);
        $this->MockCal->setReturnValue('getEngine', new Mock_Calendar_Engine($this));
    }

    public function testFragments()
    {
        $Uri = new Calendar_Util_Uri('y', 'm', 'd', 'h', 'm', 's');
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $this->assertEqual('year=&amp;month=&amp;day=&amp;hour=&amp;minute=&amp;second=', $Uri->this($this->MockCal, 'second'));
    }

    public function testScalarFragments()
    {
        $Uri         = new Calendar_Util_Uri('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->scalar = true;
        $this->assertEqual('&amp;&amp;&amp;&amp;&amp;', $Uri->this($this->MockCal, 'second'));
    }

    public function testSetSeperator()
    {
        $Uri            = new Calendar_Util_Uri('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->separator = '/';
        $this->assertEqual('year=/month=/day=/hour=/minute=/second=', $Uri->this($this->MockCal, 'second'));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfUtilUri();
    $test->run(new HtmlReporter());
}
