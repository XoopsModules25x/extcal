<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';

if (!isset($_GET['file'])) {
    $fileId = 0;
} else {
    $fileId = intval($_GET['file']);
}

$fileHandler = xoops_getmodulehandler(_EXTCAL_CLS_FILE, _EXTCAL_MODULE);

$file = $fileHandler->getFile($fileId);

header("Content-Type: " . $file->getVar('file_mimetype') . "");
header(
    "Content-Disposition: attachment; filename=\""
        . $file->getVar('file_nicename') . "\""
);

readfile(XOOPS_ROOT_PATH . "/uploads/extcal/" . $file->getVar('file_name'));
