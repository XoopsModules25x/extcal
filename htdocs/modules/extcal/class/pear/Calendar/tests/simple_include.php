<?php
// $Id: simple_include.php 1511 2011-09-01 20:56:07Z jjdai $
if (!defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', dirname(dirname(dirname(__DIR__))) . '/simpletest/');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
