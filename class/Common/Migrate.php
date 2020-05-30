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
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
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
        $class = __NAMESPACE__ . '\\' . 'Configurator';
        if (!\class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }
        $configurator       = new $class();
        $this->renameTables = $configurator->renameTables;

        $moduleDirName = \basename(\dirname(\dirname(__DIR__)));
        parent::__construct($moduleDirName);
    }

    /**
     * rename table if needed
     */
    private function renameTable()
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName) && !$this->tableHandler->useTable($newName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    private function renameColumn($tableName, $columnName, $newName)
    {
        if ($this->tableHandler->useTable($tableName)) {
            $attributes = $this->tableHandler->getColumnAttributes($tableName, $columnName);
            if (false !== \strpos($attributes, ' int(')) {
                $this->tableHandler->alterColumn($tableName, $columnName, $attributes, $newName);
            }
        }
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
        // rename table
        if ($this->renameTables && \is_array($this->renameTables)) {
            $this->renameTable();
        }
                $this->renameColumn('extcal_event', 'event_etablissement','event_location');
    }
}
