<?php

use XoopsModules\Extcal\{
    Helper,
    Perm
};

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/include/constantes.php';

// Getting eXtCal object's handler
$eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

$permHandler = Perm::getHandler();
$xoopsUser   = $xoopsUser ?: null;
if (count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0) {
    require_once XOOPS_ROOT_PATH . '/header.php';

    // Title of the page
    $xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

    // Display the submit form
    $form = $eventHandler->getEventForm();

    //$form->display();

    require_once XOOPS_ROOT_PATH . '/footer.php';
} else {
    redirect_header('index.php', 3);
}
