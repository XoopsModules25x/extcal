<?php

use XoopsModules\Extcal;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/mail_fnc.php';

/*
Extcal\Utility::echoArray($_POST);
exit;
    [mode] => add
    [event] => 3

    [mode] => remove
    [event] => 3

*/

/** @var Extcal\Helper $helper */
$helper = Extcal\Helper::getInstance();

$event_id   = $_POST['event'];
$member_uid = $xoopsUser->getVar('uid');

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

if ($xoopsUser && $helper->getConfig('whosnot_going')) {
    // If param are right
    if (\Xmf\Request::getInt('event', 0, 'POST') > 0 && ('add' === $_POST['mode'] || 'remove' === $_POST['mode'])) {
        $eventHandler          = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $eventNotMemberHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_NOT_MEMBER);

        // If the user have to be added
        if ('add' === $_POST['mode']) {
            $event = $eventHandler->getEvent(\Xmf\Request::getInt('event', 0, 'POST'), $xoopsUser);
            $eventNotMemberHandler->createEventNotMember([
                                                             'event_id' => \Xmf\Request::getInt('event', 0, 'POST'),
                                                             'uid'      => $xoopsUser->getVar('uid'),
                                                         ]);
            sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_3, _MD_EXTCAL_MSG_3);
            $rediredtMessage = _MD_EXTCAL_WHOSNOT_GOING_ADDED_TO_EVENT;

            // If the user have to be remove
        } else {
            if ('remove' === $_POST['mode']) {
                $eventNotMemberHandler->deleteEventNotMember([\Xmf\Request::getInt('event', 0, 'POST'), $xoopsUser->getVar('uid')]);
                sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_4, _MD_EXTCAL_MSG_4);
                $rediredtMessage = _MD_EXTCAL_WHOSNOT_GOING_REMOVED_TO_EVENT;
            }
        }
        redirect_header('event.php?event=' . $_POST['event'], 3, $rediredtMessage, false);
    } else {
        redirect_header('index.php', 3, _NOPERM, false);
    }
} else {
    redirect_header('index.php', 3, _NOPERM, false);
}
