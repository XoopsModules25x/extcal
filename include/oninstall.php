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
 */

use XoopsModules\Extcal;

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_extcal(\XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $className     = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($className)) {
        xoops_load('utility', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$className::checkVerXoops($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$className::checkVerPhp($module)) {
        return false;
    }

    $mod_tables = $module->getInfo('tables');
    foreach ($mod_tables as $table) {
        $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
    }

    return true;
}

/**
 *
 * Performs tasks required during installation of the module
 * @param XoopsModule $xoopsModule
 * @return bool true if installation successful, false if not
 * @internal param XoopsModule $module <a href='psi_element://XoopsModule'>XoopsModule</a>
 *
 */
function xoops_module_install_extcal(\XoopsModule $xoopsModule)
{
    $moduleDirName = basename(dirname(__DIR__));

    $moduleId = $xoopsModule->getVar('mid');
    /** @var XoopsGroupPermHandler $groupPermissionHandler */
    $groupPermissionHandler = xoops_getHandler('groupperm');
    /** @var XoopsModuleHandler $moduleHandler */
    $configHandler = xoops_getHandler('config');

    /*
     * Default public category permission mask
     */

    // Access right
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 1, XOOPS_GROUP_USERS, $moduleId);
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    // Can submit
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 2, XOOPS_GROUP_ADMIN, $moduleId);

    // Auto approve
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 4, XOOPS_GROUP_ADMIN, $moduleId);

    //    $moduleDirName = $xoopsModule->getVar('dirname');
    $configurator = include $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/include/config.php');

    /** @var Extcal\Utility $utilityClass */
    $utilityClass = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($utilityClass)) {
        xoops_load('utility', $moduleDirName);
    }

    if (count($configurator['uploadFolders']) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator['uploadFolders']) as $i) {
            $utilityClass::createFolder($configurator['uploadFolders'][$i]);
        }
    }
    if (count($configurator['copyFiles']) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator['copyFiles']) as $i) {
            $dest = $configurator['copyFiles'][$i] . '/blank.png';
            $utilityClass::copyFile($file, $dest);
        }
    }

    return true;
}
