<?php

include XOOPS_ROOT_PATH . '/header.php';

include_once(XOOPS_ROOT_PATH . '/class/xoopsformloader.php');

include_once __DIR__ . '/include/agenda_fnc.php';
include_once __DIR__ . '/include/functions.php';

include_once 'class/perm.php';
include_once 'class/form/extcalform.php';

//------------------------------------------------------
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Util/Textual.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weeks.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weekdays.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Week.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Day.php';

//------------------------------------------------------
// Getting eXtCal object's handler
$catHandler        = xoops_getmodulehandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
$eventHandler      = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
$extcalTimeHandler = ExtcalTime::getHandler();
$permHandler       = ExtcalPerm::getHandler();
$xoopsUser         = $xoopsUser ? $xoopsUser : null;
//------------------------------------------------------
// Tooltips include
$xoTheme->addScript('modules/extcal/include/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

//////////////////////////////////////////////////////////////
