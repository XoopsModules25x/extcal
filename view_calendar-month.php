<?php

use XoopsModules\Extcal\{Helper,
    Utility,
    CategoryHandler,
    EventHandler,
    Time
};
use Xmf\Request;

require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_CALMONTH, 'file' => _EXTCAL_FILE_CALMONTH];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

global $xoopsUser, $xoopsTpl;

/** @var Time $timeHandler */
/** @var CategoryHandler $categoryHandler */
/** @var EventHandler $eventHandler */
/** @var Helper $helper */
$helper = Helper::getInstance();

/* ========================================================================== */
$year  = \Xmf\Request::getInt('year', date('Y'), 'GET');
$month = \Xmf\Request::getInt('month', date('n'), 'GET');
$cat   = \Xmf\Request::getInt('cat', 0, 'GET');
/* ========================================================================== */

$form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $helper->getConfig('agenda_nb_years_before'), $helper->getConfig('agenda_nb_years_after')));
$form->addElement(getListMonths($month));
$form->addElement(Utility::getListCategories($cat));
$form->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
// $events = $eventHandler->objectToArray($eventHandler->getEventCalendarMonth($month, $year, $cat), array('cat_id'));
$criteres = [
    'periode'      => _EXTCAL_EVENTS_CALENDAR_MONTH,
    'month'        => $month,
    'year'         => $year,
    'cat'          => $cat,
    'externalKeys' => 'cat_id',
];
$events   = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/

// Calculating timestamp for the begin and the end of the month
$startMonth = mktime(0, 0, 0, $month, 1, $year);
$endMonth   = mktime(23, 59, 59, $month + 1, 0, $year);

/*
*  Adding all event occuring during this month to an array indexed by day number
*/
$eventsArray = [];
foreach ($events as $event) {
    $eventHandler->formatEventDate($event, $helper->getConfig('event_date_month'));
    $eventHandler->addEventToCalArray($event, $eventsArray, $startMonth, $endMonth);
    //     if (!$event['event_isrecur']) {
    //         // Formating date
    //         $eventHandler->formatEventDate($event, $helper->getConfig('event_date_month'));
    //         $eventHandler->addEventToCalArray($event, $eventsArray, $startMonth, $endMonth);
    //     } else {
    //         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);
    //         // Formating date
    //         $eventHandler->formatEventsDate($recurEvents, $helper->getConfig('event_date_month'));
    //         foreach ($recurEvents as $recurEvent) {
    //             $eventHandler->addEventToCalArray($recurEvent, $eventsArray, $startMonth, $endMonth);
    //         }
    //     }
}

/*
*  Making an array to create tabbed output on the template
*/
// Flag current day
$selectedDays = [
    new Calendar_Day(date('Y', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($xoopsUser))), date('n', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($xoopsUser))), date('j', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($xoopsUser)))),
];

// Build calendar object
$monthCalObj  = new Calendar_Month_Weeks($year, $month, $helper->getConfig('week_start_day'));
$pMonthCalObj = $monthCalObj->prevMonth('object');
$nMonthCalObj = $monthCalObj->nextMonth('object');
$monthCalObj->build();

$tableRows = [];
$rowId     = 0;
$cellId    = 0;
while (false !== ($weekCalObj = $monthCalObj->fetch())) {
    $weekCalObj->build($selectedDays);
    $tableRows[$rowId]['weekInfo'] = [
        'week'  => $weekCalObj->thisWeek('n_in_year'),
        'day'   => $weekCalObj->thisDay(),
        'month' => $weekCalObj->thisMonth(),
        'year'  => $weekCalObj->thisYear(),
    ];
    while (false !== ($dayCalObj = $weekCalObj->fetch())) {
        $tableRows[$rowId]['week'][$cellId] = [
            'isEmpty'    => $dayCalObj->isEmpty(),
            'number'     => $dayCalObj->thisDay(),
            'isSelected' => $dayCalObj->isSelected(),
        ];
        if (@count($eventsArray[$dayCalObj->thisDay()]) > 0 && !$dayCalObj->isEmpty()) {
            $tableRows[$rowId]['week'][$cellId]['events'] = $eventsArray[$dayCalObj->thisDay()];
        } else {
            $tableRows[$rowId]['week'][$cellId]['events'] = '';
        }
        ++$cellId;
    }
    $cellId = 0;
    ++$rowId;
}

// Assigning events to the template
$xoopsTpl->assign('tableRows', $tableRows);

// Retriving categories
$cats = $categoryHandler->objectToArray($categoryHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Retriving weekdayNames
//$weekdayNames = Calendar_Util_Textual::weekdayNames();
//$weekdayNames = array('Dimanche','Mardi','Mercresi','Jeudi','Vendredi','Samedi');
$weekdayNames = [_CAL_SUNDAY, _CAL_MONDAY, _CAL_TUESDAY, _CAL_WEDNESDAY, _CAL_THURSDAY, _CAL_FRIDAY, _CAL_SATURDAY];

for ($i = 0; $i < $helper->getConfig('week_start_day'); ++$i) {
    $weekdayName    = array_shift($weekdayNames);
    $weekdayNames[] = $weekdayName;
}
// Assigning weekdayNames to the template
$xoopsTpl->assign('weekdayNames', $weekdayNames);

// Retriving monthNames
$monthNames = Calendar_Util_Textual::monthNames();

// Making navig data
$navig = [
    'prev' => [
        'uri'  => 'year=' . $pMonthCalObj->thisYear() . '&amp;month=' . $pMonthCalObj->thisMonth(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $pMonthCalObj->getTimestamp()),
    ],
    'this' => [
        'uri'  => 'year=' . $monthCalObj->thisYear() . '&amp;month=' . $monthCalObj->thisMonth(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $monthCalObj->getTimestamp()),
    ],
    'next' => [
        'uri'  => 'year=' . $nMonthCalObj->thisYear() . '&amp;month=' . $nMonthCalObj->thisMonth(),
        'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $nMonthCalObj->getTimestamp()),
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
$xoopsTpl->assign('params', $params);

$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
$xoopsTpl->assign('list_position', $helper->getConfig('list_position'));
// echoArray($tNavBar,true);

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
$xoopsTpl->assign('view', 'calmonth');

require_once XOOPS_ROOT_PATH . '/footer.php';
