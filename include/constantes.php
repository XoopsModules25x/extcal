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
 *
 * L'utilisation de ce formulaire d'adminitration suppose
 * que la classe correspondante de la table a été générées avec classGenerator
 **/

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

//modif JJD
define('_EXTCAL_MODULE', 'extcal');

define('_EXTCAL_CLS_CAT', 'cat');
define('_EXTCAL_CLS_FILE', 'file');
define('_EXTCAL_CLS_MEMBER', 'eventmember');
define('_EXTCAL_CLS_NOT_MEMBER', 'eventnotmember');
define('_EXTCAL_CLS_ETABLISSEMENT', 'etablissement');

define('_EXTCAL_CLN_CAT', 'Category');
define('_EXTCAL_CLN_FILE', 'File');
define('_EXTCAL_CLN_MEMBER', 'Eventmember');
define('_EXTCAL_CLN_NOT_MEMBER', 'EventNotMember');
define('_EXTCAL_CLN_ETABLISSEMENT', 'Etablissement');

define('_EXTCAL_CLS_EVENT', 'event');
define('_EXTCAL_CLN_EVENT', 'Event');

//-------------------------------------------------------------------
define('_EXTCAL_PEAR_ROOT_DEFAULT', __DIR__ . '/../class/pear');
//define('_EXTCAL_PEAR_ROOT', 'F:/wamp/www/xfr254b/xoops_lib/Frameworks/pear' );

$pear_path = _EXTCAL_PEAR_ROOT_DEFAULT;
global $xoopsModule, $xoopsModuleConfig;

$name = '';
if (is_object($xoopsModule)) {
    $name = $xoopsModule->getVar('name');
}

/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('extcal');

/** @var XoopsModules\Extcal\Config $extcalConfig */
if ('extcal' === $name || is_object($module)) {
    if (is_object($xoopsModuleConfig)) {
        $extcalConfig = $xoopsModuleConfig;
    } else {
        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = xoops_getHandler('config');
        $extcalConfig  = $configHandler->getConfigList($module->getVar('mid'));
    }
}

//////////////////////////////////

//$newPP = trim($extcalConfig['pear_path']);
//if (substr($newPP, -1, 1) == '/') {
//    $newPP = substr($newPP, 0, -1);
//}
//if ($newPP <> '' && is_dir($newPP)) {
//    $pear_path = $newPP;
//}
define('_EXTCAL_PEAR_ROOT', $pear_path);

define('_EXTCAL_PEAR_CALENDAR_ROOT', _EXTCAL_PEAR_ROOT . '/Calendar');
define('CALENDAR_ROOT', _EXTCAL_PEAR_CALENDAR_ROOT . '/');

//-------------------------------------------------------------------
define('_EXTCAL_SHOW_NO_PICTURE', false);

define('_EXTCAL_PATH_HORLOGES', '/modules/extcal/assets/images/horloges/');
define('_EXTCAL_PATH_ICONS16', XOOPS_URL . '/Frameworks/moduleclasses/icons/16/');
define('_EXTCAL_PATH_ICONS32', XOOPS_URL . '/Frameworks/moduleclasses/icons/32/');
define('_EXTCAL_PATH_FO', XOOPS_URL . '/modules/extcal/');
define('_EXTCAL_PATH_BO', _EXTCAL_PATH_FO . 'admin/');
define('_EXTCAL_PATH_LG', XOOPS_URL . '/modules/extcal/languages/');

define('_EXTCAL_IMG_INTERVAL', 'interval04.png');
define('_EXTCAL_IMG_INTERVAL16', _EXTCAL_PATH_ICONS16 . _EXTCAL_IMG_INTERVAL);
define('_EXTCAL_IMG_INTERVAL32', _EXTCAL_PATH_ICONS32 . _EXTCAL_IMG_INTERVAL);

//define('_EXTCAL_DIRNAME',    $xoopsModule->getVar('dirname'));

define('_EXTCAL_NAV_CALMONTH', 'calendar-month');
define('_EXTCAL_NAV_CALWEEK', 'calendar-week');
define('_EXTCAL_NAV_YEAR', 'year');
define('_EXTCAL_NAV_MONTH', 'month');
define('_EXTCAL_NAV_WEEK', 'week');
define('_EXTCAL_NAV_DAY', 'day');
define('_EXTCAL_NAV_AGENDA_WEEK', 'agenda-week');
define('_EXTCAL_NAV_AGENDA_DAY', 'agenda-day');
define('_EXTCAL_NAV_SEARCH', 'search');
define('_EXTCAL_NAV_NEW_EVENT', 'new-event');

define(
    '_EXTCAL_NAV_LIST',
       _EXTCAL_NAV_CALMONTH . "\n" . _EXTCAL_NAV_CALWEEK . "\n" . _EXTCAL_NAV_YEAR . "\n" . _EXTCAL_NAV_MONTH . "\n" . _EXTCAL_NAV_WEEK . "\n" . _EXTCAL_NAV_DAY . "\n" . _EXTCAL_NAV_AGENDA_WEEK . "\n" . _EXTCAL_NAV_AGENDA_DAY . "\n" . _EXTCAL_NAV_SEARCH . "\n" . _EXTCAL_NAV_NEW_EVENT
);

define('_EXTCAL_PREFIX_VIEW', 'view_');
define('_EXTCAL_SUFFIX_VIEW', '.php');

define('_EXTCAL_FILE_CALMONTH', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_CALMONTH . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_CALWEEK', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_CALWEEK . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_YEAR', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_YEAR . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_MONTH', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_MONTH . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_WEEK', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_WEEK . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_DAY', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_DAY . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_AGENDA_WEEK', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_AGENDA_WEEK . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_AGENDA_DAY', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_AGENDA_DAY . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_SEARCH', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_SEARCH . _EXTCAL_SUFFIX_VIEW);
define('_EXTCAL_FILE_NEW_EVENT', _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_NEW_EVENT . _EXTCAL_SUFFIX_VIEW);

define('_EXTCAL_MULTILOADER', '/class/xoopsform/multiuploads/formmultiuploads.php');

define('_EXTCAL_STATUS_NONE', 0);
// define("_EXTCAL_STATUS_INSCRIPTION", 1);
// define("_EXTCAL_STATUS_DESINSCRIPTION", 2);
// define("_EXTCAL_STATUS_DELEGATION", 3);
// define("_EXTCAL_STATUS_MESSAGE", 4);

define('_EXTCAL_STATUS_COME', 1);
define('_EXTCAL_STATUS_NOTCOME', 2);

define('_EXTCAL_HEADER_TEXT', 0);
define('_EXTCAL_HEADER_HTML', 1);

//---------------------------------------------------
define('_EXTCAL_EVENTS_DAY', 0);

define('_EXTCAL_EVENTS_MONTH', 1);
define('_EXTCAL_EVENTS_CALENDAR_MONTH', 2);

define('_EXTCAL_EVENTS_WEEK', 4);
define('_EXTCAL_EVENTS_CALENDAR_WEEK', 3);
define('_EXTCAL_EVENTS_AGENDA_WEEK', 5);

define('_EXTCAL_EVENTS_YEAR', 6);

//---------------------------------------------------
define('_EXTCAL_TS_SECOND', 1);
define('_EXTCAL_TS_MINUTE', 60);
define('_EXTCAL_TS_HOUR', 3600);
define('_EXTCAL_TS_DAY', 86400);
define('_EXTCAL_TS_WEEK', 604800);
//---------------------------------------------------
define('_EXTCAL_TS_YEARLY', 32140800);

define('_EXTCAL_MOTIF_DATE', "#(19|20)\d{2}-(0?[1-9]|1[0-2])-(?x)(0?[1-9]|[12][0-9]|3[01])#");

define('_EXTCAL_INFOBULLE_RGB_MIN', 220);
define('_EXTCAL_INFOBULLE_RGB_MAX', 250);

//2.37
define('_EXTCAL_EVENTS_UPCOMING', 7);
