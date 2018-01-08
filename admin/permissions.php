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

require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
require_once __DIR__ . '/admin_header.php';

$step = 'default';
if (isset($_POST['step'])) {
    $step = $_POST['step'];
}

$moduleId = $xoopsModule->getVar('mid');

switch ($step) {

    case 'enreg':

        $groupPermissionHandler = xoops_getHandler('groupperm');

        // Delete old public mask
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('gperm_name', 'extcal_perm_mask'));
        $criteria->add(new \Criteria('gperm_modid', $moduleId));
        $groupPermissionHandler->deleteAll($criteria);

        foreach ($_POST['perms']['extcal_perm_mask']['group'] as $groupId => $perms) {
            foreach (array_keys($perms) as $perm) {
                $groupPermissionHandler->addRight('extcal_perm_mask', $perm, $groupId, $moduleId);
            }
        }

        redirect_header('permissions.php', 3, _AM_EXTCAL_PERM_MASK_UPDATED);

        break;

    case 'default':
    default:

        xoops_cp_header();
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //***************************************************************************************

        $memberHandler          = xoops_getHandler('member');
        $groupPermissionHandler = xoops_getHandler('groupperm');

        // Retriving the group list
        $glist = $memberHandler->getGroupList();

        // Retriving Public category permission mask
        $viewGroup        = $groupPermissionHandler->getGroupIds('extcal_perm_mask', 1, $moduleId);
        $submitGroup      = $groupPermissionHandler->getGroupIds('extcal_perm_mask', 2, $moduleId);
        $autoApproveGroup = $groupPermissionHandler->getGroupIds('extcal_perm_mask', 4, $moduleId);
        $editGroup        = $groupPermissionHandler->getGroupIds('extcal_perm_mask', 8, $moduleId);

        /**
         * @param $array
         * @param $v
         *
         * @return string
         */
        function getChecked($array, $v)
        {
            if (in_array($v, $array)) {
                return ' checked';
            } else {
                return '';
            }
        }

        echo '<script type="text/javascript" src="../include/admin.js"></script>';

        /*
         * Public category permission mask
         */
        echo '<fieldset id="defaultBookmark"><legend><a href="#defaultBookmark" style="font-weight:bold; color:#990000;" onClick="toggle(\'default\'); toggleIcon(\'defaultIcon\');"><img id="defaultIcon" src="../assets/images/icons/minus.gif">&nbsp;'
             . _AM_EXTCAL_PUBLIC_PERM_MASK
             . '</a></legend><div id="default">';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        echo _AM_EXTCAL_PUBLIC_PERM_MASK_INFO;
        echo '</fieldset><br>';
        echo '<table class="outer" style="width:100%;">';
        echo '<form method="post" action="permissions.php">';
        echo '<tr>';
        echo '<th colspan="8" style="text-align:center;">' . _AM_EXTCAL_PUBLIC_PERM_MASK . '</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<td class="head" style="text-align:center;">' . _AM_EXTCAL_GROUP_NAME . '</td>';
        echo '<td class="head" style="text-align:center;">' . _AM_EXTCAL_CAN_VIEW . '</td>';
        echo '<td class="head" style="text-align:center;">' . _AM_EXTCAL_CAN_SUBMIT . '</td>';
        echo '<td class="head" style="text-align:center;">' . _AM_EXTCAL_AUTO_APPROVE . '</td>';
        echo '<td class="head" style="text-align:center;">' . _AM_EXTCAL_CAN_EDIT . '</td>';
        echo '</tr>';
        $i = 0;
        foreach ($glist as $k => $v) {
            $style = (0 == ++$i % 2) ? 'odd' : 'even';
            echo '<tr>';
            echo '<td class="' . $style . '">' . $v . '</td>';
            echo '<td class="' . $style . '" style="text-align:center;"><input name="perms[extcal_perm_mask][group][' . $k . '][1]" type="checkbox"' . getChecked($viewGroup, $k) . '></td>';
            echo '<td class="' . $style . '" style="text-align:center;"><input name="perms[extcal_perm_mask][group][' . $k . '][2]" type="checkbox"' . getChecked($submitGroup, $k) . '></td>';
            echo '<td class="' . $style . '" style="text-align:center;"><input name="perms[extcal_perm_mask][group][' . $k . '][4]" type="checkbox"' . getChecked($autoApproveGroup, $k) . '></td>';
            echo '<td class="' . $style . '" style="text-align:center;"><input name="perms[extcal_perm_mask][group][' . $k . '][8]" type="checkbox"' . getChecked($editGroup, $k) . '></td>';
            echo '</tr>';
        }
        echo '<input type="hidden" name="type" value="public">';
        echo '<input type="hidden" name="step" value="enreg">';
        echo '<tr><td colspan="8" style="text-align:center;" class="head"><input type="submit" value="' . _SUBMIT . '"></td></tr></form>';
        echo '</table><br>';

        echo '</div></fieldset><br>';

        // Retriving category list for Group perm form
        // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        $cats = $catHandler->getAllCat($xoopsUser, 'all');

        /*
         * Access Form
         */
        $titleOfForm = _AM_EXTCAL_VIEW_PERMISSION;
        $permName    = 'extcal_cat_view';
        $permDesc    = _AM_EXTCAL_VIEW_PERMISSION_DESC;
        $form        = new \XoopsGroupPermForm($titleOfForm, $moduleId, $permName, $permDesc, 'admin/permissions.php');
        foreach ($cats as $cat) {
            $form->addItem($cat->getVar('cat_id'), $cat->getVar('cat_name'));
        }

        echo '<fieldset id="'
             . $permName
             . 'Bookmark"><legend><a href="#'
             . $permName
             . 'Bookmark" style="font-weight:bold; color:#990000;" onClick="toggle(\''
             . $permName
             . '\'); toggleIcon(\''
             . $permName
             . 'Icon\');"><img id="'
             . $permName
             . 'Icon" src="../assets/images/icons/minus.gif">&nbsp;'
             . $titleOfForm
             . '</a></legend><div id="'
             . $permName
             . '">';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        echo $permDesc;
        echo '</fieldset>';

        if ($catHandler->getCount()) {
            echo $form->render() . '<br>';
        } else {
            redirect_header('cat.php', 2, _AM_EXTCAL_NOPERMSSET, false);
        }

        echo '</div></fieldset><br>';

        /*
         * Submit form
         */
        $titleOfForm = _AM_EXTCAL_SUBMIT_PERMISSION;
        $permName    = 'extcal_cat_submit';
        $permDesc    = _AM_EXTCAL_SUBMIT_PERMISSION_DESC;
        $form        = new \XoopsGroupPermForm($titleOfForm, $moduleId, $permName, $permDesc, 'admin/permissions.php');
        foreach ($cats as $cat) {
            $form->addItem($cat->getVar('cat_id'), $cat->getVar('cat_name'));
        }

        echo '<fieldset id="'
             . $permName
             . 'Bookmark"><legend><a href="#'
             . $permName
             . 'Bookmark" style="font-weight:bold; color:#990000;" onClick="toggle(\''
             . $permName
             . '\'); toggleIcon(\''
             . $permName
             . 'Icon\');"><img id="'
             . $permName
             . 'Icon" src="../assets/images/icons/minus.gif">&nbsp;'
             . $titleOfForm
             . '</a></legend><div id="'
             . $permName
             . '">';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        echo $permDesc;
        echo '</fieldset>';
        if ($catHandler->getCount()) {
            echo $form->render() . '<br>';
        } else {
            redirect_header('cat.php', 2, _AM_EXTCAL_NOPERMSSET, false);
        }

        echo '</div></fieldset><br>';

        /*
         * Auto Approve form
         */
        $titleOfForm = _AM_EXTCAL_AUTOAPPROVE_PERMISSION;
        $permName    = 'extcal_cat_autoapprove';
        $permDesc    = _AM_EXTCAL_AUTOAPPROVE_PERMISSION_DESC;
        $form        = new \XoopsGroupPermForm($titleOfForm, $moduleId, $permName, $permDesc, 'admin/permissions.php');
        foreach ($cats as $cat) {
            $form->addItem($cat->getVar('cat_id'), $cat->getVar('cat_name'));
        }

        echo '<fieldset id="'
             . $permName
             . 'Bookmark"><legend><a href="#'
             . $permName
             . 'Bookmark" style="font-weight:bold; color:#990000;" onClick="toggle(\''
             . $permName
             . '\'); toggleIcon(\''
             . $permName
             . 'Icon\');"><img id="'
             . $permName
             . 'Icon" src="../assets/images/icons/minus.gif">&nbsp;'
             . $titleOfForm
             . '</a></legend><div id="'
             . $permName
             . '">';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        echo $permDesc;
        echo '</fieldset>';
        if ($catHandler->getCount()) {
            echo $form->render() . '<br>';
        } else {
            redirect_header('cat.php', 2, _AM_EXTCAL_NOPERMSSET, false);
        }

        echo '</div></fieldset><br>';

        /*
         * Can edit form
         */
        $titleOfForm = _AM_EXTCAL_EDIT_PERMISSION;
        $permName    = 'extcal_cat_edit';
        $permDesc    = _AM_EXTCAL_EDIT_PERMISSION_DESC;
        $form        = new \XoopsGroupPermForm($titleOfForm, $moduleId, $permName, $permDesc, 'admin/permissions.php');
        foreach ($cats as $cat) {
            $form->addItem($cat->getVar('cat_id'), $cat->getVar('cat_name'));
        }

        echo '<fieldset id="'
             . $permName
             . 'Bookmark"><legend><a href="#'
             . $permName
             . 'Bookmark" style="font-weight:bold; color:#990000;" onClick="toggle(\''
             . $permName
             . '\'); toggleIcon(\''
             . $permName
             . 'Icon\');"><img id="'
             . $permName
             . 'Icon" src="../assets/images/icons/minus.gif">&nbsp;'
             . $titleOfForm
             . '</a></legend><div id="'
             . $permName
             . '">';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        echo $permDesc;
        echo '</fieldset>';
        if ($catHandler->getCount()) {
            echo $form->render() . '<br>';
        } else {
            redirect_header('cat.php', 2, _AM_EXTCAL_NOPERMSSET, false);
        }

        echo '</div></fieldset><br>';

        /*
         * Script to auto colapse form at page load
         */
        echo '<script type="text/javascript">';
        echo 'toggle(\'extcal_cat_view\'); toggleIcon (\'extcal_cat_viewIcon\');';
        echo 'toggle(\'extcal_cat_submit\'); toggleIcon (\'extcal_cat_submitIcon\');';
        echo 'toggle(\'extcal_cat_autoapprove\'); toggleIcon (\'extcal_cat_autoapproveIcon\');';
        echo 'toggle(\'extcal_cat_edit\'); toggleIcon (\'extcal_cat_editIcon\');';
        echo '</script>';

        require_once __DIR__ . '/admin_footer.php';

        break;

}
