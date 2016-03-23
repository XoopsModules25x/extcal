<?php
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/constantes.php');

/**
 * @param $options
 *
 * @return mixed
 */
function bExtcalDayShow($options)
{
    include_once XOOPS_ROOT_PATH . '/modules/extcal/class/config.php';

    // Retriving module config
    $extcalConfig      = ExtcalConfig::getHandler();
    $xoopsModuleConfig = $extcalConfig->getModuleConfig();

    $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

    $nbEvent     = $options[0];
    $titleLenght = $options[1];
    array_shift($options);
    array_shift($options);

    // Checking if no cat is selected
    if (0 == $options[0] && 1 == count($options)) {
        $options = 0;
    }

    $events = $eventHandler->objectToArray($eventHandler->getThisDayEvent($nbEvent, $options));
    $eventHandler->serverTimeToUserTimes($events);
    $eventHandler->formatEventsDate($events, $xoopsModuleConfig['event_date_month']);

    return $events;
}

/**
 * @param $options
 *
 * @return string
 */
function bExtcalDayEdit($options)
{
    global $xoopsUser;

    $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);

    $cats = $catHandler->getAllCat($xoopsUser, 'extcal_cat_view');

    $form = _MB_EXTCAL_DISPLAY . "&nbsp;\n";
    $form .= "<input name=\"options[0]\" size=\"5\" maxlength=\"255\" value=\"" . $options[0] . "\" type=\"text\" />&nbsp;" . _MB_EXTCAL_EVENT . '<br />';
    $form .= _MB_EXTCAL_TITLE_LENGTH . " : <input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\" /><br />";
    array_shift($options);
    array_shift($options);
    $form .= _MB_EXTCAL_CAT_TO_USE . "<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
    if (array_search(0, $options) === false) {
        $form .= "<option value=\"0\">" . _MB_EXTCAL_ALL_CAT . '</option>';
    } else {
        $form .= "<option value=\"0\" selected=\"selected\">" . _MB_EXTCAL_ALL_CAT . '</option>';
    }
    foreach ($cats as $cat) {
        if (array_search($cat->getVar('cat_id'), $options) === false) {
            $form .= "<option value=\"" . $cat->getVar('cat_id') . "\">" . $cat->getVar('cat_name') . '</option>';
        } else {
            $form .= "<option value=\"" . $cat->getVar('cat_id') . "\" selected=\"selected\">" . $cat->getVar('cat_name') . '</option>';
        }
    }
    $form .= '</select>';

    return $form;
}
