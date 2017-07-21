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

include XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require_once __DIR__ . '/include/agenda_fnc.php';
require_once __DIR__ . '/class/utility.php';

require_once __DIR__ . '/class/perm.php';
require_once __DIR__ . '/class/form/extcalform.php';

xoops_loadLanguage('modinfo', _EXTCAL_MODULE);

//------------------------------------------------------
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Util/Textual.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weeks.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weekdays.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Week.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Day.php';

//------------------------------------------------------
// Getting eXtCal object's handler
$catHandler        = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
$eventHandler      = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
$extcalTimeHandler = ExtcalTime::getHandler();
$permHandler       = ExtcalPerm::getHandler();
$xoopsUser         = $xoopsUser ?: null;
//------------------------------------------------------
// Tooltips include
/** @var xos_opal_Theme $xoTheme */
$xoTheme->addScript('modules/extcal/include/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

//////////////////////////////////////////////////////////////
