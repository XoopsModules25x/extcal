<?php namespace XoopsModules\Extcal;

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

//Kraven 30
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

//class Event extends \XoopsObject
//class extcal_etablissement extends \XoopsObject

/**
 * Class Etablissement.
 */
class Etablissement extends \XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        //Toutes les attributs de la table
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('nom', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('logo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('categorie', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('adresse', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('adresse2', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cp', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('ville', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tel_fixe', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tel_portable', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('mail', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('site', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('horaires', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('divers', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tarifs', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('map', XOBJ_DTYPE_URL, false);
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsDB, $extcalConfig;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_MD_EXTCAL_ETABLISSEMENT_ADD) : sprintf(_MD_EXTCAL_ETABLISSEMENT_EDIT);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_NOM, 'nom', 50, 255, $this->getVar('nom')), true);
        $form->addElement(new \XoopsFormDhtmlTextArea(_MD_EXTCAL_ETABLISSEMENT_DESCRIPTION, 'description', $this->getVar('description'), 10), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_CATEGORIE, 'categorie', 40, 255, $this->getVar('categorie')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_ADRESSE, 'adresse', 50, 255, $this->getVar('adresse')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_ADRESSE2, 'adresse2', 50, 255, $this->getVar('adresse2')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_CP, 'cp', 10, 10, $this->getVar('cp')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_VILLE, 'ville', 20, 255, $this->getVar('ville')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_TEL_FIXE, 'tel_fixe', 20, 20, $this->getVar('tel_fixe')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_TEL_PORTABLE, 'tel_portable', 20, 20, $this->getVar('tel_portable')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_MAIL, 'mail', 50, 255, $this->getVar('mail')), false);
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_SITE, 'site', 50, 255, $this->getVar('site')), false);
        $form->addElement(new \XoopsFormTextArea(_MD_EXTCAL_ETABLISSEMENT_HORAIRES, 'horaires', $this->getVar('horaires'), 3, 40));
        $form->addElement(new \XoopsFormTextArea(_MD_EXTCAL_ETABLISSEMENT_DIVERS, 'divers', $this->getVar('divers'), 5, 40));
        //$form->addElement( new \XoopsFormTextArea(_MD_EXTCAL_ETABLISSEMENT_TARIFS, 'tarifs', $this->getVar("tarifs"), 5, 40));
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_TARIFS . ' ( ' . _MD_EXTCAL_DEVISE2 . ' )', 'tarifs', 20, 20, $this->getVar('tarifs')), false);

        //$form->addElement( new \XoopsFormTextArea(_MD_EXTCAL_ETABLISSEMENT_MAP, 'map', $this->getVar("map"), 5, 40));
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ETABLISSEMENT_MAP, 'map', 150, 255, $this->getVar('map')), false);

        //Logo
        $file_tray = new \XoopsFormElementTray(sprintf(_MD_EXTCAL_FORM_IMG, 2), '');
        if ('' != $this->getVar('logo')) {
            $file_tray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/uploads/extcal/etablissement/' . $this->getVar('logo') . "' name='image' id='image' alt=''><br><br>"));
            $check_del_img = new \XoopsFormCheckBox('', 'delimg');
            $check_del_img->addOption(1, _MD_EXTCAL_DEL_IMG);
            $file_tray->addElement($check_del_img);
            $file_img = new \XoopsFormFile(_MD_EXTCAL_IMG, 'attachedimage', 3145728);
            unset($check_del_img);
        } else {
            $file_img = new \XoopsFormFile('', 'attachedimage', 3145728);
        }
        $file_img->setExtra("size ='40'");
        $file_tray->addElement($file_img);
        $msg        = sprintf(_MD_EXTCAL_IMG_CONFIG, (int)(3145728 / 1000), 500, 500);
        $file_label = new \XoopsFormLabel('', '<br>' . $msg);
        $file_tray->addElement($file_label);
        $form->addElement($file_tray);
        $form->addElement(new \XoopsFormHidden('file', $this->getVar('logo')));
        unset($file_img, $file_tray);

        $form->addElement(new \XoopsFormHidden('op', 'save_etablissement'));
        $form->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->display();

        return $form;
    }
}
