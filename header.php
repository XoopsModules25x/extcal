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
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */
use XoopsModules\Extcal\{
    Helper,
    Utility,
    CategoryHandler,
    EventHandler,
    EventmemberHandler,
    EventNotMemberHandler,
    FileHandler,
    LocationHandler,
    Time,
    Perm
};

include __DIR__ . '/preloads/autoloader.php';
require  dirname(dirname(__DIR__)) . '/mainfile.php';

require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once __DIR__ . '/include/agenda_fnc.php';

global $xoopsUser;

$helper = Helper::getInstance();
$helper->loadLanguage('modinfo');

//------------------------------------------------------
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Util/Textual.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weeks.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weekdays.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Week.php';
require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Day.php';

//------------------------------------------------------
// Getting eXtCal object's handler
/** @var CategoryHandler $categoryHandler */
$categoryHandler = $helper->getHandler(_EXTCAL_CLN_CAT);
/** @var EventHandler $eventHandler */
$eventHandler = $helper->getHandler(_EXTCAL_CLN_EVENT);
/** @var EventmemberHandler $eventmemberHandler */
$eventmemberHandler = $helper->getHandler(_EXTCAL_CLN_MEMBER);
/** @var EventNotMemberHandler $eventNotMemberHandler */
$eventNotMemberHandler = $helper->getHandler(_EXTCAL_CLN_NOT_MEMBER);
/** @var FileHandler $fileHandler */
$fileHandler     = $helper->getHandler(_EXTCAL_CLN_FILE);
/** @var LocationHandler $locationHandler */
$locationHandler = $helper->getHandler(_EXTCAL_CLN_LOCATION);

$timeHandler  = Time::getHandler();
$permHandler  = Perm::getHandler();
$xoopsUser    = $xoopsUser ?: null;
//------------------------------------------------------
// Tooltips include
/** @var xos_opal_Theme $xoTheme */
if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require_once $GLOBALS['xoops']->path('/class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

$GLOBALS['xoTheme']->addScript('modules/extcal/assets/js/ToolTips.js');
$GLOBALS['xoTheme']->addStylesheet('modules/extcal/assets/css/infobulle.css');

//////////////////////////////////////////////////////////////
