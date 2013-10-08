<?php

include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
$params = array('view' => _EXTCAL_NAV_AGENDA_WEEK, 'file' => _EXTCAL_FILE_AGENDA_WEEK);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.html";
include_once ('header.php');

/* ========================================================================== */
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$day = isset($_GET['day']) ? intval($_GET['day']) : date('j');
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;


// Validate the date (day, month and year)
$dayTS = mktime(0, 0, 0, $month, $day, $year);
//$offset = date('w', $dayTS) - $xoopsModuleConfig['week_start_day'];
$offset = date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day']<7 ? date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day'] : 0;
$dayTS = $dayTS - ($offset * _EXTCAL_TS_DAY);
$year = date('Y', $dayTS);
$month = date('n', $dayTS);
$day = date('j', $dayTS);


$form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year,$xoopsModuleConfig['agenda_nb_years_before'],$xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListDays($day));
$form->addElement(getListCategories($cat));
$form->addElement(new XoopsFormButton("", "", _SEND, "submit"));

// Assigning the form to the template
$form->assign($xoopsTpl);

  $mTranche = $xoopsModuleConfig['agenda_tranche_minutes']; //minutes
  $hStart = $xoopsModuleConfig['agenda_start_hour'];  //heure debut de journee
  $hEnd   = $xoopsModuleConfig['agenda_end_hour']; //heure fin de journee
  //$xoopsModuleConfig['agenda_nb_days_week'] = 5; 
  $nbJours = $xoopsModuleConfig['agenda_nb_days_week']; //nombre de jour

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat, $nbJours), array('cat_id'));
$criteres = array('periode' => _EXTCAL_EVENTS_AGENDA_WEEK,
                  'day' => $day,
                  'month' => $month,
                  'year' => $year,
                  'cat' => $cat,
                  'nbJours' => $nbJours,
                  'externalKeys' => 'cat_id');
$events = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;
// Formating date
// $eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);
// 
// Treatment for recurring event
// $startWeek = mktime(0, 0, 0, $month, $day, $year);
// $endWeek = $startWeek + _EXTCAL_TS_WEEK;
// 
// $eventsArray = array();
// foreach ($events as $event) {
//     if (!$event['event_isrecur']) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startWeek, $endWeek);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
//     }
// }
// 
// Sort event array by event start
// usort($eventsArray, "orderEvents");


//-------------------------------------------------------------------
// Assigning events to the template
//-------------------------------------------------------------------

  //$params['colJourWidth'] = intval((((500-50)/$nbJours)/500*100)+.5);
  $params['colJourWidth'] = intval((((500-50)/$nbJours)/500*100)+.6);
//  echo "agenda_week : {$dayTS}<br />";
  $tAgenda = agenda_getEvents($eventsArray, $dayTS, $hStart, $hEnd, $mTranche, $nbJours); 
  //$exp = print_r($eventsArray, true);
  $exp = print_r($tAgenda, true);
  //echo "<pre>{$exp}</pre>";

$xoopsTpl->assign('agenda', $tAgenda);
//-------------------------------------------------------------------

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$weekCalObj = new Calendar_Week($year, $month, $day, $xoopsModuleConfig['week_start_day']);
$pWeekCalObj = $weekCalObj->prevWeek('object');
$nWeekCalObj = $weekCalObj->nextWeek('object');

$navig = array('prev' => array('uri' => 'year=' . $pWeekCalObj->thisYear() 
                                      . '&amp;month=' . $pWeekCalObj->thisMonth() 
                                      . '&amp;day=' . $pWeekCalObj->thisDay(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $pWeekCalObj->getTimestamp())    ), 
               'this'  => array('uri' => 'year=' . $weekCalObj->thisYear() 
                                       . '&amp;month=' . $weekCalObj->thisMonth() 
                                       . '&amp;day=' . $weekCalObj->thisDay(), 
                                'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $weekCalObj->getTimestamp())    ), 
               'next'  => array('uri'  => 'year=' . $nWeekCalObj->thisYear() 
                                        . '&amp;month=' . $nWeekCalObj->thisMonth() 
                                        . '&amp;day=' . $nWeekCalObj->thisDay(), 
               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_week'], $nWeekCalObj->getTimestamp()) )
               );

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' ' 
                                   . $navig['this']['name']);

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
?>
