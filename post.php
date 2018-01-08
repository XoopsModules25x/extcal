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

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'extcal_post.tpl';

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
//include __DIR__ . '/class/form/extcalform.php';
//include __DIR__ . '/class/perm.php';

require_once __DIR__ . '/class/Utility.php';
require_once __DIR__ . '/include/constantes.php';

$permHandler = Extcal\Perm::getHandler();
$xoopsUser   = $xoopsUser ?: null;

if (!$permHandler->isAllowed($xoopsUser, 'extcal_cat_submit', (int)$_POST['cat_id'])) {
    redirect_header('index.php', 3);
}

// Getting eXtCal object's handler
/** @var Extcal\EventHandler $eventHandler */
$eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

if (isset($_POST['form_preview'])) {
    include XOOPS_ROOT_PATH . '/header.php';

    // Title of the page
    $xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

    $data = [
        'event_title'        => $_POST['event_title'],
        'cat_id'             => (int)$_POST['cat_id'],
        'event_desc'         => $_POST['event_desc'],
        'event_nbmember'     => (int)$_POST['event_nbmember'],
        'event_contact'      => $_POST['event_contact'],
        'event_url'          => $_POST['event_url'],
        'event_email'        => $_POST['event_email'],
        'event_address'      => $_POST['event_address'],
        'event_approved'     => 1,
        'event_start'        => $_POST['event_start'],
        'have_end'           => $_POST['have_end'],
        'event_end'          => $_POST['event_end'],
        'dohtml'             => (int)$xoopsModuleConfig['allow_html'],
        'event_price'        => $_POST['event_price'],
        'event_organisateur' => $_POST['event_organisateur'],
        'event_icone'        => $_POST['event_icone'],
    ];

    if (isset($_POST['event_id'])) {
        $data['event_id'] = (int)$_POST['event_id'];
    }

    // Creating tempory event object to apply Object data filtering
    $event = $eventHandler->createEventForPreview($data);
    $event = $eventHandler->objectToArray($event, ['cat_id'], 'p');

    // Adding formated date for start and end event
    $eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_event']);

    // Assigning event to the template
    $xoopsTpl->assign('event', $event);

    //     $lang = array(
    //         'start' => _MD_EXTCAL_START, 'end' => _MD_EXTCAL_END, 'contact_info' => _MD_EXTCAL_CONTACT_INFO, 'email' => _MD_EXTCAL_EMAIL, 'url' => _MD_EXTCAL_URL, 'whos_going' => _MD_EXTCAL_WHOS_GOING, 'whosnot_going' => _MD_EXTCAL_WHOSNOT_GOING
    //     );
    //     // Assigning language data to the template
    //     $xoopsTpl->assign('lang', $lang);

    $event['cat_id']   = (int)$_POST['cat_id'];
    $event['have_end'] = $_POST['have_end'];

    // Display the submit form
    /** @var Extcal\Form\ThemeForm $form */
    $form     = $eventHandler->getEventForm('user', 'preview', $event);
    $formBody = $form->render();
    $xoopsTpl->assign('preview', true);
    $xoopsTpl->assign('formBody', $formBody);

    include XOOPS_ROOT_PATH . '/footer.php';
} elseif (isset($_POST['form_submit'])) {
    if (!isset($_POST['rrule_weekly_interval'])) {
        $_POST['rrule_weekly_interval'] = 0;
    }
    // Extcal\Utility::echoArray($_POST, '',true);
    // exit;
    // $ts = print_r($_POST,true);
    // echo "<pre>{$ts}</pre>";
    // If the date format is wrong
    //    if (
    //        !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_start']['date'])
    //            || !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_end']['date'])
    //    ) {
    //        redirect_header(
    //            'index.php', 3, _MD_EXTCAL_WRONG_DATE_FORMAT . "<br>"
    //            . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors())
    //        );
    //        exit;
    //    }
    ///////////////////////////////////////////////////////////////////////////////
    Extcal\Utility::loadImg($_REQUEST, $event_picture1, $event_picture2);
    ///////////////////////////////////////////////////////////////////////////////

    require_once __DIR__ . '/class/perm.php';

    /** @var Extcal\FileHandler $fileHandler */
    $fileHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_FILE);
    $permHandler = Extcal\Perm::getHandler();
    $approve     = $permHandler->isAllowed($xoopsUser, 'extcal_cat_autoapprove', (int)$_POST['cat_id']);

    $data = [
        'event_title'         => $_POST['event_title'],
        'cat_id'              => $_POST['cat_id'],
        'event_desc'          => $_POST['event_desc'],
        'event_nbmember'      => $_POST['event_nbmember'],
        'event_organisateur'  => $_POST['event_organisateur'],
        'event_contact'       => $_POST['event_contact'],
        'event_url'           => $_POST['event_url'],
        'event_email'         => $_POST['event_email'],
        'event_address'       => $_POST['event_address'],
        'event_approved'      => (false === $approve) ? 0 : 1,
        'event_start'         => $_POST['event_start'],
        'have_end'            => $_POST['have_end'],
        'event_end'           => $_POST['event_end'],
        'event_picture1'      => @$event_picture1,
        'event_picture2'      => @$event_picture2,
        'event_price'         => @$_POST['event_price'],
        'event_etablissement' => $_POST['event_etablissement'],
        'dohtml'              => $xoopsModuleConfig['allow_html'],
        'event_icone'         => $_POST['event_icone'],

    ];

    if (isset($_POST['event_id'])) {
        $eventHandler->modifyEvent((int)$_POST['event_id'], $data);
        $fileHandler->updateEventFile((int)$_POST['event_id']);
        $fileHandler->createFile((int)$_POST['event_id']);

        redirect_header('event.php?event=' . $_POST['event_id'], 3, _MD_EXTCAL_EVENT_UPDATED, false);
    } else {
        $data['event_submitter']  = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
        $data['event_submitdate'] = time();

        $eventHandler->createEvent($data);
        $fileHandler->createFile($eventHandler->getInsertId());

        $notifyEvent = 'new_event';
        if (!$approve) {
            $notifyEvent = 'new_event_pending';
        }

        /** @var \XoopsNotificationHandler $notificationHandler */
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global', 0, $notifyEvent, ['EVENT_TITLE' => $_POST['event_title']]);
        if (1 == $approve) {
            //            $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
            $catHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
            $cat        = $catHandler->getCat((int)$_POST['cat_id'], $xoopsUser, 'all');
            $notificationHandler->triggerEvent('cat', (int)$_POST['cat_id'], 'new_event_cat', [
                'EVENT_TITLE' => $_POST['event_title'],
                'CAT_NAME'    => $cat->getVar('cat_name'),
            ]);
        }
    }

    if ($approve) {
        redirect_header(_EXTCAL_FILE_CALMONTH, 3, _MD_EXTCAL_EVENT_CREATED, false);
    } else {
        redirect_header(_EXTCAL_FILE_CALMONTH, 3, _MD_EXTCAL_EVENT_PENDING, false);
    }
}
