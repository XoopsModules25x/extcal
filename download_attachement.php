<?php

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';

if (!isset($_GET['file'])) {
    $fileId = 0;
} else {
    $fileId = (int)$_GET['file'];
}
/** @var Extcal\FileHandler $fileHandler */
$fileHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_FILE);

$file = $fileHandler->getFile($fileId);

header('Content-Type: ' . $file->getVar('file_mimetype') . '');
header('Content-Disposition: attachment; filename="' . $file->getVar('file_nicename') . '"');

readfile(XOOPS_ROOT_PATH . '/uploads/extcal/' . $file->getVar('file_name'));
