<?php
/**
 * classGenerator
 * walls_watermarks.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 *
 * L'utilisation de ce formulaire d'adminitration suppose
 * que la classe correspondante de la table a été générées avec classGenerator
 **/

use XoopsModules\Extcal;

define('_EXTCAL_FORMAT_AGENDA_KEYD', 'Y-m-d');
define('_EXTCAL_FORMAT_AGENDA_KEYT', 'H:i');

require_once __DIR__ . '/constantes.php';
// require_once __DIR__ . '/../class/Utility.php';

$moduleDirName = basename(dirname(__DIR__));
Extcal\Helper::getInstance()->loadLanguage('main');

/*******************************************************************
 *
 ******************************************************************
 * @param        $ts
 * @param        $hStart
 * @param        $hEnd
 * @param int    $mPlage
 * @param int    $nbJours
 * @param        $formatDate
 * @param string $formatJour
 *
 * @return array
 */

function agenda_getCanevas($ts, $hStart, $hEnd, $mPlage = 15, $nbJours = 1, $formatDate, $formatJour = 'H:i')
{
    global $xoopsModuleConfig;
    $jour = date('d', $ts);
    $mois = date('m', $ts);
    $an   = date('Y', $ts);
    if (!isset($formatDate)) {
        $formatDate = $xoopsModuleConfig['event_date_week'];
    }

    //echo "agenda_getCanevas : {$jour}-{$mois}-{$an}-{$ts}<br>";
    //$tsStart = mktime($heure, $minute, $seconde, $mois, $jour, $an);
    $jName = [
        _MD_EXTCAL_DAY_SUNDAY,
        _MD_EXTCAL_DAY_MONDAY,
        _MD_EXTCAL_DAY_TUESDAY,
        _MD_EXTCAL_DAY_WEDNESDAY,
        _MD_EXTCAL_DAY_THURSDAY,
        _MD_EXTCAL_DAY_FRIDAY,
        _MD_EXTCAL_DAY_SATURDAY
    ];

    $tj = [];
    for ($j = 0; $j < $nbJours; ++$j) {
        $tsj                = mktime(0, 0, 0, $mois, $jour + $j, $an);
        $kj                 = date(_EXTCAL_FORMAT_AGENDA_KEYD, $tsj);
        $tj[$kj]['caption'] = date($formatDate, $tsj);

        $tj[$kj]['events'] = [];

        $tj[$kj]['dayWeek'] = date('w', $tsj);
        $tj[$kj]['jour']    = $jName[$tj[$kj]['dayWeek']]; //date('l', $tsj);
        if (0 == $tj[$kj]['dayWeek']) {
            $tj[$kj]['bg'] = "background='" . XOOPS_URL . "/modules/extcal/assets/images/trame.png'";
        } else {
            $tj[$kj]['bg'] = '';
        }
    }

    //echo "{$hStart}-{$hEnd}-{$mPlage}<br>";
    $sPlage  = $mPlage * _EXTCAL_TS_MINUTE; // en secondes
    $tsStart = mktime($hStart, 0, 0, 1, 1, 2000);
    $tsEnd   = mktime($hEnd + 1, 0, 0, 1, 1, 2000);

    $ta = [];
    if ($hStart > 0) {
        $tsCurent          = mktime(0, 0, 0, 1, 1, 2000);
        $k                 = date(_EXTCAL_FORMAT_AGENDA_KEYT, $tsCurent);
        $ta[$k]['caption'] = date($formatJour, $tsCurent);
        $ta[$k]['jours']   = $tj;
        $ta[$k]['class']   = 'head';
    }

    $tsCurent = $tsStart;
    $h        = 0;
    while ($tsCurent < $tsEnd) {
        $k = date(_EXTCAL_FORMAT_AGENDA_KEYT, $tsCurent);
        //echo "{$k}-$tsCurent-";
        $ta[$k]['caption'] = date($formatJour, $tsCurent);
        $ta[$k]['jours']   = $tj;
        $ta[$k]['class']   = ((0 == ($h % 2)) ? 'odd' : 'even');

        //----------------------------------------------
        ++$h;
        $tsCurent += $sPlage;
    }

    if ($hEnd < 23) {
        $tsCurent          = mktime($hEnd + 1, 0, 0, 1, 1, 2000);
        $k                 = date(_EXTCAL_FORMAT_AGENDA_KEYT, $tsCurent);
        $ta[$k]['caption'] = date($formatJour, $tsCurent);
        $ta[$k]['jours']   = $tj;
        $ta[$k]['class']   = 'foot';
    }

    return $ta;
}

/*******************************************************************
 *
 ******************************************************************
 * @param        $eventsArray
 * @param        $ts
 * @param        $hStart
 * @param        $hEnd
 * @param int    $mPlage
 * @param int    $nbJours
 * @param string $formatDate
 * @param string $formatJour
 * @return array
 */
function agenda_getEvents(
    $eventsArray,
    $ts,
    $hStart,
    $hEnd,
    $mPlage = 15,
    $nbJours = 1,
    $formatDate = 'd-m-Y',
    $formatJour = 'H:i'
) {

    //    $tAgenda = agenda_getCanevas($ts, 8, 20, $mPlage, $nbJours);
    $tAgenda = agenda_getCanevas($ts, $hStart, $hEnd - 1, $mPlage, $nbJours, $formatDate, $formatJour);
    $tk      = array_keys($tAgenda);
    $tk0     = $tk[0];
    $tk1     = $tk[count($tk) - 1];

    foreach ($eventsArray as $e) {
        $ts     = $e['event_start'];
        $kd     = date(_EXTCAL_FORMAT_AGENDA_KEYD, $ts);
        $hour   = date('H', $ts);
        $minute = date('i', $ts);
        $m      = (int)($minute / $mPlage) * $mPlage;
        //      echo "--->{$minute} / {$mPlage} = {$m}<br>";
        $sMinute = (($m < 10) ? '0' . $m : $m);
        //$kt = date(_EXTCAL_FORMAT_AGENDA_KEYT, $ts);
        if ($hour < $hStart) {
            $kt = $tk0;
        } elseif ($hour >= ($hEnd + 1)) {
            $kt = $tk1;
        } else {
            $kt = $hour . ':' . $sMinute;
        }

        $tAgenda[$kt]['jours'][$kd]['events'][] = $e;
    }

    return $tAgenda;
}

/*******************************************************************
 *
 *******************************************************************/
function test_getAgenda()
{
    $tsD1 = mktime(0, 0, 0, 01, 25, 1954);
    $t    = getAgenda($tsD1, 8, 21, 30, 7);

    $t['10:30']['jours']['1954-01-25']['events'][1]['lib'] = 'Jean';
    $t['10:30']['jours']['1954-01-25']['events'][1]['dsc'] = 'bobo';

    $t['10:30']['jours']['1954-01-25']['events'][7]['lib'] = 'polo';
    $t['10:30']['jours']['1954-01-25']['events'][7]['dsc'] = 'haribo';

    $t['11:30']['jours']['1954-01-28']['events'][5]['lib'] = 'Jean';
    $t['11:30']['jours']['1954-01-28']['events'][5]['dsc'] = 'bibi';

    $exp = print_r($t, true);
    echo "<pre>{$exp}</pre>";
}

/*******************************************************************
 *
 ******************************************************************
 * @param $event1
 * @param $event2
 * @return int
 */
function orderEvents($event1, $event2)
{
    if ($event1['event_start'] == $event2['event_start']) {
        return 0;
    }
    if ('ASC' === $GLOBALS['xoopsModuleConfig']['sort_order']) {
        $opt1 = -1;
        $opt2 = 1;
    } else {
        $opt1 = 1;
        $opt2 = -1;
    }

    return ($event1['event_start'] < $event2['event_start']) ? $opt1 : $opt2;
}

/*******************************************************************
 *
 ******************************************************************
 * @param        $year
 * @param int    $nbYearsBefore
 * @param int    $nbYearsAfter
 * @param bool   $addNone
 * @param string $name
 * @return \XoopsFormSelect
 */
function getListYears($year, $nbYearsBefore = 0, $nbYearsAfter = 5, $addNone = false, $name = 'year')
{
    // Year selectbox
    $select = new \XoopsFormSelect('', $name, $year);
    if ($addNone) {
        $select->addOption(0, ' ');
    }
    if (0 == $year) {
        $year = date('Y');
    }

    for ($i = $year - $nbYearsBefore; $i < ($year + $nbYearsAfter); ++$i) {
        $select->addOption($i);
    }

    return $select;
}

/*******************************************************************
 *
 ******************************************************************
 * @param        $month
 * @param bool   $addNone
 * @param string $name
 * @return \XoopsFormSelect
 */
function getListMonths($month, $addNone = false, $name = 'month')
{
    // Month selectbox
    $timeHandler = Extcal\Time::getHandler();

    $select = new \XoopsFormSelect('', $name, $month);
    if ($addNone) {
        $select->addOption(0, ' ');
    }

    for ($i = 1; $i < 13; ++$i) {
        $select->addOption($i, $timeHandler->getMonthName($i));
    }

    return $select;
}

/*******************************************************************
 *
 ******************************************************************
 * @param      $day
 * @param bool $addNone
 * @return \XoopsFormSelect
 */
function getListDays($day, $addNone = false)
{
    // Day selectbox
    $select = new \XoopsFormSelect('', 'day', $day);
    if ($addNone) {
        $select->addOption(0, ' ');
    }

    for ($i = 1; $i < 32; ++$i) {
        $select->addOption($i);
    }

    return $select;
}

/*******************************************************************
 *
 ******************************************************************
 * @param $name
 * @return bool
 */
function ext_loadLanguage($name)
{
    global $xoopsConfig;
    $prefix = substr($name, 4);
    switch ($prefix) {
        case '_MI_':
            $f = '';
            break;
        case '_MD_':
            $f = '';
            break;
        default:
            return false;
    }

    $file   = XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/' . $f;
    $prefix = (defined($name) ? '_MI' : '_MD');
    require_once $file;
}

/*******************************************************************
 *
 ******************************************************************
 * @param string $currentTab
 * @return array
 */

function getNavBarTabs($currentTab = '')
{
    global $xoopsModuleConfig;

    ext_loadLanguage('_MD_');

    $visibleTabs = $xoopsModuleConfig['visible_tabs'];
    $tNavBar     = $ordre = [];

    $sep     = '=';
    $tabs    = str_replace("\n", $sep, $xoopsModuleConfig['weight_tabs']);
    $tabs    = str_replace("\r", '', $tabs);
    $tabs    = str_replace(' ', '', $tabs);
    $t       = explode($sep, $tabs);
    $tWeight = array_flip($t);

    //-----------------------------------------------------------------
    $view = _EXTCAL_NAV_CALMONTH;
    //   echo "{$view} - {$currentTab}<br>";
    //   echoArray($visibleTabs,true);
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_CALMONTH,
            'name'    => _MD_EXTCAL_NAV_CALMONTH,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 110
        ];
    }

    $view = _EXTCAL_NAV_CALWEEK;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_CALWEEK,
            'name'    => _MD_EXTCAL_NAV_CALWEEK,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 120
        ];
    }

    $view = _EXTCAL_NAV_YEAR;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_YEAR,
            'name'    => _MD_EXTCAL_NAV_YEAR,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 130
        ];
    }

    $view = _EXTCAL_NAV_MONTH;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_MONTH,
            'name'    => _MD_EXTCAL_NAV_MONTH,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 140
        ];
    }

    $view = _EXTCAL_NAV_WEEK;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_WEEK,
            'name'    => _MD_EXTCAL_NAV_WEEK,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 150
        ];
    }

    $view = _EXTCAL_NAV_DAY;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_DAY,
            'name'    => _MD_EXTCAL_NAV_DAY,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 160
        ];
    }

    $view = _EXTCAL_NAV_AGENDA_WEEK;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_AGENDA_WEEK,
            'name'    => _MD_EXTCAL_NAV_AGENDA_WEEK,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 170
        ];
    }

    $view = _EXTCAL_NAV_AGENDA_DAY;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_AGENDA_DAY,
            'name'    => _MD_EXTCAL_NAV_AGENDA_DAY,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 180
        ];
    }

    $view = _EXTCAL_NAV_SEARCH;
    if (in_array($view, $visibleTabs)) {
        $tNavBar[$view] = [
            'href'    => _EXTCAL_FILE_SEARCH,
            'name'    => _MD_EXTCAL_NAV_SEARCH,
            'current' => ($view == $currentTab) ? 1 : 0,
            'weight'  => 200
        ];
    }

    $user = isset($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser'] : null;
    /** @var Extcal\CategoryHandler $catHandler */
    $catHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
    if ($catHandler->haveSubmitRight($user)) {
        $view = _EXTCAL_NAV_NEW_EVENT;
        if (in_array($view, $visibleTabs)) {
            $tNavBar[$view] = [
                'href'    => _EXTCAL_FILE_NEW_EVENT,
                'name'    => _MD_EXTCAL_NAV_NEW_EVENT,
                'current' => ($view == $currentTab) ? 1 : 0,
                'weight'  => 100
            ];
        }
    }
    //----------------------------------------------------------------
    //    $ordre = array();
    //    while (list($k, $v) = each($tNavBar)) {
    foreach ($tNavBar as $k => $v) {
        if (isset($tWeight[$k])) {
            $ordre[] = (int)$tWeight[$k]; //ordre defini dans les option du module
        } else {
            $ordre[] = $v['weight']; // ordre par defaut ddefini dans le tableau $tNavBar
        }
    }

    array_multisort($tNavBar, SORT_ASC, SORT_NUMERIC, $ordre, SORT_ASC, SORT_NUMERIC);

    //    Extcal\Utility::echoArray($tNavBar);
    //    Extcal\Utility::echoArray($ordre);
    return $tNavBar;
}

/*----------------------------------------------------------------------*/
