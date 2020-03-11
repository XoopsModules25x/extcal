<?php

namespace XoopsModules\Extcal;

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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

//defined('XOOPS_ROOT_PATH') || die('Restricted access';

/**
 * class Constants
 */
class Constants
{
    /**#@+
     * Constant definition
     */

    const DISALLOW = 0;

    //modif JJD
    const _EXTCAL_MODULE = 'extcal';

    const _EXTCAL_CLS_CAT = 'cat';
    const _EXTCAL_CLS_FILE = 'file';
    const _EXTCAL_CLS_MEMBER = 'eventmember';
    const _EXTCAL_CLS_NOT_MEMBER = 'eventnotmember';
    const _EXTCAL_CLS_LOCATION = 'location';

    const _EXTCAL_CLN_CAT = 'Category';
    const _EXTCAL_CLN_FILE = 'File';
    const _EXTCAL_CLN_MEMBER = 'Eventmember';
    const _EXTCAL_CLN_NOT_MEMBER = 'EventNotMember';
    const _EXTCAL_CLN_LOCATION = 'Location';

    const _EXTCAL_CLS_EVENT = 'event';
    const _EXTCAL_CLN_EVENT = 'Event';

    //-------------------------------------------------------------------
    //    const _EXTCAL_PEAR_ROOT_DEFAULT =  dirname(__DIR__) . '/class/pear';
    //const _EXTCAL_PEAR_ROOT = 'F:/wamp/www/xfr254b/xoops_lib/Frameworks/pear' );

    //    const _EXTCAL_PEAR_ROOT = $pear_path;

    //    const _EXTCAL_PEAR_CALENDAR_ROOT = _EXTCAL_PEAR_ROOT . '/Calendar';
    //    const CALENDAR_ROOT = _EXTCAL_PEAR_CALENDAR_ROOT . '/';

    //-------------------------------------------------------------------
    const _EXTCAL_SHOW_NO_PICTURE = false;

    //    const _EXTCAL_PATH_HORLOGES = '/modules/extcal/assets/images/horloges/';
    //    const _EXTCAL_PATH_ICONS16 = XOOPS_URL . '/Frameworks/moduleclasses/icons/16/';
    //    const _EXTCAL_PATH_ICONS32 = XOOPS_URL . '/Frameworks/moduleclasses/icons/32/';
    //    const _EXTCAL_PATH_FO = XOOPS_URL . '/modules/extcal/';
    //    const _EXTCAL_PATH_BO = _EXTCAL_PATH_FO . 'admin/';
    //    const _EXTCAL_PATH_LG = XOOPS_URL . '/modules/extcal/languages/';

    const _EXTCAL_IMG_INTERVAL = 'interval04.png';
    //    const _EXTCAL_IMG_INTERVAL16 = _EXTCAL_PATH_ICONS16 . _EXTCAL_IMG_INTERVAL;
    //    const _EXTCAL_IMG_INTERVAL32 = _EXTCAL_PATH_ICONS32 . _EXTCAL_IMG_INTERVAL;

    //const _EXTCAL_DIRNAME =    $xoopsModule->getVar('dirname'));

    const _EXTCAL_NAV_CALMONTH = 'calendar-month';
    const _EXTCAL_NAV_CALWEEK = 'calendar-week';
    const _EXTCAL_NAV_YEAR = 'year';
    const _EXTCAL_NAV_MONTH = 'month';
    const _EXTCAL_NAV_WEEK = 'week';
    const _EXTCAL_NAV_DAY = 'day';
    const _EXTCAL_NAV_AGENDA_WEEK = 'agenda-week';
    const _EXTCAL_NAV_AGENDA_DAY = 'agenda-day';
    const _EXTCAL_NAV_SEARCH = 'search';
    const _EXTCAL_NAV_NEW_EVENT = 'new-event';

    //    const _EXTCAL_NAV_LIST = _EXTCAL_NAV_CALMONTH
    //                             . "\n"
    //                             . _EXTCAL_NAV_CALWEEK
    //                             . "\n"
    //                             . _EXTCAL_NAV_YEAR
    //                             . "\n"
    //                             . _EXTCAL_NAV_MONTH
    //                             . "\n"
    //                             . _EXTCAL_NAV_WEEK
    //                             . "\n"
    //                             . _EXTCAL_NAV_DAY
    //                             . "\n"
    //                             . _EXTCAL_NAV_AGENDA_WEEK
    //                             . "\n"
    //                             . _EXTCAL_NAV_AGENDA_DAY
    //                             . "\n"
    //                             . _EXTCAL_NAV_SEARCH
    //                             . "\n"
    //                             . _EXTCAL_NAV_NEW_EVENT;

    const _EXTCAL_PREFIX_VIEW = 'view_';
    const _EXTCAL_SUFFIX_VIEW = '.php';

    //    const _EXTCAL_FILE_CALMONTH = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_CALMONTH . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_CALWEEK = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_CALWEEK . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_YEAR = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_YEAR . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_MONTH = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_MONTH . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_WEEK = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_WEEK . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_DAY = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_DAY . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_AGENDA_WEEK = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_AGENDA_WEEK . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_AGENDA_DAY = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_AGENDA_DAY . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_SEARCH = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_SEARCH . _EXTCAL_SUFFIX_VIEW;
    //    const _EXTCAL_FILE_NEW_EVENT = _EXTCAL_PREFIX_VIEW . _EXTCAL_NAV_NEW_EVENT . _EXTCAL_SUFFIX_VIEW;

    const _EXTCAL_MULTILOADER = '/class/xoopsform/multiuploads/formmultiuploads.php';

    const _EXTCAL_STATUS_NONE = 0;
    // define("_EXTCAL_STATUS_INSCRIPTION", 1);
    // define("_EXTCAL_STATUS_DESINSCRIPTION", 2);
    // define("_EXTCAL_STATUS_DELEGATION", 3);
    // define("_EXTCAL_STATUS_MESSAGE", 4);

    const _EXTCAL_STATUS_COME = 1;
    const _EXTCAL_STATUS_NOTCOME = 2;

    const _EXTCAL_HEADER_TEXT = 0;
    const _EXTCAL_HEADER_HTML = 1;

    //---------------------------------------------------
    const _EXTCAL_EVENTS_DAY = 0;

    const _EXTCAL_EVENTS_MONTH = 1;
    const _EXTCAL_EVENTS_CALENDAR_MONTH = 2;

    const _EXTCAL_EVENTS_WEEK = 4;
    const _EXTCAL_EVENTS_CALENDAR_WEEK = 3;
    const _EXTCAL_EVENTS_AGENDA_WEEK = 5;

    const _EXTCAL_EVENTS_YEAR = 6;

    //---------------------------------------------------
    const _EXTCAL_TS_SECOND = 1;
    const _EXTCAL_TS_MINUTE = 60;
    const _EXTCAL_TS_HOUR = 3600;
    const _EXTCAL_TS_DAY = 86400;
    const _EXTCAL_TS_WEEK = 604800;
    //---------------------------------------------------
    const _EXTCAL_TS_YEARLY = 32140800;

    const _EXTCAL_MOTIF_DATE = "#(19|20)\d{2}-(0?[1-9]|1[0-2])-(?x)(0?[1-9]|[12][0-9]|3[01])#";

    const _EXTCAL_INFOBULLE_RGB_MIN = 220;
    const _EXTCAL_INFOBULLE_RGB_MAX = 250;

    //2.37
    const _EXTCAL_EVENTS_UPCOMING = 7;

    /**#@-*/
}
