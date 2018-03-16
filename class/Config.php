<?php namespace XoopsModules\Extcal;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Config.
 */
class Config
{
    /**
     * @return Config
     */
    public static function getHandler()
    {
        static $configHandler;
        if (!isset($configHandler[0])) {
            $configHandler[0] = new self();
        }

        return $configHandler[0];
    }

    /**
     * @return mixed
     */
    public function getModuleConfig()
    {
        global $xoopsModule;
        static $moduleConfig;
        $dirname = (isset($xoopsModule) ? $xoopsModule->getVar('dirname') : 'system');
        if ('extcal' === $dirname) {
            $moduleConfig = $GLOBALS['xoopsModuleConfig'];
        } else {
            if (!isset($moduleConfig)) {
                /** @var \XoopsModuleHandler $moduleHandler */
                $moduleHandler = xoops_getHandler('module');
                $module        = $moduleHandler->getByDirname('extcal');
                $configHandler = xoops_getHandler('config');
                $moduleConfig  = $configHandler->getConfigList($module->getVar('mid'));
            }
        }

        return $moduleConfig;
    }
}
