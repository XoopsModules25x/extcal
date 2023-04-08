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
    Utility,
    Common
};

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !$GLOBALS['xoopsUser']->isAdmin()) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * @param string $tablename
 *
 * @return bool
 */
function tableExists($tablename)
{
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return $GLOBALS['xoopsDB']->getRowsNum($result) > 0;
}

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_extcal(\XoopsModule $module)
{
    /** @var Utility $utility */
    $utility = new Utility();

    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);

    //mb    return $xoopsSuccess && $phpSuccess;

    //    XoopsLoad::load('migrate', 'extcal');
    /** @var Common\Configurator $configurator */
    $configurator = new Common\Configurator();

    //create upload folders
    $uploadFolders = $configurator->uploadFolders;
    foreach ($uploadFolders as $value) {
        $utility::prepareFolder($value);
    }

    /* handled under: xoops_module_update_extcal
    $migrator = new Common\Migrate($configurator);
    $migrator->synchronizeSchema();
    */

    return true;
}

/**
 * Performs tasks required during update of the module
 * @param \XoopsModule $module {@link XoopsModule}
 * @param null         $prevVersion
 *
 * @return bool true if update successful, false if not
 */
function xoops_module_update_extcal(\XoopsModule $module, $prevVersion = null)
{
    //    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));

    $newVersion = (int)\str_replace('.', '', $module->getVar('version'));
    $previousVersion = (int)\str_replace('.', '', $prevVersion);
    if ($newVersion == $previousVersion) {
        return true;
    }

    $fld = XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/versions/';
    $cls = 'Extcal_%1$s';

    $version = [
        '2_04' => 204,
        '2_15' => 215,
        '2_21' => 221,
        '2_28' => 228,
        '2_29' => 229,
        '2_33' => 233,
        '2_34' => 234,
        '2_35' => 235,
        '2_37' => 237,
    ];

    //    while (list($key, $val) = each($version)) {
    foreach ($version as $key => $val) {
        if ($previousVersion < $val) {
            $name = sprintf($cls, $key);
            $f    = $fld . $name . '.php';
            //ext_echo ("<hr>{$f}<hr>");
            if (is_readable($f)) {
                echo "update version: {$key} = {$val}<br>";
                require_once $f;
                $cl = new $name($module, ['previousVersion' => $previousVersion]);
            }
        }
    }

    $moduleDirNameUpper = mb_strtoupper($moduleDirName);

    /** @var Utility $utility */
    /** @var Common\Configurator $configurator */
    $utility      = new Utility();
    $configurator = new Common\Configurator();

    $migrator = new Common\Migrate($configurator);
    //$migrator->synchronizeSchema(); // TODO goffy: deactivate temporary, as it deletes columns from extcal_cat

    if ($previousVersion < 240) {
        //delete old HTML templates
        if (count($configurator->templateFolders) > 0) {
            foreach ($configurator->templateFolders as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), ['..', '.']);
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new \SplFileInfo($templateFolder . $v);
                        if ('html' === $fileInfo->getExtension() && 'index.html' !== $fileInfo->getFilename() && file_exists($templateFolder . $v)) {
                            unlink($templateFolder . $v);
                        }
                    }
                }
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

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator->oldFiles) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFiles) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
                if (is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //---------------------

        //delete .html entries from the tpl table
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
        $GLOBALS['xoopsDB']->queryF($sql);

        // Load class XoopsFile ====================
        xoops_load('XoopsFile');

        //delete /images directory ============
        $imagesDirectory = $GLOBALS['xoops']->path('modules/' . $module->getVar('dirname', 'n') . '/images/');
        $folderHandler   = \XoopsFile::getHandler('folder', $imagesDirectory);
        $folderHandler->delete($imagesDirectory);
    }
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');

    return $grouppermHandler->deleteByModule($module->getVar('mid'), 'item_read');
}
