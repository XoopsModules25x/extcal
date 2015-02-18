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
class extcal_2_28
{
//----------------------------------------------------

    /**
     * @param $module
     * @param $options
     */
    function extcal_2_28(& $module, $options)
{
global $xoopsDB;

  $this->addTable_etablissement();
  $this->alterTable_event();

}
//----------------------------------------------------
function alterTable_event()
{
global $xoopsDB;

  $tbl = $xoopsDB->prefix('extcal_event');

$sql = <<<__sql__
ALTER TABLE `{$tbl}`
  add  `event_organisateur` varchar(255) NOT NULL default '',
  add  `event_picture1` varchar(255) NOT NULL,
  add  `event_picture2` varchar(255) NOT NULL,
  add  `event_price` varchar(255) NOT NULL default '',
  add  `event_etablissement` int(5) NOT NULL DEFAULT '1';
__sql__;

  $xoopsDB->queryF($sql);

}

//----------------------------------------------------
function addTable_etablissement()
{
global $xoopsDB;

  $tbl = $xoopsDB->prefix('extcal_etablissement');

$sql = <<<__sql__
CREATE TABLE `{$tbl}` (
  `id` int(5) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `adresse2` varchar(255) NOT NULL,
  `cp` varchar(10) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `tel_fixe` varchar(20) NOT NULL,
  `tel_portable` varchar(20) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `horaires` text NOT NULL,
  `divers` text NOT NULL,
  `tarifs` text NOT NULL,
  `map` text NOT NULL,

  PRIMARY KEY  (`id`)
) ENGINE = MYISAM ;
__sql__;

  $xoopsDB->queryF($sql);
//---------------------------------------------------

}
//-----------------------------------------------------------------
}   // fin de la classe
