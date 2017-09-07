<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

require_once __DIR__ . '/./decorator_test.php';

/**
 * Class TestOfDecoratorTextual.
 */
class TestOfDecoratorTextual extends TestOfDecorator
{
    /**
     * TestOfDecoratorTextual constructor.
     */
    public function __construct()
    {
        $this->UnitTestCase('Test of Calendar_Decorator_Textual');
    }

    public function testMonthNamesLong()
    {
        $Textual    = new Calendar_Decorator_Textual($this->mockcal);
        $monthNames = [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        $this->assertEqual($monthNames, $Textual->monthNames());
    }

    public function testMonthNamesShort()
    {
        $Textual    = new Calendar_Decorator_Textual($this->mockcal);
        $monthNames = [
            1  => 'Jan',
            2  => 'Feb',
            3  => 'Mar',
            4  => 'Apr',
            5  => 'May',
            6  => 'Jun',
            7  => 'Jul',
            8  => 'Aug',
            9  => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];
        $this->assertEqual($monthNames, $Textual->monthNames('short'));
    }

    public function testMonthNamesTwo()
    {
        $Textual    = new Calendar_Decorator_Textual($this->mockcal);
        $monthNames = [
            1  => 'Ja',
            2  => 'Fe',
            3  => 'Ma',
            4  => 'Ap',
            5  => 'Ma',
            6  => 'Ju',
            7  => 'Ju',
            8  => 'Au',
            9  => 'Se',
            10 => 'Oc',
            11 => 'No',
            12 => 'De',
        ];
        $this->assertEqual($monthNames, $Textual->monthNames('two'));
    }

    public function testMonthNamesOne()
    {
        $Textual    = new Calendar_Decorator_Textual($this->mockcal);
        $monthNames = [
            1  => 'J',
            2  => 'F',
            3  => 'M',
            4  => 'A',
            5  => 'M',
            6  => 'J',
            7  => 'J',
            8  => 'A',
            9  => 'S',
            10 => 'O',
            11 => 'N',
            12 => 'D',
        ];
        $this->assertEqual($monthNames, $Textual->monthNames('one'));
    }

    public function testWeekdayNamesLong()
    {
        $Textual      = new Calendar_Decorator_Textual($this->mockcal);
        $weekdayNames = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        $this->assertEqual($weekdayNames, $Textual->weekdayNames());
    }

    public function testWeekdayNamesShort()
    {
        $Textual      = new Calendar_Decorator_Textual($this->mockcal);
        $weekdayNames = [
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
        ];
        $this->assertEqual($weekdayNames, $Textual->weekdayNames('short'));
    }

    public function testWeekdayNamesTwo()
    {
        $Textual      = new Calendar_Decorator_Textual($this->mockcal);
        $weekdayNames = [
            0 => 'Su',
            1 => 'Mo',
            2 => 'Tu',
            3 => 'We',
            4 => 'Th',
            5 => 'Fr',
            6 => 'Sa',
        ];
        $this->assertEqual($weekdayNames, $Textual->weekdayNames('two'));
    }

    public function testWeekdayNamesOne()
    {
        $Textual      = new Calendar_Decorator_Textual($this->mockcal);
        $weekdayNames = [
            0 => 'S',
            1 => 'M',
            2 => 'T',
            3 => 'W',
            4 => 'T',
            5 => 'F',
            6 => 'S',
        ];
        $this->assertEqual($weekdayNames, $Textual->weekdayNames('one'));
    }

    public function testPrevMonthNameShort()
    {
        $Textual = new Calendar_Decorator_Textual($this->mockcal);
        $this->assertEqual('Sep', $Textual->prevMonthName('short'));
    }

    public function testThisMonthNameShort()
    {
        $Textual = new Calendar_Decorator_Textual($this->mockcal);
        $this->assertEqual('Oct', $Textual->thisMonthName('short'));
    }

    public function testNextMonthNameShort()
    {
        $Textual = new Calendar_Decorator_Textual($this->mockcal);
        $this->assertEqual('Nov', $Textual->nextMonthName('short'));
    }

    public function testThisDayNameShort()
    {
        $Textual = new Calendar_Decorator_Textual($this->mockcal);
        $this->assertEqual('Wed', $Textual->thisDayName('short'));
    }

    public function testOrderedWeekdaysShort()
    {
        $weekdayNames = [
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
        ];
        $nShifts      = CALENDAR_FIRST_DAY_OF_WEEK;
        while ($nShifts-- > 0) {
            $day = array_shift($weekdayNames);
            array_push($weekdayNames, $day);
        }
        $Textual = new Calendar_Decorator_Textual($this->mockcal);
        $this->assertEqual($weekdayNames, $Textual->orderedWeekdays('short'));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfDecoratorTextual();
    $test->run(new HtmlReporter());
}
