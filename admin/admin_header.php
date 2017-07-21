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

$path = dirname(dirname(dirname(__DIR__)));
require_once $path . '/mainfile.php';
require_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$moduleDirName = basename(dirname(__DIR__));

xoops_load('XoopsRequest');

// Load language files
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

$pathIcon16           = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons16'));
$pathIcon32           = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons32'));
$xoopsModuleAdminPath = $GLOBALS['xoops']->path('www/' . $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin'));
require_once $xoopsModuleAdminPath . '/moduleadmin.php';

/** @var ExtcalCatHandler $catHandler */
$catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
/** @var ExtcalEventHandler $eventHandler */
$eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
/** @var ExtcalEventmemberHandler $eventMemberHandler */
$eventMemberHandler = xoops_getModuleHandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);
//xoops_cp_header();
$adminObject = new ModuleAdmin();
