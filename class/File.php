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
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

/**
 * Class File.
 */
class File extends \XoopsObject
{
    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->initVar('file_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('file_nicename', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('file_mimetype', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('file_size', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_download', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_approved', XOBJ_DTYPE_INT, null, false);
        $this->initVar('event_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    }
}
