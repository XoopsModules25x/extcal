<?php
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

require_once __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();

$classUtility = ucfirst($moduleDirName) . 'Utility';
if (!class_exists($classUtility)) {
    xoops_load('utility', $moduleDirName);
}

$configurator = include __DIR__ .  '/../include/config.php';

foreach (array_keys($configurator['uploadFolders']) as $i) {
    $classUtility::createFolder($configurator['uploadFolders'][$i]);
    $adminObject->addConfigBoxLine($configurator['uploadFolders'][$i], 'folder');
    //    $adminObject->addConfigBoxLine(array($configurator['uploadFolders'][$i], '777'), 'chmod');
}




echo $adminObject->displayNavigation(basename(__FILE__));
echo $adminObject->renderIndex();

require_once __DIR__ . '/admin_footer.php';
