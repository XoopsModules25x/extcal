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

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

use XoopsModules\Extcal\{
    Helper,
    Utility,
    CategoryHandler,
    EventHandler,
    Time
};
use Xmf\Request;

require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_WEEK, 'file' => _EXTCAL_FILE_WEEK];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

global $xoopsUser, $xoopsTpl;
/** @var CategoryHandler $categoryHandler */
/** @var EventHandler $eventHandler */
/** @var Time $timeHandler */
/** @var Helper $helper */
$helper = Helper::getInstance();

/* ========================================================================== */
$year  = Request::getInt('year', date('Y'), 'GET');
$month = Request::getInt('month', date('n'), 'GET');
$day   = Request::getInt('day', date('j'), 'GET');
$cat   = Request::getInt('cat', 0, 'GET');
/* ========================================================================== */

// Validate the date (day, month and year)
$dayTS = mktime(0, 0, 0, $month, $day, $year);

//$offset = $helper->getConfig('week_start_day') - date('w', $dayTS);
$offset = date('w', $dayTS) + 7 - $helper->getConfig('week_start_day') < 7 ? date('w', $dayTS) + 7 - $helper->getConfig('week_start_day') : 0;

$dayTS -= ($offset * _EXTCAL_TS_DAY);
$year  = date('Y', $dayTS);
$month = date('n', $dayTS);
$day   = date('j', $dayTS);

$form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $helper->getConfig('agenda_nb_years_before'), $helper->getConfig('agenda_nb_years_after')));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(Utility::getListCategories($cat));
$form->addElement(new \XoopsFormButton('', '', _SUBMIT, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat), array('cat_id'));
$criteres = [
    'periode'      => _EXTCAL_EVENTS_WEEK,
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
// $eventHandler->formatEventsDate($events, $helper->getConfig('event_date_year'));
//
// Treatment for recurring event
// $startWeek = mktime(0, 0, 0, $month, $day, $year);
// $endWeek = $startWeek + _EXTCAL_TS_WEEK;
//
// $eventsArray = [];
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $helper->getConfig('event_date_week'));
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startWeek, $endWeek);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $helper->getConfig('event_date_week'));
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//     }
// }
//
// Sort event array by event start
// usort($eventsArray, "orderEvents");

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $categoryHandler->objectToArray($categoryHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$weekCalObj  = new Calendar_Week($year, $month, $day, $helper->getConfig('week_start_day'));
$pWeekCalObj = $weekCalObj->prevWeek('object');
$nWeekCalObj = $weekCalObj->nextWeek('object');
$navig       = [
    'prev' => [
        'uri'  => 'year=' . $pWeekCalObj->thisYear() . '&amp;month=' . $pWeekCalObj->thisMonth() . '&amp;day=' . $pWeekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_week'), $pWeekCalObj->getTimestamp()),
    ],
    'this' => [
        'uri'  => 'year=' . $weekCalObj->thisYear() . '&amp;month=' . $weekCalObj->thisMonth() . '&amp;day=' . $weekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_week'), $weekCalObj->getTimestamp()),
    ],
    'next' => [
        'uri'  => 'year=' . $nWeekCalObj->thisYear() . '&amp;month=' . $nWeekCalObj->thisMonth() . '&amp;day=' . $nWeekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_week'), $nWeekCalObj->getTimestamp()),
    ],
];

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', $helper->getModule()->getVar('name') . ' ' . $navig['this']['name']);

// Assigning navig data to the template
$xoopsTpl->assign('navig', $navig);

//Display tooltip
$xoopsTpl->assign('showInfoBulle', $helper->getConfig('showInfoBulle'));
$xoopsTpl->assign('showId', $helper->getConfig('showId'));

// Assigning current form navig data to the template
$xoopsTpl->assign('selectedCat', $cat);
$xoopsTpl->assign('year', $year);
$xoopsTpl->assign('month', $month);
$xoopsTpl->assign('day', $day);
$xoopsTpl->assign('params', $params);

$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
$xoopsTpl->assign('list_position', $helper->getConfig('list_position'));
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
$xoopsTpl->assign('view', 'week');

require_once XOOPS_ROOT_PATH . '/footer.php';
