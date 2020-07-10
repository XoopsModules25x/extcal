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

use Xmf\Request;
use XoopsModules\Extcal\{
    Helper,
    Perm
};

require_once __DIR__ . '/include/constantes.php';
$params                                  = ['view' => _EXTCAL_NAV_NEW_EVENT, 'file' => _EXTCAL_FILE_NEW_EVENT];
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.tpl";

require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$permHandler = Perm::getHandler();
$xoopsUser   = $xoopsUser ?: null;

if (0 == count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit'))
    && 0 == count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_edit'))) {
    redirect_header('index.php', 3);
}



/* ========================================================================== */

// Tooltips include
/** @var xos_opal_Theme $xoTheme */
$xoTheme->addScript('modules/extcal/assets/js/ToolTips.js');
$xoTheme->addStylesheet('modules/extcal/assets/css/infobulle.css');

$eventId = Request::getInt('event', 0, 'GET');
$action  = Request::getString('action', 'edit', 'GET');

// Getting eXtCal object's handler
$eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

require_once XOOPS_ROOT_PATH . '/header.php';

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

// Display the submit form
/** @var \XoopsThemeForm $form */
$form = $eventHandler->getEventForm('user', $action, ['event_id' => $eventId]);
$form->display();

require_once XOOPS_ROOT_PATH . '/footer.php';
