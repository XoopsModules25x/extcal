<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include __DIR__ . '/class/form/extcalform.php';
include __DIR__ . '/class/perm.php';
include_once __DIR__ . '/include/constantes.php';

$permHandler = ExtcalPerm::getHandler();
$xoopsUser   = $xoopsUser ? $xoopsUser : null;

if (count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) == 0
    && count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_edit')) == 0
) {
    redirect_header("index.php", 3);
    exit;
}

$params                                  = array('view' => _EXTCAL_NAV_NEW_EVENT, 'file' => _EXTCAL_FILE_NEW_EVENT);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
include XOOPS_ROOT_PATH . '/header.php';
/* ========================================================================== */

// Tooltips include
$xoTheme->addScript('modules/extcal/include/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

if (!isset($_GET['event'])) {
    $eventId = 0;
} else {
    $eventId = intval($_GET['event']);
}
if (!isset($_GET['action'])) {
    $action = 'edit';
} else {
    $action = $_GET['action'];
}

// Getting eXtCal object's handler
$eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

include XOOPS_ROOT_PATH . '/header.php';

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

// Display the submit form
$form = $eventHandler->getEventForm('user', $action, array('event_id' => $eventId));
$form->display();

include XOOPS_ROOT_PATH . '/footer.php';
