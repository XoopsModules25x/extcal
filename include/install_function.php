<?php

/**
 * @param XoopsModule $xoopsModule
 *
 * @return bool
 */
function xoops_module_install_extcal(\XoopsModule $xoopsModule)
{

    // Create eXtCal upload directory
    $dir = XOOPS_ROOT_PATH . '/uploads/extcal';
    if (!is_dir($dir)) {
        mkdir($dir);
        mkdir($dir . '/etablissement');

        // Copy index.html files on uploads folders
        $indexFile = __DIR__ . '/index.html';
        copy($indexFile, $dir . '/index.html');
        copy($indexFile, $dir . '/etablissement/index.html');
    }

    $moduleId               = $xoopsModule->getVar('mid');
    $groupPermissionHandler = xoops_getHandler('groupperm');
    $configHandler          = xoops_getHandler('config');

    /*
     * Default public category permission mask
     */

    // Access right
    $groupPermissionHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $groupPermissionHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_USERS, $moduleId);
    $groupPermissionHandler->addRight('extcal_perm_mask', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    // Can submit
    $groupPermissionHandler->addRight('extcal_perm_mask', 2, XOOPS_GROUP_ADMIN, $moduleId);

    // Auto approve
    $groupPermissionHandler->addRight('extcal_perm_mask', 4, XOOPS_GROUP_ADMIN, $moduleId);

    return true;
}
