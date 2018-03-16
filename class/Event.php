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
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/ExtDateTime.php';
require_once __DIR__ . '/utility.php';
require_once __DIR__ . '/../include/constantes.php';

/**
 * Class Event.
 */
class Event extends \XoopsObject
{
    public $externalKey = [];

    /**
     *
     */
    public function __construct()
    {
        $this->initVar('event_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('event_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('event_desc', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('event_organisateur', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('event_contact', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('event_url', XOBJ_DTYPE_URL, '', false);
        $this->initVar('event_email', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('event_address', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('event_approved', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_start', XOBJ_DTYPE_INT, null, true);
        $this->initVar('event_end', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_submitter', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_submitdate', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_nbmember', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_isrecur', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_recur_rules', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('event_recur_start', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_recur_end', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('event_picture1', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('event_picture2', XOBJ_DTYPE_TXTBOX, null, false);
        //$this->initVar("event_price",XOBJ_DTYPE_OTHER,null,false,10);
        $this->initVar('event_price', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('event_etablissement', XOBJ_DTYPE_INT, 5, false);
        $this->initVar('event_icone', XOBJ_DTYPE_TXTBOX, '', false);

        $this->externalKey['cat_id']          = [
            'className'      => 'Category',
            'getMethodeName' => 'getCat',
            'keyName'        => 'cat',
            'core'           => false,
        ];
        $this->externalKey['event_submitter'] = [
            'className'      => 'user',
            'getMethodeName' => 'get',
            'keyName'        => 'user',
            'core'           => true,
        ];
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getExternalKey($key)
    {
        return $this->externalKey[$key];
    }
}
