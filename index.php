<?php

use XoopsModules\Extcal;
/** @var Extcal\Helper $helper */
$helper = Extcal\Helper::getInstance();

include __DIR__ . '/../../mainfile.php';

//modif JJD
require_once __DIR__ . '/include/constantes.php';

header("Location: {$helper->getConfig('start_page')}");
