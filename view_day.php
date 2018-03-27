<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use XoopsModules\Extcal;

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_DAY, 'file' => _EXTCAL_FILE_DAY];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

/* ========================================================================== */
$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$day   = isset($_GET['day']) ? (int)$_GET['day'] : date('j');
$cat   = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
/* ========================================================================== */

$form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $xoopsModuleConfig['agenda_nb_years_before'], $xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(Extcal\Utility::getListCategories($cat));
$form->addElement(new \XoopsFormButton('', '', _SUBMIT, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventDay($day, $month, $year, $cat), array('cat_id'));
$criteres = [
    'periode'      => _EXTCAL_EVENTS_DAY,
    'day'          => $day,
    'month'        => $month,
    'year'         => $year,
    'cat'          => $cat,
    'externalKeys' => 'cat_id',
];
$events   = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;

// Formating date
//$eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);

// Treatment for recurring event
// $startDay = mktime(0, 0, 0, $month, $day, $year);
// $endDay = $startDay + _EXTCAL_TS_DAY;
//$eventsArray = array();

// foreach ($events as $event) {
//
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startDay, $endDay);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//
//     }
//
// }

// Sort event array by event start
// usort($eventsArray, "orderEvents");

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$dayCalObj  = new Calendar_Day($year, $month, $day);
$pDayCalObj = $dayCalObj->prevDay('object');
$nDayCalObj = $dayCalObj->nextDay('object');

$navig = [
    'prev' => [
        'uri'  => 'year=' . $pDayCalObj->thisYear() . '&amp;month=' . $pDayCalObj->thisMonth() . '&amp;day=' . $pDayCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $pDayCalObj->getTimestamp()),
    ],
    'this' => [
        'uri'  => 'year=' . $dayCalObj->thisYear() . '&amp;month=' . $dayCalObj->thisMonth() . '&amp;day=' . $dayCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $dayCalObj->getTimestamp()),
    ],
    'next' => [
        'uri'  => 'year=' . $nDayCalObj->thisYear() . '&amp;month=' . $nDayCalObj->thisMonth() . '&amp;day=' . $nDayCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $nDayCalObj->getTimestamp()),
    ],
];

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
$xoopsTpl->assign('month', $month);
$xoopsTpl->assign('day', $day);
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
/** @var xos_opal_Theme $xoTheme */
$xoTheme->addScript('browse.php?modules/extcal/assets/js/highslide.js');
$xoTheme->addStylesheet('browse.php?modules/extcal/assets/js/highslide.css');

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
$xoopsTpl->assign('view', 'day');

include XOOPS_ROOT_PATH . '/footer.php';
