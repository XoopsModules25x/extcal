<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./decorator_test.php';

/**
 * Class TestOfDecoratorUri.
 */
class TestOfDecoratorUri extends TestOfDecorator
{
    /**
     * TestOfDecoratorUri constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Calendar_Decorator_Uri');
    }

    public function testFragments()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $this->assertEqual('year=&amp;month=&amp;day=&amp;hour=&amp;minute=&amp;second=', $Uri->this('second'));
    }

    public function testScalarFragments()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->setScalar();
        $this->assertEqual('&amp;&amp;&amp;&amp;&amp;', $Uri->this('second'));
    }

    public function testSetSeperator()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->setSeparator('/');
        $this->assertEqual('year=/month=/day=/hour=/minute=/second=', $Uri->this('second'));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfDecoratorUri();
    $test->run(new HtmlReporter());
}
