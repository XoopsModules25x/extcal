<?php

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_CALWEEK, 'file' => _EXTCAL_FILE_CALWEEK];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

/* ========================================================================== */
$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$day   = isset($_GET['day']) ? (int)$_GET['day'] : date('j');
$cat   = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
/* ========================================================================== */

// Validate the date (day, month and year)
$dayTS = mktime(0, 0, 0, $month, $day, $year);
//$offset = date('w', $dayTS) - $xoopsModuleConfig['week_start_day'];
$offset = date('w', $dayTS) + 7 - $xoopsModuleConfig['week_start_day'] < 7 ? date('w', $dayTS) + 7 - $xoopsModuleConfig['week_start_day'] : 0;
$dayTS  -= ($offset * _EXTCAL_TS_DAY);
$year   = date('Y', $dayTS);
$month  = date('n', $dayTS);
$day    = date('j', $dayTS);

//echo $dayTS . '   dayTS-2 <br>';
//echo gmdate("Y-m-d\TH:i:s\Z", $dayTS). '   dayTS-2 <br>';

$form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year, $xoopsModuleConfig['agenda_nb_years_before'], $xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(Extcal\Utility::getListCategories($cat));
$form->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventCalendarWeek($day, $month, $year, $cat), array('cat_id'));

$criteres = [
    'periode'      => _EXTCAL_EVENTS_CALENDAR_WEEK,
    'day'          => $day,
    'month'        => $month,
    'year'         => $year,
    'cat'          => $cat,
    'externalKeys' => 'cat_id',
];
$events   = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
//$eventsArray = $events;

// Calculating timestamp for the begin and the end of the month
$startWeek = mktime(0, 0, 0, $month, $day, $year);
$endWeek   = $startWeek + _EXTCAL_TS_WEEK - 1;

//echo $startWeek . '   startWeek <br>';
//echo gmdate("Y-m-d\TH:i:s\Z", $startWeek). '   startWeek <br>';
//echo $endWeek . '   endWeek <br>';
//echo gmdate("Y-m-d\TH:i:s\Z", $endWeek). '   endWeek <br>';

/*
*  Adding all event occuring during this week to an array indexed by day number
*/
$eventsArray = [];
foreach ($events as $event) {
    $eventHandler->addEventToCalArray($event, $eventsArray, $startWeek, $endWeek);
    //     if (!$event['event_isrecur']) {
    //         // Formating date
    //         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
    //         $eventHandler->addEventToCalArray($event, $eventsArray, $startWeek, $endWeek);
    //     } else {
    //         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startWeek, $endWeek);
    //         // Formating date
    //         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
    //         foreach ($recurEvents as $recurEvent) {
    //             $eventHandler->addEventToCalArray($recurEvent, $eventsArray, $startWeek, $endWeek);
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
$weekCalObj  = new Calendar_Week($year, $month, $day, $xoopsModuleConfig['week_start_day']);
$pWeekCalObj = $weekCalObj->prevWeek('object');
$nWeekCalObj = $weekCalObj->nextWeek('object');
$weekCalObj->build($selectedDays);

$week   = [];
$cellId = 0;
while ($dayCalObj = $weekCalObj->fetch()) {
    $week[$cellId] = [
        'isEmpty'    => $dayCalObj->isEmpty(),
        'dayNumber'  => $dayCalObj->thisDay(),
        'month'      => $dayCalObj->thisMonth(),
        'year'       => $dayCalObj->thisYear(),
        'isSelected' => $dayCalObj->isSelected(),
    ];
    if (!$dayCalObj->isEmpty() && @count($eventsArray[$dayCalObj->thisDay()]) > 0) {
        $week[$cellId]['events'] = $eventsArray[$dayCalObj->thisDay()];
    } else {
        $week[$cellId]['events'] = '';
    }
    ++$cellId;
}

// Assigning events to the template
$xoopsTpl->assign('week', $week);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Retriving weekdayNames
//$weekdayNames = Calendar_Util_Textual::weekdayNames();
$weekdayNames = [_CAL_SUNDAY, _CAL_MONDAY, _CAL_TUESDAY, _CAL_WEDNESDAY, _CAL_THURSDAY, _CAL_FRIDAY, _CAL_SATURDAY];
for ($i = 0; $i < $xoopsModuleConfig['week_start_day']; ++$i) {
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
        'uri'  => 'year=' . $pWeekCalObj->thisYear() . '&amp;month=' . $pWeekCalObj->thisMonth() . '&amp;day=' . $pWeekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $pWeekCalObj->getTimestamp()),
    ],
    'this' => [
        'uri'  => 'year=' . $weekCalObj->thisYear() . '&amp;month=' . $weekCalObj->thisMonth() . '&amp;day=' . $weekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $weekCalObj->getTimestamp()),
    ],
    'next' => [
        'uri'  => 'year=' . $nWeekCalObj->thisYear() . '&amp;month=' . $nWeekCalObj->thisMonth() . '&amp;day=' . $nWeekCalObj->thisDay(),
        'name' => $timeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $nWeekCalObj->getTimestamp()),
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
$xoopsTpl->assign('view', 'calweek');

include XOOPS_ROOT_PATH . '/footer.php';
