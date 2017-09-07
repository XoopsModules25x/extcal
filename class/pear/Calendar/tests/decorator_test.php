<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

Mock::generate('Calendar_Engine_Interface', 'Mock_Calendar_Engine');
Mock::generate('Calendar_Second', 'Mock_Calendar_Second');
Mock::generate('Calendar_Week', 'Mock_Calendar_Week');
Mock::generate('Calendar_Day', 'Mock_Calendar_Day');

/**
 * Class TestOfDecorator.
 */
class TestOfDecorator extends UnitTestCase
{
    public $mockengine;
    public $mockcal;
    public $decorator;

    /**
     * TestOfDecorator constructor.
     */
    public function __construct()
    {
        parent::__construct('Test of Calendar_Decorator');
    }

    public function setUp()
    {
        $this->mockengine = new Mock_Calendar_Engine($this);
        $this->mockcal    = new Mock_Calendar_Second($this);
        $this->mockcal->setReturnValue('prevYear', 2002);
        $this->mockcal->setReturnValue('thisYear', 2003);
        $this->mockcal->setReturnValue('nextYear', 2004);
        $this->mockcal->setReturnValue('prevMonth', 9);
        $this->mockcal->setReturnValue('thisMonth', 10);
        $this->mockcal->setReturnValue('nextMonth', 11);
        $this->mockcal->setReturnValue('prevDay', 14);
        $this->mockcal->setReturnValue('thisDay', 15);
        $this->mockcal->setReturnValue('nextDay', 16);
        $this->mockcal->setReturnValue('prevHour', 12);
        $this->mockcal->setReturnValue('thisHour', 13);
        $this->mockcal->setReturnValue('nextHour', 14);
        $this->mockcal->setReturnValue('prevMinute', 29);
        $this->mockcal->setReturnValue('thisMinute', 30);
        $this->mockcal->setReturnValue('nextMinute', 31);
        $this->mockcal->setReturnValue('prevSecond', 44);
        $this->mockcal->setReturnValue('thisSecond', 45);
        $this->mockcal->setReturnValue('nextSecond', 46);
        $this->mockcal->setReturnValue('getEngine', $this->mockengine);
        $this->mockcal->setReturnValue('getTimestamp', 12345);
    }

    public function tearDown()
    {
        unset($this->engine, $this->mockcal);
    }

    public function testPrevYear()
    {
        $this->mockcal->expectOnce('prevYear', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(2002, $Decorator->prevYear());
    }

    public function testThisYear()
    {
        $this->mockcal->expectOnce('thisYear', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(2003, $Decorator->thisYear());
    }

    public function testNextYear()
    {
        $this->mockcal->expectOnce('nextYear', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(2004, $Decorator->nextYear());
    }

    public function testPrevMonth()
    {
        $this->mockcal->expectOnce('prevMonth', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(9, $Decorator->prevMonth());
    }

    public function testThisMonth()
    {
        $this->mockcal->expectOnce('thisMonth', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(10, $Decorator->thisMonth());
    }

    public function testNextMonth()
    {
        $this->mockcal->expectOnce('nextMonth', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(11, $Decorator->nextMonth());
    }

    public function testPrevWeek()
    {
        $mockweek = new Mock_Calendar_Week($this);
        $mockweek->setReturnValue('prevWeek', 1);
        $mockweek->expectOnce('prevWeek', ['n_in_month']);
        $Decorator = new Calendar_Decorator($mockweek);
        $this->assertEqual(1, $Decorator->prevWeek());
    }

    public function testThisWeek()
    {
        $mockweek = new Mock_Calendar_Week($this);
        $mockweek->setReturnValue('thisWeek', 2);
        $mockweek->expectOnce('thisWeek', ['n_in_month']);
        $Decorator = new Calendar_Decorator($mockweek);
        $this->assertEqual(2, $Decorator->thisWeek());
    }

    public function testNextWeek()
    {
        $mockweek = new Mock_Calendar_Week($this);
        $mockweek->setReturnValue('nextWeek', 3);
        $mockweek->expectOnce('nextWeek', ['n_in_month']);
        $Decorator = new Calendar_Decorator($mockweek);
        $this->assertEqual(3, $Decorator->nextWeek());
    }

    public function testPrevDay()
    {
        $this->mockcal->expectOnce('prevDay', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(14, $Decorator->prevDay());
    }

    public function testThisDay()
    {
        $this->mockcal->expectOnce('thisDay', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(15, $Decorator->thisDay());
    }

    public function testNextDay()
    {
        $this->mockcal->expectOnce('nextDay', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(16, $Decorator->nextDay());
    }

    public function testPrevHour()
    {
        $this->mockcal->expectOnce('prevHour', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(12, $Decorator->prevHour());
    }

    public function testThisHour()
    {
        $this->mockcal->expectOnce('thisHour', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(13, $Decorator->thisHour());
    }

    public function testNextHour()
    {
        $this->mockcal->expectOnce('nextHour', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(14, $Decorator->nextHour());
    }

    public function testPrevMinute()
    {
        $this->mockcal->expectOnce('prevMinute', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(29, $Decorator->prevMinute());
    }

    public function testThisMinute()
    {
        $this->mockcal->expectOnce('thisMinute', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(30, $Decorator->thisMinute());
    }

    public function testNextMinute()
    {
        $this->mockcal->expectOnce('nextMinute', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(31, $Decorator->nextMinute());
    }

    public function testPrevSecond()
    {
        $this->mockcal->expectOnce('prevSecond', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(44, $Decorator->prevSecond());
    }

    public function testThisSecond()
    {
        $this->mockcal->expectOnce('thisSecond', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(45, $Decorator->thisSecond());
    }

    public function testNextSecond()
    {
        $this->mockcal->expectOnce('nextSecond', ['int']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(46, $Decorator->nextSecond());
    }

    public function testGetEngine()
    {
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertIsA($Decorator->getEngine(), 'Mock_Calendar_Engine');
    }

    public function testSetTimestamp()
    {
        $this->mockcal->expectOnce('setTimestamp', ['12345']);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->setTimestamp('12345');
    }

    public function testGetTimestamp()
    {
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual(12345, $Decorator->getTimestamp());
    }

    public function testSetSelected()
    {
        $this->mockcal->expectOnce('setSelected', [true]);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->setSelected();
    }

    public function testIsSelected()
    {
        $this->mockcal->setReturnValue('isSelected', true);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertTrue($Decorator->isSelected());
    }

    public function testAdjust()
    {
        $this->mockcal->expectOnce('adjust', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->adjust();
    }

    public function testToArray()
    {
        $this->mockcal->expectOnce('toArray', [12345]);
        $testArray = ['foo' => 'bar'];
        $this->mockcal->setReturnValue('toArray', $testArray);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual($testArray, $Decorator->toArray(12345));
    }

    public function testReturnValue()
    {
        $this->mockcal->expectOnce('returnValue', ['a', 'b', 'c', 'd']);
        $this->mockcal->setReturnValue('returnValue', 'foo');
        $Decorator = new Calendar_Decorator($this->mockcal);
        $this->assertEqual('foo', $Decorator->returnValue('a', 'b', 'c', 'd'));
    }

    public function testSetFirst()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->expectOnce('setFirst', [true]);
        $Decorator = new Calendar_Decorator($mockday);
        $Decorator->setFirst();
    }

    public function testSetLast()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->expectOnce('setLast', [true]);
        $Decorator = new Calendar_Decorator($mockday);
        $Decorator->setLast();
    }

    public function testIsFirst()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->setReturnValue('isFirst', true);
        $Decorator = new Calendar_Decorator($mockday);
        $this->assertTrue($Decorator->isFirst());
    }

    public function testIsLast()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->setReturnValue('isLast', true);
        $Decorator = new Calendar_Decorator($mockday);
        $this->assertTrue($Decorator->isLast());
    }

    public function testSetEmpty()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->expectOnce('setEmpty', [true]);
        $Decorator = new Calendar_Decorator($mockday);
        $Decorator->setEmpty();
    }

    public function testIsEmpty()
    {
        $mockday = new Mock_Calendar_Day($this);
        $mockday->setReturnValue('isEmpty', true);
        $Decorator = new Calendar_Decorator($mockday);
        $this->assertTrue($Decorator->isEmpty());
    }

    public function testBuild()
    {
        $testArray = ['foo' => 'bar'];
        $this->mockcal->expectOnce('build', [$testArray]);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->build($testArray);
    }

    public function testFetch()
    {
        $this->mockcal->expectOnce('fetch', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->fetch();
    }

    public function testFetchAll()
    {
        $this->mockcal->expectOnce('fetchAll', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->fetchAll();
    }

    public function testSize()
    {
        $this->mockcal->expectOnce('size', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->size();
    }

    public function testIsValid()
    {
        $this->mockcal->expectOnce('isValid', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->isValid();
    }

    public function testGetValidator()
    {
        $this->mockcal->expectOnce('getValidator', []);
        $Decorator = new Calendar_Decorator($this->mockcal);
        $Decorator->getValidator();
    }
}
