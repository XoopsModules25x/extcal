<?php

use XoopsModules\Extcal;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/include/constantes.php';
$params                                  = [
    'view' => _EXTCAL_NAV_SEARCH,
    'file' => _EXTCAL_FILE_SEARCH,
];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";
require_once __DIR__ . '/header.php';

/** @var Extcal\Helper $helper */
$helper = Extcal\Helper::getInstance();

$recurEventsArray = [];
//needed to save the state of the form, so we don't show on the first time the list of available events
$num_tries = isset($_POST['num_tries']) ? $_POST['num_tries'] + 1 : 0;

/* ========================================================================== */
/***************************************************************/
/*  ajout des elements de recherche                            */
/***************************************************************/
$searchExp = \Xmf\Request::getString('searchExp', '', 'POST');
$andor     = \Xmf\Request::getString('andor', '', 'POST');
$year      = \Xmf\Request::getInt('year', date('Y'), 'POST');
$month     = \Xmf\Request::getInt('month', date('n'), 'POST');
$day       = \Xmf\Request::getInt('day', 0, 'POST');
$cat       = \Xmf\Request::getInt('cat', 0, 'POST');
$orderby1  = isset($_POST['orderby1']) ? $_POST['orderby1'] : 'cat_name ASC';
$orderby2  = isset($_POST['orderby2']) ? $_POST['orderby2'] : 'event_title ASC';
$orderby3  = \Xmf\Request::getString('orderby3', '', 'POST');
/* ========================================================================== */

//$orderby = isset($_GET['orderby']) ? (int)($_GET['orderby']) : 0;

//---------------------------------------------------------------
$search              = [];
$exp                 = new \XoopsFormText(_MD_EXTCAL_EXPRESSION, 'searchExp', 80, 80, $searchExp);
$search['searchExp'] = $exp->render();
$search['andor']     = Extcal\Utility::getListAndOr('andor', '', $andor)->render();
//$search['year']  = getListYears($year,$helper->getConfig('agenda_nb_years_before'),$helper->getConfig('agenda_nb_years_after'), true)->render();
$search['year']  = getListYears($year, 2, 5, true)->render();
$search['month'] = getListMonths($month, true)->render();
$search['day']   = getListDays($day, true)->render();

//$search['cat']   = implode('', getCheckeCategories());
$search['cat'] = Extcal\Utility::getListCategories($cat, true, 'cat')->render();

$search['orderby1'] = Extcal\Utility::getListOrderBy('orderby1', '', $orderby1, false)->render();
$search['orderby2'] = Extcal\Utility::getListOrderBy('orderby2', '', $orderby2, true)->render();
$search['orderby3'] = Extcal\Utility::getListOrderBy('orderby3', '', $orderby3, true)->render();

//echoArray($search,true);
$xoopsTpl->assign('search', $search);
/***************************************************************/

// $form = new \XoopsSimpleForm('', 'navigSelectBox', $params['file'], 'get');
// // $form->addElement(getListYears($year,$helper->getConfig('agenda_nb_years_before'),$helper->getConfig('agenda_nb_years_after'), true));
// // $form->addElement(getListMonths($month, rtue));
// $form->addElement(getListCategories($cat));
// $form->addElement(Extcal\Utility::getListOrderBy($orderby));
//
// $form->addElement( new \XoopsFormText(_MD_EXTCAL_SEARCH_EXP, 'searchExp', 80, 80, $searchExp));
//
// $form->addElement( new \XoopsFormButton("", "", _SEND, "submit"));
//
// // Assigning the form to the template
// $form->assign($xoopsTpl);

// Retriving events
//echoArray($_GET, false);
$orderBy = [
    $orderby1,
    $orderby2,
    $orderby3,
];
$userId  = 0;
$user    = '';
//get all events for the date
$events = $eventHandler->getSearchEvent2($year, $month, $day, $cat, $searchExp, $andor, $orderBy, $userId, $user);

$eventHandler->serverTimeToUserTimes($events);

// Formating date
$eventHandler->formatEventsDate($events, $helper->getConfig('event_date_year'));

// Treatment for recurring event
$startMonth     = mktime(0, 0, 0, $month, 1, $year);
$daysInTheMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$endMonth       = mktime(23, 59, 59, $month, $daysInTheMonth, $year);

//$startMonth = mktime(0, 0, 0, 1, 1, 2011);
//$endMonth   = mktime(23, 59, 59, 12, 31, 2011);

//echo "Start & End Month ===><br>{$startMonth}<br>{$endMonth}<br>";

$eventsArray = [];
foreach ($events as $event) {
    if (!$event['event_isrecur']) {
        // Formating date
        $eventHandler->formatEventDate($event, $helper->getConfig('event_date_week'));
        $eventsArray[] = $event;
    } else {
        $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);

        // Formating date
        $eventHandler->formatEventsDate($recurEvents, $helper->getConfig('event_date_week'));
        //$eventsArray = array_merge($eventsArray, $recurEvents);
    }
}

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('event_isrecur', 1));

if ($cat > 0) {
    $criteria->add(new \Criteria('cat_id', $cat));
}

//$criteria =  new \Criteria('event_isrecur', 1);

$recurrents = $eventHandler->getAllEvents($criteria, false);
//$categoryHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
$categoryHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);

//=========================================
foreach ($recurrents as $h => $hValue) {
    //    $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);
    $recurEvents = $eventHandler->getRecurEventToDisplay($recurrents[$h], $startMonth, $endMonth);

    $categoryObject = $categoryHandler->getCat($recurrents[$h]['cat_id']);

    //    echo '------------ CATEGORY OBJECT ----------------------------';
    //    var_dump($categoryObject);
    //
    //    $recurEvents['cat']['cat_name']        = $categoryObject->vars['cat_name']['value'];
    //    $recurEvents['cat']['cat_color']       = $categoryObject->vars['cat_color']['value'];
    //    $recurEvents['cat']['cat_light_color'] = Extcal\Utility::getLighterColor($categoryObject->vars['cat_color']['value'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);

    // Formating date
    $eventHandler->formatEventsDate($recurEvents, $helper->getConfig('event_date_week'));
    foreach ($recurEvents as $val) {
        $val['cat']['cat_name']        = $categoryObject->vars['cat_name']['value'];
        $val['cat']['cat_color']       = $categoryObject->vars['cat_color']['value'];
        $val['cat']['cat_light_color'] = Extcal\Utility::getLighterColor($categoryObject->vars['cat_color']['value'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);
        $recurEventsArray[]            = $val;
    }
}

$eventsArray = array_merge($eventsArray, $recurEventsArray);

// Sort event array by event start
//usort($eventsArray, "orderEvents");
//echoArray($eventsArray,false);

// Assigning events to the template
$xoopsTpl->assign('evenements_trouves', sprintf(_MD_EXTCAL_EVENTS_FOUND, count($eventsArray)));
$xoopsTpl->assign('events', $eventsArray);

// Retriving categories and  Assigning categories to the template
$cats = $categoryHandler->objectToArray($categoryHandler->getAllCat($xoopsUser));
$xoopsTpl->assign('cats', $cats);

// Making navig data
// $monthCalObj = new Calendar_Month_Weekdays($year, $month);
// $pMonthCalObj = $monthCalObj->prevMonth('object');
// $nMonthCalObj = $monthCalObj->nextMonth('object');
// $navig = array('prev' => array('uri' => 'year=' . $pMonthCalObj->thisYear()
//                                       . '&amp;month=' . $pMonthCalObj->thisMonth(),
//                                'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $pMonthCalObj->getTimestamp())),
//               'this' => array( 'uri'  => 'year=' . $monthCalObj->thisYear()
//                                        . '&amp;month=' . $monthCalObj->thisMonth(),
//                                'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $monthCalObj->getTimestamp())    ),
//               'next'  => array('uri' => 'year=' . $nMonthCalObj->thisYear()
//                                       . '&amp;month=' . $nMonthCalObj->thisMonth(),
//                                'name' => $timeHandler->getFormatedDate($helper->getConfig('nav_date_month'), $nMonthCalObj->getTimestamp())    )
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
$xoopsTpl->assign('showInfoBulle', $helper->getConfig('showInfoBulle'));
$xoopsTpl->assign('showId', $helper->getConfig('showId'));

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
$xoopsTpl->assign('view', 'search');

require_once XOOPS_ROOT_PATH . '/footer.php';
