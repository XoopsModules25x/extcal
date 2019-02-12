<?php

namespace XoopsModules\Extcal\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
    private $renameTables;

    /**
     * Migrate constructor.
     * @param \XoopsModules\Extcal\Common\Configurator $configurator
     */
    public function __construct(\XoopsModules\Extcal\Common\Configurator $configurator)
    {
        //   require_once  dirname(dirname(__DIR__)) . '/include/config.php';
        $this->renameTables = $configurator->renameTables;

        $moduleDirName = basename(dirname(dirname(__DIR__)));
        parent::__construct($moduleDirName);
    }

    /**
     * change table prefix if needed
     */
    private function changePrefix()
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    /**
     * Change integer IPv4 column to varchar IPv6 capable
     *
     * @param string $tableName  table to convert
     * @param string $columnName column with IP address
     */
    private function convertIPAddresses($tableName, $columnName)
    {
        if ($this->tableHandler->useTable($tableName)) {
            $attributes = $this->tableHandler->getColumnAttributes($tableName, $columnName);
            if (false !== mb_strpos($attributes, ' int(')) {
                if (false === mb_strpos($attributes, 'unsigned')) {
                    $this->tableHandler->alterColumn($tableName, $columnName, " bigint(16) NOT NULL  DEFAULT '0' ");
                    $this->tableHandler->update($tableName, [$columnName => "4294967296 + $columnName"], "WHERE $columnName < 0", false);
                }
                $this->tableHandler->alterColumn($tableName, $columnName, " varchar(45)  NOT NULL  DEFAULT '' ");
                $this->tableHandler->update($tableName, [$columnName => "INET_NTOA($columnName)"], '', false);
            }
        }
    }

    /**
     * Move do* columns from tdmmoney_posts to tdmmoney_posts_text table
     */
    private function moveDoColumns()
    {
        //for an example, see newbb 5.0
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     */
    protected function preSyncActions()
    {
        // change table prefix
        if ($this->renameTables && is_array($this->renameTables)) {
            $this->changePrefix();
        }
        //        // columns dohtml, dosmiley, doxcode, doimage and dobr moved between tables as some point
        //        $this->moveDoColumns();
        //        // Convert IP address columns from int to readable varchar(45) for IPv6
        //        $this->convertIPAddresses('extgallery_posts', 'poster_ip');
        //        $this->convertIPAddresses('extgallery_report', 'reporter_ip');
    }
}
