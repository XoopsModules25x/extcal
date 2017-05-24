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
 * @copyright    XOOPS Project http://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_extcal(XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $className     = ucfirst($moduleDirName) . 'Utilities';
    if (!class_exists($className)) {
        xoops_load('utilities', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$className::checkXoopsVer($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$className::checkPhpVer($module)) {
        return false;
    }

    $mod_tables =& $module->getInfo('tables');
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
function xoops_module_install_extcal(XoopsModule $xoopsModule)
{
    $moduleDirName = basename(dirname(__DIR__));

    $moduleId = $xoopsModule->getVar('mid');
    /** @var XoopsGroupPermHandler $groupPermissionHandler */
    $groupPermissionHandler = xoops_getHandler('groupperm');
    /** @var XoopsConfigHandler $configHandler */
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

    $classUtilities = ucfirst($moduleDirName) . 'Utilities';
    if (!class_exists($classUtilities)) {
        xoops_load('utilities', $moduleDirName);
    }

    if (count($configurator['uploadFolders']) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator['uploadFolders']) as $i) {
            $classUtilities::createFolder($configurator['uploadFolders'][$i]);
        }
    }
    if (count($configurator['copyFiles']) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator['copyFiles']) as $i) {
            $dest = $configurator['copyFiles'][$i] . '/blank.png';
            $classUtilities::copyFile($file, $dest);
        }
    }

    return true;
}
