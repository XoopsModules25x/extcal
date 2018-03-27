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

use Xmf\Request;
use XoopsModules\Extcal;

require_once __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/../../../class/xoopsformloader.php';
require_once __DIR__ . '/admin_header.php';
// require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/constantes.php';

//$gepeto = array_merge($_GET, $_POST);
//while (list($key, $value) = each($gepeto)) {
//foreach ($gepeto as $key => $value) {
//    $$k = $value;
//}

//if (!isset($op)) {
//    $op = '';
//}
$op = 'list';
if (Request::hasVar('op', 'GET')) {
    $op     = Request::getString('op', '');
    $cat_id = Request::getInt('cat_id', 0);
}

// $t=print_r($gepeto,true);
// echo "<pre>{$t}</pre>";

switch ($op) {
    case 'enreg':
        // Modify cat
        $varArr = [
            'cat_name'   => Request::getString('cat_name', '', 'POST'),
            'cat_desc'   => Request::getText('cat_desc', '', 'POST'),
            'cat_weight' => Request::getInt('cat_weight', 0, 'POST'),
            'cat_color'  => substr(Request::getString('cat_color', '', 'POST'), 1),
            'cat_icone'  => Request::getInt('cat_icone', 0, 'POST')
        ];
        if (isset($cat_id)) {
            /** @var Extcal\CategoryHandler $catHandler */
            // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
            //            $varArr = [
            //                'cat_name'   => Request::getString('cat_name', '', 'POST'),
            //                'cat_desc'   => Request::getText('cat_desc', '', 'POST'),
            //                'cat_weight' => Request::getInt('cat_weight', 0, 'POST'),
            //                'cat_color'  => substr(Request::getString('cat_color', '', 'POST'), 1),
            //                'cat_icone'  => Request::getInt('cat_icone', 0, 'POST')
            //            ];

            $catHandler->modifyCat($cat_id, $varArr);
            redirect_header('cat.php', 3, _AM_EXTCAL_CAT_EDITED, false);
        // Create new cat
        } else {
            // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
            //            $varArr = [
            //                'cat_name'   => $cat_name,
            //                'cat_desc'   => $cat_desc,
            //                'cat_weight' => $cat_weight,
            //                'cat_color'  => substr($cat_color, 1),
            //                'cat_icone'  => $cat_icone,
            //            ];
            $catHandler->createCat($varArr);
            redirect_header('cat.php', 3, _AM_EXTCAL_CAT_CREATED, false);
        }

        break;

    case 'new':
        xoops_cp_header();

        // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        //$cat        = $catHandler->getCat($cat_id, true);

        $form = new \XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255), true);
        $form->addElement(new \XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', ''), false);
        $form->addElement(new \XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, 0), false);
        $form->addElement(new \XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color', '#FF0000'));

        $file_path = __DIR__ . '/../assets/css/images';
        $tf        = \XoopsLists::getImgListAsArray($file_path);
        array_unshift($tf, _MD_EXTCAL_NONE);
        //$xfIcones = new \XoopsFormSelect(_AM_EXTCAL_ICONE, "cat_icone", $cat->getVar('cat_icone'), '');
        $xfIcones = new \XoopsFormSelect(_AM_EXTCAL_ICONE, 'cat_icone', '', '');
        $xfIcones->addOptionArray($tf);
        $form->addElement($xfIcones, false);

        $form->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'), false);

        $form->display();

        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'edit':
        xoops_cp_header();

        // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        if (isset($cat_id) && 0 != $cat_id) {
            $cat = $catHandler->getCat($cat_id, true);
        } else {
            //            $cat = $catHandler->getCat($cat_id, true);
        }

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_EDIT_CATEGORY . '</legend>';

        $form = new \XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255, $cat->getVar('cat_name')), true);
        $form->addElement(new \XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', $cat->getVar('cat_desc')), false);
        $form->addElement(new \XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
        $form->addElement(new \XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color', '#' . $cat->getVar('cat_color')));

        $file_path = __DIR__ . '/../assets/css/images';
        $tf        = \XoopsLists::getImgListAsArray($file_path);
        array_unshift($tf, _MD_EXTCAL_NONE);
        $xfIcones = new \XoopsFormSelect(_AM_EXTCAL_ICONE, 'cat_icone', $cat->getVar('cat_icone'), '');
        $xfIcones->addOptionArray($tf);
        $form->addElement($xfIcones, false);

        $form->addElement(new \XoopsFormHidden('cat_id', $cat->getVar('cat_id')), false);
        $form->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'), false);
        $form->display();

        echo '</fieldset>';

        xoops_cp_footer();
        break;

    case 'delete':
        if (!isset($confirm)) {
            xoops_cp_header();
            $hiddens = [
                'cat_id'      => $cat_id,
                'form_delete' => '',
                'confirm'     => 1,
            ];
            xoops_confirm($hiddens, 'cat.php?op=delete', _AM_EXTCAL_CONFIRM_DELETE_CAT, _DELETE, 'cat.php');

            xoops_cp_footer();
        } else {
            if (1 == $confirm) {
                // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
                $catHandler->deleteCat($cat_id);
                redirect_header('cat.php', 3, _AM_EXTCAL_CAT_DELETED, false);
            }
        }
        break;

    //     case 'modify':
    //
    //         if (isset($form_modify)) {
    //             xoops_cp_header();
    //
    //             // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    //             $cat = $catHandler->getCat($cat_id, true);
    //
    //             echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //                 . _AM_EXTCAL_EDIT_CATEGORY . '</legend>';
    //
    //             $form = new \XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
    //             $form->addElement( new \XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255, $cat->getVar('cat_name')), true);
    //             $form->addElement( new \XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', $cat->getVar('cat_desc')), false);
    //             $form->addElement( new \XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
    //             $form->addElement(
    //                  new \XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color',
    //                     '#' . $cat->getVar('cat_color'))
    //             );
    //             $form->addElement( new \XoopsFormHidden('cat_id', $cat->getVar('cat_id')), false);
    //             $form->addElement( new \XoopsFormButton("", "form_submit", _SEND, "submit"), false);
    //             $form->display();
    //
    //             echo '</fieldset>';
    //
    //             xoops_cp_footer();
    //         } else {
    //             if (isset($form_delete)) {
    //                 if (!isset($confirm)) {
    //                     xoops_cp_header();
    //         // @author      Gregory Mage (Aka Mage)
    //         //***************************************************************************************
    //         require_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
    //         $adminObject = \Xmf\Module\Admin::getInstance();
    //         $adminObject->displayNavigation(basename(__FILE__));
    //         //***************************************************************************************
    //
    //                     $hiddens = array('cat_id' => $cat_id, 'form_delete' => '', 'confirm' => 1);
    //                     xoops_confirm($hiddens, 'cat.php?op=modify', _AM_EXTCAL_CONFIRM_DELETE_CAT, _DELETE, 'cat.php');
    //
    //                     xoops_cp_footer();
    //                 } else {
    //                     if (isset($confirm) && $confirm == 1) {
    //                         // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    //                         $catHandler->deleteCat($cat_id);
    //                         redirect_header("cat.php", 3, _AM_EXTCAL_CAT_DELETED, false);
    //                     }
    //                 }
    //             }
    //         }
    //
    //         break;
    //
    //
    //     case 'default':
    //
    //         xoops_cp_header();
    //         // @author      Gregory Mage (Aka Mage)
    //         //***************************************************************************************
    //         $adminObject = \Xmf\Module\Admin::getInstance();
    //         $adminObject->displayNavigation(basename(__FILE__));
    //         //***************************************************************************************
    //
    //         // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    //         $cats = $catHandler->getAllCat($xoopsUser, 'all');
    //
    //
    //         echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //             . _AM_EXTCAL_EDIT_OR_DELETE_CATEGORY . '</legend>';
    //         $form = new \XoopsThemeForm(_AM_EXTCAL_EDIT_OR_DELETE_CATEGORY, 'mod_cat', 'cat.php?op=modify', 'post', true);
    //         $catSelect = new \XoopsFormSelect(_AM_EXTCAL_CATEGORY, 'cat_id');
    //
    //         foreach (
    //             $cats as $cat
    //) {
    //             $catSelect->addOption($cat->getVar('cat_id'), $cat->getVar('cat_name'));
    //         }
    //
    //         $form->addElement($catSelect, true);
    //         $button = new \XoopsFormElementTray('');
    //         $button->addElement( new \XoopsFormButton("", "form_modify", _EDIT, "submit"), false);
    //         $button->addElement( new \XoopsFormButton("", "form_delete", _DELETE, "submit"), false);
    //         $form->addElement($button, false);
    //         $form->display();
    //
    //
    //         echo '</fieldset><br>';
    //         echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //             . _AM_EXTCAL_ADD_CATEGORY . '</legend>';
    //
    //         $form = new \XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
    //         $form->addElement( new \XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255), true);
    //         $form->addElement( new \XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', ''), false);
    //         $form->addElement( new \XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
    //         $form->addElement( new \XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color'));
    //         $form->addElement( new \XoopsFormButton("", "form_submit", _SEND, "submit"), false);
    //         $form->display();
    //
    //         echo '</fieldset><br>';
    //
    //         require_once __DIR__ . '/admin_footer.php';
    //
    //         break;

    case 'list':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        $adminObject->addItemButton('Add Category', 'cat.php?op=new', 'add', '');
        $adminObject->displayButton('left', '');

        // $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        $cats = $catHandler->getAllCatById($xoopsUser);

        $xoopsTpl->assign('cats', $cats);
        //$xoopsTpl->assign("module_dirname",    $xoopsModule->getVar("dirname") );

        $xoopsTpl->display('db:admin/extcal_admin_cat_list.tpl');
        require_once __DIR__ . '/admin_footer.php';
        break;
}
