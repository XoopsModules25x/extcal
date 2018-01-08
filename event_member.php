<?php

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/mail_fnc.php';

// $member_uid = 1;
// $event_id = 393;

$message   = _MD_EXTCAL_MESSAGE;
$newStatus = 1;
$oldStatus = 0;
$userName  = $xoopsUser->getVar('uname');

$event_id   = $_POST['event'];
$member_uid = $xoopsUser->getVar('uid');

/*
Extcal\Utility::echoArray($_POST);
exit;
    [mode] => add
    [event] => 3

    [mode] => remove
    [event] => 3

*/

//sendMail2member($mode, $event_id, $member_uid, $subject, $tplMessage)
//sendMail2member($xoopsModuleConfig['email_Mode'], $event_id, $member_uid, $newStatus, $oldStatus, $message);
sendMail2member(_EXTCAL_HEADER_HTML, $event_id, $member_uid, $userName, $message);

// $t = print_r(get_defined_constants(), true);
// $t = print_r($xoopsConfig, true);
// echo "<div align='left'><pre>{$t}</pre></div>";

//echo "<hr>";

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

if ($xoopsUser && $xoopsModuleConfig['whos_going']) {
    // If param are right
    if ((int)$_POST['event'] > 0 && ('add' === $_POST['mode'] || 'remove' === $_POST['mode'])) {
        $eventHandler       = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $eventMemberHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_MEMBER);

        // If the user have to be added
        if ('add' === $_POST['mode']) {
            $event = $eventHandler->getEvent((int)$_POST['event'], $xoopsUser);

            if ($event->getVar('event_nbmember') > 0
                && $eventMemberHandler->getNbMember((int)$_POST['event']) >= $event->getVar('event_nbmember')) {
                sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_0, _MD_EXTCAL_MSG_0);
                $rediredtMessage = _MD_EXTCAL_MAX_MEMBER_REACHED;
            } else {
                $eventMemberHandler->createEventmember([
                                                           'event_id' => (int)$_POST['event'],
                                                           'uid'      => $xoopsUser->getVar('uid'),
                                                       ]);
                sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_1, _MD_EXTCAL_MSG_1);
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_ADDED_TO_EVENT;
            }
            // If the user have to be remove
        } else {
            if ('remove' === $_POST['mode']) {
                $eventMemberHandler->deleteEventmember([(int)$_POST['event'], $xoopsUser->getVar('uid')]);
                sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_2, _MD_EXTCAL_MSG_2);
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_REMOVED_TO_EVENT;
            }
        }
        redirect_header('event.php?event=' . $_POST['event'], 3, $rediredtMessage, false);
    } else {
        redirect_header('index.php', 3, _NOPERM, false);
    }
} else {
    redirect_header('index.php', 3, _NOPERM, false);
}
