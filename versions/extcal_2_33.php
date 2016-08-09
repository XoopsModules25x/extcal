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
class extcal_2_33
{
    //----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    public function __construct(& $module, $options)
    {
        global $xoopsDB;

        $this->alterTable_cat();
        $this->alterTable_eventmember();
    }

    //----------------------------------------------------
    public function alterTable_cat()
    {
        global $xoopsDB;
        $tbl = $xoopsDB->prefix('extcal_cat');

        $sql = <<<__sql__
ALTER TABLE `{$tbl}`
  ADD `cat_weight` INT NOT NULL DEFAULT '0';
__sql__;

        $xoopsDB->queryF($sql);
    }

    //----------------------------------------------------
    public function alterTable_eventmember()
    {
        global $xoopsDB;
        $tbl = $xoopsDB->prefix('eventmember');

        $sql = <<<__sql__
ALTER TABLE `{$tbl}`
  ADD `status` INT NOT NULL DEFAULT '0';
__sql__;

        $xoopsDB->queryF($sql);
    }

    //-----------------------------------------------------------------
}   // fin de la classe

