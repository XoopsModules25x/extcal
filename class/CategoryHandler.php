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

use XoopsModules\Extcal;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// // require_once __DIR__ . '/ExtcalPersistableObjectHandler.php';
//require_once __DIR__ . '/perm.php';
//require_once __DIR__ . '/time.php';

/**
 * Class CategoryHandler.
 */
class CategoryHandler extends ExtcalPersistableObjectHandler
{
    public $_extcalPerm;

    /**
     * @param $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        $this->_extcalPerm = Extcal\Perm::getHandler();
        //        parent::__construct($db, 'extcal_cat', _EXTCAL_CLN_CAT, 'cat_id');
        parent::__construct($db, 'extcal_cat', Category::class, 'cat_id');
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
        /** @var \XoopsGroupPermHandler $groupPermissionHandler */
        $groupPermissionHandler = xoops_getHandler('groupperm');
        $moduleId               = $GLOBALS['xoopsModule']->getVar('mid');

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('gperm_name', 'extcal_perm_mask'));
        $criteria->add(new \Criteria('gperm_modid', $moduleId));
        $permMask = $groupPermissionHandler->getObjects($criteria);

        // Retriving group list
        $memberHandler = xoops_getHandler('member');
        $glist         = $memberHandler->getGroupList();

        // Applying permission mask
        foreach ($permMask as $perm) {
            if (1 == $perm->getVar('gperm_itemid')) {
                $groupPermissionHandler->addRight('extcal_cat_view', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if (2 == $perm->getVar('gperm_itemid')) {
                $groupPermissionHandler->addRight('extcal_cat_submit', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if (4 == $perm->getVar('gperm_itemid')) {
                $groupPermissionHandler->addRight('extcal_cat_autoapprove', $cat->getVar('cat_id'), $perm->getVar('gperm_groupid'), $moduleId);
            }
            if (8 == $perm->getVar('gperm_itemid')) {
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
        $this->deleteById($catId);
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
        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('cat_id', $catId));
        if (!$skipPerm) {
            $this->addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);
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
        $criteriaCompo = new \CriteriaCompo();
        if ('all' !== $perm) {
            $this->addCatPermCriteria($criteriaCompo, $user, $perm);
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
        $criteriaCompo = new \CriteriaCompo();
        if ('all' !== $perm) {
            $this->addCatPermCriteria($criteriaCompo, $user, $perm);
        }

        $t = $this->objectToArray($this->getObjects($criteriaCompo));
        $r = [];
        //        while (list($k, $v) = each($t)) {
        foreach ($t as $k => $v) {
            $r[$v['cat_id']] = $v;
        }

        return $r;
    }

    /**
     * @param   \CriteriaElement $criteria
     * @param                    $user
     * @param string             $perm
     */
    public function addCatPermCriteria(\CriteriaElement $criteria, $user, $perm = 'extcal_cat_view')
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
            $criteria->add(new \Criteria('cat_id', $in, 'IN'));
        } else {
            $criteria->add(new \Criteria('cat_id', '(0)', 'IN'));
        }
    }

    /**
     * @param \XoopsUser|string $xoopsUser
     *
     * @return bool
     */
    public function haveSubmitRight($xoopsUser)
    {
        return count($this->_extcalPerm->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0;
    }
}
