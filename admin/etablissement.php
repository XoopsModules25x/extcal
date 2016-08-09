<?php
/**
 * ****************************************************************************
 *  - TDMAds By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer et de rendre publiques les modifications
 *
 * @license       TDMFR PRO license
 * @author        TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

// Include xoops admin header
include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/modules/extcal/class/ExtcalPersistableObjectHandler.php';
include_once(XOOPS_ROOT_PATH . '/kernel/module.php');
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/modules/extcal/class/etablissement.php';
include_once XOOPS_ROOT_PATH . '/modules/extcal/include/constantes.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
include_once(XOOPS_ROOT_PATH . '/class/uploader.php');
include_once __DIR__ . '/admin_header.php';

//include_once("functions.php");
//include_once("../include/functions.php");

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('extcal');
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname', 'e'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname', 'e'));
$myts = MyTextSanitizer::getInstance();

//appel des class
$etablissementHandler = xoops_getModuleHandler(_EXTCAL_CLS_ETABLISSEMENT, _EXTCAL_MODULE);

xoops_cp_header();

$op = 'liste';
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
}

//appel du menu admin
// if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
// adminmenu(4, _MI_EXTCAL_ETABLISSEMENT);
// } else {
// include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
// loadModuleAdminMenu (4, _MI_EXTCAL_ETABLISSEMENT);
// }

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'liste':

        // @author   JJDAI
        //***************************************************************************************
        $etablissementAdmin = new ModuleAdmin();
        echo $etablissementAdmin->addNavigation(basename(__FILE__));
        //***************************************************************************************

        $criteria = new CriteriaCompo();
        if (isset($_REQUEST['limit'])) {
            $criteria->setLimit($_REQUEST['limit']);
            $limit = $_REQUEST['limit'];
        } else {
            $criteria->setLimit(10);
            $limit = 10;
        }
        if (isset($_REQUEST['start'])) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
            $start = 0;
        }
        $criteria->setSort('nom');
        $criteria->setOrder('ASC');
        $etablissement_arr = $etablissementHandler->getObjects($criteria);
        $numrows           = $etablissementHandler->getCount($criteria);
        if ($numrows > $limit) {
            $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=liste&limit=' . $limit);
            $pagenav = $pagenav->renderNav(4);
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des téléchargements brisés
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center">' . _AM_EXTCAL_ETABLISSEMENT_FORM_NOM . '</th>';
            echo '<th align="center" width="20%">' . _AM_EXTCAL_ETABLISSEMENT_FORM_ADRESSE . '</th>';
            echo '<th align="center" width="20%">' . _AM_EXTCAL_ETABLISSEMENT_FORM_CITY . '</th>';
            echo '<th align="center" width="15%">' . _AM_EXTCAL_ETABLISSEMENT_FORM_TELEPHONE . '</th>';
            echo '<th align="center" width="15%">' . _AM_EXTCAL_ETABLISSEMENT_FORM_ACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($etablissement_arr) as $i) {
                $class                   = ($class === 'even') ? 'odd' : 'even';
                $etablissement_id        = $etablissement_arr[$i]->getVar('id');
                $etablissement_nom       = $etablissement_arr[$i]->getVar('nom');
                $etablissement_adresse   = $etablissement_arr[$i]->getVar('adresse');
                $etablissement_city      = $etablissement_arr[$i]->getVar('ville');
                $etablissement_telephone = $etablissement_arr[$i]->getVar('tel_fixe');
                echo '<tr class="' . $class . '">';
                echo '<td align="left">' . '<a href="etablissement.php?op=edit_etablissement&etablissement_id=' . $etablissement_id . '">' . $etablissement_nom . '</a>' . '</td>';

                echo '<td align="center"><b>' . $etablissement_adresse . '</td>';
                echo '<td align="center"><b>' . $etablissement_city . '</td>';
                echo '<td align="center"><b>' . $etablissement_telephone . '</td>';
                echo '<td align="center" width="15%">';
                echo '<a href="etablissement.php?op=edit_etablissement&etablissement_id=' . $etablissement_id . '"><img src=' . $pathIcon16 . '/edit.png alt="' . _AM_EXTCAL_ETABLISSEMENT_FORM_EDIT . '" title="' . _AM_EXTCAL_ETABLISSEMENT_FORM_EDIT . '"></a> ';
                echo '<a href="etablissement.php?op=delete_etablissement&etablissement_id=' . $etablissement_id . '"><img src=' . $pathIcon16 . '/delete.png alt="' . _AM_EXTCAL_ETABLISSEMENT_FORM_DELETE . '" title="' . _AM_EXTCAL_ETABLISSEMENT_FORM_DELETE . '"></a> ';
                echo '</td>';
            }
            echo '</table><br />';
            echo '<br /><div align=right>' . $pagenav . '</div><br />';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_EXTCAL_ERREUR_NO_ETABLISSEMENT . '</div>';
        }

        // Affichage du formulaire
        $obj  = $etablissementHandler->create();
        $form = $obj->getForm(false);
        break;

    // permet de suprimmer le rapport de téléchargment brisé
    case 'delete_etablissement':
        $obj = $etablissementHandler->get($_REQUEST['etablissement_id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('etablissement.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($etablissementHandler->delete($obj)) {
                redirect_header('etablissement.php', 1, _AM_EXTCAL_REDIRECT_DELOK);
            }
        } else {
            xoops_confirm(array('ok' => 1, 'etablissement_id' => $_REQUEST['etablissement_id'], 'op' => 'delete_etablissement'), $_SERVER['REQUEST_URI'], _AM_EXTCAL_ETABLISSEMENT_SURDEL . '<br>');
        }
        break;

    case 'edit_etablissement':
        // @author   JJDAI
        //***************************************************************************************
        $etablissementAdmin = new ModuleAdmin();
        echo $etablissementAdmin->addNavigation(basename(__FILE__));
        //***************************************************************************************
        //Affichage du formulaire de création des téléchargements
        $obj  = $etablissementHandler->get($_REQUEST['etablissement_id']);
        $form = $obj->getForm(false);
        break;

    case 'save_etablissement':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('etablissement.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['etablissement_id'])) {
            $obj =& $etablissementHandler->get($_REQUEST['etablissement_id']);
        } else {
            $obj =& $etablissementHandler->create();
        }

        $obj->setVar('nom', $_REQUEST['nom']);
        $obj->setVar('description', $_REQUEST['description']);
        $obj->setVar('categorie', $_REQUEST['categorie']);
        $obj->setVar('adresse', $_REQUEST['adresse']);
        $obj->setVar('adresse2', $_REQUEST['adresse2']);
        $obj->setVar('cp', $_REQUEST['cp']);
        $obj->setVar('ville', $_REQUEST['ville']);
        $obj->setVar('tel_fixe', $_REQUEST['tel_fixe']);
        $obj->setVar('tel_portable', $_REQUEST['tel_portable']);
        $obj->setVar('mail', $_REQUEST['mail']);
        $obj->setVar('site', $_REQUEST['site']);
        $obj->setVar('horaires', $_REQUEST['horaires']);
        $obj->setVar('divers', $_REQUEST['divers']);
        $obj->setVar('tarifs', $_REQUEST['tarifs']);
        $obj->setVar('map', $_REQUEST['map']);

        //Logo
        $uploaddir_etablissement = XOOPS_ROOT_PATH . '/uploads/extcal/etablissement/';
        $uploadurl_etablissement = XOOPS_URL . '/uploads/extcal/etablissement/';

        $delimg = @$_REQUEST['delimg'];
        $delimg = isset($delimg) ? (int)$delimg : 0;
        if ($delimg == 0 && !empty($_REQUEST['xoops_upload_file'][0])) {
            $upload = new XoopsMediaUploader($uploaddir_etablissement, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), 3145728, null, null);
            if ($upload->fetchMedia($_REQUEST['xoops_upload_file'][0])) {
                $upload->setPrefix('etablissement_');
                $upload->fetchMedia($_REQUEST['xoops_upload_file'][0]);
                if (!$upload->upload()) {
                    $errors = $upload->getErrors();
                    redirect_header('javascript:history.go(-1)', 3, $errors);
                } else {
                    $logo = $upload->getSavedFileName();
                }
            } elseif (!empty($_REQUEST['file'])) {
                $logo = $_REQUEST['file'];
            }
        } else {
            $logo              = '';
            $url_etablissement = XOOPS_ROOT_PATH . '/uploads/extcal/etablissement/' . $_REQUEST['file'];
            if (is_file($url_etablissement)) {
                chmod($url_etablissement, 0777);
                unlink($url_etablissement);
            }
        }
        $obj->setVar('logo', $logo);

        if ($etablissementHandler->insert($obj)) {
        }

        //include_once("../include/forms.php");
        echo $obj->getHtmlErrors();
        $form = $obj->getForm(false, 0);
        //echo "<hr>exit <<<<<<<<<<<<<<<<<<<<";exit;
        redirect_header('etablissement.php', 2, _AM_EXTCAL_FORMOK);

        break;
}

include_once __DIR__ . '/admin_footer.php';
