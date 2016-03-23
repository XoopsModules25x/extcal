<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/modules/extcal/class/ExtcalPersistableObjectHandler.php';
include_once XOOPS_ROOT_PATH . '/modules/extcal/class/perm.php';
include_once XOOPS_ROOT_PATH . '/modules/extcal/class/time.php';

/**
 * Class ExtcalCat
 */
class ExtcalCat extends XoopsObject
{
    public $externalKey = array();

    /**
     * ExtcalCat constructor.
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

/**
 * Class ExtcalCatHandler
 */
class ExtcalCatHandler extends ExtcalPersistableObjectHandler
{
    public $_extcalPerm;

    /**
     * @param $db
     */
    public function __construct(XoopsDatabase $db)
    {
        $this->_extcalPerm = ExtcalPerm::getHandler();
        parent::__construct($db, 'extcal_cat', _EXTCAL_CLN_CAT, 'cat_id');
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function createCat($data)
    {
        $cat = $this->create();
        $cat->setVars($data);
        $this->insert($cat);

        $catId = $this->getInsertId();

        // Retriving permission mask
        $groupPermissionHandler = xoops_getHandler('groupperm');
        $moduleId               = $GLOBALS['xoopsModule']->getVar('mid');

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('gperm_name', 'extcal_perm_mask'));
        $criteria->add(new Criteria('gperm_modid', $moduleId));
        $permMask = $groupPermissionHandler->getObjects($criteria);

        // Retriving group list
        $memberHandler = xoops_getHandler('member');
        $glist         = $memberHandler->getGroupList();

        // Applying permission mask
        foreach ($permMask as $perm) {
            if ($perm->getVar('gperm_itemid') == 1) {
                $groupPermissionHandler->addRight('extcal_cat_view', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if ($perm->getVar('gperm_itemid') == 2) {
                $groupPermissionHandler->addRight('extcal_cat_submit', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if ($perm->getVar('gperm_itemid') == 4) {
                $groupPermissionHandler->addRight('extcal_cat_autoapprove', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if ($perm->getVar('gperm_itemid') == 8) {
                $groupPermissionHandler->addRight('extcal_cat_edit', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
        }

        return true;
    }

    /**
     * @param $catId
     * @param $data
     *
     * @return bool
     */
    public function modifyCat($catId, $data)
    {
        $cat = $this->get($catId);
        $cat->setVars($data);

        return $this->insert($cat);
    }

    /**
     * @param $catId
     */
    public function deleteCat($catId)
    {
        /* TODO :
           - Delete all events in this category
          */
        $this->delete($catId);
    }

    // Return one cat selected by his id
    /**
     * @param      $catId
     * @param bool $skipPerm
     *
     * @return bool
     */
    public function getCat($catId, $skipPerm = false)
    {
        $criteriaCompo = new CriteriaCompo();
        $criteriaCompo->add(new Criteria('cat_id', $catId));
        if (!$skipPerm) {
            $this->_addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);
        }
        $ret =& $this->getObjects($criteriaCompo);
        if (isset($ret[0])) {
            return $ret[0];
        } else {
            return false;
        }
    }

    /**
     * @param        $user
     * @param string $perm
     *
     * @return array
     */
    public function getAllCat($user, $perm = 'extcal_cat_view')
    {
        $criteriaCompo = new CriteriaCompo();
        if ($perm !== 'all') {
            $this->_addCatPermCriteria($criteriaCompo, $user, $perm);
        }

        return $this->getObjects($criteriaCompo);
    }

    /**
     * @param        $user
     * @param string $perm
     *
     * @return array
     */
    public function getAllCatById($user, $perm = 'all')
    {
        $criteriaCompo = new CriteriaCompo();
        if ($perm !== 'all') {
            $this->_addCatPermCriteria($criteriaCompo, $user, $perm);
        }

        $t = $this->objectToArray($this->getObjects($criteriaCompo));
        $r = array();
        while (list($k, $v) = each($t)) {
            $r[$v['cat_id']] = $v;
        }

        return $r;
    }

    /**
     * @param        $criteria
     * @param        $user
     * @param string $perm
     */
    public function _addCatPermCriteria(&$criteria, &$user, $perm = 'extcal_cat_view')
    {
        $authorizedAccessCats = $this->_extcalPerm->getAuthorizedCat($user, 'extcal_cat_view');
        $count                = count($authorizedAccessCats);
        if ($count > 0) {
            $in = '(' . $authorizedAccessCats[0];
            array_shift($authorizedAccessCats);
            foreach ($authorizedAccessCats as $authorizedAccessCat) {
                $in .= ',' . $authorizedAccessCat;
            }
            $in .= ')';
            $criteria->add(new Criteria('cat_id', $in, 'IN'));
        } else {
            $criteria->add(new Criteria('cat_id', '(0)', 'IN'));
        }
    }

    /**
     * @param $xoopsUser
     *
     * @return bool
     */
    public function haveSubmitRight(&$xoopsUser)
    {
        return count($this->_extcalPerm->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0;
    }
}
