<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';
$params                                  = array('view' => _EXTCAL_NAV_WEEK, 'file' => _EXTCAL_FILE_WEEK);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
include_once __DIR__ . '/header.php';

/* ========================================================================== */
$year  = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$day   = isset($_GET['day']) ? intval($_GET['day']) : date('j');
$cat   = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
/* ========================================================================== */

// Validate the date (day, month and year)
$dayTS = mktime(0, 0, 0, $month, $day, $year);

//$offset = $xoopsModuleConfig['week_start_day'] - date('w', $dayTS);
$offset = date('w', $dayTS) + 7 - $xoopsModuleConfig['week_start_day'] < 7 ? date('w', $dayTS) + 7 - $xoopsModuleConfig['week_start_day'] : 0;

$dayTS = $dayTS - ($offset * _EXTCAL_TS_DAY);
$year  = date('Y', $dayTS);
$month = date('n', $dayTS);
$day   = date('j', $dayTS);

$form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $xoopsModuleConfig['agenda_nb_years_before'], $xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(getListCategories($cat));
$form->addElement(new XoopsFormButton("", "", _SEND, "submit"));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat), array('cat_id'));
$criteres = array(
    'periode'      => _EXTCAL_EVENTS_WEEK,
    'day'          => $day,
    'month'        => $month,
    'year'         => $year,
    'cat'          => $cat,
    'externalKeys' => 'cat_id'
);
$events   = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;
// Formating date
// $eventHandler->formatEventsDate($events, $extcalConfig['event_date_year']);
//
// Treatment for recurring event
// $startWeek = mktime(0, 0, 0, $month, $day, $year);
// $endWeek = $startWeek + _EXTCAL_TS_WEEK;
//
// $eventsArray = array();
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $extcalConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startWeek, $endWeek);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $extcalConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//     }
// }
//
// Sort event array by event start
// usort($eventsArray, "orderEvents");

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$weekCalObj  = new Calendar_Week($year, $month, $day, $extcalConfig['week_start_day']);
$pWeekCalObj = $weekCalObj->prevWeek('object');
$nWeekCalObj = $weekCalObj->nextWeek('object');
$navig       = array(
    'prev' => array(
        'uri'  => 'year=' . $pWeekCalObj->thisYear() . '&amp;month=' . $pWeekCalObj->thisMonth() . '&amp;day=' . $pWeekCalObj->thisDay(),
        'name' => $extcalTimeHandler->getFormatedDate($extcalConfig['nav_date_week'], $pWeekCalObj->getTimestamp())
    ),
    'this' => array(
        'uri'  => 'year=' . $weekCalObj->thisYear() . '&amp;month=' . $weekCalObj->thisMonth() . '&amp;day=' . $weekCalObj->thisDay(),
        'name' => $extcalTimeHandler->getFormatedDate($extcalConfig['nav_date_week'], $weekCalObj->getTimestamp())
    ),
    'next' => array(
        'uri'  => 'year=' . $nWeekCalObj->thisYear() . '&amp;month=' . $nWeekCalObj->thisMonth() . '&amp;day=' . $nWeekCalObj->thisDay(),
        'name' => $extcalTimeHandler->getFormatedDate($extcalConfig['nav_date_week'], $nWeekCalObj->getTimestamp())
    )
);

// Title of the page
$xoopsTpl->assign(
    'xoops_pagetitle',
    $xoopsModule->getVar('name') . ' ' . $navig['this']['name']
);

// Assigning navig data to the template
$xoopsTpl->assign('navig', $navig);

//Display tooltip
$xoopsTpl->assign('showInfoBulle', $extcalConfig['showInfoBulle']);
$xoopsTpl->assign('showId', $extcalConfig['showId']);

// Assigning current form navig data to the template
$xoopsTpl->assign('selectedCat', $cat);
$xoopsTpl->assign('year', $year);
$xoopsTpl->assign('month', $month);
$xoopsTpl->assign('day', $day);
$xoopsTpl->assign('params', $params);

$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
$xoopsTpl->assign('list_position', $extcalConfig['list_position']);
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

$xoTheme->addScript('browse.php?modules/extcal/assets/js/highslide.js');
$xoTheme->addStylesheet('browse.php?modules/extcal/assets/js/highslide.css');

//mb missing for xBootstrap templates by Angelo
$lang = array(
    'start' => _MD_EXTCAL_START,
    'end' => _MD_EXTCAL_END,
    'calmonth' => _MD_EXTCAL_NAV_CALMONTH,
    'calweek' => _MD_EXTCAL_NAV_CALWEEK,
    'year' => _MD_EXTCAL_NAV_YEAR,
    'month' => _MD_EXTCAL_NAV_MONTH,
    'week' => _MD_EXTCAL_NAV_WEEK,
    'day' => _MD_EXTCAL_NAV_DAY,
    'agendaweek' => _MD_EXTCAL_NAV_AGENDA_WEEK,
    'agendaday' => _MD_EXTCAL_NAV_AGENDA_DAY,
    'search' => _MD_EXTCAL_NAV_SEARCH,
    'newevent' => _MD_EXTCAL_NAV_NEW_EVENT
);

// Assigning language data to the template
$xoopsTpl->assign('lang', $lang);
$xoopsTpl->assign('view', "week");

include XOOPS_ROOT_PATH . '/footer.php';
