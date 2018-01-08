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
 * @author       JJDai <http://xoops.kiolo.com>
 *
 */
//----------------------------------------------------
class Extcal_2_21
{
    //----------------------------------------------------

    /**
     * @param XoopsModule $module
     * @param             $options
     */
    public function __construct(\XoopsModule $module, $options)
    {
        global $xoopsDB;

        // Create eXtcal upload directory if don't exist
        $dir = XOOPS_ROOT_PATH . '/uploads/extcal';
        if (!is_dir($dir)) {
            mkdir($dir);

            // Copy index.html files on uploads folders
            $indexFile = __DIR__ . '/index.html';
            copy($indexFile, XOOPS_ROOT_PATH . '/uploads/extcal/index.html');
        }

        // Create who's not going table to fix bug. If the table exist, the query will faile
        $sql = 'CREATE TABLE `'
               . $xoopsDB->prefix('extcal_eventnotmember')
               . "` (`eventnotmember_id` INT(11) NOT NULL AUTO_INCREMENT,`event_id` INT(11) NOT NULL DEFAULT '0',`uid` INT(11) NOT NULL DEFAULT '0',PRIMARY KEY  (`eventnotmember_id`),UNIQUE KEY `eventnotmember` (`event_id`,`uid`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe
