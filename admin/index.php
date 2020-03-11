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
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

use XoopsModules\Extcal;
use XoopsModules\Extcal\Common;

require_once __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();
/** @var Extcal\Utility $utility */

$configurator = new Common\Configurator();

//count "total categories"
/** @var \XoopsPersistableObjectHandler $categoryHandler */
$countCategory = $categoryHandler->getCount();
//count "total events"
/** @var \XoopsPersistableObjectHandler $eventHandler */
$countEvent = $eventHandler->getCount();
//count "total eventmembers"
/** @var \XoopsPersistableObjectHandler $eventMemberHandler */
$countEventmember = $eventMemberHandler->getCount();
//count "total eventnotmembers"
/** @var \XoopsPersistableObjectHandler $eventNotMemberHandler */
$countEventnotmember = $eventNotMemberHandler->getCount();
//count "total files"
/** @var \XoopsPersistableObjectHandler $fileHandler */
$countFile = $fileHandler->getCount();
//count "total location"
/** @var \XoopsPersistableObjectHandler $locationHandler */
$countLocation = $locationHandler->getCount();
// InfoBox Statistics
$adminObject->addInfoBox(AM_EXTCAL_STATISTICS);

// InfoBox extcal_cat
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_CAT, $countCategory));

// InfoBox extcal_event
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_EVENT, $countEvent));

// InfoBox extcal_eventmember
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_EVENTMEMBER, $countEventmember));

// InfoBox extcal_eventnotmember
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_EVENTNOTMEMBER, $countEventnotmember));

// InfoBox extcal_file
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_FILE, $countFile));

// InfoBox extcal_location
$adminObject->addInfoBoxLine(sprintf(AM_EXTCAL_THEREARE_EXTCAL_LOCATION, $countLocation));
// Render Index
$adminObject->displayNavigation(basename(__FILE__));

//check for latest release
//$newRelease = $utility::checkVerModule($helper);
//if (!empty($newRelease)) {
//    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
//}

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    $yamlFile            = dirname(__DIR__) . '/config/admin.yml';
    $config              = loadAdminConfig($yamlFile);
    $displaySampleButton = $config['displaySampleButton'];

    if (1 == $displaySampleButton) {
        xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once dirname(__DIR__) . '/testdata/index.php';

        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
        //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
    } else {
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
        $displaySampleButton = $config['displaySampleButton'];
    }
    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = \Xmf\Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app['displaySampleButton'] = 0;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app['displaySampleButton'] = 1;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = \Xmf\Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}

echo $utility::getServerStats();

require __DIR__ . '/admin_footer.php';

