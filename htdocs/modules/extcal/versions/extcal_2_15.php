<?php
/**
 * extcal module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright	The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package	extcal
 * @since		2.2
 * @author 		JJDai <http://xoops.kiolo.com>
 * @version		$Id$
**/

//----------------------------------------------------
class extcal_2_15
{
//----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    function extcal_2_15(& $module, $options)
{
global $xoopsDB;

        //$xoopsDB =& Database::getInstance();
        $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();

        $sql = "ALTER TABLE `".$xoopsDB->prefix('extcal_event')."` CHANGE `event_approved` `event_approved` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";
        $xoopsDB->query($sql);

        $sql = "ALTER TABLE `".$xoopsDB->prefix('extcal_event')."` ADD `event_isrecur` TINYINT( 1 ) NOT NULL AFTER `event_nbmember` ;";
        $xoopsDB->query($sql);

        $sql = "ALTER TABLE `".$xoopsDB->prefix('extcal_event')."` ADD `event_recur_rules` VARCHAR( 255 ) NOT NULL AFTER `event_isrecur` ";
        $xoopsDB->query($sql);

        $sql = "ALTER TABLE `".$xoopsDB->prefix('extcal_event')."` ADD `event_recur_start` INT( 11 ) NOT NULL AFTER `event_recur_rules` ;";
        $xoopsDB->query($sql);

        $sql = "ALTER TABLE `".$xoopsDB->prefix('extcal_event')."` ADD `event_recur_end` INT( 11 ) NOT NULL AFTER `event_recur_start` ;";
        $xoopsDB->query($sql);

        $sql = "CREATE TABLE `".$xoopsDB->prefix('extcal_event')."` (`eventnotmember_id` int(11) NOT NULL auto_increment,`event_id` int(11) NOT NULL default '0',`uid` int(11) NOT NULL default '0',PRIMARY KEY  (`eventnotmember_id`),UNIQUE KEY `eventnotmember` (`event_id`,`uid`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);

        $sql = "CREATE TABLE `".$xoopsDB->prefix('extcal_file')."` (`file_id` int(11) NOT NULL auto_increment,`file_name` varchar(255) NOT NULL,`file_nicename` varchar(255) NOT NULL,`file_mimetype` varchar(255) NOT NULL,`file_size` int(11) NOT NULL,`file_download` int(11) NOT NULL,`file_date` int(11) NOT NULL,`file_approved` tinyint(1) NOT NULL,`event_id` int(11) NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY  (`file_id`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);

}

//-----------------------------------------------------------------
}   // fin de la classe
