<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';
$params                                  = array('view' => _EXTCAL_NAV_YEAR, 'file' => _EXTCAL_FILE_YEAR);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
include_once __DIR__ . '/header.php';

/* ========================================================================== */
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$cat  = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Getting eXtCal object's handler
$catHandler   = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
$eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

// Tooltips include
$xoTheme->addScript('modules/extcal/include/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

$form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $xoopsModuleConfig['agenda_nb_years_before'], $xoopsModuleConfig['agenda_nb_years_after']));

$form->addElement(getListCategories($cat));
$form->addElement(new XoopsFormButton('', 'form_submit', _SEND, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventYear($year, $cat), array('cat_id'));
$criteres = array(
    'periode'      => _EXTCAL_EVENTS_YEAR,
    'year'         => $year,
    'cat'          => $cat,
    'externalKeys' => 'cat_id');
$events   = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;
// Formating date
// $eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);
//
// // Treatment for recurring event
// $startYear = mktime(0, 0, 0, 1, 1, $year);
// $endYear = mktime(23, 59, 59, 12, 31, $year);
//
// $eventsArray = array();
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startYear, $endYear);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//     }
// }
//
// // Sort event array by event start
// usort($eventsArray, "orderEvents");

// $t=print_r($eventsArray,true);
// echo "<pre>{$t}</pre>";

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));

// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

$prevYear = $year - 1;
$nexYear  = $year + 1;
// Making navig data
$navig = array(
    'prev' => array(
        'uri'  => 'year=' . $prevYear,
        'name' => $prevYear),
    'this' => array(
        'uri'  => 'year=' . $year,
        'name' => $year),
    'next' => array(
        'uri'  => 'year=' . $nexYear,
        'name' => $nexYear));

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' ' . $navig['this']['name']);

// Assigning navig data to the template
$xoopsTpl->assign('navig', $navig);

//Display tooltip
$xoopsTpl->assign('showInfoBulle', $xoopsModuleConfig['showInfoBulle']);
$xoopsTpl->assign('showId', $xoopsModuleConfig['showId']);

// Assigning current form navig data to the template
$xoopsTpl->assign('selectedCat', $cat);
$xoopsTpl->assign('year', $year);
$xoopsTpl->assign('params', $params);

$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
$xoopsTpl->assign('list_position', $xoopsModuleConfig['list_position']);
// echoArray($tNavBar,true);

//---------------------------------------------------------------
if ($xoopsUser) {
    $xoopsTpl->assign('isAdmin', $xoopsUser->isAdmin());
    $canEdit = false;
    /* todo
        $canEdit
            =
            $permHandler->isAllowed($xoopsUser, 'extcal_cat_edit', $event['cat']['cat_id'])
                && $xoopsUser->getVar('uid') == $event['user']['uid'];
        $xoopsTpl->assign('canEdit', $canEdit);
    */
} else {
    $xoopsTpl->assign('isAdmin', false);
    $xoopsTpl->assign('canEdit', false);
}

//mb missing for xBootstrap templates by Angelo
$lang = array(
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
    'newevent'   => _MD_EXTCAL_NAV_NEW_EVENT);
// Assigning language data to the template
$xoopsTpl->assign('lang', $lang);
$xoopsTpl->assign('view', 'year');

include XOOPS_ROOT_PATH . '/footer.php';
