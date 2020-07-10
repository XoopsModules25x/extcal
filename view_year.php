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

use Xmf\Request;
use XoopsModules\Extcal\{
    Helper,
    CategoryHandler,
    EventHandler,
    Utility
};

require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_YEAR, 'file' => _EXTCAL_FILE_YEAR];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

global $xoopsUser, $xoopsTpl;

/** @var Helper $helper */
$helper = Helper::getInstance();

/* ========================================================================== */
$year = \Xmf\Request::getInt('year', date('Y'), 'GET');
$cat  = \Xmf\Request::getInt('cat', 0, 'GET');

// Getting eXtCal object's handler
$categoryHandler   = Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
$eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

// Tooltips include
/** @var xos_opal_Theme $xoTheme */
$xoTheme->addScript('modules/extcal/assets/js/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

$form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $helper->getConfig('agenda_nb_years_before'), $helper->getConfig('agenda_nb_years_after')));

$form->addElement(Utility::getListCategories($cat));
$form->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventYear($year, $cat), array('cat_id'));
$criteres = [
    'periode'      => _EXTCAL_EVENTS_YEAR,
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
// // Treatment for recurring event
// $startYear = mktime(0, 0, 0, 1, 1, $year);
// $endYear = mktime(23, 59, 59, 12, 31, $year);
//
// $eventsArray = [];
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $helper->getConfig('event_date_week'));
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startYear, $endYear);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $helper->getConfig('event_date_week'));
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
$cats = $categoryHandler->objectToArray($categoryHandler->getAllCat($xoopsUser));

// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

$prevYear = $year - 1;
$nexYear  = $year + 1;
// Making navig data
$navig = [
    'prev' => [
        'uri'  => 'year=' . $prevYear,
        'name' => $prevYear,
    ],
    'this' => [
        'uri'  => 'year=' . $year,
        'name' => $year,
    ],
    'next' => [
        'uri'  => 'year=' . $nexYear,
        'name' => $nexYear,
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
$xoopsTpl->assign('view', 'year');

require_once XOOPS_ROOT_PATH . '/footer.php';
