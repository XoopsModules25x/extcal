<?php
//

require_once __DIR__ . '/simple_include.php';
require_once __DIR__ . '/calendar_include.php';

define('TEST_RUNNING', true);

require_once __DIR__ . '/calendar_tests.php';
require_once __DIR__ . '/calendar_tabular_tests.php';
require_once __DIR__ . '/validator_tests.php';
require_once __DIR__ . '/calendar_engine_tests.php';
require_once __DIR__ . '/calendar_engine_tests.php';
require_once __DIR__ . '/table_helper_tests.php';
require_once __DIR__ . '/decorator_tests.php';
require_once __DIR__ . '/util_tests.php';

/**
 * Class AllTests.
 */
class AllTests extends GroupTest
{
    /**
     * AllTests constructor.
     */
    public function __construct()
    {
        $this->GroupTest('All PEAR::Calendar Tests');
        $this->AddTestCase(new CalendarTests());
        $this->AddTestCase(new CalendarTabularTests());
        $this->AddTestCase(new ValidatorTests());
        $this->AddTestCase(new CalendarEngineTests());
        $this->AddTestCase(new TableHelperTests());
        $this->AddTestCase(new DecoratorTests());
        $this->AddTestCase(new UtilTests());
    }
}

$test = new AllTests();
$test->run(new HtmlReporter());
