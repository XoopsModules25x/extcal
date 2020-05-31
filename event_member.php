<?php

use XoopsModules\Extcal\{
    Helper,
    Utility
};
use Xmf\Request;

require_once __DIR__ . '/header.php';

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/mail_fnc.php';

/** @var Helper $helper */
$helper = Helper::getInstance();

// $memberUid = 1;
// $eventId = 393;

$message   = _MD_EXTCAL_MESSAGE;
$newStatus = 1;
$oldStatus = 0;
$userName  = $xoopsUser->getVar('uname');

$eventId   = Request::getInt('event', 0, 'POST');
$memberUid = $xoopsUser->getVar('uid');

/*
Utility::echoArray($_POST);
exit;
    [mode] => add
    [event] => 3

    [mode] => remove
    [event] => 3

*/

//sendMail2member($mode, $eventId, $memberUid, $subject, $tplMessage)
//sendMail2member($helper->getConfig('email_Mode'), $eventId, $memberUid, $newStatus, $oldStatus, $message);
sendMail2member(_EXTCAL_HEADER_HTML, $eventId, $memberUid, $userName, $message);

// $t = print_r(get_defined_constants(), true);
// $t = print_r($xoopsConfig, true);
// echo "<div align='left'><pre>{$t}</pre></div>";

//echo "<hr>";

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

if ($xoopsUser && $helper->getConfig('whos_going')) {
    $mode = Request::getString('mode', '', 'POST');
    // If param are right
    if ($eventId > 0 && ('add' === $mode || 'remove' === $mode)) {
        $eventHandler       = $helper->getHandler(_EXTCAL_CLN_EVENT);
        $eventmemberHandler = $helper->getHandler(_EXTCAL_CLN_MEMBER);

        // If the user have to be added
        if ('add' === $mode) {
            $event = $eventHandler->getEvent($eventId, $xoopsUser);

            if ($event->getVar('event_nbmember') > 0
                && $eventmemberHandler->getNbMember($eventId) >= $event->getVar('event_nbmember')) {
                sendMail2member($mode, $eventId, $memberUid, _MD_EXTCAL_SUBJECT_0, _MD_EXTCAL_MSG_0);
                $rediredtMessage = _MD_EXTCAL_MAX_MEMBER_REACHED;
            } else {
                $eventmemberHandler->createEventmember(
                    [
                        'event_id' => $eventId,
                        'uid'      => $xoopsUser->getVar('uid'),
                    ]
                );
                sendMail2member($mode, $eventId, $memberUid, _MD_EXTCAL_SUBJECT_1, _MD_EXTCAL_MSG_1);
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_ADDED_TO_EVENT;
            }
            // If the user have to be remove
        } else {
            if ('remove' === $mode) {
                $eventmemberHandler->deleteEventmember([$eventId, $xoopsUser->getVar('uid')]);
                sendMail2member($mode, $eventId, $memberUid, _MD_EXTCAL_SUBJECT_2, _MD_EXTCAL_MSG_2);
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_REMOVED_TO_EVENT;
            }
        }
        redirect_header('event.php?event=' . $eventId, 3, $rediredtMessage, false);
    } else {
        redirect_header('index.php', 3, _NOPERM, false);
    }
} else {
    redirect_header('index.php', 3, _NOPERM, false);
}
