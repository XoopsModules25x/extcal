<?php
/**
 * classGenerator
 * walls_watermarks
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 *
 * L'utilisation de ce formulaire d'adminitration suppose
 * que la classe correspondante de la table a été générées avec classGenerator
 **/

include_once(XOOPS_ROOT_PATH . '/class/uploader.php');
require(XOOPS_ROOT_PATH . '/class/mail/phpmailer/class.phpmailer.php'); // First we require the PHPMailer libary in our script
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/functions.php');
include_once(XOOPS_ROOT_PATH . '/modules/extcal/include/constantes.php');
include_once(XOOPS_ROOT_PATH . '/class/template.php');

/********************************************************************
 *
 *******************************************************************
 * @param $mode
 * @param $event_id
 * @param $member_uid
 * @param $subject
 * @param $tplMessage
 */
function sendMail2member($mode, $event_id, $member_uid, $subject, $tplMessage)
{
    //mode = 0 pas d'entete
    //mode = 1 format text
    //mode = 2: format html

    global $xoopsConfig, $xoopsDB;
    // $t = print_r($xoopsConfig, true);
    // echo "<pre>{$t}</pre>";
    /*
    $member_uid = 1;
    $event_id = 393;
    $message = "Bonne journée à tous";
    $newStatus = 1;
    $oldStatus = 0;

    */

    //l'utilisateur ne pas etre notifié par mail
    //if ($mode == 0) exit;
    //-------------------------------------------------------
    $tblMember    = $xoopsDB->prefix('extcal_eventmember');
    $tblNotMember = $xoopsDB->prefix('extcal_eventnotmember');
    $tblUsers     = $xoopsDB->prefix('users');
    $tblEvent     = $xoopsDB->prefix('extcal_event');

    //--------------------------------------------------------------
    //Recuperation des données event,user et member
    //Recuperation des données de l'evennement
    $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
    $obj          = $eventHandler->getEvent($event_id);
    $event        = $eventHandler->objectToArray($obj);
    $eventHandler->formatEventDate($event, _MD_EXTCAL_FORMAT_DATE);

    $submiter_uid = $event['event_submitter'];
    // ext_echoArray($event,'event');
    //--------------------------------------------------------------
    //Recuperation des données du user createur de l'evennement
    $sql = <<<__sql__
  SELECT if(tu.name='', tu.uname, tu.name) AS name,     tu.uname,   tu.email
  FROM {$tblUsers} tu
  WHERE tu.uid = {$submiter_uid};
__sql__;

    $rst      = $xoopsDB->query($sql);
    $submiter = $xoopsDB->fetchArray($rst);
    // echo "{$sql}<br>";
    // ext_echoArray($submiter,'submiter');
    //--------------------------------------------------------------
    //Recuperation des données du membre inscrit
    $sql = <<<__sql__
  SELECT if(tu.name='', tu.uname, tu.name) AS name,     tu.uname,   tu.email
  FROM {$tblUsers} tu
  WHERE tu.uid = {$member_uid};
__sql__;

    $rst    = $xoopsDB->query($sql);
    $acteur = $xoopsDB->fetchArray($rst);
    //echo "{$sql}<br>";
    // ext_echoArray($acteur,'acteur');
    //--------------------------------------------------------------
    //Recuperation des données des membres présents
    $sql = <<<__sql__
SELECT tu.uid, if(tu.name='', tu.uname, tu.name) AS name,   tu.uname,   tu.email,
        tm.status
FROM {$tblMember} tm,
     {$tblUsers}  tu
WHERE tm.uid = tu.uid
  AND tm.event_id = {$event_id}
__sql__;

    $rst     = $xoopsDB->query($sql);
    $members = array();
    while ($row = $xoopsDB->fetchArray($rst)) {
        $row['status']        = _MD_EXTCAL_PRESENT;
        $members[$row['uid']] = $row;
    }

    //--------------------------------------------------------------
    //Recuperation des données des membres absents
    $sql = <<<__sql__
SELECT tu.uid, if(tu.name='', tu.uname, tu.name) AS name,   tu.uname,   tu.email,
        tm.status
FROM {$tblNotMember} tm,
     {$tblUsers}  tu
WHERE tm.uid = tu.uid
  AND tm.event_id = {$event_id}
__sql__;

    $rst = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($rst)) {
        $row['status']        = _MD_EXTCAL_ABSENT;
        $members[$row['uid']] = $row;
    }

    // ext_echoArray($members,'members');
    // exit;

    //--------------------------------------------------------------
    //Message et sujet du mail
    $action  = ''; //a voir   JJD
    $message = sprintf($tplMessage, $acteur['name']);
    //$subject .= ' (' . rand(1, 100) . ')';
    $subject .= ' - ' . $acteur['name'];
    //--------------------------------------------------------------
    //Chargement du template dans le dossier de langue
    //$f = _EXTCAL_PATH_LG . $xoopsConfig['language'] . '\mail_inscription.html';
    //$tpl = new tpl($f);
    $tpl = new XoopsTpl();

    $tpl->assign('dateAction', date(_MD_EXTCAL_FORMAT_DATE));
    $tpl->assign('submiter', $submiter);
    $tpl->assign('event', $event);
    $tpl->assign('acteur', $acteur);
    $tpl->assign('members', $members);
    $tpl->assign('action', $action);
    $tpl->assign('subject', $subject);
    $tpl->assign('message', $message);
    $tpl->assign('xoopsConfig', $xoopsConfig);
    $tpl->assign('br', '<br>');

    //--------------------------------------------------------------
    $destinataires                     = array();
    $destinataires[$submiter['email']] = $submiter['email'];
    $destinataires[$acteur['email']]   = $acteur['email'];
    while (list($k, $row) = each($members)) {
        $destinataires[$row['email']] = $row['email'];
    }

    // ext_echoArray($destinataires);
    // exit;

    $mail_fromName  = $xoopsConfig['sitename'];
    $mail_fromemail = $xoopsConfig['adminmail'];
    $mail_subject   = $subject;

    $bEcho = false;
    $mode  = _EXTCAL_HEADER_HTML;
    $sep   = '|';

    $template = 'extcal_mail_member_text.tpl';
    if ($mode == _EXTCAL_HEADER_HTML) {
        $template = 'extcal_mail_member_html.tpl';
    }
    $mail_body = $tpl->fetch('db:' . $template);

    extcal_SendMail($destinataires, $mail_fromName, $mail_fromemail, $mail_subject, $mail_body, $bEcho = false, $mode = 0, $sep = '|');

    //Prépartion de l'envoi

    //--------------------------------------------------------------
}

/*****************************************************************
 ****************************************************************
 * @param        $destinataires
 * @param        $mail_fromname
 * @param        $mail_fromemail
 * @param        $mail_subject
 * @param        $mail_body
 * @param bool $bEcho
 * @param int $mode
 * @param string $sep
 */
function extcal_SendMail($destinataires, $mail_fromname, $mail_fromemail, $mail_subject, $mail_body, $bEcho = false, $mode = 0, $sep = '|')
{
    global $ModName, $signature, $mail_admin, $xoopsConfig, $xoopsDB, $xoopsModule;

    //$bEcho=false;
    //echo "<hr>function hermesMail<hr>";

    // $destinataires = array('jjd@kiolo.com','jjdelalandre@wanadoo.fr','admin@win-trading.com');
    //$mail_fromname = "test jjd hermes";
    if ($mail_fromname == '') {
        $mail_fromname = $mail_fromemail;
    }

    //$mail_fromemail = "admin@win-trading.com";
    //$mail_subject = "test hemes";
    //$mail_body = getContentTestMail();
    //-----------------------------
    if (!is_array($destinataires)) {
        $destinataires = explode($sep, $destinataires);
    }
    $header = extcal_getHeader(1, $mail_fromemail);
    //-----------------------------
    $myts = MyTextSanitizer::getInstance();
    //$xoopsMailer = getMailer();
    $xoopsMailer = xoops_getMailer();

    //$xoopsMailer->setToUsers($destinataires[$i]);

    while (list($k, $v) = each($destinataires)) {
        //for ( $i = 0; $i < count($destinataires); ++$i) {
        //$xoopsMailer->setToUsers($destinataires[$i]);
        $xoopsMailer->setToEmails($v);
        //echo "setToUsers : {$destinataires[$i]}<br>";
    }

    $xoopsMailer->multimailer->isHTML(true);
    $xoopsMailer->setFromName($myts->oopsStripSlashesGPC($mail_fromname));

    $xoopsMailer->setFromEmail($myts->oopsStripSlashesGPC($mail_fromemail));

    $xoopsMailer->setSubject($myts->oopsStripSlashesGPC($mail_subject));
    $xoopsMailer->setBody($myts->oopsStripSlashesGPC($mail_body));
    //$xoopsMailer->encodeBody($mail_body);

    $xoopsMailer->useMail();

    //function $xoopsMailer->sendMail($email, $subject, $body, $headers)
    $xoopsMailer->send($bEcho);

    if ($bEcho) {
        ext_echo($xoopsMailer->getSuccess());
        ext_echo($xoopsMailer->getErrors());
    }
    /*

     echo "<hr>mail_fromname : {$mail_fromname}<br>"
          ."mail_fromemail : {$mail_fromemail}<br>"
          ."mail_subject : {$mail_subject}<br>"
          ."mail_body : {$mail_body}<br><hr>";
    */
    //---------------------------
    /*

      $adresse = "jjd@kiolo.com";
      $bolOk = mail($adresse, "test envoi mail", "test envoi envoi mail via php");
      $r= (($bolOk) ? " => Succés" : "Echec");
      echo "<hr>==> <b>{$r}</b> de l'envoi du mail a: ==> {$adresse}<br>" ;
    */
}

/****************************************************************************
 *
 ***************************************************************************
 * @param $mode
 * @param $emailSender
 *
 * @return string
 */
function extcal_getHeader($mode, $emailSender)
{
    //mode = 0 pas d'entete
    //mode = 1 format text
    //mode = 2: format html

    global $xoopsConfig;

    // $t = print_r($xoopsConfig, true);
    // echo "<pre>{$t}</pre>";

    //------------------------------------------------------
    $d = date('d-m-Y h:m:h', time());
    //-----------------------------------------------------------
    //defini l'expediteur du mail
    if ($emailSender == '') {
        if ($xoopsConfig['adminmail'] == '') {
            $emailSender = "webmaster@{$_SERVER['SERVER_NAME']}";
        } else {
            $emailSender = $xoopsConfig['adminmail'];
        }
    }
    //-----------------------------------------------------------
    $header   = array();
    $header[] = "From: {$emailSender}";
    $header[] = "Reply-To: {$emailSender}";
    $header[] = 'X-Mailer: PHP/' . phpversion();

    if ($mode == _EXTCAL_HEADER_HTML) {
        $header[] = 'MIME-Version: 1.0';
        $header[] = 'Content-type: text/html; charset=iso-8859-1';
    } else {
        //bin rien a prori
    }
    $header[] = '';

    //$sHeader = implode("\r\n", $header);
    $sHeader = implode("\r\n", $header);

    return $sHeader;
}
