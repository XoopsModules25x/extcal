<?php

include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';
$params                                  = array(
    'view' => _EXTCAL_NAV_SEARCH,
    'file' => _EXTCAL_FILE_SEARCH);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
include_once __DIR__ . '/header.php';

//needed to save the state of the form, so we don't show on the first time the list of available events
$num_tries = isset($_POST['num_tries']) ? $_POST['num_tries'] + 1 : 0;

/* ========================================================================== */
/***************************************************************/
/*  ajout des elements de recherche                            */
/***************************************************************/
$searchExp = isset($_POST['searchExp']) ? $_POST['searchExp'] : '';
$andor     = isset($_POST['andor']) ? $_POST['andor'] : '';
$year      = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');
$month     = isset($_POST['month']) ? (int)$_POST['month'] : date('n');
$day       = isset($_POST['day']) ? (int)$_POST['day'] : 0;
$cat       = isset($_POST['cat']) ? (int)$_POST['cat'] : 0;
$orderby1  = isset($_POST['orderby1']) ? $_POST['orderby1'] : 'cat_name ASC';
$orderby2  = isset($_POST['orderby2']) ? $_POST['orderby2'] : 'event_title ASC';
$orderby3  = isset($_POST['orderby3']) ? $_POST['orderby3'] : '';
/* ========================================================================== */

//$orderby = isset($_GET['orderby']) ? (int)($_GET['orderby']) : 0;

//---------------------------------------------------------------
$search              = array();
$exp                 = new XoopsFormText(_MD_EXTCAL_EXPRESSION, 'searchExp', 80, 80, $searchExp);
$search['searchExp'] = $exp->render();
$search['andor']     = getListAndOr('andor', '', $andor)->render();
//$search['year']  = getListYears($year,$xoopsModuleConfig['agenda_nb_years_before'],$xoopsModuleConfig['agenda_nb_years_after'], true)->render();
$search['year']  = getListYears($year, 2, 5, true)->render();
$search['month'] = getListMonths($month, true)->render();
$search['day']   = getListDays($day, true)->render();

//$search['cat']   = implode('', getCheckeCategories());
$search['cat'] = getListCategories($cat, true, 'cat')->render();

$search['orderby1'] = getListOrderBy('orderby1', '', $orderby1, false)->render();
$search['orderby2'] = getListOrderBy('orderby2', '', $orderby2, true)->render();
$search['orderby3'] = getListOrderBy('orderby3', '', $orderby3, true)->render();

//echoArray($search,true);
$xoopsTpl->assign('search', $search);
/***************************************************************/

// $form = new XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
// // $form->addElement(getListYears($year,$xoopsModuleConfig['agenda_nb_years_before'],$xoopsModuleConfig['agenda_nb_years_after'], true));
// // $form->addElement(getListMonths($month, rtue));
// $form->addElement(getListCategories($cat));
// $form->addElement(getListOrderBy($orderby));
//
// $form->addElement(new XoopsFormText(_MD_EXTCAL_SEARCH_EXP, 'searchExp', 80, 80, $searchExp));
//
// $form->addElement(new XoopsFormButton("", "", _SEND, "submit"));
//
// // Assigning the form to the template
// $form->assign($xoopsTpl);

// Retriving events
//echoArray($_GET, false);
$orderBy = array(
    $orderby1,
    $orderby2,
    $orderby3);
$userId  = 0;
$user    = '';
//get all events for the date
$events = $eventHandler->getSearchEvent2($year, $month, $day, $cat, $searchExp, $andor, $orderBy, $userId, $user);

$eventHandler->serverTimeToUserTimes($events);

// Formating date
$eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_year']);

// Treatment for recurring event
$startMonth     = mktime(0, 0, 0, $month, 1, $year);
$daysInTheMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$endMonth       = mktime(23, 59, 59, $month, $daysInTheMonth, $year);

//$startMonth = mktime(0, 0, 0, 1, 1, 2011);
//$endMonth   = mktime(23, 59, 59, 12, 31, 2011);

//echo "Start & End Month ===><br>{$startMonth}<br>{$endMonth}<br>";

$eventsArray = array();
foreach ($events as $event) {
    if (!$event['event_isrecur']) {
        // Formating date
        $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_week']);
        $eventsArray[] = $event;
    } else {
        $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);

        // Formating date
        $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
        //$eventsArray = array_merge($eventsArray, $recurEvents);
    }
}

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('event_isrecur', 1));

if ($cat > 0) {
    $criteria->add(new Criteria('cat_id', $cat));
}

//$criteria = new criteria('event_isrecur', 1);

$recurrents = $eventHandler->getAllEvents($criteria, false);
$catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);

//=========================================
for ($h = 0, $count = count($recurrents); $h < $count; ++$h) {

    //    $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);
    $recurEvents = $eventHandler->getRecurEventToDisplay($recurrents[$h], $startMonth, $endMonth);

    $categoryObject = $catHandler->getCat($recurrents[$h]['cat_id']);

    //    echo '------------ CATEGORY OBJECT ----------------------------';
    //    var_dump($categoryObject);
    //
    //    $recurEvents['cat']['cat_name']        = $categoryObject->vars['cat_name']['value'];
    //    $recurEvents['cat']['cat_color']       = $categoryObject->vars['cat_color']['value'];
    //    $recurEvents['cat']['cat_light_color'] = eclaircirCouleur($categoryObject->vars['cat_color']['value'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);

    // Formating date
    $eventHandler->formatEventsDate($recurEvents, $xoopsModuleConfig['event_date_week']);
    foreach ($recurEvents as $val) {
        $val['cat']['cat_name']        = $categoryObject->vars['cat_name']['value'];
        $val['cat']['cat_color']       = $categoryObject->vars['cat_color']['value'];
        $val['cat']['cat_light_color'] = eclaircirCouleur($categoryObject->vars['cat_color']['value'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);
        $recurEventsArray[]            = $val;
    }
}

$eventsArray = array_merge((array)$eventsArray, (array)$recurEventsArray);

// Sort event array by event start
//usort($eventsArray, "orderEvents");
//echoArray($eventsArray,false);

// Assigning events to the template
$xoopsTpl->assign('evenements_trouves', sprintf(_MD_EXTCAL_EVENTS_FOUND, count($eventsArray)));
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories and  Assigning categories to the template
$cats = $catHandler->objectToArray($catHandler->getAllCat($xoopsUser));
$xoopsTpl->assign('cats', $cats);

// Making navig data
// $monthCalObj = new Calendar_Month_Weekdays($year, $month);
// $pMonthCalObj = $monthCalObj->prevMonth('object');
// $nMonthCalObj = $monthCalObj->nextMonth('object');
// $navig = array('prev' => array('uri' => 'year=' . $pMonthCalObj->thisYear()
//                                       . '&amp;month=' . $pMonthCalObj->thisMonth(),
//                                'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $pMonthCalObj->getTimestamp())),
//               'this' => array( 'uri'  => 'year=' . $monthCalObj->thisYear()
//                                        . '&amp;month=' . $monthCalObj->thisMonth(),
//                                'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $monthCalObj->getTimestamp())    ),
//               'next'  => array('uri' => 'year=' . $nMonthCalObj->thisYear()
//                                       . '&amp;month=' . $nMonthCalObj->thisMonth(),
//                                'name' => $extcalTimeHandler->getFormatedDate($xoopsModuleConfig['nav_date_month'], $nMonthCalObj->getTimestamp())    )
//               );
//
// // Title of the page
// $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' ' .
//                                      $navig['this']['name']
// );
//
// // Assigning navig data to the template
// $xoopsTpl->assign('navig', $navig);

//Display tooltip
$xoopsTpl->assign('showInfoBulle', $xoopsModuleConfig['showInfoBulle']);
$xoopsTpl->assign('showId', $xoopsModuleConfig['showId']);

// Assigning current form navig data to the template
$xoopsTpl->assign('selectedCat', $cat);
$xoopsTpl->assign('year', $year);
$xoopsTpl->assign('month', $month);

$xoopsTpl->assign('num_tries', $num_tries);

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

//---------------------------------------------------------------
$xoopsTpl->assign('params', $params);
$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
$xoopsTpl->assign('list_position', -1);
// echoArray($tNavBar,true);
//---------------------------------------------------------------

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
$xoopsTpl->assign('view', 'search');

include XOOPS_ROOT_PATH . '/footer.php';
