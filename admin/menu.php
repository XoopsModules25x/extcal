<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
$module_handler  = xoops_getHandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i                      = 1;
$adminmenu[$i]['title'] = _MI_EXTCAL_INDEX;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_EXTCAL_CATEGORY;
$adminmenu[$i]['link']  = 'admin/cat.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_EXTCAL_EVENT;
$adminmenu[$i]['link']  = 'admin/event.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/event.png';
++$i;
$adminmenu[$i]['title'] = _MI_EXTCAL_ETABLISSEMENTS;
$adminmenu[$i]['link']  = 'admin/etablissement.php';
//$adminmenu[$i]["icon"] = '../../'.$pathImageAdmin.'/etablissement.png';
$adminmenu[$i]['icon'] = 'assets/images/icons/32/etablissement.png';
//echo $adminmenu[$i]["icon"]."<br>";

++$i;
$adminmenu[$i]['title'] = _MI_EXTCAL_PERMISSIONS;
$adminmenu[$i]['link']  = 'admin/permissions.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/permissions.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_EXTCAL_PRUNING;
//$adminmenu[$i]['link'] = "admin/prune.php";
//$adminmenu[$i]["icon"] = "assets/images/admin/about.png";
++$i;
$adminmenu[$i]['title'] = _MI_EXTCAL_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
