<?php

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/constantes.php';
$xoopsOption['template_main'] = 'extcal_etablissement.tpl';
include_once __DIR__ . '/header.php';

//include_once XOOPS_ROOT_PATH."/modules/extcal/class/etablissement.php";
$etablissementHandler = xoops_getmodulehandler(_EXTCAL_CLS_ETABLISSEMENT, _EXTCAL_MODULE);
//include_once XOOPS_ROOT_PATH.'/header.php';

$etablissement_id = (isset($_REQUEST['etablissement_id'])) ? $_REQUEST['etablissement_id'] : 0;

global $xoopsUser, $xoopsModuleConfig, $xoopsModule, $xoopsDB;

//On regarde si le lien existe
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('id', $etablissement_id, '='));
$etablissement_exist = $etablissementHandler->getCount($criteria);

if ($etablissement_exist == 0) {
    redirect_header(XOOPS_URL . "/modules/extcal/index.php", 3, _NOPERM);
    exit();
}

$view_etablissement = $etablissementHandler->getEtablissement($etablissement_id, true);
$etablissement      = $etablissementHandler->objectToArray($view_etablissement);

if ($xoopsUser) {
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $isAdmin = true;
    } else {
        $isAdmin = false;
    }
} else {
    $isAdmin = false;
}

/* todo a deplacer dans le template JJD */
$uid = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
global $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');

if (is_object($xoopsUser) && $isAdmin) {
    $edit_delete = '<a href="' . XOOPS_URL . '/modules/extcal/admin/etablissement.php?op=edit_etablissement&etablissement_id=' . $etablissement_id
        . '"><img src="' . $pathIcon16 . '/edit.png" width="16px" height="16px" border="0" title="' . _MD_EXTCAL_ETABLISSEMENT_EDIT . '"/></a><a href="' . XOOPS_URL
        . '/modules/extcal/admin/etablissement.php?op=delete_etablissement&etablissement_id=' . $etablissement_id
        . '"><img src="' . $pathIcon16 . '/delete.png" width="16px" height="16px" border="0" title="' . _MD_EXTCAL_ETABLISSEMENT_DELETE . '"/></a>';
} else {
    $edit_delete = '';
}
$xoopsTpl->assign('edit_delete', $edit_delete);

$xoopsTpl->assign('etablissement', $etablissement);

$date = mktime(0, 0, 0, date("m"), date("d"), date("y"));

$requete = $xoopsDB->query(
    "SELECT event_id, event_title, event_desc, event_picture1, event_start FROM " . $xoopsDB->prefix("extcal_event") . " WHERE event_etablissement='" . $etablissement_id . "' AND event_start >='"
    . $date . "'"
);
while ($donnees = $xoopsDB->fetchArray($requete)) {
    if ($donnees['event_desc'] > 210) {
        $event_desc = $donnees['event_desc'];
    } else {
        $event_desc = substr($donnees['event_desc'], 0, 210) . "...";
    }
    $xoopsTpl->append(
        'events',
        array(
            'event_picture1' => $donnees['event_picture1'],
            'event_id'       => $donnees['event_id'],
            'event_title'    => $donnees['event_title'],
            'event_desc'     => $event_desc,
            'event_start'    => date("Y-m-d", $donnees['event_start'])
        )
    );
}
$xoTheme->addScript('browse.php?modules/extcal/assets/js/highslide.js');
$xoTheme->addStylesheet('browse.php?modules/extcal/assets/js/highslide.css');
include_once XOOPS_ROOT_PATH . '/footer.php';
