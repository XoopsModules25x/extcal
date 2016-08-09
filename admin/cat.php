<?php

include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include dirname(dirname(dirname(__DIR__))) . '/class/xoopsformloader.php';
include_once __DIR__ . '/admin_header.php';
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/functions.php');
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/constantes.php');

$gepeto = array_merge($_GET, $_POST);
while (list($k, $v) = each($gepeto)) {
    $$k = $v;
}
if (!isset($op)) {
    $op = '';
}

// $t=print_r($gepeto,true);
// echo "<pre>{$t}</pre>";

switch ($op) {

    case 'enreg':

        // Modify cat
        if (isset($cat_id)) {
            $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
            $varArr     = array(
                'cat_name'   => $cat_name,
                'cat_desc'   => $cat_desc,
                'cat_weight' => $cat_weight,
                'cat_color'  => substr($cat_color, 1),
                'cat_icone'  => $cat_icone);

            $catHandler->modifyCat($cat_id, $varArr);
            redirect_header('cat.php', 3, _AM_EXTCAL_CAT_EDITED, false);
            // Create new cat
        } else {
            $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
            $varArr     = array(
                'cat_name'   => $cat_name,
                'cat_desc'   => $cat_desc,
                'cat_weight' => $cat_weight,
                'cat_color'  => substr($cat_color, 1),
                'cat_icone'  => $cat_icone);
            $catHandler->createCat($varArr);
            redirect_header('cat.php', 3, _AM_EXTCAL_CAT_CREATED, false);
        }

        break;

    case 'new':

        xoops_cp_header();

        $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        //$cat        = $catHandler->getCat($cat_id, true);

        $form = new XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
        $form->addElement(new XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255), true);
        $form->addElement(new XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', ''), false);
        $form->addElement(new XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, 0), false);
        $form->addElement(new XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color', '#FF0000'));

        $file_path = XOOPS_ROOT_PATH . '/modules/extcal/assets/css/images';
        $tf        = XoopsLists::getImgListAsArray($file_path);
        array_unshift($tf, _MD_EXTCAL_NONE);
        //$xfIcones = new XoopsFormSelect(_AM_EXTCAL_ICONE, "cat_icone", $cat->getVar('cat_icone'), '');
        $xfIcones = new XoopsFormSelect(_AM_EXTCAL_ICONE, 'cat_icone', '', '');
        $xfIcones->addOptionArray($tf);
        $form->addElement($xfIcones, false);

        $form->addElement(new XoopsFormButton('', 'form_submit', _SEND, 'submit'), false);

        $form->display();

        include_once __DIR__ . '/admin_footer.php';
        break;

    case 'edit':
        xoops_cp_header();

        $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        if ($cat_id <> 0) {
            $cat = $catHandler->getCat($cat_id, true);
        } else {
            $cat = $catHandler->getCat($cat_id, true);
        }

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_EDIT_CATEGORY . '</legend>';

        $form = new XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
        $form->addElement(new XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255, $cat->getVar('cat_name')), true);
        $form->addElement(new XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', $cat->getVar('cat_desc')), false);
        $form->addElement(new XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
        $form->addElement(new XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color', '#' . $cat->getVar('cat_color')));

        $file_path = XOOPS_ROOT_PATH . '/modules/extcal/assets/css/images';
        $tf        = XoopsLists::getImgListAsArray($file_path);
        array_unshift($tf, _MD_EXTCAL_NONE);
        $xfIcones = new XoopsFormSelect(_AM_EXTCAL_ICONE, 'cat_icone', $cat->getVar('cat_icone'), '');
        $xfIcones->addOptionArray($tf);
        $form->addElement($xfIcones, false);

        $form->addElement(new XoopsFormHidden('cat_id', $cat->getVar('cat_id')), false);
        $form->addElement(new XoopsFormButton('', 'form_submit', _SEND, 'submit'), false);
        $form->display();

        echo '</fieldset>';

        xoops_cp_footer();
        break;

    case 'delete':
        if (!isset($confirm)) {
            xoops_cp_header();
            $hiddens = array(
                'cat_id'      => $cat_id,
                'form_delete' => '',
                'confirm'     => 1);
            xoops_confirm($hiddens, 'cat.php?op=delete', _AM_EXTCAL_CONFIRM_DELETE_CAT, _DELETE, 'cat.php');

            xoops_cp_footer();
        } else {
            if (1 == $confirm) {
                $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
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
    //             $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    //             $cat = $catHandler->getCat($cat_id, true);
    //
    //             echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //                 . _AM_EXTCAL_EDIT_CATEGORY . '</legend>';
    //
    //             $form = new XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
    //             $form->addElement(new XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255, $cat->getVar('cat_name')), true);
    //             $form->addElement(new XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', $cat->getVar('cat_desc')), false);
    //             $form->addElement(new XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
    //             $form->addElement(
    //                 new XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color',
    //                     '#' . $cat->getVar('cat_color'))
    //             );
    //             $form->addElement(new XoopsFormHidden('cat_id', $cat->getVar('cat_id')), false);
    //             $form->addElement(new XoopsFormButton("", "form_submit", _SEND, "submit"), false);
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
    //         include_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
    //         $categoryAdmin = new ModuleAdmin();
    //         echo $categoryAdmin->addNavigation(basename(__FILE__));
    //         //***************************************************************************************
    //
    //                     $hiddens = array('cat_id' => $cat_id, 'form_delete' => '', 'confirm' => 1);
    //                     xoops_confirm($hiddens, 'cat.php?op=modify', _AM_EXTCAL_CONFIRM_DELETE_CAT, _DELETE, 'cat.php');
    //
    //                     xoops_cp_footer();
    //                 } else {
    //                     if (isset($confirm) && $confirm == 1) {
    //                         $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
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
    //         $categoryAdmin = new ModuleAdmin();
    //         echo $categoryAdmin->addNavigation(basename(__FILE__));
    //         //***************************************************************************************
    //
    //         $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    //         $cats = $catHandler->getAllCat($xoopsUser, 'all');
    //
    //
    //         echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //             . _AM_EXTCAL_EDIT_OR_DELETE_CATEGORY . '</legend>';
    //         $form = new XoopsThemeForm(_AM_EXTCAL_EDIT_OR_DELETE_CATEGORY, 'mod_cat', 'cat.php?op=modify', 'post', true);
    //         $catSelect = new XoopsFormSelect(_AM_EXTCAL_CATEGORY, 'cat_id');
    //
    //         foreach (
    //             $cats as $cat
    //) {
    //             $catSelect->addOption($cat->getVar('cat_id'), $cat->getVar('cat_name'));
    //         }
    //
    //         $form->addElement($catSelect, true);
    //         $button = new XoopsFormElementTray('');
    //         $button->addElement(new XoopsFormButton("", "form_modify", _EDIT, "submit"), false);
    //         $button->addElement(new XoopsFormButton("", "form_delete", _DELETE, "submit"), false);
    //         $form->addElement($button, false);
    //         $form->display();
    //
    //
    //         echo '</fieldset><br />';
    //         echo'<fieldset><legend style="font-weight:bold; color:#990000;">'
    //             . _AM_EXTCAL_ADD_CATEGORY . '</legend>';
    //
    //         $form = new XoopsThemeForm(_AM_EXTCAL_ADD_CATEGORY, 'add_cat', 'cat.php?op=enreg', 'post', true);
    //         $form->addElement(new XoopsFormText(_AM_EXTCAL_NAME, 'cat_name', 30, 255), true);
    //         $form->addElement(new XoopsFormDhtmlTextArea(_AM_EXTCAL_DESCRIPTION, 'cat_desc', ''), false);
    //         $form->addElement(new XoopsFormText(_AM_EXTCAL_WEIGHT, 'cat_weight', 30, 5, $cat->getVar('cat_weight')), false);
    //         $form->addElement(new XoopsFormColorPicker(_AM_EXTCAL_COLOR, 'cat_color'));
    //         $form->addElement(new XoopsFormButton("", "form_submit", _SEND, "submit"), false);
    //         $form->display();
    //
    //         echo '</fieldset><br />';
    //
    //         include_once __DIR__ . '/admin_footer.php';
    //
    //         break;

    case 'list':
    default:
        xoops_cp_header();
        $categoryAdmin = new ModuleAdmin();
        echo $categoryAdmin->addNavigation(basename(__FILE__));

        $categoryAdmin->addItemButton('Add Category', 'cat.php?op=new', 'add', '');
        echo $categoryAdmin->renderButton('left', '');

        $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        $cats       = $catHandler->getAllCatById($xoopsUser);

        $xoopsTpl->assign('cats', $cats);
        //$xoopsTpl->assign("module_dirname",    $xoopsModule->getVar("dirname") );

        $xoopsTpl->display('db:admin/extcal_admin_cat_list.tpl');
        include_once __DIR__ . '/admin_footer.php';
        break;
}
