<?php

use Xmf\Request;
use XoopsModules\Extcal\{
    Helper,
    EventHandler,
    EventNotMemberHandler,
    Perm
};

require_once __DIR__ . '/header.php';

require_once __DIR__ . '/include/constantes.php';
require_once __DIR__ . '/include/mail_fnc.php';

/** @var EventHandler $eventHandler */
/** @var EventNotMemberHandler $eventNotMemberHandler */
/** @var Helper $helper */
$helper = Helper::getInstance();

$eventId   = Request::getInt('event', 0, 'POST');
$memberUid = $xoopsUser->getVar('uid');

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

if ($xoopsUser && $helper->getConfig('whosnot_going')) {
    $mode = Request::getString('mode', '', 'POST');
    // If param are right
    if ($eventId > 0 && ('add' === $mode || 'remove' === $mode)) {
        $eventHandler          = $helper->getHandler(_EXTCAL_CLN_EVENT);
        $eventNotMemberHandler = $helper->getHandler(_EXTCAL_CLN_NOT_MEMBER);

        // If the user have to be added
        if ('add' === $mode) {
            $event = $eventHandler->getEvent($eventId, $xoopsUser);
            $eventNotMemberHandler->createEventNotMember(
                [
                    'event_id' => Request::getInt('event', 0, 'POST'),
                    'uid'      => $xoopsUser->getVar('uid'),
                ]
            );
            sendMail2member($mode, $eventId, $memberUid, _MD_EXTCAL_SUBJECT_3, _MD_EXTCAL_MSG_3);
            $rediredtMessage = _MD_EXTCAL_WHOSNOT_GOING_ADDED_TO_EVENT;
            // If the user have to be remove
        } else {
            if ('remove' === $mode) {
                $eventNotMemberHandler->deleteEventNotMember([$eventId, $xoopsUser->getVar('uid')]);
                sendMail2member($mode, $eventId, $memberUid, _MD_EXTCAL_SUBJECT_4, _MD_EXTCAL_MSG_4);
                $rediredtMessage = _MD_EXTCAL_WHOSNOT_GOING_REMOVED_TO_EVENT;
            }
        }
        redirect_header('event.php?event=' . $eventId, 3, $rediredtMessage, false);
    } else {
        redirect_header('index.php', 3, _NOPERM, false);
    }
} else {
    redirect_header('index.php', 3, _NOPERM, false);
}
