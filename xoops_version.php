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
    CategoryHandler
};



require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/agenda_fnc.php';
//require_once __DIR__ . '/class/Config.php';
//$loc_de = setlocale (LC_ALL, 'french');

//echo "local :" .  setlocale(LC_TIME, $xoopsConfig['language'])."</ br>";
setlocale(LC_TIME, $xoopsConfig['language']);

//***************************************************************************************
$modversion['version']          = '2.40';
$modversion['module_status']    = 'RC 3';
$modversion['release_date']     = '2020/07/09';
$modversion['name']             = _MI_EXTCAL_NAME;
$modversion['description']      = _MI_EXTCAL_DESC;
$modversion['credits']          = 'Zoullou';
$modversion['author']           = 'Zoullou, Mage, Mamba, JJ Delalandre (JJDai)';
$modversion['nickname']         = '';
$modversion['website']          = '';
$modversion['license']          = 'GPL see LICENSE';
$modversion['license_url']      = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']         = 0;
$modversion['image']            = 'assets/images/logoModule.png';
$modversion['dirname']          = basename(__DIR__);
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['system_menu']      = 1;
$modversion['help']             = 'page=help';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
//about
$modversion['module_website_url']  = 'www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '7.1';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];
// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';


//Install/Uninstall Functions
$modversion['onInstall']   = 'include/oninstall.php';
$modversion['onUpdate']    = 'include/onupdate.php';
//$modversion['onUninstall'] = 'include/onuninstall.php';

// Menu

// definitioin des menus
$modversion['hasMain'] = 1;
$i                     = 0;

if (isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule'])
    && 'extcal' === $GLOBALS['xoopsModule']->getVar('dirname')) {
    $user = $GLOBALS['xoopsUser'] ?? null;
    $categoryHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
    if ($categoryHandler->haveSubmitRight($user)) {
        $modversion['sub'][0]['name'] = _MI_EXTCAL_SUBMIT_EVENT;
        $modversion['sub'][0]['url']  = _EXTCAL_FILE_NEW_EVENT;
    }

    $tTabs = getNavBarTabs();
    //    while (list($key, $value) = each($tTabs)) {
    foreach ($tTabs as $key => $value) {
        ++$i;
        $modversion['sub'][$i]['name'] = $value['name'];
        $modversion['sub'][$i]['url']  = $value['href'];
    }
}

////////////////////////////////////////////////////////////////////////////
// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'] = [
    $moduleDirName . '_' . 'cat',
    $moduleDirName . '_' . 'event',
    $moduleDirName . '_' . 'eventmember',
    $moduleDirName . '_' . 'eventnotmember',
    $moduleDirName . '_' . 'file',
    $moduleDirName . '_' . 'location',
];

// SQL

// Comments
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'event';
$modversion['comments']['pageName'] = 'event.php';

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'extcal_search';

// Config items

$modversion['config'][] = [
    'name'        => 'visible_tabs',
    'title'       => '_MI_EXTCAL_VISIBLE_TABS',
    'description' => '_MI_EXTCAL_VISIBLE_TABS_DESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => [
        _EXTCAL_NAV_CALMONTH,
        _EXTCAL_NAV_CALWEEK,
        _EXTCAL_NAV_YEAR,
        _EXTCAL_NAV_MONTH,
        _EXTCAL_NAV_WEEK,
        _EXTCAL_NAV_DAY,
        _EXTCAL_NAV_AGENDA_WEEK,
        _EXTCAL_NAV_AGENDA_DAY,
        _EXTCAL_NAV_SEARCH,
        _EXTCAL_NAV_NEW_EVENT,
    ],
    // $t = print_r($modversion['config'][$i]['default'],true);
    // echo _EXTCAL_NAV_CALMONTH . "<br><pre>{$t}</pre>";
    'options'     => [
        '_MI_EXTCAL_NAV_CALMONTH'    => _EXTCAL_NAV_CALMONTH,
        '_MI_EXTCAL_NAV_CALWEEK'     => _EXTCAL_NAV_CALWEEK,
        '_MI_EXTCAL_NAV_YEAR'        => _EXTCAL_NAV_YEAR,
        '_MI_EXTCAL_NAV_MONTH'       => _EXTCAL_NAV_MONTH,
        '_MI_EXTCAL_NAV_WEEK'        => _EXTCAL_NAV_WEEK,
        '_MI_EXTCAL_NAV_DAY'         => _EXTCAL_NAV_DAY,
        '_MI_EXTCAL_NAV_AGENDA_WEEK' => _EXTCAL_NAV_AGENDA_WEEK,
        '_MI_EXTCAL_NAV_AGENDA_DAY'  => _EXTCAL_NAV_AGENDA_DAY,
        '_MI_EXTCAL_NAV_SEARCH'      => _EXTCAL_NAV_SEARCH,
        '_MI_EXTCAL_NAV_NEW_EVENT'   => _EXTCAL_NAV_NEW_EVENT,
    ],
];

//-----------------------------------------------------------------------------
$modversion['config'][] = [
    'name'        => 'weight_tabs',
    'title'       => '_MI_EXTCAL_TABS_WEIGHT',
    'description' => '_MI_EXTCAL_TABS_WEIGHT_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    //'default' =>  "10,20,30,40,50,_EXTCAL_TS_MINUTE,70,80,90,100,0",
    'default'     => _EXTCAL_NAV_LIST,
];

//-----------------------------------------------------------------------------
$modversion['config'][] = [
    'name'        => 'start_page',
    'title'       => '_MI_EXTCAL_START_PAGE',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => _EXTCAL_FILE_CALMONTH,
    'options'     => [
        '_MI_EXTCAL_NAV_CALMONTH'    => _EXTCAL_FILE_CALMONTH,
        '_MI_EXTCAL_NAV_CALWEEK'     => _EXTCAL_FILE_CALWEEK,
        '_MI_EXTCAL_NAV_YEAR'        => _EXTCAL_FILE_YEAR,
        '_MI_EXTCAL_NAV_MONTH'       => _EXTCAL_FILE_MONTH,
        '_MI_EXTCAL_NAV_WEEK'        => _EXTCAL_FILE_WEEK,
        '_MI_EXTCAL_NAV_DAY'         => _EXTCAL_FILE_DAY,
        '_MI_EXTCAL_NAV_AGENDA_WEEK' => _EXTCAL_FILE_AGENDA_WEEK,
        '_MI_EXTCAL_NAV_AGENDA_DAY'  => _EXTCAL_FILE_AGENDA_DAY,
        '_MI_EXTCAL_NAV_SEARCH'      => _EXTCAL_FILE_SEARCH,
        '_MI_EXTCAL_NAV_NEW_EVENT'   => _EXTCAL_FILE_NEW_EVENT,
    ],
];

$modversion['config'][] = [
    'name'        => 'week_start_day',
    'title'       => '_MI_EXTCAL_WEEK_START_DAY',
    'description' => '_MI_EXTCAL_WEEK_START_DAY_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => [
        '_MI_EXTCAL_DAY_SUNDAY'    => 0,
        '_MI_EXTCAL_DAY_MONDAY'    => 1,
        '_MI_EXTCAL_DAY_TUESDAY'   => 2,
        '_MI_EXTCAL_DAY_WEDNESDAY' => 3,
        '_MI_EXTCAL_DAY_THURSDAY'  => 4,
        '_MI_EXTCAL_DAY_FRIDAY'    => 5,
        '_MI_EXTCAL_DAY_SATURDAY'  => 6,
    ],
];

$modversion['config'][] = [
    'name'        => 'list_position',
    'title'       => '_MI_EXTCAL_LIST_POS',
    'description' => '_MI_EXTCAL_LIST_POS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 1,
    'options'     => [
        '_MI_EXTCAL_BEFORE' => 0,
        '_MI_EXTCAL_AFTER'  => 1,
    ],
];

xoops_load('XoopsEditorHandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'editorAdmin',
    'title'       => '_MI_EXTCAL_EDITOR_ADMIN',
    'description' => '_MI_EXTCAL_EDITOR_ADMIN_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'dhtml',
];

$modversion['config'][] = [
    'name'        => 'editorUser',
    'title'       => '_MI_EXTCAL_EDITOR_USER',
    'description' => '_MI_EXTCAL_EDITOR_USER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'dhtml',
];

$modversion['config'][] = [
    'name'        => 'rss_cache_time',
    'title'       => '_MI_EXTCAL_RSS_CACHE_TIME',
    'description' => '_MI_EXTCAL_RSS_CACHE_TIME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => _EXTCAL_TS_MINUTE,
];

$modversion['config'][] = [
    'name'        => 'rss_nb_event',
    'title'       => '_MI_EXTCAL_RSS_NB_EVENT',
    'description' => '_MI_EXTCAL_RSS_NB_EVENT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'whos_going',
    'title'       => '_MI_EXTCAL_WHOS_GOING',
    'description' => '_MI_EXTCAL_WHOS_GOING_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'whosnot_going',
    'title'       => '_MI_EXTCAL_WHOSNOT_GOING',
    'description' => '_MI_EXTCAL_WHOSNOT_GOING_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'sort_order',
    'title'       => '_MI_EXTCAL_SORT_ORDER',
    'description' => '_MI_EXTCAL_SORT_ORDER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 1,
    'options'     => [
        '_MI_EXTCAL_ASCENDING'  => 'ASC',
        '_MI_EXTCAL_DESCENDING' => 'DESC',
    ],
];

$modversion['config'][] = [
    'name'        => 'event_date_year',
    'title'       => '_MI_EXTCAL_EY_DATE_PATTERN',
    'description' => '_MI_EXTCAL_EY_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_EY_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'nav_date_month',
    'title'       => '_MI_EXTCAL_NM_DATE_PATTERN',
    'description' => '_MI_EXTCAL_NM_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_NM_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'event_date_month',
    'title'       => '_MI_EXTCAL_EM_DATE_PATTERN',
    'description' => '_MI_EXTCAL_EM_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_EM_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'nav_date_week',
    'title'       => '_MI_EXTCAL_NW_DATE_PATTERN',
    'description' => '_MI_EXTCAL_NW_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_NW_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'event_date_week',
    'title'       => '_MI_EXTCAL_EW_DATE_PATTERN',
    'description' => '_MI_EXTCAL_EW_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_EW_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'nav_date_day',
    'title'       => '_MI_EXTCAL_ND_DATE_PATTERN',
    'description' => '_MI_EXTCAL_ND_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_ND_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'event_date_day',
    'title'       => '_MI_EXTCAL_ED_DATE_PATTERN',
    'description' => '_MI_EXTCAL_ED_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_ED_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'event_date_event',
    'title'       => '_MI_EXTCAL_EE_DATE_PATTERN',
    'description' => '_MI_EXTCAL_EE_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_EE_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'event_date_block',
    'title'       => '_MI_EXTCAL_EB_DATE_PATTERN',
    'description' => '_MI_EXTCAL_EB_DATE_PATTERN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _MI_EXTCAL_EB_DATE_PATTERN_VALUE,
];

$modversion['config'][] = [
    'name'        => 'diplay_past_event_list',
    'title'       => '_MI_EXTCAL_DISP_PAST_E_LIST',
    'description' => '_MI_EXTCAL_DISP_PAST_E_LIST_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'diplay_past_event_cal',
    'title'       => '_MI_EXTCAL_DISP_PAST_E_CAL',
    'description' => '_MI_EXTCAL_DISP_PAST_E_CAL_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'allowed_file_extention',
    'title'       => '_MI_EXTCAL_FILE_EXTENTION',
    'description' => '_MI_EXTCAL_FILE_EXTENTION_DESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => ['doc', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'txt'],
    'options'     => [
        'ai'    => 'ai',
        'aif'   => 'aif',
        'aiff'  => 'aiff',
        'asc'   => 'asc',
        'au'    => 'au',
        'avi'   => 'avi',
        'bin'   => 'bin',
        'bmp'   => 'bmp',
        'class' => 'class',
        'csh'   => 'csh',
        'css'   => 'css',
        'dcr'   => 'dcr',
        'dir'   => 'dir',
        'dll'   => 'dll',
        'doc'   => 'doc',
        'dot'   => 'dot',
        'dtd'   => 'dtd',
        'dxr'   => 'dxr',
        'ent'   => 'ent',
        'eps'   => 'eps',
        'exe'   => 'exe',
        'gif'   => 'gif',
        'gtar'  => 'gtar',
        'gz'    => 'gz',
        'hqx'   => 'hqx',
        'htm'   => 'htm',
        'html'  => 'html',
        'ics'   => 'ics',
        'ifb'   => 'ifb',
        'jpe'   => 'jpe',
        'jpeg'  => 'jpeg',
        'jpg'   => 'jpg',
        'js'    => 'js',
        'kar'   => 'kar',
        'lha'   => 'lha',
        'lzh'   => 'lzh',
        'm3u'   => 'm3u',
        'mid'   => 'mid',
        'midi'  => 'midi',
        'mod'   => 'mod',
        'mov'   => 'mov',
        'mp1'   => 'mp1',
        'mp2'   => 'mp2',
        'mp3'   => 'mp3',
        'mpe'   => 'mpe',
        'mpeg'  => 'mpeg',
        'mpg'   => 'mpg',
        'pbm'   => 'pbm',
        'pdf'   => 'pdf',
        'pgm'   => 'pgm',
        'php'   => 'php',
        'php3'  => 'php3',
        'php5'  => 'php5',
        'phtml' => 'phtml',
        'png'   => 'png',
        'pnm'   => 'pnm',
        'ppm'   => 'ppm',
        'ppt'   => 'ppt',
        'ps'    => 'ps',
        'qt'    => 'qt',
        'ra'    => 'ra',
        'ram'   => 'ram',
        'rm'    => 'rm',
        'rpm'   => 'rpm',
        'rtf'   => 'rtf',
        'sgm'   => 'sgm',
        'sgml'  => 'sgml',
        'sh'    => 'sh',
        'sit'   => 'sit',
        'smi'   => 'smi',
        'smil'  => 'smil',
        'snd'   => 'snd',
        'so'    => 'so',
        'spl'   => 'spl',
        'swf'   => 'swf',
        'tar'   => 'tar',
        'tcl'   => 'tcl',
        'tif'   => 'tif',
        'tiff'  => 'tiff',
        'tsv'   => 'tsv',
        'txt'   => 'txt',
        'wav'   => 'wav',
        'wbmp'  => 'wbmp',
        'wbxml' => 'wbxml',
        'wml'   => 'wml',
        'wmlc'  => 'wmlc',
        'wmls'  => 'wmls',
        'wmlsc' => 'wmlsc',
        'xbm'   => 'xbm',
        'xht'   => 'xht',
        'xhtml' => 'xhtml',
        'xla'   => 'xla',
        'xls'   => 'xls',
        'xlt'   => 'xlt',
        'xpm'   => 'xpm',
        'xsl'   => 'xsl',
        'zip'   => 'zip',
    ],
];

$modversion['config'][] = [
    'name'        => 'allow_html',
    'title'       => '_MI_EXTCAL_HTML',
    'description' => '_MI_EXTCAL_HTML_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

//modif JJD ------------------------------------------------------------
$modversion['config'][] = [
    'name'        => 'agenda_tranche_minutes',
    'title'       => '_MI_EXTCAL_AGENDA_SLICE_MINUTES',
    'description' => '_MI_EXTCAL_AGENDA_SLICE_MINUTES_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 15,
];

$modversion['config'][] = [
    'name'        => 'agenda_start_hour',
    'title'       => '_MI_EXTCAL_AGENDA_START_HOUR',
    'description' => '_MI_EXTCAL_AGENDA_START_HOUR_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 8,
];

$modversion['config'][] = [
    'name'        => 'agenda_end_hour',
    'title'       => '_MI_EXTCAL_AGENDA_END_HOUR',
    'description' => '_MI_EXTCAL_AGENDA_END_HOUR_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 20,
];

$modversion['config'][] = [
    'name'        => 'agenda_nb_days_week',
    'title'       => '_MI_EXTCAL_AGENDA_NB_DAYS_WEEK',
    'description' => '_MI_EXTCAL_AGENDA_NB_DAYS_WEEK_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'agenda_nb_days_day',
    'title'       => '_MI_EXTCAL_AGENDA_NB_DAYS_DAY',
    'description' => '_MI_EXTCAL_AGENDA_NB_DAYS_DAY_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 1,

];

$modversion['config'][] = [
    'name'        => 'agenda_nb_years_before',
    'title'       => '_MI_EXTCAL_NB_YEARS_BEFORE',
    'description' => '_MI_EXTCAL_NB_YEARS_BEFORE_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'agenda_nb_years_after',
    'title'       => '_MI_EXTCAL_NB_YEARS_AFTER',
    'description' => '_MI_EXTCAL_NB_YEARS_AFTER_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 5,

];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_EXTCAL_SHOW_FORMOPTIONS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'formShowIcon',
    'title'       => '_MI_EXTCAL_FORMSHOW_ICON',
    'description' => '_MI_EXTCAL_FORMSHOW_ICON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowLocation',
    'title'       => '_MI_EXTCAL_FORMSHOW_LOCATION',
    'description' => '_MI_EXTCAL_FORMSHOW_LOCATION_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowPrice',
    'title'       => '_MI_EXTCAL_FORMSHOW_PRICE',
    'description' => '_MI_EXTCAL_FORMSHOW_PRICE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowOrganizer',
    'title'       => '_MI_EXTCAL_FORMSHOW_ORGANIZER',
    'description' => '_MI_EXTCAL_FORMSHOW_ORGANIZER_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowContact',
    'title'       => '_MI_EXTCAL_FORMSHOW_CONTACT',
    'description' => '_MI_EXTCAL_FORMSHOW_CONTACT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowUrl',
    'title'       => '_MI_EXTCAL_FORMSHOW_URL',
    'description' => '_MI_EXTCAL_FORMSHOW_URL_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowEmail',
    'title'       => '_MI_EXTCAL_FORMSHOW_EMAIL',
    'description' => '_MI_EXTCAL_FORMSHOW_EMAIL_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowAddress',
    'title'       => '_MI_EXTCAL_FORMSHOW_ADDRESS',
    'description' => '_MI_EXTCAL_FORMSHOW_ADDRESS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowRecurence',
    'title'       => '_MI_EXTCAL_FORMSHOW_RECURENCE',
    'description' => '_MI_EXTCAL_FORMSHOW_RECURENCE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowFile',
    'title'       => '_MI_EXTCAL_FORMSHOW_FILE',
    'description' => '_MI_EXTCAL_FORMSHOW_FILE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'formShowPicture',
    'title'       => '_MI_EXTCAL_FORMSHOW_PICTURE',
    'description' => '_MI_EXTCAL_FORMSHOW_PICTURE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'break' . $i,
    'title'       => '_MI_EXTCAL_SHOW_OTHEROPTIONS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

//----------------------------------------------------------
$modversion['config'][] = [
    'name'        => 'showInfoBulle',
    'title'       => '_MI_EXTCAL_SHOW_INFOBULLE',
    'description' => '_MI_EXTCAL_SHOW_INFOBULLE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'showId',
    'title'       => '_MI_EXTCAL_SHOW_ID',
    'description' => '_MI_EXTCAL_SHOW_ID_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/*
inutilise pour le moment, prevu pour ajout navigation dans minical
*/
$modversion['config'][] = [
    'name'        => 'offsetMinical',
    'title'       => '_MI_EXTCAL_OFFSET_MINICAL',
    'description' => '_MI_EXTCAL_OFFSET_MINICAL_DESC',
    'formtype'    => 'hidden',
    'valuetype'   => 'int',
    'default'     => 0,

];

$modversion['config'][] = [
    'name'        => 'nbEventsByPage',
    'title'       => '_MI_EXTCAL_NB_EVENTS_BY_PAGE',
    'description' => '_MI_EXTCAL_NB_EVENTS_BY_PAGE_DESC',
    'formtype'    => 'int',
    'valuetype'   => 'int',
    'default'     => 10,

    // utilisation de security image
];

$modversion['config'][] = [
    'name'        => 'email_Mode',
    'title'       => '_MI_EXTCAL_EMAIL_MODE',
    'description' => '_MI_EXTCAL_EMAIL_MODE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 0,
    'options'     => [
        '_MI_EXTCAL_EMAIL_MODE_NONE' => 0,
        '_MI_EXTCAL_EMAIL_MODE_TEXT' => 1,
        '_MI_EXTCAL_EMAIL_MODE_HTML' => 2,
    ],
];

$modversion['config'][] = [
    'name'        => 'pear_path',
    'title'       => '_MI_EXTCAL_PEAR_PATH',
    'description' => '_MI_EXTCAL_PEAR_PATH_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'size'        => 80,
    'default'     => '',
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => '_MI_EXTCAL_SHOW_SAMPLE_BUTTON',
    'description' => '_MI_EXTCAL_SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => '_MI_EXTCAL_SHOW_DEV_TOOLS',
    'description' => '_MI_EXTCAL_SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

//modif JJD ------------------------------------------------------------

// Templates
$modversion['templates'] = [
    ['file' => 'extcal_buttons_event.tpl', 'description' => 'buttons event'],
    ['file' => 'extcal_categorie.tpl', 'description' => 'Category'],
    ['file' => 'extcal_event.tpl', 'description' => ''],
    ['file' => 'extcal_event_list1.tpl', 'description' => 'Events list'],
    ['file' => 'extcal_event_nav_btn.tpl', 'description' => 'navigation buttonsn'],
    ['file' => 'extcal_horloge.tpl', 'description' => 'Time'],
    ['file' => 'extcal_imgXoops.tpl', 'description' => 'Images Xoops'],
    ['file' => 'extcal_info_bulle.tpl', 'description' => 'Info bulle'],
    ['file' => 'extcal_location.tpl', 'description' => 'location'],
    ['file' => 'extcal_navbar.tpl', 'description' => ''],
    ['file' => 'extcal_post.tpl', 'description' => ''],
    ['file' => 'extcal_rss.tpl', 'description' => ''],
    ['file' => 'extcal_view_agenda-day.tpl', 'description' => ''],
    ['file' => 'extcal_view_agenda-week.tpl', 'description' => ''],
    ['file' => 'extcal_view_calendar-month.tpl', 'description' => ''],
    ['file' => 'extcal_view_calendar-week.tpl', 'description' => ''],
    ['file' => 'extcal_view_day.tpl', 'description' => ''],
    ['file' => 'extcal_view_month.tpl', 'description' => ''],
    ['file' => 'extcal_view_new-event.tpl', 'description' => 'New event'],
    ['file' => 'extcal_view_search.tpl', 'description' => 'search events'],
    ['file' => 'extcal_view_week.tpl', 'description' => ''],
    ['file' => 'extcal_view_year.tpl', 'description' => ''],
    //-------------------------------------------------------------
    ['file' => 'extcal_mail_member_text.tpl', 'description' => 'Mail text member inscription/desinscription'],
    ['file' => 'extcal_mail_member_html.tpl', 'description' => 'Mail html member inscription/desinscription'],
    //-------------------------------------------------------------
    //template de l'admin
    //-------------------------------------------------------------
    ['file' => 'admin/extcal_admin_cat_list.tpl', 'description' => 'Category list'],
];

//-------------------------------------------------------------

// Blocs

$modversion['blocks'][] = [
    'file' => 'minical.php',
    'name' => _MI_EXTCAL_BNAME1,
    'description' => _MI_EXTCAL_BNAME1_DESC,
    'show_func' => 'bExtcalMinicalShow',
    'options' => '0|0|150|225|1|3|10|0|1|1,2,3,4,5|| |120|120',
    'edit_func' => 'bExtcalMinicalEdit',
    'template' => 'extcal_block_minical.tpl',
];

$modversion['blocks'][] = [
    'file'        => "spotlight_events.php",
    'name'        => _MI_EXTCAL_BNAME2,
    'description' => _MI_EXTCAL_BNAME2_DESC,
    'show_func'   => "bExtcalSpotlightShow",
    'options'     => "0|0|0|1|0",
    'edit_func'   => "bExtcalSpotlightEdit",
    'template'    => 'extcal_block_spotlight.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'upcoming.php',
    'name'        => _MI_EXTCAL_BNAME3,
    'description' => _MI_EXTCAL_BNAME3_DESC,
    'show_func'   => 'bExtcalUpcomingShow',
    'options'     => '5|25|30|0',
    'edit_func'   => 'bExtcalUpcomingEdit',
    'template'    => 'extcal_block_upcoming.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'day_events.php',
    'name'        => _MI_EXTCAL_BNAME4,
    'description' => _MI_EXTCAL_BNAME4_DESC,
    'show_func'   => 'bExtcalDayShow',
    'options'     => '5|25|0',
    'edit_func'   => 'bExtcalDayEdit',
    'template'    => 'extcal_block_day.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'new_events.php',
    'name'        => _MI_EXTCAL_BNAME5,
    'description' => _MI_EXTCAL_BNAME5_DESC,
    'show_func'   => 'bExtcalNewShow',
    'options'     => '5|25|0',
    'edit_func'   => 'bExtcalNewEdit',
    'template'    => 'extcal_block_new.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'random_events.php',
    'name'        => _MI_EXTCAL_BNAME6,
    'description' => _MI_EXTCAL_BNAME6_DESC,
    'show_func'   => 'bExtcalRandomShow',
    'options'     => '5|25|0',
    'edit_func'   => 'bExtcalRandomEdit',
    'template'    => 'extcal_block_random.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'category_events.php',
    'name'        => _MI_EXTCAL_BNAME7,
    'description' => _MI_EXTCAL_BNAME7_DESC,
    'show_func'   => 'bExtcalUpcomingByCategoryShow',
    'options'     => '5|25|0',
    'edit_func'   => 'bExtcalUpcomingByCategoryEdit',
    'template'    => 'extcal_block_upcomingByCategory.tpl',
];


//---------------------------------------------------------
// Notifications
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'extcal_notify_iteminfo';

$modversion['notification']['category'][] = [
    'name'           => 'global',
    'title'          => _MI_EXTCAL_GLOBAL_NOTIFY,
    'description'    => _MI_EXTCAL_GLOBAL_NOTIFYDSC,
    'subscribe_from' => '*',
    'item_name'      => '',
];

$modversion['notification']['category'][] = [
    'name'           => 'cat',
    'title'          => _MI_EXTCAL_CAT_NOTIFY,
    'description'    => _MI_EXTCAL_CAT_NOTIFYDSC,
    'subscribe_from' => ['calendar.php', 'year.php', 'day.php'],
    'item_name'      => 'cat',
];

$modversion['notification']['category'][] = [
    'name'           => 'event',
    'title'          => _MI_EXTCAL_EVENT_NOTIFY,
    'description'    => _MI_EXTCAL_EVENT_NOTIFYDSC,
    'subscribe_from' => 'event.php',
    'item_name'      => 'event',
    'allow_bookmark' => 1,
];

$modversion['notification']['event'][]    = [
    'name'          => 'new_event',
    'category'      => 'global',
    'title'         => _MI_EXTCAL_NEW_EVENT_NOTIFY,
    'caption'       => _MI_EXTCAL_NEW_EVENT_NOTIFYCAP,
    'description'   => _MI_EXTCAL_NEW_EVENT_NOTIFYDSC,
    'mail_template' => 'global_new_event',
    'mail_subject'  => _MI_EXTCAL_NEW_EVENT_NOTIFYSBJ,
];

$modversion['notification']['event'][]    = [
    'name'          => 'new_event_pending',
    'category'      => 'global',
    'title'         => _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFY,
    'caption'       => _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYCAP,
    'description'   => _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYDSC,
    'mail_template' => 'global_new_event_pending',
    'mail_subject'  => _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYSBJ,
    'admin_only'    => 1,
];

$modversion['notification']['event'][]    = [
    'name'          => 'new_event_cat',
    'category'      => 'cat',
    'title'         => _MI_EXTCAL_NEW_EVENT_CAT_NOTIFY,
    'caption'       => _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYCAP,
    'description'   => _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYDSC,
    'mail_template' => 'cat_new_event',
    'mail_subject'  => _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYSBJ,
];


// XoopsInfo
$modversion['developer_website_url']  = 'http://www.zoullou.net/';
$modversion['developer_website_name'] = 'eXtCal and EXTCALlery module for XOOPS : Zoullou.net';
$modversion['download_website']       = 'http://www.zoullou.net/';
$modversion['status_fileinfo']        = '';
$modversion['demo_site_url']          = 'http://www.zoullou.net/modules/extcal/';
$modversion['demo_site_name']         = 'eXtCal and EXTCALlery module for XOOPS : Zoullou.net';
$modversion['support_site_url']       = 'http://www.zoullou.net/';
$modversion['support_site_name']      = 'eXtCal and EXTCALlery module for XOOPS : Zoullou.net';
$modversion['submit_bug']             = 'http://sourceforge.net/tracker/?func=add&group_id=177145&atid=880070';
$modversion['submit_feature']         = 'http://sourceforge.net/tracker/?func=add&group_id=177145&atid=880073';
