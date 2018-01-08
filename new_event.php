<?php

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/header.php';

// Getting eXtCal object's handler
$eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

$permHandler = Extcal\Perm::getHandler();
$xoopsUser   = $xoopsUser ?: null;
if (count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0) {
    include XOOPS_ROOT_PATH . '/header.php';

    // Title of the page
    $xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

    // Display the submit form
    $form = $eventHandler->getEventForm();

    //$form->display();

    include XOOPS_ROOT_PATH . '/footer.php';
} else {
    redirect_header('index.php', 3);
}
