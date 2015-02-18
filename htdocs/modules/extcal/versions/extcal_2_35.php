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
class extcal_2_35
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

}
//----------------------------------------------------
function alterTable_event()
{
global $xoopsDB;
  $tbl = $xoopsDB->prefix('extcal_event');

$sql = <<<__sql__
ALTER TABLE `{$tbl}`
 CHANGE `event_etablissement` `event_etablissement` INT( 5 ) NOT NULL DEFAULT '0';
__sql__;

  $xoopsDB->queryF($sql);

}

//-----------------------------------------------------------------
}   // fin de la classe
