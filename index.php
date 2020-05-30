<?php

use XoopsModules\Extcal;

require_once __DIR__ . '/header.php';

//modif JJD
require_once __DIR__ . '/include/constantes.php';

/** @var Extcal\Helper $helper */
$helper = Extcal\Helper::getInstance();

header("Location: {$helper->getConfig('start_page')}");
