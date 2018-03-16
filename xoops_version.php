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

// defined('XOOPS_ROOT_PATH') || die('XOOPS Root Path not defined');

include __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/agenda_fnc.php';
require_once __DIR__ . '/class/config.php';
//$loc_de = setlocale (LC_ALL, 'french');

//echo "local :" .  setlocale(LC_TIME, $xoopsConfig['language'])."</ br>";
setlocale(LC_TIME, $xoopsConfig['language']);

//***************************************************************************************
$modversion['version']          = '2.40';
$modversion['module_status']    = 'Beta 1';
$modversion['release_date']     = '2018/01/08';
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
$modversion['onInstall']        = 'include/install_function.php';
$modversion['onUpdate']         = 'include/update_function.php';
$modversion['system_menu']      = 1;
$modversion['help']             = 'page=help';
//$modversion['dirmoduleadmin']   = 'Frameworks/moduleclasses/moduleadmin';
//$modversion['sysicons16']       = 'Frameworks/moduleclasses/icons/16';
//$modversion['sysicons32']       = 'Frameworks/moduleclasses/icons/32';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
//about
$modversion['module_website_url']  = 'www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Menu

// definitioin des menus
$modversion['hasMain'] = 1;
$i                     = 0;

if (isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule'])
    && 'extcal' === $GLOBALS['xoopsModule']->getVar('dirname')) {
    $user = isset($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser'] : null;
    //    $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    $catHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
    if ($catHandler->haveSubmitRight($user)) {
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
    $moduleDirName . '_' . 'etablissement'
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
$i = 0;

$modversion['config'][$i]['name']        = 'visible_tabs';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_VISIBLE_TABS';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_VISIBLE_TABS_DESC';
$modversion['config'][$i]['formtype']    = 'select_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = [
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
];
// $t = print_r($modversion['config'][$i]['default'],true);
// echo _EXTCAL_NAV_CALMONTH . "<br><pre>{$t}</pre>";
$modversion['config'][$i]['options'] = [
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
];
//-----------------------------------------------------------------------------
++$i;
$modversion['config'][$i]['name']        = 'weight_tabs';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_TABS_WEIGHT';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_TABS_WEIGHT_DESC';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
//$modversion['config'][$i]['default'] = "10,20,30,40,50,_EXTCAL_TS_MINUTE,70,80,90,100,0";
$modversion['config'][$i]['default'] = _EXTCAL_NAV_LIST;

//-----------------------------------------------------------------------------
++$i;
$modversion['config'][$i]['name']        = 'start_page';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_START_PAGE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _EXTCAL_FILE_CALMONTH;
$modversion['config'][$i]['options']     = [
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
];

++$i;
$modversion['config'][$i]['name']        = 'week_start_day';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_WEEK_START_DAY';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_WEEK_START_DAY_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = [
    '_MI_EXTCAL_DAY_SUNDAY'    => 0,
    '_MI_EXTCAL_DAY_MONDAY'    => 1,
    '_MI_EXTCAL_DAY_TUESDAY'   => 2,
    '_MI_EXTCAL_DAY_WEDNESDAY' => 3,
    '_MI_EXTCAL_DAY_THURSDAY'  => 4,
    '_MI_EXTCAL_DAY_FRIDAY'    => 5,
    '_MI_EXTCAL_DAY_SATURDAY'  => 6,
];
++$i;
$modversion['config'][$i]['name']        = 'list_position';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_LIST_POS';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_LIST_POS_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = [
    '_MI_EXTCAL_BEFORE' => 0,
    '_MI_EXTCAL_AFTER'  => 1,
];

xoops_load('XoopsEditorHandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

++$i;
$modversion['config'][$i]['name']        = 'editorAdmin';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EDITOR_ADMIN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EDITOR_ADMIN_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = $editorList;
$modversion['config'][$i]['default']     = 'dhtml';

++$i;
$modversion['config'][$i]['name']        = 'editorUser';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EDITOR_USER';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EDITOR_USER_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = $editorList;
$modversion['config'][$i]['default']     = 'dhtml';

++$i;
$modversion['config'][$i]['name']        = 'rss_cache_time';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_RSS_CACHE_TIME';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_RSS_CACHE_TIME_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = _EXTCAL_TS_MINUTE;
++$i;
$modversion['config'][$i]['name']        = 'rss_nb_event';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_RSS_NB_EVENT';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_RSS_NB_EVENT_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
++$i;
$modversion['config'][$i]['name']        = 'whos_going';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_WHOS_GOING';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_WHOS_GOING_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
++$i;
$modversion['config'][$i]['name']        = 'whosnot_going';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_WHOSNOT_GOING';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_WHOSNOT_GOING_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
++$i;
$modversion['config'][$i]['name']        = 'sort_order';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_SORT_ORDER';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_SORT_ORDER_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = [
    '_MI_EXTCAL_ASCENDING'  => 'ASC',
    '_MI_EXTCAL_DESCENDING' => 'DESC',
];
++$i;
$modversion['config'][$i]['name']        = 'event_date_year';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EY_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EY_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_EY_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'nav_date_month';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_NM_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_NM_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_NM_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'event_date_month';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EM_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EM_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_EM_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'nav_date_week';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_NW_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_NW_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_NW_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'event_date_week';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EW_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EW_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_EW_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'nav_date_day';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_ND_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_ND_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_ND_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'event_date_day';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_ED_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_ED_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_ED_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'event_date_event';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EE_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EE_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_EE_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'event_date_block';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EB_DATE_PATTERN';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EB_DATE_PATTERN_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_EXTCAL_EB_DATE_PATTERN_VALUE;
++$i;
$modversion['config'][$i]['name']        = 'diplay_past_event_list';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_DISP_PAST_E_LIST';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_DISP_PAST_E_LIST_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
++$i;
$modversion['config'][$i]['name']        = 'diplay_past_event_cal';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_DISP_PAST_E_CAL';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_DISP_PAST_E_CAL_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
++$i;
$modversion['config'][$i]['name']        = 'allowed_file_extention';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_FILE_EXTENTION';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_FILE_EXTENTION_DESC';
$modversion['config'][$i]['formtype']    = 'select_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = ['doc', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'txt'];
$modversion['config'][$i]['options']     = [
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
];
++$i;
$modversion['config'][$i]['name']        = 'allow_html';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_HTML';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_HTML_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
//modif JJD ------------------------------------------------------------
++$i;
$modversion['config'][$i]['name']        = 'agenda_tranche_minutes';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_AGENDA_SLICE_MINUTES';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_AGENDA_SLICE_MINUTES_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 15;
++$i;
$modversion['config'][$i]['name']        = 'agenda_start_hour';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_AGENDA_START_HOUR';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_AGENDA_START_HOUR_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 8;
++$i;
$modversion['config'][$i]['name']        = 'agenda_end_hour';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_AGENDA_END_HOUR';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_AGENDA_END_HOUR_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 20;
++$i;
$modversion['config'][$i]['name']        = 'agenda_nb_days_week';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_AGENDA_NB_DAYS_WEEK';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_AGENDA_NB_DAYS_WEEK_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
++$i;
$modversion['config'][$i]['name']        = 'agenda_nb_days_day';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_AGENDA_NB_DAYS_DAY';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_AGENDA_NB_DAYS_DAY_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

++$i;
$modversion['config'][$i]['name']        = 'agenda_nb_years_before';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_NB_YEARS_BEFORE';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_NB_YEARS_BEFORE_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
++$i;
$modversion['config'][$i]['name']        = 'agenda_nb_years_after';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_NB_YEARS_AFTER';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_NB_YEARS_AFTER_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 5;

++$i;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_EXTCAL_SHOW_OTHEROPTIONS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';

//----------------------------------------------------------
++$i;
$modversion['config'][$i]['name']        = 'showInfoBulle';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_SHOW_INFOBULLE';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_SHOW_INFOBULLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

++$i;
$modversion['config'][$i]['name']        = 'showId';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_SHOW_ID';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_SHOW_ID_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/*
inutilise pour le moment, prevu pour ajout navigation dans minical
*/
++$i;
$modversion['config'][$i]['name']        = 'offsetMinical';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_OFFSET_MINICAL';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_OFFSET_MINICAL_DESC';
$modversion['config'][$i]['formtype']    = 'hidden';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

++$i;
$modversion['config'][$i]['name']        = 'nbEventsByPage';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_NB_EVENTS_BY_PAGE';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_NB_EVENTS_BY_PAGE_DESC';
$modversion['config'][$i]['formtype']    = 'int';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;

// utilisation de security image
++$i;
$modversion['config'][$i]['name']        = 'email_Mode';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_EMAIL_MODE';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_EMAIL_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$modversion['config'][$i]['options']     = [
    '_MI_EXTCAL_EMAIL_MODE_NONE' => 0,
    '_MI_EXTCAL_EMAIL_MODE_TEXT' => 1,
    '_MI_EXTCAL_EMAIL_MODE_HTML' => 2,
];

++$i;
$modversion['config'][$i]['name']        = 'pear_path';
$modversion['config'][$i]['title']       = '_MI_EXTCAL_PEAR_PATH';
$modversion['config'][$i]['description'] = '_MI_EXTCAL_PEAR_PATH_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['size']        = 80;
$modversion['config'][$i]['default']     = '';

//modif JJD ------------------------------------------------------------

// Templates
$i                                          = 1;
$modversion['templates'][$i]['file']        = 'extcal_view_year.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_month.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_week.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_day.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_calendar-month.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_calendar-week.tpl';
$modversion['templates'][$i]['description'] = '';
//modif JJD
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_agenda-day.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_agenda-week.tpl';
$modversion['templates'][$i]['description'] = '';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_search.tpl';
$modversion['templates'][$i]['description'] = 'recherche evenements';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_view_new-event.tpl';
$modversion['templates'][$i]['description'] = 'Nouvel evenement';
//------------------------------------------------------------
++$i;
$modversion['templates'][$i]['file']        = 'extcal_event_list1.tpl';
$modversion['templates'][$i]['description'] = 'Liste des evennements';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_event_nav_btn.tpl';
$modversion['templates'][$i]['description'] = 'boutons de navigation';
//------------------------------------------------------------

++$i;
$modversion['templates'][$i]['file']        = 'extcal_event.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_post.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_rss.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_navbar.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'extcal_etablissement.tpl';
$modversion['templates'][$i]['description'] = 'etablissement';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_info_bulle.tpl';
$modversion['templates'][$i]['description'] = 'Info bulle';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_categorie.tpl';
$modversion['templates'][$i]['description'] = 'Categorie';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_horloge.tpl';
$modversion['templates'][$i]['description'] = 'Horloge';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_imgXoops.tpl';
$modversion['templates'][$i]['description'] = 'Images Xoops';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_buttons_event.tpl';
$modversion['templates'][$i]['description'] = 'buttons event';

//-------------------------------------------------------------
++$i;
$modversion['templates'][$i]['file']        = 'extcal_mail_member_text.tpl';
$modversion['templates'][$i]['description'] = 'Mail text member inscription/desinscription';

++$i;
$modversion['templates'][$i]['file']        = 'extcal_mail_member_html.tpl';
$modversion['templates'][$i]['description'] = 'Mail html member inscription/desinscription';
//-------------------------------------------------------------
//template de l'admin
//-------------------------------------------------------------
++$i;
$modversion['templates'][$i]['file']        = 'admin/extcal_admin_cat_list.tpl';
$modversion['templates'][$i]['description'] = 'Category list';

//-------------------------------------------------------------

// Blocs
$i                                       = 1;
$modversion['blocks'][$i]['file']        = 'minical.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME1_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalMinicalShow';
$modversion['blocks'][$i]['options']     = '0|0|150|225|1|3|10|0|1|1,2,3,4,5|| |120|120';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalMinicalEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_minical.tpl';
//++$i;
//$modversion['blocks'][$i]['file'] = "spotlight_events.php";
//$modversion['blocks'][$i]['name'] = _MI_EXTCAL_BNAME2;
//$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME2_DESC;
//$modversion['blocks'][$i]['show_func'] = "bExtcalSpotlightShow";
//$modversion['blocks'][$i]['options'] = "0|0|0|1|0";
//$modversion['blocks'][$i]['edit_func'] = "bExtcalSpotlightEdit";
//$modversion['blocks'][$i]['template'] = 'extcal_block_spotlight.tpl';
++$i;
$modversion['blocks'][$i]['file']        = 'upcoming.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME3_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalUpcomingShow';
$modversion['blocks'][$i]['options']     = '5|25|30|0';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalUpcomingEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_upcoming.tpl';
++$i;
$modversion['blocks'][$i]['file']        = 'day_events.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME4_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalDayShow';
$modversion['blocks'][$i]['options']     = '5|25|0';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalDayEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_day.tpl';
++$i;
$modversion['blocks'][$i]['file']        = 'new_events.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME5;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME5_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalNewShow';
$modversion['blocks'][$i]['options']     = '5|25|0';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalNewEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_new.tpl';
++$i;
$modversion['blocks'][$i]['file']        = 'random_events.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME6;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME6_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalRandomShow';
$modversion['blocks'][$i]['options']     = '5|25|0';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalRandomEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_random.tpl';
++$i;
$modversion['blocks'][$i]['file']        = 'category_events.php';
$modversion['blocks'][$i]['name']        = _MI_EXTCAL_BNAME7;
$modversion['blocks'][$i]['description'] = _MI_EXTCAL_BNAME7_DESC;
$modversion['blocks'][$i]['show_func']   = 'bExtcalUpcomingByCategoryShow';
$modversion['blocks'][$i]['options']     = '5|25|0';
$modversion['blocks'][$i]['edit_func']   = 'bExtcalUpcomingByCategoryEdit';
$modversion['blocks'][$i]['template']    = 'extcal_block_upcomingByCategory.tpl';

//---------------------------------------------------------
// Notifications
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'extcal_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_EXTCAL_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_EXTCAL_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = '*';
$modversion['notification']['category'][1]['item_name']      = '';

$modversion['notification']['category'][2]['name']           = 'cat';
$modversion['notification']['category'][2]['title']          = _MI_EXTCAL_CAT_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_EXTCAL_CAT_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = ['calendar.php', 'year.php', 'day.php'];
$modversion['notification']['category'][2]['item_name']      = 'cat';

$modversion['notification']['category'][3]['name']           = 'event';
$modversion['notification']['category'][3]['title']          = _MI_EXTCAL_EVENT_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_EXTCAL_EVENT_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'event.php';
$modversion['notification']['category'][3]['item_name']      = 'event';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name']          = 'new_event';
$modversion['notification']['event'][1]['category']      = 'global';
$modversion['notification']['event'][1]['title']         = _MI_EXTCAL_NEW_EVENT_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_EXTCAL_NEW_EVENT_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_EXTCAL_NEW_EVENT_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_new_event';
$modversion['notification']['event'][1]['mail_subject']  = _MI_EXTCAL_NEW_EVENT_NOTIFYSBJ;

$modversion['notification']['event'][2]['name']          = 'new_event_pending';
$modversion['notification']['event'][2]['category']      = 'global';
$modversion['notification']['event'][2]['title']         = _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_new_event_pending';
$modversion['notification']['event'][2]['mail_subject']  = _MI_EXTCAL_NEW_EVENT_PENDING_NOTIFYSBJ;
$modversion['notification']['event'][2]['admin_only']    = 1;

$modversion['notification']['event'][3]['name']          = 'new_event_cat';
$modversion['notification']['event'][3]['category']      = 'cat';
$modversion['notification']['event'][3]['title']         = _MI_EXTCAL_NEW_EVENT_CAT_NOTIFY;
$modversion['notification']['event'][3]['caption']       = _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYCAP;
$modversion['notification']['event'][3]['description']   = _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'cat_new_event';
$modversion['notification']['event'][3]['mail_subject']  = _MI_EXTCAL_NEW_EVENT_CAT_NOTIFYSBJ;

// XoopsInfo
$modversion['developer_website_url']  = 'http://www.zoullou.net/';
$modversion['developer_website_name'] = 'eXtCal and eXtGallery module for XOOPS : Zoullou.net';
$modversion['download_website']       = 'http://www.zoullou.net/';
$modversion['status_fileinfo']        = '';
$modversion['demo_site_url']          = 'http://www.zoullou.net/modules/extcal/';
$modversion['demo_site_name']         = 'eXtCal and eXtGallery module for XOOPS : Zoullou.net';
$modversion['support_site_url']       = 'http://www.zoullou.net/';
$modversion['support_site_name']      = 'eXtCal and eXtGallery module for XOOPS : Zoullou.net';
$modversion['submit_bug']             = 'http://sourceforge.net/tracker/?func=add&group_id=177145&atid=880070';
$modversion['submit_feature']         = 'http://sourceforge.net/tracker/?func=add&group_id=177145&atid=880073';
