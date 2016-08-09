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
 * @copyright           XOOPS Project (http://xoops.org)
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package             extcal
 * @since               2.2
 * @author              JJDai <http://xoops.kiolo.com>
 * @version             $Id$
 **/
//----------------------------------------------------
class extcal_2_04
{
    //----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    public function __construct(& $module, $options)
    {
        global $xoopsDB;

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . "` ADD `event_nbmember` tinyint(4) NOT NULL default '0' AFTER `event_submitdate` ;";
        $xoopsDB->query($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe
