<?php


include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
$params = array('view' => _EXTCAL_NAV_NEW_EVENT, 'file' => _EXTCAL_FILE_NEW_EVENT);
$GLOBALS['xoopsOption']['template_main'] = 'extcal_event.html';
include_once ('header.php');




//exit;

include XOOPS_ROOT_PATH . '/include/comment_view.php';

if (!isset($_GET['event'])) {
    $eventId = 0;
} else {
    $eventId = intval($_GET['event']);
}
$eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
$fileHandler = xoops_getmodulehandler(_EXTCAL_CLS_FILE, _EXTCAL_MODULE);
$eventMemberHandler = xoops_getmodulehandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);
$eventNotMemberHandler = xoops_getmodulehandler(_EXTCAL_CLS_NOT_MEMBER, _EXTCAL_MODULE);
$permHandler = ExtcalPerm::getHandler();
include_once XOOPS_ROOT_PATH."/modules/extcal/class/etablissement.php";
include_once XOOPS_ROOT_PATH."/modules/extcal/include/functions.php";
$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object

if (!function_exists('clear_unicodeslashes')){
	function clear_unicodeslashes($text) {
		$text = str_replace(array("\\'"), "'", $text);
		$text = str_replace(array("\\\\\\'"), "'", $text);
		$text = str_replace(array('\\"'), '"', $text);
		return $text;
	}
}


// Retriving event
$eventObj = $eventHandler->getEvent($eventId);

if (!$eventObj) {
    redirect_header('index.php', 3, '');
}

$event = $eventHandler->objectToArray($eventObj, array('cat_id', 'event_submitter'));
$eventHandler->serverTimeToUserTime($event);

    $configHandler =& xoops_gethandler('config');
    $extcalConfig  = $configHandler->getConfigList($module->getVar("mid"));

// Adding formated date for start and end event
$eventHandler->formatEventDate($event, $extcalConfig['event_date_event']);

// Assigning event to the template
$xoopsTpl->assign('event', $event);
$xoopsTpl->assign('event_desc', html_entity_decode($myts->displayTarea(clear_unicodeslashes($event['event_desc']), 1, 1, 1, 1, 1)));
$xoopsTpl->assign('event_address', html_entity_decode($myts->displayTarea(clear_unicodeslashes($event['event_address']), 1, 1, 1, 1, 1)));

// Title of the page
$xoopsTpl->assign('xoops_pagetitle', $event['event_title']);

// $lang = array(
//     'start' => _MD_EXTCAL_START, 'end' => _MD_EXTCAL_END, 'contact_info' => _MD_EXTCAL_CONTACT_INFO, 'email' => _MD_EXTCAL_EMAIL, 'url' => _MD_EXTCAL_URL, 'whos_going' => _MD_EXTCAL_WHOS_GOING, 'whosnot_going' => _MD_EXTCAL_WHOSNOT_GOING, 'reccur_rule' => _MD_EXTCAL_RECCUR_RULE, 'posted_by' => _MD_EXTCAL_POSTED_BY, 'on' => _MD_EXTCAL_ON
// );
// // Assigning language data to the template
// $xoopsTpl->assign('lang', $lang);

// Getting event attachement
$eventFiles = $fileHandler->objectToArray($fileHandler->getEventFiles($eventId));
$fileHandler->formatFilesSize($eventFiles);
$xoopsTpl->assign('event_attachement', $eventFiles);

// Token to disallow direct posting on membre/nonmember page
$xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());

// Etablissement 
$etablissementHandler = xoops_getmodulehandler(_EXTCAL_CLS_ETABLISSEMENT, _EXTCAL_MODULE);
$etablissementObj = $etablissementHandler->get($event['event_etablissement']);
//$etablissement = $etablissementHandler->objectToArray($etablissementObj);
$etablissement = $etablissementObj->vars;
$xoopsTpl->assign('etablissement', $etablissement);

// $t =print_r($etablissementObj->vars,true);
// echo "<hr>etablissement {$event['event_etablissement']}<hr><pre>{$t}</pre><hr>";


// ### For Who's Going function ###

// If the who's goging function is enabled
if ($extcalConfig['whos_going']) {

    // Retriving member's for this event
    $members = $eventMemberHandler->getMembers($eventId);

    // Initializing variable
    $eventmember['member']['show_button'] = false;

    $nbUser = 0;
    // Making a list with members and counting regitered user's
    foreach (
        $members as $k
        => $v
    ) {
        $nbUser++;
        $eventmember['member']['userList'][] = array('uid' => $k, 'uname' => $v->getVar('uname'));
    }
    $eventmember['member']['nbUser'] = $nbUser;

    // If the user is logged
    if ($xoopsUser) {

        // Initializing variable
        $eventmember['member']['show_button'] = true;
        $eventmember['member']['button_disabled'] = '';

        // If the user is already restired to this event
        if (array_key_exists($xoopsUser->getVar('uid'), $members)) {
            $eventmember['member']['button_text'] = _MD_EXTCAL_REMOVE_ME;
            $eventmember['member']['joinevent_mode'] = 'remove';
        } else {
            $eventmember['member']['button_text'] = _MD_EXTCAL_ADD_ME;
            $eventmember['member']['joinevent_mode'] = 'add';

            // If this event is full
            if ($event['event_nbmember'] != 0
                && $eventMemberHandler->getNbMember($eventId)
                    >= $event['event_nbmember']
            ) {
                $eventmember['member']['disabled'] = ' disabled="disabled"';
            }
        }

    }

}

// ### For Who's not Going function ###

// If the who's not goging function is enabled
if ($extcalConfig['whosnot_going']) {

    // Retriving not member's for this event
    $notmembers = $eventNotMemberHandler->getMembers($eventId);

    // Initializing variable
    $eventmember['notmember']['show_button'] = false;

    $nbUser = 0;
    // Making a list with not members
    foreach (
        $notmembers as $k
        => $v
    ) {
        $nbUser++;
        $eventmember['notmember']['userList'][] = array('uid' => $k, 'uname' => $v->getVar('uname'));
    }
    $eventmember['notmember']['nbUser'] = $nbUser;

    // If the user is logged
    if ($xoopsUser) {

        // Initializing variable
        $eventmember['notmember']['show_button'] = true;
        $eventmember['notmember']['button_disabled'] = '';

        // If the user is already restired to this event
        if (array_key_exists($xoopsUser->getVar('uid'), $notmembers)) {
            $eventmember['notmember']['button_text'] = _MD_EXTCAL_REMOVE_ME;
            $eventmember['notmember']['joinevent_mode'] = 'remove';
        } else {
            $eventmember['notmember']['button_text'] = _MD_EXTCAL_ADD_ME;
            $eventmember['notmember']['joinevent_mode'] = 'add';
        }
    }

}

// If who's going or not going function is enabled
if ($extcalConfig['whos_going'] || $extcalConfig['whosnot_going']) {
    $xoopsTpl->assign('eventmember', $eventmember);
}

// Checking user perm
if ($xoopsUser) {
    $xoopsTpl->assign('isAdmin', $xoopsUser->isAdmin());
    $canEdit
        =
        $permHandler->isAllowed($xoopsUser, 'extcal_cat_edit', $event['cat']['cat_id'])
            && $xoopsUser->getVar('uid') == $event['user']['uid'];
    $xoopsTpl->assign('canEdit', $canEdit);
} else {
    $xoopsTpl->assign('isAdmin', false);
    $xoopsTpl->assign('canEdit', false);
}

$xoopsTpl->assign('whosGoing', $extcalConfig['whos_going']);
$xoopsTpl->assign('whosNotGoing', $extcalConfig['whosnot_going']);

//-------------
$xoopsTpl->assign('params', $params);
$tNavBar = getNavBarTabs($params['view']);
$xoopsTpl->assign('tNavBar', $tNavBar);
//---------------------

/*  test modofication status    JJD
  $k = 'status';
  $isStatus = _EXTCAL_STATUS_DESINSCRIPTION;
  $xfStatus = new XoopsFormSelect('', $k, $isStatus, 1, false) ; 
  $tStatus = array(_EXTCAL_STATUS_NONE    => _MD_EXTCAL_LIB_NONE,
                   _EXTCAL_STATUS_COME    => _MD_EXTCAL_LIB_COME,
                   _EXTCAL_STATUS_NOTCOME => _MD_EXTCAL_LIB_NOTCOME);
 
  
  $xfStatus->addOptionArray($tStatus);
  $xoopsTpl->assign('status', $xfStatus->render());
*/
  
$xoTheme->addScript('browse.php?modules/extcal/js/highslide.js');
$xoTheme->addStylesheet('browse.php?modules/extcal/js/highslide.css');
  
  
    //function XoopsFormDhtmlTextArea($caption, $name, $value = "", $rows = 5, $cols = 50, $hiddentext = "xoopsHiddenText", $options = array());
    

include(XOOPS_ROOT_PATH . "/footer.php");
?>
