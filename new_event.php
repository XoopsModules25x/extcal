<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';
include_once __DIR__ . '/header.php';

// Getting eXtCal object's handler
$eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

$permHandler = ExtcalPerm::getHandler();
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
