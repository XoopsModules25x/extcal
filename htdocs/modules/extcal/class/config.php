<?php

// defined("XOOPS_ROOT_PATH") || exit("XOOPS root path not defined");

/**
 * Class ExtcalConfig
 */
class ExtcalConfig
{

    /**
     * @return ExtcalConfig
     */
    static function &getHandler()
    {
        static $configHandler;
        if (!isset($configHandler[0])) {
            $configHandler[0] = new ExtcalConfig();
        }

        return $configHandler[0];
    }

    function getModuleConfig()
    {
        global $xoopsModule;
        static $moduleConfig;
        $dirname = (isset($xoopsModule) ? $xoopsModule->getVar('dirname') : 'system');
        if ($dirname == 'extcal') {
            $moduleConfig = $GLOBALS['xoopsModuleConfig'];
        } else {
            if (!isset($moduleConfig)) {
                $moduleHandler =& xoops_gethandler('module');
                $module        = $moduleHandler->getByDirname('extcal');
                $configHandler =& xoops_gethandler('config');
                $moduleConfig  = $configHandler->getConfigList($module->getVar("mid"));
            }
        }

        return $moduleConfig;
    }

}
