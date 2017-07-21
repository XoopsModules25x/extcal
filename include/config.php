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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once __DIR__ . '/../../../mainfile.php';
$moduleDirName = basename(dirname(__DIR__));

if (!defined('EXTCAL_DIRNAME')) {
    define('EXTCAL_DIRNAME', $moduleDirName);
    define('EXTCAL_PATH', XOOPS_ROOT_PATH . '/modules/' . EXTCAL_DIRNAME);
    define('EXTCAL_URL', XOOPS_URL . '/modules/' . EXTCAL_DIRNAME);
    define('EXTCAL_ADMIN', EXTCAL_URL . '/admin/index.php');
    define('EXTCAL_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . EXTCAL_DIRNAME);
    define('EXTCAL_AUTHOR_LOGOIMG', EXTCAL_URL . '/assets/images/logoModule.png');
    define('EXTCAL_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . EXTCAL_DIRNAME); // WITHOUT Trailing slash
    define('EXTCAL_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . EXTCAL_DIRNAME); // WITHOUT Trailing slash

}

//Configurator
return array(
    'name'          => 'Module Configurator',
    'uploadFolders' => array(
        EXTCAL_UPLOAD_PATH,
        EXTCAL_UPLOAD_PATH . '/etablissement'
    ),
//    'copyFiles'     => array(
//        EXTCAL_UPLOAD_PATH,
//        EXTCAL_UPLOAD_PATH . '/etablissement'
//    ),

    'templateFolders' => array(
        '/templates/',
        '/templates/blocks/',
        '/templates/admin/'

    ),
    'oldFiles'      => array(
        '/include/update_functions.php',
        '/include/install_functions.php'
    ),
);

// module information
$mod_copyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . EXTCAL_AUTHOR_LOGOIMG . "' alt='XOOPS Project' /></a>";
