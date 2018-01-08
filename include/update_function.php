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

/**
 * @param XoopsModule $xoopsModule
 * @param null        $previousVersion
 *
 * @return bool
 */
function xoops_module_update_extcal(\XoopsModule $xoopsModule, $previousVersion = null)
{
    $newVersion = $xoopsModule->getVar('version') * 100;
    if ($newVersion == $previousVersion) {
        return true;
    }

    //----------------------------------------------------------
    // Create eXtCal upload directory
    $indexFile = __DIR__ . '/index.html';

    $dir = XOOPS_ROOT_PATH . '/uploads/extcal';
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
        copy($indexFile, $dir . '/index.html');
    }

    $dir = XOOPS_ROOT_PATH . '/uploads/extcal/etablissement';
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
        copy($indexFile, $dir . '/index.html');
    }
    //------------------------------------------------------------

    $fld = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/versions/';
    $cls = 'extcal_%1$s';

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
                echo "mise à jour version : {$key} = {$val}<br>";
                require_once $f;
                $cl = new $name($xoopsModule, ['previousVersion' => $previousVersion]);
            }
        }
    }

    /*
        //$db = \XoopsDatabaseFactory::getDatabaseConnection();
        $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();

        $sql = "ALTER TABLE `".$db->prefix('extcal_event')."` ADD `event_organisateur` varchar( 255 ) NOT NULL AFTER `event_desc` ;";
        $db->query($sql);
        ///////////
        // Create eXtcal upload directory
        $dir = XOOPS_ROOT_PATH."/uploads/extcal/etablissement";
        if(!is_dir($dir))
            mkdir($dir, 0777);
            chmod($dir, 0777);

        // Copy index.html files on uploads folders
        $indexFile = XOOPS_ROOT_PATH."/modules/extcal/include/index.html";
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/extcal/etablissement/index.html");

        */

    return true;
}
