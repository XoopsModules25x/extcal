<?php

use XoopsModules\Extcal;

require_once __DIR__ . '/../../../include/cp_header.php';
include __DIR__ . '/../../../class/xoopsformloader.php';
require_once __DIR__ . '/admin_header.php';

function extgalleryLastVersion()
{
    //return @file_get_contents("http://www.zoullou.net/extcal.version");  //the Website is not longer working
}

/**
 * @return bool
 */
function isUpToDate()
{
    $version = extgalleryLastVersion();

    return $GLOBALS['xoopsModule']->getVar('version') >= $version;
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = 'default';
    if (isset($_POST['op'])) {
        $op = $_POST['op'];
    }
}
$fct = 'default';
if (isset($_GET['fct'])) {
    $fct = $_GET['fct'];
}

switch ($op) {
    case 'notification':
        switch ($fct) {
            case 'send':
                if (!$GLOBALS['xoopsSecurity']->check()) {
                    redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
                }
                xoops_cp_header();
                adminMenu(1);

                $myts        = \MyTextSanitizer::getInstance();
                $xoopsMailer = xoops_getMailer();
                //                $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
                //                $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
                //                $eventMemberHandler = xoops_getModuleHandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);
                $extcalTime        = Extcal\Time::getHandler();
                $extcalConfig      = Extcal\Config::getHandler();
                $xoopsModuleConfig = $extcalConfig->getModuleConfig();

                $event = $eventHandler->getEvent($_POST['event_id'], $xoopsUser, true);
                $cat   = $catHandler->getCat($event->getVar('cat_id'), $xoopsUser, 'all');

                $xoopsMailer->setToUsers($eventMemberHandler->getMembers($_POST['event_id']));
                $xoopsMailer->setFromName($myts->oopsStripSlashesGPC($_POST['mail_fromname']));
                $xoopsMailer->setFromEmail($myts->oopsStripSlashesGPC($_POST['mail_fromemail']));
                $xoopsMailer->setSubject($myts->oopsStripSlashesGPC($_POST['mail_subject']));
                $xoopsMailer->setBody($myts->oopsStripSlashesGPC($_POST['mail_body']));
                if (in_array('mail', $_POST['mail_send_to'])) {
                    $xoopsMailer->useMail();
                }
                if (empty($_POST['mail_inactive']) && in_array('pm', $_POST['mail_send_to'])) {
                    $xoopsMailer->usePM();
                }
                $tag = [
                    'EV_CAT'   => $cat->getVar('cat_name'),
                    'EV_TITLE' => $event->getVar('event_title'),
                    'EV_START' => $extcalTime->getFormatedDate($xoopsModuleConfig['date_long'], $event->getVar('event_start')),
                    'EV_END'   => $extcalTime->getFormatedDate($xoopsModuleConfig['date_long'], $event->getVar('event_end')),
                    'EV_LINK'  => XOOPS_URL . '/modules/extcal/event.php?event=' . $event->getVar('event_id'),
                ];
                $xoopsMailer->assign($tag);
                $xoopsMailer->send(true);
                echo $xoopsMailer->getSuccess();
                echo $xoopsMailer->getErrors();

                xoops_cp_footer();

                break;

            case 'default':
            default:
                xoops_cp_header();
                $fromemail      = !empty($xoopsConfig['adminmail']) ? $xoopsConfig['adminmail'] : $xoopsUser->getVar('email', 'E');
                $subjectCaption = _AM_EXTCAL_SUBJECT . "<br><br><span style='font-size:x-small;font-weight:bold;'>" . _AM_EXTCAL_USEFUL_TAGS . "</span><br><span style='font-size:x-small;font-weight:normal;'>" . _AM_EXTCAL_MAILTAGS6 . '<br>' . _AM_EXTCAL_MAILTAGS2 . '</span>&nbsp;&nbsp;&nbsp;';
                $bodyCaption    = _AM_EXTCAL_BODY
                                  . "<br><br><span style='font-size:x-small;font-weight:bold;'>"
                                  . _AM_EXTCAL_USEFUL_TAGS
                                  . "</span><br><span style='font-size:x-small;font-weight:normal;'>"
                                  . _AM_EXTCAL_MAILTAGS1
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS2
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS3
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS4
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS5
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS6
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS7
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS8
                                  . '<br>'
                                  . _AM_EXTCAL_MAILTAGS9
                                  . '</span>&nbsp;&nbsp;&nbsp;';
                $toCheckBbox    = new \XoopsFormCheckBox(_AM_EXTCAL_SEND_TO, 'mail_send_to', 'mail');
                $toCheckBox->addOption('mail', _AM_EXTCAL_EMAIL);
                $toCheckBox->addOption('pm', _AM_EXTCAL_PM);

                echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_APPROVED_EVENT . '</legend>';
                echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
                echo _AM_EXTCAL_INFO_SEND_NOTIF;
                echo '</fieldset><br>';
                $form = new \XoopsThemeForm(_AM_EXTCAL_SEND_NOTIFICATION, 'mailusers', 'index.php?op=notification&amp;fct=send', 'post', true);
                $form->addElement(new \XoopsFormText(_AM_EXTCAL_FROM_NAME, 'mail_fromname', 30, 255, $xoopsConfig['sitename']), true);
                $form->addElement(new \XoopsFormText(_AM_EXTCAL_FROM_EMAIL, 'mail_fromemail', 30, 255, $fromemail), true);
                $form->addElement(new \XoopsFormText($subjectCaption, 'mail_subject', 50, 255, _AM_EXTCAL_SEND_NOTIFICATION_SUBJECT), true);
                $form->addElement(new \XoopsFormTextArea($bodyCaption, 'mail_body', _AM_EXTCAL_SEND_NOTIFICATION_BODY, 10), true);
                $form->addElement($toCheckBox, true);
                $form->addElement(new \XoopsFormHidden('event_id', $_GET['event_id']), false);
                $form->addElement(new \XoopsFormButton('', 'mail_submit', _SUBMIT, 'submit'));
                $form->display();
                echo '</fieldset>';

                xoops_cp_footer();

                break;
        }
        break;

    default:
    case 'default':
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
        xoops_cp_header();
        //        require_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
        //        $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
        //        $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addInfoBox(_MI_EXTCAL_DASHBOARD);
        $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_EXTCAL_INDEX_CATEGORIES . '</infolabel>', $catHandler->getCount()), '', 'Green');
        $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_EXTCAL_INDEX_EVENT . '</infolabel>', $eventHandler->getCount(new \Criteria('event_approved', 1))), '', 'Green');
        $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_EXTCAL_INDEX_PENDING . '</infolabel>', $eventHandler->getCount(new \Criteria('event_approved', 0))), '', 'Red');
        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $criteriaCompo->add(new \Criteria('event_start', time(), '>='));
        $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_EXTCAL_INDEX_APPROVED . '</infolabel><infotext>', $eventHandler->getCount($criteriaCompo) . '</infotext>'), '', 'Green');

        $adminObject->addConfigBoxLine();
        $adminObject->addConfigBoxLine(_AM_EXTCAL_PEAR_PATH);
        $adminObject->addConfigBoxLine(_EXTCAL_PEAR_ROOT, 'folder');

        //JJD
        //         $adminObject->addConfigBoxLine(XOOPS_ROOT_PATH,'folder');

        //        $adminObject->addLineConfigLabel(_AM_EXTCAL_CONFIG_PHP, $xoopsModule->getInfo("min_php"), 'php');
        //        $adminObject->addLineConfigLabel(_AM_EXTCAL_CONFIG_XOOPS, $xoopsModule->getInfo("min_xoops"), 'xoops');
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->displayIndex();
        //***************************************************************************************
        $pendingEvent = $eventHandler->objectToArray($eventHandler->getPendingEvent(), ['cat_id']);
        $eventHandler->formatEventsDate($pendingEvent, 'd/m/Y');

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_PENDING_EVENT . '</legend>';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        //        echo '<img src="../assets/images/icons/on.png" >&nbsp;&nbsp;'._AM_EXTCAL_INFO_APPROVE_PENDING_EVENT.'<br>';
        echo '<img src=' . $pathIcon16 . '/edit.png>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_EDIT_PENDING_EVENT . '<br>';
        echo '<img src=' . $pathIcon16 . '/delete.png>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_DELETE_PENDING_EVENT . '<br>';
        echo '</fieldset><br>';

        echo '<table class="outer" style="width:100%;">';
        echo '<tr style="text-align:center;">';
        echo '<th>' . _AM_EXTCAL_CATEGORY . '</th>';
        echo '<th>' . _AM_EXTCAL_TITLE . '</th>';
        echo '<th>' . _AM_EXTCAL_START_DATE . '</th>';
        echo '<th>' . _AM_EXTCAL_ACTION . '</th>';
        echo '</tr>';

        if (count($pendingEvent) > 0) {
            $i = 0;
            foreach ($pendingEvent as $event) {
                $class = (0 == ++$i % 2) ? 'even' : 'odd';
                echo '<tr style="text-align:center;" class="' . $class . '">';
                echo '<td>' . $event['Category']['cat_name'] . '</td>';
                echo '<td>' . $event['event_title'] . '</td>';
                echo '<td>' . $event['formated_event_start'] . '</td>';
                echo '<td style="width:10%; text-align:center;">';
                echo '<a href="event.php?op=modify&amp;event_id=' . $event['event_id'] . '"><img src=' . $pathIcon16 . '/edit.png></a>&nbsp;&nbsp;';
                echo '<a href="event.php?op=delete&amp;event_id=' . $event['event_id'] . '"><img src=' . $pathIcon16 . '/delete.png></a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">' . _AM_EXTCAL_NO_PENDING_EVENT . '</td></tr>';
        }

        echo '</table></fieldset><br>';

        require_once __DIR__ . '/admin_footer.php';

        break;
}
