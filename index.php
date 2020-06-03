<?php

use XoopsModules\Extcal\{
    Helper
};

require_once __DIR__ . '/header.php';

//modif JJD
require_once __DIR__ . '/include/constantes.php';

/** @var Helper $helper */
$helper = Helper::getInstance();

header("Location: {$helper->getConfig('start_page')}");
