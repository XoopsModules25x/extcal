<?php

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';

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

$event_id   = $_POST['event'];
$member_uid = $xoopsUser->getVar('uid');

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

if ($xoopsUser && $xoopsModuleConfig['whosnot_going']) {
    // If param are right
    if ((int)$_POST['event'] > 0 && ('add' === $_POST['mode'] || 'remove' === $_POST['mode'])) {
        $eventHandler          = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $eventNotMemberHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_NOT_MEMBER);

        // If the user have to be added
        if ('add' === $_POST['mode']) {
            $event = $eventHandler->getEvent((int)$_POST['event'], $xoopsUser);
            $eventNotMemberHandler->createEventNotMember([
                                                             'event_id' => (int)$_POST['event'],
                                                             'uid'      => $xoopsUser->getVar('uid'),
                                                         ]);
            sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_3, _MD_EXTCAL_MSG_3);
            $rediredtMessage = _MD_EXTCAL_WHOSNOT_GOING_ADDED_TO_EVENT;

        // If the user have to be remove
        } else {
            if ('remove' === $_POST['mode']) {
                $eventNotMemberHandler->deleteEventNotMember([(int)$_POST['event'], $xoopsUser->getVar('uid')]);
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
