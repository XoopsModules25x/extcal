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
class extcal_2_37
{
//----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    function __construct(& $module, $options)
{
global $xoopsDB;

  $this->alterTable_event();
  $this->alterTable_cat();

}
//----------------------------------------------------
function alterTable_event()
{
global $xoopsDB;
  $tbl = $xoopsDB->prefix('extcal_event');

$sql = <<<__sql__
ALTER TABLE `{$tbl}` ADD `event_icone` VARCHAR(50) NOT NULL;
__sql__;

  $xoopsDB->queryF($sql);
}

//-----------------------------------------------------------------

function alterTable_cat()
{
global $xoopsDB;
  $tbl = $xoopsDB->prefix('extcal_cat');

$sql = <<<__sql__
ALTER TABLE `{$tbl}` ADD `cat_icone` VARCHAR(50) NOT NULL ;
__sql__;

  $xoopsDB->queryF($sql);

}

//-----------------------------------------------------------------
}   // fin de la classe
