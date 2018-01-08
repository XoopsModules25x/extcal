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
class Extcal_2_15
{
    //----------------------------------------------------

    /**
     * @param XoopsModule $module
     * @param             $options
     */
    public function __construct(\XoopsModule $module, $options)
    {
        global $xoopsDB;

        //$xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
        $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . "` CHANGE `event_approved` `event_approved` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";
        $xoopsDB->query($sql);

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . '` ADD `event_isrecur` TINYINT( 1 ) NOT NULL AFTER `event_nbmember` ;';
        $xoopsDB->query($sql);

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . '` ADD `event_recur_rules` VARCHAR( 255 ) NOT NULL AFTER `event_isrecur` ';
        $xoopsDB->query($sql);

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . '` ADD `event_recur_start` INT( 11 ) NOT NULL AFTER `event_recur_rules` ;';
        $xoopsDB->query($sql);

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . '` ADD `event_recur_end` INT( 11 ) NOT NULL AFTER `event_recur_start` ;';
        $xoopsDB->query($sql);

        $sql = 'CREATE TABLE `'
               . $xoopsDB->prefix('extcal_event')
               . "` (`eventnotmember_id` INT(11) NOT NULL AUTO_INCREMENT,`event_id` INT(11) NOT NULL DEFAULT '0',`uid` INT(11) NOT NULL DEFAULT '0',PRIMARY KEY  (`eventnotmember_id`),UNIQUE KEY `eventnotmember` (`event_id`,`uid`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);

        $sql = 'CREATE TABLE `'
               . $xoopsDB->prefix('extcal_file')
               . "` (`file_id` INT(11) NOT NULL AUTO_INCREMENT,`file_name` VARCHAR(255) NOT NULL,`file_nicename` VARCHAR(255) NOT NULL,`file_mimetype` VARCHAR(255) NOT NULL,`file_size` INT(11) NOT NULL,`file_download` INT(11) NOT NULL,`file_date` INT(11) NOT NULL,`file_approved` TINYINT(1) NOT NULL,`event_id` INT(11) NOT NULL,`uid` INT(11) NOT NULL,PRIMARY KEY  (`file_id`)) COMMENT='eXtcal By Zoullou' ;";
        $xoopsDB->query($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe
