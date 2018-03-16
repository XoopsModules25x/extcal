<?php namespace XoopsModules\Extcal;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Perm.
 */
class Perm
{
    /**
     * @return Perm
     */
    public static function getHandler()
    {
        static $permHandler;
        if (!isset($permHandler)) {
            $permHandler = new self();
        }

        return $permHandler;
    }

    /**
     * @param $user
     *
     * @return string
     */
    public function getUserGroup(&$user)
    {
        if (is_a($user, 'XoopsUser')) {
            return $user->getGroups();
        } else {
            return XOOPS_GROUP_ANONYMOUS;
        }
    }

    /**
     * @param           $user
     * @param           $perm
     *
     * @return bool
     */
    public function getAuthorizedCat($user, $perm)
    {
        static $authorizedCat;
        $userId = $user ? $user->getVar('uid') : 0;
        if (!isset($authorizedCat[$perm][$userId])) {
            $groupPermHandler = xoops_getHandler('groupperm');
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname('extcal');
            if (!$module) {
                return false;
            }
            $authorizedCat[$perm][$userId] = $groupPermHandler->getItemIds($perm, $this->getUserGroup($user), $module->getVar('mid'));
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
