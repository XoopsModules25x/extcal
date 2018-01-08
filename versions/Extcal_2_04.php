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
class Extcal_2_04
{
    //----------------------------------------------------

    /**
     * @param XoopsModule $module
     * @param             $options
     */
    public function __construct(\XoopsModule $module, $options)
    {
        global $xoopsDB;

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('extcal_event') . "` ADD `event_nbmember` TINYINT(4) NOT NULL DEFAULT '0' AFTER `event_submitdate` ;";
        $xoopsDB->query($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe
