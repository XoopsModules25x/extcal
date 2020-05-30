<?php

/**
 * @param \XoopsModule $xoopsModule
 *
 * @return bool
 */

use XoopsModules\Extcal\Helper;

function xoops_module_install_extcal(\XoopsModule $xoopsModule)
{
    // Create eXtCal upload directory
    $dir = XOOPS_ROOT_PATH . '/uploads/extcal';
    if (!is_dir($dir)) {
        mkdir($dir);
        mkdir($dir . '/location');

        // Copy index.html files on uploads folders
        $indexFile = __DIR__ . '/index.html';
        copy($indexFile, $dir . '/index.html');
        copy($indexFile, $dir . '/location/index.html');
    }

    $helper = Helper::getInstance();

    $moduleId               = $helper->getModule()->getVar('mid');
    $grouppermHandler = xoops_getHandler('groupperm');
    $configHandler          = xoops_getHandler('config');

    /*
     * Default public category permission mask
     */

    // Access right
    $grouppermHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_USERS, $moduleId);
    $grouppermHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    // Can submit
    $grouppermHandler->addRight('extcal_perm_mask', 2, XOOPS_GROUP_ADMIN, $moduleId);

    // Auto approve
    $grouppermHandler->addRight('extcal_perm_mask', 4, XOOPS_GROUP_ADMIN, $moduleId);

    // Can Edit
    $grouppermHandler->addRight('extcal_perm_mask', 8, XOOPS_GROUP_ADMIN, $moduleId);

    return true;
}
