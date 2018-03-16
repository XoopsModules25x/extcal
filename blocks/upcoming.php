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
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

use XoopsModules\Extcal;

require_once __DIR__ . '/../include/constantes.php';

/**
 * @param $options
 *
 * @return array
 */
function bExtcalUpcomingShow($options)
{
    //    // require_once __DIR__ . '/../class/config.php';

    // Retriving module config
    $extcalConfig      = Extcal\Config::getHandler();
    $xoopsModuleConfig = $extcalConfig->getModuleConfig();

    $eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

    $nbEvent     = $options[0];
    $titleLenght = $options[1];
    $nbDays      = $options[2];

    array_shift($options);
    array_shift($options);
    array_shift($options);

    // Checking if no cat is selected
    if (0 == $options[0] && 1 == count($options)) {
        $options = 0;
    }

    //-------------------
    //mb $events = $eventHandler->objectToArray($eventHandler->getUpcommingEvent($nbEvent, $options));

    /* ========================================================================== */
    $year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $day   = isset($_GET['day']) ? (int)$_GET['day'] : date('j');
    $cat   = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
    /* ========================================================================== */

    // Validate the date (day, month and year)
    $dayTS = mktime(0, 0, 0, $month, $day, $year);

    //$offset = $xoopsModuleConfig['week_start_day'] - date('w', $dayTS);

    //------- mb --------------
    //   let's make sure that the upcoming events start tomorrow
    //    $offset = date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day']<7 ? date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day'] : 0;
    //    $dayTS = $dayTS - ($offset * _EXTCAL_TS_DAY);

    $dayTS += _EXTCAL_TS_DAY;
    //------- mb -----------------

    $year  = date('Y', $dayTS);
    $month = date('n', $dayTS);
    $day   = date('j', $dayTS);

    // Retriving events and formatting them
    //$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat), array('cat_id'));
    $criteres = [
        'periode'      => _EXTCAL_EVENTS_UPCOMING,
        //        'periode'      => _EXTCAL_EVENTS_CALENDAR_WEEK,
        'day'          => $day,
        'month'        => $month,
        'year'         => $year,
        'cat'          => $cat,
        'externalKeys' => 'cat_id',
        'nbEvent'      => $nbEvent,
        'nbDays'       => $nbDays,
    ];
    $events   = $eventHandler->getEventsOnPeriode($criteres);

    //----------------------------

    //$eventHandler->serverTimeToUserTimes($events);
    $eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_month']);

    if (count($events) > $nbEvent) {
        $events = array_slice($events, 0, $nbEvent);
    }

    return $events;
}

/**
 * @param $options
 *
 * @return string
 */
function bExtcalUpcomingEdit($options)
{
    global $xoopsUser;

    $catHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);

    $cats = $catHandler->getAllCat($xoopsUser, 'extcal_cat_view');

    $form = _MB_EXTCAL_DISPLAY . "&nbsp;\n";
    $form .= '<input name="options[0]" size="5" maxlength="255" value="' . $options[0] . '" type="text">&nbsp;' . _MB_EXTCAL_EVENT . '<br>';
    $form .= _MB_EXTCAL_TITLE_LENGTH . ' : <input name="options[1]" size="5" maxlength="255" value="' . $options[1] . '" type="text"><br>';

    $form .= _MB_EXTCAL_UPCOMING_DAYS . ' : <input name="options[2]" size="5" maxlength="255" value="' . $options[2] . '" type="text"><br>';

    array_shift($options);
    array_shift($options);
    array_shift($options);

    $form .= _MB_EXTCAL_CAT_TO_USE . '<br><select name="options[]" multiple="multiple" size="5">';
    if (false === array_search(0, $options)) {
        $form .= '<option value="0">' . _MB_EXTCAL_ALL_CAT . '</option>';
    } else {
        $form .= '<option value="0" selected="selected">' . _MB_EXTCAL_ALL_CAT . '</option>';
    }
    foreach ($cats as $cat) {
        if (false === array_search($cat->getVar('cat_id'), $options)) {
            $form .= '<option value="' . $cat->getVar('cat_id') . '">' . $cat->getVar('cat_name') . '</option>';
        } else {
            $form .= '<option value="' . $cat->getVar('cat_id') . '" selected="selected">' . $cat->getVar('cat_name') . '</option>';
        }
    }
    $form .= '</select>';

    return $form;
}
