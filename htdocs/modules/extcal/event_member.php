<?php

include '../../mainfile.php';

include_once("include/constantes.php");
include_once("include/mail_fnc.php");
include_once("include/functions.php");

// $member_uid = 1;
// $event_id = 393;

$message = "Bonne journ�e � tous";
$newStatus = 1;
$oldStatus = 0;

$event_id = $_POST['event']; 
$member_uid = $xoopsUser->getVar('uid');

/*
ext_echoArray($_POST);
exit;  
    [mode] => add
    [event] => 3

    [mode] => remove
    [event] => 3

*/





            
//sendMail2member($xoopsModuleConfig['email_Mode'], $event_id, $member_uid, $newStatus, $oldStatus, $message);
sendMail2member(_EXTCAL_HEADER_HTML, $event_id, $member_uid, $newStatus, $oldStatus, $message);

 
// $t = print_r(get_defined_constants(), true);
// $t = print_r($xoopsConfig, true);
// echo "<div align='left'><pre>{$t}</pre></div>"; 



//echo "<hr>";


if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(
        'index.php', 3, _NOPERM . "<br />"
        . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())
    );
    exit;
}

if ($xoopsUser && $xoopsModuleConfig['whos_going']) {
    // If param are right
    if (($_POST['mode'] == 'add' || $_POST['mode'] == 'remove')
        && intval($_POST['event']) > 0
    ) {

        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $eventMemberHandler = xoops_getmodulehandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);

        // If the user have to be added
        if ($_POST['mode'] == 'add') {
            $event = $eventHandler->getEvent(intval($_POST['event']), $xoopsUser);

            if ($event->getVar('event_nbmember') > 0
                && $eventMemberHandler->getNbMember(intval($_POST['event']))
                    >= $event->getVar('event_nbmember')
            ) {
sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_0, _MD_EXTCAL_MSG_0);                
                $rediredtMessage = _MD_EXTCAL_MAX_MEMBER_REACHED;
            } else {
                $eventMemberHandler->createEventmember(array('event_id' => intval($_POST['event']), 'uid' => $xoopsUser->getVar('uid')));
sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_1, _MD_EXTCAL_MSG_1);                
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_ADDED_TO_EVENT; 
            }
            // If the user have to be remove
        } else {
            if ($_POST['mode'] == 'remove') {
                $eventMemberHandler->deleteEventmember(array(intval($_POST['event']), $xoopsUser->getVar('uid')));
sendMail2member($mode, $event_id, $member_uid, _MD_EXTCAL_SUBJECT_2, _MD_EXTCAL_MSG_2);                
                $rediredtMessage = _MD_EXTCAL_WHOS_GOING_REMOVED_TO_EVENT;
            }
        } 
        redirect_header(
            "event.php?event=" . $_POST['event'], 3, $rediredtMessage, false
        );
    } else {
        redirect_header("index.php", 3, _NOPERM, false);
    }
} else {
    redirect_header("index.php", 3, _NOPERM, false);
}  


?>
