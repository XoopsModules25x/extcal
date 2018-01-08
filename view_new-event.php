<?php

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_NEW_EVENT, 'file' => _EXTCAL_FILE_NEW_EVENT];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

/* ========================================================================== */
//Extcal\Utility::echoArray($_GET);

$eventId = (isset($_GET['event']) ? $_GET['event'] : 0);
$action  = (isset($_GET['action']) ? $_GET['action'] : 'edit');

//------------------------------------------------------------------------------

//exit;
if (count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0) {
    include XOOPS_ROOT_PATH . '/header.php';

    // Title of the page
    $xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

    // Display the submit form
    if (0 == $eventId) {
        $form = $eventHandler->getEventForm();
    } else {
        $form = $eventHandler->getEventForm('user', $action, ['event_id' => $eventId]);
    }
    $xoopsTpl->assign('formEdit', $form->render());

    //-----------------------------------------------
    $xoopsTpl->assign('params', $params);

    $tNavBar = getNavBarTabs($params['view']);
    $xoopsTpl->assign('tNavBar', $tNavBar);
    $xoopsTpl->assign('list_position', -1);
    //-----------------------------------------------

    //$form->display();

    //mb missing for xBootstrap templates by Angelo
    $lang = [
        'start'      => _MD_EXTCAL_START,
        'end'        => _MD_EXTCAL_END,
        'calmonth'   => _MD_EXTCAL_NAV_CALMONTH,
        'calweek'    => _MD_EXTCAL_NAV_CALWEEK,
        'year'       => _MD_EXTCAL_NAV_YEAR,
        'month'      => _MD_EXTCAL_NAV_MONTH,
        'week'       => _MD_EXTCAL_NAV_WEEK,
        'day'        => _MD_EXTCAL_NAV_DAY,
        'agendaweek' => _MD_EXTCAL_NAV_AGENDA_WEEK,
        'agendaday'  => _MD_EXTCAL_NAV_AGENDA_DAY,
        'search'     => _MD_EXTCAL_NAV_SEARCH,
        'newevent'   => _MD_EXTCAL_NAV_NEW_EVENT,
    ];
    // Assigning language data to the template
    $xoopsTpl->assign('lang', $lang);
    $xoopsTpl->assign('view', 'newevent');

    include XOOPS_ROOT_PATH . '/footer.php';
} else {
    redirect_header('index.php', 3);
}
