<?php
/**
 * Description: a SOAP Calendar Server.
 */
if (!@include 'SOAP/Server.php') {
    die('You must have PEAR::SOAP installed');
}

if (!@include 'Calendar/Calendar.php') {
    define('CALENDAR_ROOT', '../../');
}

/**
 * Class Calendar_Server.
 */
class Calendar_Server
{
    public $__dispatch_map = [];
    public $__typedef      = [];

    public function __construct()
    {
        $this->__dispatch_map['getMonth'] = [
            'in'  => ['year' => 'int', 'month' => 'int'],
            'out' => ['month' => '{urn:PEAR_SOAP_Calendar}Month'],
        ];
        $this->__typedef['Month']         = [
            'monthname' => 'string',
            'days'      => '{urn:PEAR_SOAP_Calendar}MonthDays',
        ];
        $this->__typedef['MonthDays']     = [['{urn:PEAR_SOAP_Calendar}Day']];
        $this->__typedef['Day']           = [
            'isFirst' => 'int',
            'isLast'  => 'int',
            'isEmpty' => 'int',
            'day'     => 'int',
        ];
    }

    /**
     * @param $methodname
     * @return mixed|void
     */
    public function __dispatch($methodname)
    {
        if (isset($this->__dispatch_map[$methodname])) {
            return $this->__dispatch_map[$methodname];
        }

        return;
    }

    /**
     * @param $year
     * @param $month
     *
     * @return array
     */
    public function getMonth($year, $month)
    {
        require_once CALENDAR_ROOT . 'Month/Weekdays.php';
        $Month = new Calendar_Month_Weekdays($year, $month);
        if (!$Month->isValid()) {
            $V        = $Month->getValidator();
            $errorMsg = '';
            while ($error = $V->fetch()) {
                $errorMsg .= $error->toString() . "\n";
            }

            return new Soap_fault($errorMsg, 'Client');
        } else {
            $monthname = date('F Y', $Month->getTimestamp());
            $days      = [];
            $Month->build();
            while ($Day = $Month->fetch()) {
                $day    = [
                    'isFirst' => (int)$Day->isFirst(),
                    'isLast'  => (int)$Day->isLast(),
                    'isEmpty' => (int)$Day->isEmpty(),
                    'day'     => (int)$Day->thisDay(),
                ];
                $days[] = $day;
            }

            return ['monthname' => $monthname, 'days' => $days];
        }
    }
}

$server                    = new Soap_server();
$server->_auto_translation = true;
$calendar                  = new Calendar_Server();
$server->addObjectMap($calendar, 'urn:PEAR_SOAP_Calendar');

if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
    $server->service($GLOBALS['HTTP_RAW_POST_DATA']);
} else {
    require_once 'SOAP/Disco.php';
    $disco = new SOAP_DISCO_Server($server, 'PEAR_SOAP_Calendar');
    if (isset($_SERVER['QUERY_STRING']) && 0 == strcasecmp($_SERVER['QUERY_STRING'], 'wsdl')) {
        header('Content-type: text/xml');
        echo $disco->getWSDL();
    } else {
        echo 'This is a PEAR::SOAP Calendar Server. For client try <a href="8.php">here</a><br>';
        echo 'For WSDL try <a href="?wsdl">here</a>';
    }
    exit;
}
