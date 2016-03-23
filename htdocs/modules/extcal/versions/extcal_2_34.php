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
class extcal_2_34
{
    //----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    public function __construct(& $module, $options)
    {
        global $xoopsDB;

        $this->alterTable_etablissement();
    }

    //----------------------------------------------------
    public function alterTable_etablissement()
    {
        global $xoopsDB;
        $tbl = $xoopsDB->prefix('extcal_etablissement');

        $sql = <<<__sql__
ALTER TABLE `{$tbl}`
 CHANGE `desc` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
__sql__;

        $xoopsDB->queryF($sql);
        //echo "<hr>{$sql}<hr>";
    }
    //----------------------------------------------------

    //-----------------------------------------------------------------
}   // fin de la classe

