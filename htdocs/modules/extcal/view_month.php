<?php

include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
$params = array('view' => _EXTCAL_NAV_MONTH, 'file' => _EXTCAL_FILE_MONTH);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.html";
include_once ('header.php');

/* ========================================================================== */
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
/* ========================================================================== */



$form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
$form->addElement(getListYears($year,$xoopsModuleConfig['agenda_nb_years_before'],$xoopsModuleConfig['agenda_nb_years_after']));
$form->addElement(getListMonths($month));
$form->addElement(getListCategories($cat));
$form->addElement(new XoopsFormButton("", "", _SEND, "submit"));

// Assigning the form to the template
$form->assign($xoopsTpl);

/**********************************************************************/
// Retriving events and formatting them
//$events = $eventHandler->objectToArray($eventHandler->getEventMonth($month, $year, $cat), array('cat_id'));
$criteres = array('periode' => _EXTCAL_EVENTS_MONTH,
                  'month' => $month,
                  'year' => $year,
                  'cat' => $cat,
                  'externalKeys' => 'cat_id');
$events = $eventHandler->getEventsOnPeriode($criteres);
/**********************************************************************/
$eventsArray = $events;

// Formating date
//$eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);

// Treatment for recurring event
$startMonth = mktime(0, 0, 0, $month, 1, $year);
$endMonth = mktime(23, 59, 59, $month, 31, $year);

// $eventsArray = array();
// foreach ($events as $event) {
//     if ($event['event_isrecur']==0) {
//         // Formating date
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     } else {
//         //$recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);
//         // Formating date
//         //$eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         //$eventsArray = array_merge($eventsArray, $recurEvents);
//         $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
//         $eventsArray[] = $event;
//     }
// }

// $criteria  = new criteria('event_isrecur',1);
// $recurrents =  $eventHandler->getAllEvents($criteria, false);
// //echoArray($recurrents,false,'<b>Evennements reccurents</b>');
// 
// for ($h=0,$count=count($recurrents);$h<$count;$h++){
//         $recurEvents = $eventHandler->getRecurEventToDisplay($recurrents[$h], $startMonth, $endMonth);
// //        echoArray($recurEvents,false,"liste des evennements a ajouter pour " . $recurrents[$h]['event_title']);
//         // Formating date
//         $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
//         $eventsArray = array_merge($eventsArray, $recurEvents);
// }
// 
// Sort event array by event start
//usort($eventsArray, "orderEvents");
//echoArray($eventsArray,false,'<b>Evennements reccurents</b>');

// Assigning events to the template
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
// Assigning categories to the template
$xoopsTpl->assign('cats', $cats);

// Making navig data
$monthCalObj = new Calendar_Month_Weekdays($year, $month);
$pMonthCalObj = $monthCalObj->prevMonth('object');
$nMonthCalObj = $monthCalObj->nextMonth('object');
$navig = array('prev' => array('uri' => 'year=' . $pMonthCalObj->thisYear() 
                                      . '&amp;month=' . $pMonthCalObj->thisMonth(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $pMonthCalObj->getTimestamp())), 
              'this' => array( 'uri'  => 'year=' . $monthCalObj->thisYear() 
                                       . '&amp;month=' . $monthCalObj->thisMonth(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $monthCalObj->getTimestamp())    ), 
              'next'  => array('uri' => 'year=' . $nMonthCalObj->thisYear() 
                                      . '&amp;month=' . $nMonthCalObj->thisMonth(), 
                               'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $nMonthCalObj->getTimestamp())    )
              );

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' ' . 
                                     $navig['this']['name']
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


include XOOPS_ROOT_PATH . '/footer.php';
?>
