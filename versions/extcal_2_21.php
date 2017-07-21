<?php
/**
 * extcal module.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright           XOOPS Project (https://xoops.org)
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 *
 * @since               2.2
 *
 * @author              JJDai <http://xoops.kiolo.com>
 **/
//----------------------------------------------------
class Extcal_2_21
{
    //----------------------------------------------------

    /**
     * @param XoopsModule $module
     * @param             $options
     */
    public function __construct(XoopsModule $module, $options)
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
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('extcal_eventnotmember')
               . "` (`eventnotmember_id` int(11) NOT NULL auto_increment,`event_id` int(11) NOT NULL default '0',`uid` int(11) NOT NULL default '0',PRIMARY KEY  (`eventnotmember_id`),UNIQUE KEY `eventnotmember` (`event_id`,`uid`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe
