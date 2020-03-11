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

use XoopsModules\Extcal;

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_extcal(\XoopsModule $module)
{
    include __DIR__ . '/common.php';

    /** @var \XoopsModules\Extcal\Utility $utility */
    $utility = new \XoopsModules\Extcal\Utility();
    //check for minimum XOOPS version
    $xoopsSuccess = $utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = $utility::checkVerPhp($module);

    if ($xoopsSuccess && $phpSuccess) {
        $moduleTables = &$module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

/**
 * Performs tasks required during installation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_extcal(\XoopsModule $xoopsModule)
{
    require_once dirname(__DIR__) . '/preloads/autoloader.php';

    $moduleDirName = basename(dirname(__DIR__));

    /** @var \XoopsModules\Extcal\Helper $helper */ /** @var \XoopsModules\Extcal\Utility $utility */
    /** @var \XoopsModules\Extcal\Common\Configurator $configurator */
    $helper       = \XoopsModules\Extcal\Helper::getInstance();
    $utility      = new \XoopsModules\Extcal\Utility();
    $configurator = new \XoopsModules\Extcal\Common\Configurator();
    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');

    $moduleId = $xoopsModule->getVar('mid');
    /** @var \XoopsGroupPermHandler $groupPermissionHandler */
    $groupPermissionHandler = xoops_getHandler('groupperm');
    /** @var \XoopsModuleHandler $moduleHandler */
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

    // Can Edit
    $groupPermissionHandler->addRight($moduleDirName . '_perm_mask', 8, XOOPS_GROUP_ADMIN, $moduleId);

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }

    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return true;
}
