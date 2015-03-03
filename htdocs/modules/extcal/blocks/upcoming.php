<?php
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/constantes.php');

/**
 * @param $options
 *
 * @return array
 */
function bExtcalUpcomingShow($options)
{

    include_once XOOPS_ROOT_PATH . '/modules/extcal/class/config.php';

    // Retriving module config
    $extcalConfig      = ExtcalConfig::getHandler();
    $xoopsModuleConfig = $extcalConfig->getModuleConfig();

    $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

    $nbEvent     = $options[0];
    $titleLenght = $options[1];
    $nbDays      = $options[2];

    array_shift($options);
    array_shift($options);
    array_shift($options);

    // Checking if no cat is selected
    if (count($options) == 1 && $options[0] == 0) {
        $options = 0;
    }

    //-------------------
    //mb $events = $eventHandler->objectToArray($eventHandler->getUpcommingEvent($nbEvent, $options));

    /* ========================================================================== */
    $year  = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
    $day   = isset($_GET['day']) ? intval($_GET['day']) : date('j');
    $cat   = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
    /* ========================================================================== */

    // Validate the date (day, month and year)
    $dayTS = mktime(0, 0, 0, $month, $day, $year);

    //$offset = $xoopsModuleConfig['week_start_day'] - date('w', $dayTS);

//------- mb --------------
//   let's make sure that the upcoming events start tomorrow
//    $offset = date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day']<7 ? date('w', $dayTS) + 7-$xoopsModuleConfig['week_start_day'] : 0;
//    $dayTS = $dayTS - ($offset * _EXTCAL_TS_DAY);

    $dayTS = $dayTS + _EXTCAL_TS_DAY;
//------- mb -----------------

    $year  = date('Y', $dayTS);
    $month = date('n', $dayTS);
    $day   = date('j', $dayTS);

    // Retriving events and formatting them
    //$events = $eventHandler->objectToArray($eventHandler->getEventWeek($day, $month, $year, $cat), array('cat_id'));
    $criteres = array(
        'periode'      => _EXTCAL_EVENTS_UPCOMING,
        //        'periode'      => _EXTCAL_EVENTS_CALENDAR_WEEK,
        'day'          => $day,
        'month'        => $month,
        'year'         => $year,
        'cat'          => $cat,
        'externalKeys' => 'cat_id',
        'nbEvent'      => $nbEvent,
        'nbDays'       => $nbDays
    );
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

    $catHandler = xoops_getmodulehandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);

    $cats = $catHandler->getAllCat($xoopsUser, 'extcal_cat_view');

    $form = _MB_EXTCAL_DISPLAY . "&nbsp;\n";
    $form .= "<input name=\"options[0]\" size=\"5\" maxlength=\"255\" value=\"" . $options[0] . "\" type=\"text\" />&nbsp;" . _MB_EXTCAL_EVENT . "<br />";
    $form .= _MB_EXTCAL_TITLE_LENGTH . " : <input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\" /><br />";

    $form .= _MB_EXTCAL_UPCOMING_DAYS . " : <input name=\"options[2]\" size=\"5\" maxlength=\"255\" value=\"" . $options[2] . "\" type=\"text\" /><br />";

    array_shift($options);
    array_shift($options);
    array_shift($options);

    $form .= _MB_EXTCAL_CAT_TO_USE . "<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
    if (array_search(0, $options) === false) {
        $form .= "<option value=\"0\">" . _MB_EXTCAL_ALL_CAT . "</option>";
    } else {
        $form
            .= "<option value=\"0\" selected=\"selected\">" . _MB_EXTCAL_ALL_CAT . "</option>";
    }
    foreach (
        $cats as $cat
    ) {
        if (array_search($cat->getVar('cat_id'), $options) === false) {
            $form .= "<option value=\"" . $cat->getVar('cat_id') . "\">" . $cat->getVar('cat_name') . "</option>";
        } else {
            $form .= "<option value=\"" . $cat->getVar('cat_id') . "\" selected=\"selected\">" . $cat->getVar('cat_name') . "</option>";
        }
    }
    $form .= "</select>";

    return $form;
}
