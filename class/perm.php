<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class ExtcalPerm
 */
class ExtcalPerm
{

    /**
     * @return ExtcalPerm
     */
    public static function getHandler()
    {
        static $permHandler;
        if (!isset($permHandler)) {
            $permHandler = new ExtcalPerm();
        }

        return $permHandler;
    }

    /**
     * @param $user
     *
     * @return string
     */
    public function _getUserGroup(&$user)
    {
        if (is_a($user, 'XoopsUser')) {
            return $user->getGroups();
        } else {
            return XOOPS_GROUP_ANONYMOUS;
        }
    }

    /**
     * @param $user
     * @param $perm
     *
     * @return bool
     */
    public function getAuthorizedCat(&$user, $perm)
    {
        static $authorizedCat;
        $userId = $user ? $user->getVar('uid') : 0;
        if (!isset($authorizedCat[$perm][$userId])) {
            $groupPermHandler = xoops_getHandler('groupperm');
            $moduleHandler    = xoops_getHandler('module');
            $module           = $moduleHandler->getByDirname('extcal');
            if (!$module) {
                return false;
            }
            $authorizedCat[$perm][$userId] = $groupPermHandler->getItemIds($perm, $this->_getUserGroup($user), $module->getVar('mid'));
        }

        return $authorizedCat[$perm][$userId];
    }

    /**
     * @param $user
     * @param $perm
     * @param $catId
     *
     * @return bool
     */
    public function isAllowed(&$user, $perm, $catId)
    {
        $autorizedCat = $this->getAuthorizedCat($user, $perm);

        return in_array($catId, $autorizedCat);
    }
}
