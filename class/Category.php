<?php namespace XoopsModules\Extcal;

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
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// require_once __DIR__ . '/ExtcalPersistableObjectHandler.php';
require_once __DIR__ . '/perm.php';
require_once __DIR__ . '/time.php';

/**
 * Class Category.
 */
class Category extends \XoopsObject
{
    public $externalKey = [];

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('cat_desc', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('cat_color', XOBJ_DTYPE_TXTBOX, '000000', false, 255);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cat_icone', XOBJ_DTYPE_TXTBOX, '', false, 50);
    }
}
