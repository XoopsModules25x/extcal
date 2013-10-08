<?php

include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
$params = array('view' => _EXTCAL_NAV_AGENDA_DAY, 'file' => _EXTCAL_FILE_AGENDA_DAY);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.html";
include_once ('header.php');

/* ========================================================================== */
//recupe des variables get
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$day = isset($_GET['day']) ? intval($_GET['day']) : date('j');
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
/* ========================================================================== */

//echo "{$params['view']}-{$year}-{$month}-{$day}<hr>extcal_{$params['view']}.html<br>";

$form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year,$xoopsModuleConfig['agenda_nb_years_before'],$xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(getListCategories($cat));
$form->addElement(new XoopsFormButton("", "", _SEND, "submit"));

//------------------------------------------------------
// Assigning the form to the template
$form->assign($xoopsTpl);


  $mTranche = $xoopsModuleConfig['agenda_tranche_minutes']; //minutes
  $hStart = $xoopsModuleConfig['agenda_start_hour'];  //heure debut de journee
  $hEnd   = $xoopsModuleConfig['agenda_end_hour']; //heure fin de journee
  $nbJours = $xoopsModuleConfig['agenda_nb_days_day']; //nombre de jour

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat, $nbJours), array('cat_id'));
$criteres = array('periode' => _EXTCAL_EVENTS_DAY,
                  'day' => $day,
                  'month' => $month,
                  'year' => $year,
                  'cat' => $cat,
                  'nbJours' => $nbJours,
                  'externalKeys' => 'cat_id');
$events = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;
$startDay = mktime(0, 0, 0, $month, $day, $year);
$endDay = $startDay + _EXTCAL_TS_DAY;
// Formating date
// $eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);
// 
// // Treatment for recurring event
// 
// $eventsArray = array();
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startDay, $endDay);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//     }
// }
// 
// // Sort event array by event start
// usort($eventsArray, "orderEvents");
// -------------------------------------------------------------------
// hack JJD pour affichage agenda
// -------------------------------------------------------------------

//-------------------------------------------------------------------
// Assigning events to the template
//-------------------------------------------------------------------


  $tAgenda = agenda_getEvents($eventsArray, $startDay, $hStart, $hEnd, $mTranche, $nbJours);
  //$exp = print_r($eventsArray, true);
  $exp = print_r($tAgenda, true);
  //echo "<pre>{$exp}</pre>";


$xoopsTpl->assign('agenda', $tAgenda);
//$xoopsTpl->assign('events', $eventsArray);
//-------------------------------------------------------------------

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$dayCalObj = new Calendar_Day($year, $month, $day);
$pDayCalObj = $dayCalObj->prevDay('object');
$nDayCalObj = $dayCalObj->nextDay('object');

$navig = array('prev' => array('uri' => 'year=' . $pDayCalObj->thisYear() 
                                      . '&amp;month=' . $pDayCalObj->thisMonth() 
                                      . '&amp;day=' . $pDayCalObj->thisDay(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $pDayCalObj->getTimestamp())    ), 
               'this' => array('uri' => 'year=' . $dayCalObj->thisYear() 
                                      . '&amp;month=' . $dayCalObj->thisMonth() 
                                      . '&amp;day=' . $dayCalObj->thisDay(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $dayCalObj->getTimestamp())    ), 
               'next' => array('uri' => 'year=' . $nDayCalObj->thisYear() 
                                      . '&amp;month=' . $nDayCalObj->thisMonth() 
                                      . '&amp;day=' . $nDayCalObj->thisDay(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_day'], $nDayCalObj->getTimestamp())    )
              );

// Title of the page
$xoopsTpl->assign(
    'xoops_pagetitle',
    $xoopsModule->getVar('name') . ' ' . $navig['this']['name']
);

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

include XOOPS_ROOT_PATH . '/footer.php';
