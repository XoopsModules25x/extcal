<?php

include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once dirname(__DIR__) . '/class/form/extcalform.php';
include_once __DIR__ . '/admin_header.php';

include_once XOOPS_ROOT_PATH . '/modules/extcal/include/functions.php';

$gepeto = array_merge($_GET, $_POST);
while (list($k, $v) = each($gepeto)) {
    $$k = $v;
}
if (!isset($op)) {
    $op = '';
}
//--------------------------------------------------------------------
/**
 * @param $ids
 *
 * @return bool
 */
function deleteEvents($ids)
{
    $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
    $criteria     = new Criteria('event_id', "($ids)", 'IN');

    //Supression des images
    $rst = $eventHandler->getAllEvents($criteria);

    while (list($k, $t) = each($rst)) {
        if ($t['event_picture1'] != '') {
            $f = XOOPS_ROOT_PATH . '/uploads/extcal/' . $t['event_picture1'];
            unlink($f);
            echo $f . '<br>';
        }

        if ($t['event_picture2'] != '') {
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

        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $fileHandler  = xoops_getmodulehandler(_EXTCAL_CLS_FILE, _EXTCAL_MODULE);
// $t = print_r($_POST,true);
// echo "<pre>{$t}</pre><br>";
// exit;
        // If the date format is wrong
//        if (
//            !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_start']['date'])
//                || !preg_match(_EXTCAL_MOTIF_DATE, $_POST['event_end']['date'])
//        ) {
//            redirect_header(
//                'event.php', 3, _MD_EXTCAL_WRONG_DATE_FORMAT . "<br />"
//                . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())
//            );
//            exit;
//        }

//exit;
        ///////////////////////////////////////////////////////////////////////////////
        extcal_loadImg($_REQUEST, $event_picture1, $event_picture2);
        ///////////////////////////////////////////////////////////////////////////////
        $data = array(
            'event_title'         => $_POST['event_title'],
            'cat_id'              => $_POST['cat_id'],
            'event_desc'          => $_POST['event_desc'],
            'event_nbmember'      => $_POST['event_nbmember'],
            'event_organisateur'  => $_POST['event_organisateur'],
            'event_contact'       => $_POST['event_contact'],
            'event_url'           => $_POST['event_url'],
            'event_email'         => $_POST['event_email'],
            'event_address'       => $_POST['event_address'],
            'event_approved'      => 1,
            'event_start'         => $_POST['event_start'],
            'have_end'            => $_POST['have_end'],
            'event_end'           => $_POST['event_end'],
            'event_picture1'      => @$event_picture1,
            'event_picture2'      => @$event_picture2,
            'event_price'         => @$_POST['event_price'],
            'event_etablissement' => $_POST['event_etablissement'],
            'dohtml'              => $extcalConfig['allow_html'],
            'event_icone'         => $_POST['event_icone']
        );

        // Event edited
        if (isset($_POST['event_id'])) {

            if (!$eventHandler->modifyEvent($_POST['event_id'], $data)) {
                redirect_header("event.php", 3, _AM_EXTCAL_EVENT_EDIT_FAILED, false);
            } else {
                $fileHandler->createFile(intval($_POST['event_id']));
                redirect_header("event.php", 3, _AM_EXTCAL_EVENT_EDITED, false);
            }

            // New event
        } else {
            $notificationHandler =& xoops_gethandler('notification');
            $catHandler          = xoops_getmodulehandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);

            $data['event_submitter']  = ($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
            $data['event_submitdate'] = time();

            if ($eventHandler->createEvent($data, $_POST)) {

                $fileHandler->createFile($eventHandler->getInsertId());
                $cat = $catHandler->getCat($_POST['cat_id'], $xoopsUser, 'all');
                $notificationHandler->triggerEvent('global', 0, 'new_event', array('EVENT_TITLE' => $_POST['event_title']));
                $notificationHandler->triggerEvent('cat', $_POST['cat_id'], 'new_event_cat', array('EVENT_TITLE' => $_POST['event_title'], 'CAT_NAME' => $cat->getVar('cat_name')));
                redirect_header("event.php", 3, _AM_EXTCAL_EVENT_CREATED, false);
            } else {
                redirect_header("event.php", 3, _AM_EXTCAL_EVENT_CREATE_FAILED, false);
            }

        }
        break;

    case 'clone': /* sur validation du formulaire */
    case 'modify':
        $action = (($op == 'clone') ? 'clone' : 'edit');
        xoops_cp_header();
//================================================
// include_once (XOOPS_ROOT_PATH . '/class/xoopsform/tc_calendar/formtccalendar.php');
//
// 		  // Call the calendar constructor - use the desired form and format, according to the instructions/samples provided on triconsole.com
// 		  $dateBirthday = new XoopsTcCalendar("datez1", true, false);
// 		  //$dateBirthday->setIcon("/images/iconCalendar.gif");
// 		  $dateBirthday->setIcon("/class/xoopsform/tc_calendar/images/iconCalendar.gif");
// 		  //$dateBirthday->rtl=false;
// 		  $dateBirthday->setAutoHide(false);
//
//       //$myCalendar->setDate(date('d'), date('m'), date('Y'));
//       //$dateBirthday->setDate($p['date1_day'], $p['date1_month'], $p['date1_year']);
// 		  $dateBirthday->setDate(date('d'), date('m'), date('Y'));
//
// 		  $dateBirthday->setPath(XOOPS_URL . "/class/xoopsform/tc_calendar/");
// 		  $dateBirthday->zindex = 150; //default 1
// 		  $dateBirthday->setYearInterval(1995, date('Y'));
// 		  $dateBirthday->dateAllow('1960-03-01', date('Y-m-d'));
// 		  //$dateBirthday->autoSubmit(true, "calendar");
// 		  $dateBirthday->disabledDay("sat");
// 		  $dateBirthday->disabledDay("sun");
// 		  $dateBirthday->setSpecificDate(array("2011-04-14", "2010-12-25"), 0, 'month');
// 		  $dateBirthday->setSpecificDate(array("2011-04-01"), 0, 'year');
// 		  $dateBirthday->setAlignment('right', 'bottom'); //optional
// echo "<table><tr><td>zzzzz</td><td></td><td>";
// echo $dateBirthday->render();
// echo "</td></tr></table>";
//echo $dateBirthday->render();
//================================================
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
//         include_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
        $eventAdmin = new ModuleAdmin();
        echo $eventAdmin->addNavigation('event.php');
        //***************************************************************************************

        $eventId      = $_GET['event_id'];
        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _MD_EXTCAL_EDIT_EVENT . '</legend>';

        if ($form = $eventHandler->getEventForm('admin', $action, array('event_id' => $eventId))) {
            $form->display();
        }

        echo '</fieldset><br />';

        xoops_cp_footer();

        break;

    case 'clone2': /* sur clique de l'icone du formulaire*/

        //$newEventId = 1;
        $eventId      = $_GET['event_id'];
        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $event        = $eventHandler->getEvent($eventId);
        $t            = $event->getVars();
        $data         = array();
        while (list($key, $val) = each($t)) {
            $data[$key] = $val['value'];
        }

        $data['event_id'] = 0;
        $data['event_title'] .= ' (' . _AM_EXTCAL_CLONE_OF . $eventId . ')';

        $newEvent = $eventHandler->create();
        $newEvent->setVars($data);
        $t = $eventHandler->insert($newEvent, true);

        $newEventId = $newEvent->getVar('event_id');
        $ts         = print_r($newEventId, true);

        redirect_header("event.php?op=modify&event_id={$newEventId}", 3, _AM_EXTCAL_EVENT_DELETED, false);
        break;

    case 'delete':

        if (isset($_POST['confirm'])) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header(
                    'index.php',
                    3,
                    _NOPERM . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())
                );
                exit;
            }
//             $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
//             $eventHandler->deleteEvent($_POST['event_id']);
            deleteEvents($_POST['event_id']);
            redirect_header("event.php", 3, _AM_EXTCAL_EVENT_DELETED, false);

        } else {
            xoops_cp_header();
            // @author      Gregory Mage (Aka Mage)
            //***************************************************************************************
            //include_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
            $eventAdmin = new ModuleAdmin();
            echo $eventAdmin->addNavigation('event.php');
            //***************************************************************************************

            $hiddens = array('event_id' => $_GET['event_id'], 'form_delete' => '', 'confirm' => 1);
            xoops_confirm($hiddens, 'event.php?op=delete', _AM_EXTCAL_CONFIRM_DELETE_EVENT, _DELETE, 'event.php');

            xoops_cp_footer();
        }

        break;

    case 'deleteSelection':

        xoops_cp_header();
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************
        //include_once XOOPS_ROOT_PATH . "/modules/extcal/class/admin.php";
        $eventAdmin = new ModuleAdmin();
        echo $eventAdmin->addNavigation('event.php');
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
        $hiddens = array('event_ids' => $ids, 'form_delete' => '', 'confirm' => 1);
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
                redirect_header(
                    'index.php',
                    3,
                    _NOPERM . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())
                );
                exit;
            }

            deleteEvents($_POST['event_ids']);

            redirect_header("event.php", 3, _AM_EXTCAL_EVENTS_DELETED, false);

        }

        break;

    case 'default':
    default:

        //global $extcalConfig;
        $extcalConfig      = ExtcalConfig::getHandler();
        $xoopsModuleConfig = $extcalConfig->getModuleConfig();

        $start          = (isset($_GET['start'])) ? $_GET['start'] : 0;
        $nbEventsByPage = $xoopsModuleConfig['nbEventsByPage'];

        xoops_cp_header();
        // @author      Gregory Mage (Aka Mage)
        //***************************************************************************************

        $eventAdmin = new ModuleAdmin();
        echo $eventAdmin->addNavigation('event.php');
        //***************************************************************************************

        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $events       = $eventHandler->objectToArray($eventHandler->getNewEvent($start, $nbEventsByPage, 0, true), array('cat_id'));
        $eventHandler->formatEventsDate($events, _SHORTDATESTRING);

        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _AM_EXTCAL_APPROVED_EVENT . '</legend>';
        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _AM_EXTCAL_INFORMATION . '</legend>';
        //echo'<img src='. XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/assets/images/action/edit.png' .' '.'style=vertical-align:middle;/>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_EDIT . '<br />';
        //echo'<img src='. XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/assets/images/action/delete.png'. ' '."style=vertical-align:middle;/>&nbsp;&nbsp;". _AM_EXTCAL_INFO_DELETE;

        echo '<img src=' . $pathIcon16 . '/edit.png' . ' ' . 'style=vertical-align:middle;/>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_EDIT . '<br />';
        echo '<img src=' . $pathIcon16 . '/delete.png' . ' ' . 'style=vertical-align:middle;/>&nbsp;&nbsp;' . _AM_EXTCAL_INFO_DELETE . '<br />';

        echo '</fieldset><br />';

        echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">' . _MD_EXTCAL_SUBMITED_EVENT . '</legend>';

        echo '<form method="POST" action="event.php">';
        echo '<input type="hidden" name="op" value="deleteSelection" />';

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
            foreach (
                $events as $event
            ) {
                $class = (++$i % 2 == 0) ? 'even' : 'odd';
                echo '<tr style="text-align:left;" class="' . $class . '">';
                echo "<td width='10%' align='center'>";
                echo "<input type='checkbox' name='deleteEvents[{$event['event_id']}]' value='1' >";
                echo "<input type='hidden' name='deleteAllEvents[{$event['event_id']}]' value='1' />";
                echo "</td>";
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
                echo '<td align="center">' . (($event['event_isrecur'] == 1) ? _YES : _NO) . '</td>';
                if (!isset($event['formated_reccur_rule'])) {
                    $event['formated_reccur_rule'] = '';
                }
                echo '<td>' . $event['formated_reccur_rule'] . '</td>';

                echo '<td style="width:10%; text-align:center;">';
                echo '<a href=event.php?op=modify&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/edit.png' title='" . _AM_EXTCAL_ICONE_EDIT . "' /></a>&nbsp;&nbsp;";
                echo '<a href=event.php?op=delete&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/delete.png' title='" . _AM_EXTCAL_ICONE_DELETE . "' /></a>&nbsp;&nbsp;";
                echo '<a href=event.php?op=clone&amp;event_id=' . $event['event_id'] . "><img src='" . $pathIcon16 . "/editcopy.png' title='" . _AM_EXTCAL_ICONE_CLONE . "' /></a>";
                echo '</td>';

                echo '</tr>';
            }
            //---------------------------------------------------------
            $pageNav = new XoopsPageNav($eventHandler->getCountNewEvent(), $nbEventsByPage, $start);

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
        echo '</fieldset><br /><br />';
        //Fin de la liste des evennement -------------------------------------
        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _MD_EXTCAL_SUBMIT_EVENT . '</legend>';

        $form = $eventHandler->getEventForm('admin');
        $form->display();

        echo '</fieldset>';

        include_once __DIR__ . '/admin_footer.php';

        break;
}
