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

use XoopsModules\Extcal;

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';
$GLOBALS['xoopsOption']['template_main'] = 'extcal_etablissement.tpl';
require_once __DIR__ . '/header.php';

//require_once XOOPS_ROOT_PATH."/modules/extcal/class/etablissement.php";
$etablissementHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_ETABLISSEMENT);
//require_once XOOPS_ROOT_PATH.'/header.php';

$etablissement_id = isset($_REQUEST['etablissement_id']) ? $_REQUEST['etablissement_id'] : 0;

global $xoopsUser, $xoopsModuleConfig, $xoopsModule, $xoopsDB;

//On regarde si le lien existe
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('id', $etablissement_id, '='));
$etablissement_exist = $etablissementHandler->getCount($criteria);

if (0 == $etablissement_exist) {
    redirect_header(XOOPS_URL . '/modules/extcal/index.php', 3, _NOPERM);
}

$view_etablissement = $etablissementHandler->getEtablissement($etablissement_id, true);
$etablissement      = $etablissementHandler->objectToArray($view_etablissement);

$isAdmin = false;
if (isset($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
    $isAdmin = true;
}

/* todo a deplacer dans le template JJD */
$uid = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
global $xoopsModule;
$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);

$edit_delete = '';
if (is_object($xoopsUser) && $isAdmin) {
    $edit_delete = '<a href="'
                   . XOOPS_URL
                   . '/modules/extcal/admin/etablissement.php?op=edit_etablissement&etablissement_id='
                   . $etablissement_id
                   . '"><img src="'
                   . $pathIcon16
                   . '/edit.png" width="16px" height="16px" border="0" title="'
                   . _MD_EXTCAL_ETABLISSEMENT_EDIT
                   . '"></a><a href="'
                   . XOOPS_URL
                   . '/modules/extcal/admin/etablissement.php?op=delete_etablissement&etablissement_id='
                   . $etablissement_id
                   . '"><img src="'
                   . $pathIcon16
                   . '/delete.png" width="16px" height="16px" border="0" title="'
                   . _MD_EXTCAL_ETABLISSEMENT_DELETE
                   . '"></a>';
}
$xoopsTpl->assign('edit_delete', $edit_delete);

$xoopsTpl->assign('etablissement', $etablissement);

$date = mktime(0, 0, 0, date('m'), date('d'), date('y'));

$requete = $xoopsDB->query('SELECT event_id, event_title, event_desc, event_picture1, event_start FROM ' . $xoopsDB->prefix('extcal_event') . " WHERE event_etablissement='" . $etablissement_id . "' AND event_start >='" . $date . "'");
while (false !== ($donnees = $xoopsDB->fetchArray($requete))) {
    if ($donnees['event_desc'] > 210) {
        $event_desc = $donnees['event_desc'];
    } else {
        $event_desc = substr($donnees['event_desc'], 0, 210) . '...';
    }
    $xoopsTpl->append('events', [
        'event_picture1' => $donnees['event_picture1'],
        'event_id'       => $donnees['event_id'],
        'event_title'    => $donnees['event_title'],
        'event_desc'     => $event_desc,
        'event_start'    => date('Y-m-d', $donnees['event_start']),
    ]);
}
/** @var xos_opal_Theme $xoTheme */
$xoTheme->addScript('browse.php?modules/extcal/assets/js/highslide.js');
$xoTheme->addStylesheet('browse.php?modules/extcal/assets/js/highslide.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
