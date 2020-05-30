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



/**
 * interface Constants
 */
interface Constants
{
    /**#@+
     * Constant definition
     */

    public const DISALLOW = 0;

    //modif JJD
    public const _EXTCAL_MODULE = 'extcal';
    public const _EXTCAL_CLS_CAT = 'cat';
    public const _EXTCAL_CLS_FILE = 'file';
    public const _EXTCAL_CLS_MEMBER = 'eventmember';
    public const _EXTCAL_CLS_NOT_MEMBER = 'eventnotmember';
    public const _EXTCAL_CLS_LOCATION = 'location';
    public const _EXTCAL_CLN_CAT = 'Category';
    public const _EXTCAL_CLN_FILE = 'File';
    public const _EXTCAL_CLN_MEMBER = 'Eventmember';
    public const _EXTCAL_CLN_NOT_MEMBER = 'EventNotMember';
    public const _EXTCAL_CLN_LOCATION = 'Location';
    public const _EXTCAL_CLS_EVENT = 'event';
    public const _EXTCAL_CLN_EVENT = 'Event';

    //-------------------------------------------------------------------
    //    const _EXTCAL_PEAR_ROOT_DEFAULT =  dirname(__DIR__) . '/class/pear';
    //const _EXTCAL_PEAR_ROOT = 'F:/wamp/www/xfr254b/xoops_lib/Frameworks/pear' );

    //    const _EXTCAL_PEAR_ROOT = $pear_path;

    //    const _EXTCAL_PEAR_CALENDAR_ROOT = _EXTCAL_PEAR_ROOT . '/Calendar';
    //    const CALENDAR_ROOT = _EXTCAL_PEAR_CALENDAR_ROOT . '/';

    //-------------------------------------------------------------------
    public const _EXTCAL_SHOW_NO_PICTURE = false;

    //    const _EXTCAL_PATH_HORLOGES = '/modules/extcal/assets/images/horloges/';
    //    const _EXTCAL_PATH_ICONS16 = XOOPS_URL . '/Frameworks/moduleclasses/icons/16/';
    //    const _EXTCAL_PATH_ICONS32 = XOOPS_URL . '/Frameworks/moduleclasses/icons/32/';
    //    const _EXTCAL_PATH_FO = XOOPS_URL . '/modules/extcal/';
    //    const _EXTCAL_PATH_BO = _EXTCAL_PATH_FO . 'admin/';
    //    const _EXTCAL_PATH_LG = XOOPS_URL . '/modules/extcal/languages/';

    public const _EXTCAL_IMG_INTERVAL = 'interval04.png';
    //    const _EXTCAL_IMG_INTERVAL16 = _EXTCAL_PATH_ICONS16 . _EXTCAL_IMG_INTERVAL;
    //    const _EXTCAL_IMG_INTERVAL32 = _EXTCAL_PATH_ICONS32 . _EXTCAL_IMG_INTERVAL;

    //const _EXTCAL_DIRNAME =    $xoopsModule->getVar('dirname'));

    public const _EXTCAL_NAV_CALMONTH = 'calendar-month';
    public const _EXTCAL_NAV_CALWEEK = 'calendar-week';
    public const _EXTCAL_NAV_YEAR = 'year';
    public const _EXTCAL_NAV_MONTH = 'month';
    public const _EXTCAL_NAV_WEEK = 'week';
    public const _EXTCAL_NAV_DAY = 'day';
    public const _EXTCAL_NAV_AGENDA_WEEK = 'agenda-week';
    public const _EXTCAL_NAV_AGENDA_DAY = 'agenda-day';
    public const _EXTCAL_NAV_SEARCH = 'search';
    public const _EXTCAL_NAV_NEW_EVENT = 'new-event';

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

    public const _EXTCAL_PREFIX_VIEW = 'view_';
    public const _EXTCAL_SUFFIX_VIEW = '.php';

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

    public const _EXTCAL_MULTILOADER = '/class/xoopsform/multiuploads/formmultiuploads.php';
    public const _EXTCAL_STATUS_NONE = 0;
    // define("_EXTCAL_STATUS_INSCRIPTION", 1);
    // define("_EXTCAL_STATUS_DESINSCRIPTION", 2);
    // define("_EXTCAL_STATUS_DELEGATION", 3);
    // define("_EXTCAL_STATUS_MESSAGE", 4);

    public const _EXTCAL_STATUS_COME = 1;
    public const _EXTCAL_STATUS_NOTCOME = 2;
    public const _EXTCAL_HEADER_TEXT = 0;
    public const _EXTCAL_HEADER_HTML = 1;

    //---------------------------------------------------
    public const _EXTCAL_EVENTS_DAY = 0;
    public const _EXTCAL_EVENTS_MONTH = 1;
    public const _EXTCAL_EVENTS_CALENDAR_MONTH = 2;
    public const _EXTCAL_EVENTS_WEEK = 4;
    public const _EXTCAL_EVENTS_CALENDAR_WEEK = 3;
    public const _EXTCAL_EVENTS_AGENDA_WEEK = 5;
    public const _EXTCAL_EVENTS_YEAR = 6;

    //---------------------------------------------------
    public const _EXTCAL_TS_SECOND = 1;
    public const _EXTCAL_TS_MINUTE = 60;
    public const _EXTCAL_TS_HOUR = 3600;
    public const _EXTCAL_TS_DAY = 86400;
    public const _EXTCAL_TS_WEEK = 604800;
    //---------------------------------------------------
    public const _EXTCAL_TS_YEARLY = 32140800;
    public const _EXTCAL_MOTIF_DATE = "#(19|20)\d{2}-(0?[1-9]|1[0-2])-(?x)(0?[1-9]|[12][0-9]|3[01])#";
    public const _EXTCAL_INFOBULLE_RGB_MIN = 220;
    public const _EXTCAL_INFOBULLE_RGB_MAX = 250;

    //2.37
    public const _EXTCAL_EVENTS_UPCOMING = 7;

    /**#@-*/
}
