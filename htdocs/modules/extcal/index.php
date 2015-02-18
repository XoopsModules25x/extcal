<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';

//modif JJD
include_once __DIR__ . '/include/constantes.php';

header("Location: {$xoopsModuleConfig['start_page']}");
