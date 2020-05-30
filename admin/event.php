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
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

use XoopsModules\Extcal\{
    Helper,
    EventHandler,
    FileHandler,
    Utility,
    CategoryHandler,
    EventmemberHandler,
    LocationHandler
};
use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
// require_once  dirname(__DIR__) . '/class/form/extcalform.php';

// require_once  dirname(__DIR__) . '/class/Utility.php';

/** @var Helper $helper */
$helper = Helper::getInstance();

//TODO get individual variables
$gepeto = array_merge($_GET, $_POST);
//while (list($k, $v) = each($gepeto)) {
foreach ($gepeto as $k => $v) {
    ${$k} = $v;
}

$op = Request::getCmd('op', '');

//--------------------------------------------------------------------
/**
 * @param $ids
 *
 * @return bool
 */
function deleteEvents($ids)
{
    /** @var EventHandler $eventHandler */
    $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
    $criteria     = new \Criteria('event_id', "($ids)", 'IN');

    //Supression des images
    $rst = $eventHandler->getAllEvents($criteria);

    //    while (list($k, $t) = each($rst)) {
    foreach ($rst as $k => $t) {
        if ('' != $t['event_picture1']) {
            $f = XOOPS_ROOT_PATH . '/uploads/extcal/' . $t['event_picture1'];
            unlink($f);
            echo $f . '<br>';
        }

        if ('' != $t['event_picture2']) {
            $f = XOOPS_ROOT_PATH . '/uploads/extcal/' . $t['event_picture1'];
            unlink($f);
            echo $f . '<br>';
        }
    }

    //Supression des enregistrements
    $eventHandler->deleteAllEvents($criteria);

    return true;
}

switch ($op) {
    case 'enreg':

        $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $fileHandler  = Helper::getInstance()->getHandler(_EXTCAL_CLN_FILE);
        // $t = print_r($_POST,true);
        // echo "<pre>{$t}</pre><br>";
        // exit;
        // If the date format is wrong
        //        if (
        //            !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_start']['date'])
        //                || !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_end']['date'])
        //        ) {
        //            redirect_header(
        //                'event.php', 3, _MD_EXTCAL_WRONG_DATE_FORMAT . "<br>"
        //                . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors())
        //            );
        //            exit;
        //        }

        //exit;
        ///////////////////////////////////////////////////////////////////////////////
        Utility::loadImg($_REQUEST, $event_picture1, $event_picture2);
        ///////////////////////////////////////////////////////////////////////////////
        $data = [
            'event_title'        => Request::getString('event_title', '', 'POST'),
            'cat_id'             => Request::getInt('cat_id', 0, 'POST'),
            'event_desc'         => Request::getString('event_desc', '', 'POST'),
            'event_nbmember'     => Request::getInt('event_nbmember', 0, 'POST'),
            'event_organisateur' => Request::getString('event_organisateur', '', 'POST'),
            'event_contact'      => Request::getString('event_contact', '', 'POST'),
            'event_url'          => Request::getString('event_url', '', 'POST'),
            'event_email'        => Request::getString('event_email', '', 'POST'),
            'event_address'      => Request::getString('event_address', '', 'POST'),
            'event_approved'     => 1,
            'event_start'        => Request::getArray('event_start', [], 'POST'),
            'have_end'           => Request::getInt('have_end', 0, 'POST'),
            'event_end'          => Request::getArray('event_end', [], 'POST'),
            'event_picture1'     => @$event_picture1,
            'event_picture2'     => @$event_picture2,
            'event_price'        => @Request::getString('event_price', '', 'POST'),
            'event_location'     => Request::getInt('event_location', 0, 'POST'),
            'dohtml'             => $helper->getConfig('allow_html'),
            'event_icone'        => Request::getString('event_icone', '', 'POST'),
        ];

        // Event edited
        if (Request::hasVar('event_id', 'POST')) {
            if (!$eventHandler->modifyEvent(Request::getInt('event_id', 0, 'POST'), $data)) {
                redirect_header('event.php', 3, _AM_EXTCAL_EVENT_EDIT_FAILED, false);
            } else {
                $fileHandler->createFile(Request::getInt('event_id', 0, 'POST'));
                redirect_header('event.php', 3, _AM_EXTCAL_EVENT_EDITED, false);
            }
            // New event
        } else {
            /** @var \XoopsNotificationHandler $notificationHandler */
            $notificationHandler = xoops_getHandler('notification');
            /** @var CategoryHandler $categoryHandler */
            $categoryHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);

            $data['event_submitter']  = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
            $data['event_submitdate'] = time();

            if ($eventHandler->createEvent($data)) {
                $fileHandler->createFile($eventHandler->getInsertId());
                $cat = $categoryHandler->getCat(Request::getInt('cat_id', 0, 'POST'), $xoopsUser, 'all');
                $notificationHandler->triggerEvent('global', 0, 'new_event', ['EVENT_TITLE' => Request::getString('event_title', '', 'POST')]);
                $notificationHandler->triggerEvent(
                    'category',
                    $_POST['cat_id'],
                    'new_event_cat',
                    [
                        'EVENT_TITLE' => Request::getString('event_title', '', 'POST'),
                        'CAT_NAME'    => $cat->getVar('cat_name'),
                    ]
                );
                redirect_header('event.php', 3, _AM_EXTCAL_EVENT_CREATED, false);
            } else {
                redirect_header('event.php', 3, _AM_EXTCAL_EVENT_CREATE_FAILED, false);
            }
        }
        break;
    case 'clone': /* sur validation du formulaire */
    case 'modify':
        $action = (('clone' === $op) ? 'clone' : 'edit');
        xoops_cp_header();
        //================================================
        // require_once (XOOPS_ROOT_PATH . '/class/xoopsform/tc_calendar/formtccalendar.php');
        //
        //        // Call the calendar constructor - use the desired form and format, according to the instructions/samples provided on triconsole.com
        //        $dateBirthday = new \XoopsTcCalendar("datez1", true, false);
        //        //$dateBirthday->setIcon("/images/iconCalendar.gif");
        //        $dateBirthday->setIcon("/class/xoopsform/tc_calendar/images/iconCalendar.gif");
        //        //$dateBirthday->rtl=false;
        //        $dateBirthday->setAutoHide(false);
        //
        //       //$myCalendar->setDate(date('d'), date('m'), date('Y'));
        //       //$dateBirthday->setDate($p['date1_day'], $p['date1_month'], $p['date1_year']);
        //        $dateBirthday->setDate(date('d'), date('m'), date('Y'));
        //
        //        $dateBirthday->setPath(XOOPS_URL . "/class/xoopsform/tc_calendar/");
        //        $dateBirthday->zindex = 150; //default 1
        //        $dateBirthday->setYearInterval(1995, date('Y'));
        //        $dateBirthday->dateAllow('1960-03-01', date('Y-m-d'));
        //        //$dateBirthday->autoSubmit(true, "calendar");
        //        $dateBirthday->disabledDay("sat");
        //        $dateBirthday->disabledDay("sun");
        //        $dateBirthday->setSpecificDate(array("2011-04-14", "2010-12-25"), 0, 'month');
        //        $dateBirthday->setSpecificDate(array("2011-04-01"), 0, 'year');
        //        $dateBirthday->setAlignment('right', 'bottom'); //optional
        // echo "<table><tr><td>zzzzz</td><td></td><td>";
        // echo $dateBirthday->render();
        // echo "</td></tr></table>";
        //echo $dateBirthday->render();
        //================================================
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
        //         require_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //***************************************************************************************

        $eventId      = $_GET['event_id'];
        $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _MD_EXTCAL_EDIT_EVENT . '</legend>';

        $form = $eventHandler->getEventForm('admin', $action, ['event_id' => $eventId]);
        if ($form) {
            $form->display();
        }

        echo '</fieldset><br>';

        xoops_cp_footer();

        break;
    case 'clone2': /* sur clique de l'icone du formulaire*/

        //$newEventId = 1;
        $eventId      = Request::getInt('event_id', 0, 'GET');
        $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $event        = $eventHandler->getEvent($eventId);
        $t            = $event->getVars();
        $data         = [];
        //        while (list($key, $val) = each($t)) {
        foreach ($t as $key => $val) {
            $data[$key] = $val['value'];
        }

        $data['event_id']    = 0;
        $data['event_title'] .= ' (' . _AM_EXTCAL_CLONE_OF . $eventId . ')';

        $newEvent = $eventHandler->create();
        $newEvent->setVars($data);
        $t = $eventHandler->insert($newEvent, true);

        $newEventId = $newEvent->getVar('event_id');
        $ts         = print_r($newEventId, true);

        redirect_header("event.php?op=modify&event_id={$newEventId}", 3, _AM_EXTCAL_EVENT_DELETED, false);
        break;
    case 'delete':

        if (Request::hasVar('confirm', 'POST')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            //             $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
            //             $eventHandler->deleteEvent($_POST['event_id']);
            deleteEvents(Request::getInt('event_id', 0, 'POST'));
            redirect_header('event.php', 3, _AM_EXTCAL_EVENT_DELETED, false);
        } else {
            xoops_cp_header();
            // @author      Gregory Mage (Aka Mage)
            //***************************************************************************************
            //require_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            //***************************************************************************************

            $hiddens = ['event_id' => Request::getInt('event_id', 0, 'GET'), 'form_delete' => '', 'confirm' => 1];
            xoops_confirm($hiddens, 'event.php?op=delete', _AM_EXTCAL_CONFIRM_DELETE_EVENT, _DELETE, 'event.php');

            xoops_cp_footer();
        }

        break;
    case 'deleteSelection':

        xoops_cp_header();
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
        //require_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //***************************************************************************************
        if (isset($_POST['deleteSelection'][0])) {
            $msg = _AM_EXTCAL_CONFIRM_DELETE_ALL;
            $ids = array_keys($_POST['deleteAllEvents']);
        } else {
            $msg = _AM_EXTCAL_CONFIRM_DELETE_SELECTION;
            $ids = array_keys($_POST['deleteEvents']);
        }

        //           $msg = ((isset($_POST['deleteSelection'][0])) ? _AM_EXTCAL_CONFIRM_DELETE_ALL : _AM_EXTCAL_CONFIRM_DELETE_SELECTION);
        //           $ids = array_keys($_POST['deleteEvents']);
        $ids = implode(',', $ids);
        //echo $ids.'<br>';
        $hiddens = ['event_ids' => $ids, 'form_delete' => '', 'confirm' => 1];
        //$hiddens = array('event_ids' => $_POST['deleteEvents'], 'form_delete' => '', 'confirm' => 1);
        xoops_confirm($hiddens, 'event.php?op=deleteSelectionOK', $msg, _DELETE, 'event.php');

        xoops_cp_footer();

        break;
    case 'deleteSelectionOK':
        //-----------------------------------------
        // $t = print_r($_GET,true);
        // echo "<hr><pre>{$t}</pre><hr>";
        //
        // $t = print_r($_POST,true);
        // echo "<hr><pre>{$t}</pre><hr>";
        // exit;
        //-----------------------------------------

        if (isset($_POST['deleteSelection'][0])) {
        } else {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('index.php', 3, _NOPERM . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            deleteEvents($_POST['event_ids']);

            redirect_header('event.php', 3, _AM_EXTCAL_EVENTS_DELETED, false);
        }

        break;
    case 'default':
    default:

        $start          = Request::getInt('start', 0, 'GET');
        $nbEventsByPage = $helper->getConfig('nbEventsByPage');

        xoops_cp_header();
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************

        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //***************************************************************************************

        $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $events       = $eventHandler->objectToArray($eventHandler->getNewEvent($start, $nbEventsByPage, 0, true), ['cat_id']);
        $eventHandler->formatEventsDate($events, _SHORTDATESTRING);

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_APPROVED_EVENT . '</legend>';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        //echo'<img src='. XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/assets/images/action/edit.png' .' '.'style=vertical-align:middle;>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_EDIT . '<br>';
        //echo'<img src='. XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/assets/images/action/delete.png'. ' '."style=vertical-align:middle;>&nbsp;&nbsp;". _AM_EXTCAL_INFO_DELETE;

        echo '<img src=' . $pathIcon16 . '/edit.png' . ' ' . 'style=vertical-align:middle;>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_EDIT . '<br>';
        echo '<img src=' . $pathIcon16 . '/delete.png' . ' ' . 'style=vertical-align:middle;>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_DELETE . '<br>';

        echo '</fieldset><br>';

        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _MD_EXTCAL_SUBMITED_EVENT . '</legend>';

        echo '<form method="POST" action="event.php">';
        echo '<input type="hidden" name="op" value="deleteSelection">';

        echo '<table class="outer" style="width:100%;">';
        echo '<tr style="text-align:center;">';
        echo '<th>' . _AM_EXTCAL_DELETE . '</th>';
        echo '<th>#</th>';
        echo '<th>' . _AM_EXTCAL_CATEGORY . '</th>';
        echo '<th>' . _AM_EXTCAL_TITLE . '</th>';
        echo '<th>' . _AM_EXTCAL_START_DATE . '</th>';
        echo '<th>' . _AM_EXTCAL_END_DATE . '</th>';
        echo '<th>' . _AM_EXTCAL_RECURRENT . '</th>';
        echo '<th>' . _AM_EXTCAL_START_RULES . '</th>';
        echo '<th>' . _AM_EXTCAL_ACTION . '</th>';

        echo '</tr>';

        if (count($events) > 0) {
            $i = 0;
            foreach ($events as $event) {
                $class = (0 == ++$i % 2) ? 'even' : 'odd';
                echo '<tr style="text-align:left;" class="' . $class . '">';
                echo "<td width='10%' align='center'>";
                echo "<input type='checkbox' name='deleteEvents[{$event['event_id']}]' value='1' >";
                echo "<input type='hidden' name='deleteAllEvents[{$event['event_id']}]' value='1'>";
                echo '</td>';
                echo "<td align = 'center' width='5%'>" . $event['event_id'] . '</td>';
                echo "<td  width='10%'>" . '<a href=cat.php?op=modify&amp;cat_id=' . $event['cat']['cat_id'] . '&form_modify' . '>' . $event['cat']['cat_name'] . '</a>' . '</td>';

                echo '<td>' . '<a href=event.php?op=modify&amp;event_id=' . $event['event_id'] . '>' . $event['event_title'] . '</a>' . '</td>';

                //                 if ($event['event_isrecur']) {
                //                     echo '<td>' . $event['formated_reccur_rule'] . '</td>';
                //                 } else {
                //                     echo '<td>' . $event['formated_event_start'] . '</td>';
                //                 }

                echo "<td align = 'center' width='10%'>" . $event['formated_event_start'] . '</td>';
                echo "<td align = 'center' width='10%'>" . $event['formated_event_end'] . '</td>';
                echo '<td align="center">' . ((1 == $event['event_isrecur']) ? _YES : _NO) . '</td>';
                if (!isset($event['formated_reccur_rule'])) {
                    $event['formated_reccur_rule'] = '';
                }
                echo '<td>' . $event['formated_reccur_rule'] . '</td>';

                echo '<td style="width:10%; text-align:center;">';
                echo '<a href=event.php?op=modify&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/edit.png' title='" . _AM_EXTCAL_ICONE_EDIT . "'></a>&nbsp;&nbsp;";
                echo '<a href=event.php?op=delete&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/delete.png' title='" . _AM_EXTCAL_ICONE_DELETE . "'></a>&nbsp;&nbsp;";
                echo '<a href=event.php?op=clone&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/editcopy.png' title='" . _AM_EXTCAL_ICONE_CLONE . "'></a>";
                echo '</td>';

                echo '</tr>';
            }
            //---------------------------------------------------------
            $pageNav = new \XoopsPageNav($eventHandler->getCountNewEvent(), $nbEventsByPage, $start);

            echo '<tr><td colspan="2" style="text-align: right;">';
            echo $pageNav->renderNav(2);
            echo '</td>';

            echo '<td colspan="2" style="text-align: right;">';

            echo '<input type="submit" value="' . _AM_EXTCAL_DELETE_ALL . '" name="deleteSelection[0]">';
            echo '<input type="submit" value="' . _AM_EXTCAL_DELETE_SELECTION . '" name="deleteSelection[1]">';

            echo '</td>';
            echo '</tr>';
        } else {
            echo '<tr><td colspan="5">' . _AM_EXTCAL_NO_PENDING_EVENT . '</td></tr>';
        }
        echo '</table>';
        echo '</form>';

        echo '</fieldset>';
        echo '</fieldset><br><br>';
        //Fin de la liste des evennement -------------------------------------
        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _MD_EXTCAL_SUBMIT_EVENT . '</legend>';

        $form = $eventHandler->getEventForm('admin');
        $form->display();

        echo '</fieldset>';

        require_once __DIR__ . '/admin_footer.php';

        break;
}
