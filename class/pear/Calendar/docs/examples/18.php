<?php
/**
 * Description: demonstrates using the Wrapper decorator.
 */
if (!@include 'Calendar/Calendar.php') {
    define('CALENDAR_ROOT', '../../');
}
require_once CALENDAR_ROOT . 'Month.php';
require_once CALENDAR_ROOT . 'Decorator.php'; // Not really needed but added to help this make sense
require_once CALENDAR_ROOT . 'Decorator/Wrapper.php';

/**
 * Class MyBoldDecorator.
 */
class MyBoldDecorator extends Calendar_Decorator
{
    /**
     * @param $Calendar
     */
    public function __construct(&$Calendar)
    {
        parent::__construct($Calendar);
    }

    /**
     * @return string
     */
    public function thisDay()
    {
        return '<b>' . parent::thisDay() . '</b>';
    }
}

$Month = new Calendar_Month(date('Y'), date('n'));

$Wrapper = new Calendar_Decorator_Wrapper($Month);
$Wrapper->build();

echo '<h2>The Wrapper decorator</h2>';
echo '<i>Day numbers are rendered in bold</i><br> <br>';
while ($DecoratedDay = $Wrapper->fetch('MyBoldDecorator')) {
    echo $DecoratedDay->thisDay() . '<br>';
}
