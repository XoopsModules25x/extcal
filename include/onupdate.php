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

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)
    || !$GLOBALS['xoopsUser']->IsAdmin()
) {
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

    return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) ? true : false;
}

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_extcal(XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $classUtility  = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($classUtility)) {
        xoops_load('utility', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$classUtility::checkVerXoops($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$classUtility::checkVerPhp($module)) {
        return false;
    }

    return true;
}

/**
 *
 * Performs tasks required during update of the module
 * @param XoopsModule $module {@link XoopsModule}
 * @param null        $previousVersion
 *
 * @return bool true if update successful, false if not
 */

function xoops_module_update_extcal(XoopsModule $module, $previousVersion = null)
{
    //    global $xoopsDB;

    $moduleDirName = basename(dirname(__DIR__));

    $newVersion = $xoopsModule->getVar('version') * 100;
    if ($newVersion == $previousVersion) {
        return true;
    }

    $fld = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/versions/';
    $cls = 'extcal_%1$s';

    $version = array(
        '2_04' => 204,
        '2_15' => 215,
        '2_21' => 221,
        '2_28' => 228,
        '2_29' => 229,
        '2_33' => 233,
        '2_34' => 234,
        '2_35' => 235,
        '2_37' => 237,
    );

//    while (list($key, $val) = each($version)) {
    foreach ($version as $key => $val) {
        if ($previousVersion < $val) {
            $name = sprintf($cls, $key);
            $f    = $fld . $name . '.php';
            //ext_echo ("<hr>{$f}<hr>");
            if (is_readable($f)) {
                echo "mise à jour version : {$key} = {$val}<br>";
                require_once $f;
                $cl = new $name($xoopsModule, array('previousVersion' => $previousVersion));
            }
        }
    }

    if ($previousVersion < 240) {
        $configurator = include __DIR__ . '/config.php';
        $classUtility = ucfirst($moduleDirName) . 'Utility';
        if (!class_exists($classUtility)) {
            xoops_load('utility', $moduleDirName);
        }

        //delete old HTML templates
        if (count($configurator['templateFolders']) > 0) {
            foreach ($configurator['templateFolders'] as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), array('..', '.'));
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new SplFileInfo($templateFolder . $v);
                        if ($fileInfo->getExtension() === 'html' && $fileInfo->getFilename() !== 'index.html') {
                            if (file_exists($templateFolder . $v)) {
                                unlink($templateFolder . $v);
                            }
                        }
                    }
                }
            }
        }

        //  ---  COPY blank.png FILES ---------------
        if (count($configurator['copyFiles']) > 0) {
            $file = __DIR__ . '/../assets/images/blank.png';
            foreach (array_keys($configurator['copyFiles']) as $i) {
                $dest = $configurator['copyFiles'][$i] . '/blank.png';
                $classUtility::copyFile($file, $dest);
            }
        }

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator['oldFiles']) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator['oldFiles']) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator['oldFiles'][$i]);
                if (is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //---------------------

        //delete .html entries from the tpl table
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
        $xoopsDB->queryF($sql);

        // Load class XoopsFile ====================
        xoops_load('XoopsFile');

        //delete /images directory ============
        $imagesDirectory = $GLOBALS['xoops']->path('modules/' . $module->getVar('dirname', 'n') . '/images/');
        $folderHandler   = XoopsFile::getHandler('folder', $imagesDirectory);
        $folderHandler->delete($imagesDirectory);
    }

    $gpermHandler = xoops_getHandler('groupperm');

    return $gpermHandler->deleteByModule($module->getVar('mid'), 'item_read');
}
