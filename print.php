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
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

use XoopsModules\Extcal;

include __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constantes.php';

require_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/calendar.php';

if (!isset($_GET['event'])) {
    $eventId = 0;
} else {
    $eventId = (int)$_GET['event'];
}
$eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
$event        = $eventHandler->objectToArray($eventHandler->getEvent($eventId), ['cat_id']);

//adding location
/** @var Extcal\EtablissementHandler $locationHandler */
$locationHandler = Extcal\Helper::getInstance()->getHandler('Etablissement');
if ($event['event_etablissement'] > 0) {
    $location = $locationHandler->objectToArray($locationHandler->getEtablissement($event['event_etablissement'], true));
}

// Adding formated date for start and end event
$eventHandler->formatEventDate($event, $xoopsModuleConfig['event_date_event']);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
echo '<head>' . "\n";
echo '<meta http-equiv="content-type" content="text/html; charset=' . _CHARSET . '">' . "\n";
echo '<title>' . $event['cat']['cat_name'] . ' - ' . $event['event_title'] . '</title>' . "\n";
echo '</head>' . "\n";
echo '<body onload="window.print()">' . "\n";
echo '<table style="border:1px solid black; width:640px;" cellspacing="0" cellspadding="0">' . "\n";
echo '<tr>' . "\n";
echo '<td colspan="2" style="font-size:1.2em; border:1px solid black;">' . "\n";
echo $event['event_title'] . "\n";
echo '</td>' . "\n";
echo '</tr>' . "\n";
echo '<tr>' . "\n";
echo '<td style="width:50%; border:1px solid black;">' . "\n";
echo '<b>' . $event['cat']['cat_name'] . '</b><br>' . "\n";
echo '<span style="font-weight:normal;">' . $event['cat']['cat_desc'] . '</span>' . "\n";
echo '</td>' . "\n";
echo '<td style="border:1px solid black;">' . "\n";
if (!$event['event_isrecur']) {
    echo '<b>' . _MD_EXTCAL_START . ' :</b> <span style="font-weight:normal;">' . $event['formated_event_start'] . '</span><br>' . "\n";
    echo '<b>' . _MD_EXTCAL_END . ' :</b> <span style="font-weight:normal;">' . $event['formated_event_end'] . '</span>' . "\n";
} else {
    echo '<b>' . _MD_EXTCAL_RECCUR_RULE . ' :</b> <span style="font-weight:normal;">' . $event['formated_reccur_rule'] . '</span>' . "\n";
}
echo '</td>' . "\n";
echo '</tr>' . "\n";

echo '<tr>' . "\n";
if ('' != $event['event_desc']) {
    echo '<td style="border:1px solid black;">' . $event['event_desc'] . '</td>' . "\n";
}
if ('' != $event['event_price']) {
    echo '<td style="border:1px solid black;">' . _MD_EXTCAL_ETABLISSEMENT_PRICE . $event['event_price'] . ' ' . _MD_EXTCAL_DEVISE2 . '</td>' . "\n";
}
echo '</tr>' . "\n";

//show contact info
if ('' != $event['event_contact']) {
    echo '<tr>' . "\n";
    echo '<td style="border:1px solid black;">' . "\n";
    echo '<b>' . _MD_EXTCAL_CONTACT_INFO . '</b><br>' . "\n";
    echo '<span style="font-weight:normal;">' . $event['event_organisateur'] . '<br>' . "\n";
    echo '<span style="font-weight:normal;">' . $event['event_contact'] . '<br>' . "\n";
    echo $event['event_address'] . '</span>' . "\n";
    echo '</td>' . "\n";
    echo '<td style="border:1px solid black;">' . "\n";
    echo '<b>' . _MD_EXTCAL_EMAIL . ' :</b> <a href="mailto:' . $event['event_email'] . '">' . $event['event_email'] . '</a><br>' . "\n";
    echo '<b>' . _MD_EXTCAL_URL . ' :</b> <a href="' . $event['event_url'] . '">' . $event['event_url'] . '</a>' . "\n";
    echo '</td>' . "\n";
    echo '</tr>' . "\n";
}

//show location
if ($event['event_etablissement'] = 0) {
    echo '<tr>' . "\n";

    //    echo($location['nom']);
    //    var_dump($location);
    //    var_dump($event);

    echo '<td style="border:1px solid black;">' . "\n";
    echo '<b>' . _MD_EXTCAL_ETABLISSEMENT . '</b>' . "\n";
    if ('' != $location['categorie']) {
        echo '<span style="font-weight:normal;"> (' . $location['categorie'] . ') <br>' . "\n";
    }
    if ('' != $location['logo']) {
        echo '<img align=right style="border:1px solid #FFFFFF;margin-right:6px" src=' . XOOPS_URL . '/uploads/extcal/etablissement/' . $location['logo'] . ' height="75px">' . '' . "\n";
    }

    echo '<span style="font-weight:normal;">' . $location['nom'] . '<br>' . "\n";
    echo $location['description'] . '<br>' . "\n";
    echo $location['adresse'] . '</span> <br>' . "\n";
    if ('' != $location['adresse2']) {
        echo $location['adresse2'] . '</span> <br>' . "\n";
    }
    echo $location['ville'] . "\n";
    echo $location['cp'] . '<br>' . "\n";

    if ('' != $location['horaires']) {
        echo $location['horaires'] . '</span> <br>' . "\n";
    }

    if ('' != $location['divers']) {
        echo $location['divers'] . '</span> <br>' . "\n";
    }

    if ('' != $location['tarifs']) {
        echo $location['tarifs'] . '</span> ' . _MD_EXTCAL_DEVISE2 . "\n";
    }

    echo '</td>' . "\n";
    echo '<td style="border:1px solid black;">' . "\n";

    if ('' != $location['tel_fixe']) {
        echo '<b>' . _MD_EXTCAL_ETABLISSEMENT_TEL_FIXE . ' :</b>' . $location['tel_fixe'] . '<br>' . "\n";
    }
    if ('' != $location['tel_portable']) {
        echo '<b>' . _MD_EXTCAL_ETABLISSEMENT_TEL_PORTABLE . ' :</b>' . $location['tel_portable'] . '<br>' . "\n";
    }

    echo '<b>' . _MD_EXTCAL_EMAIL . ' :</b> <a href="mailto:' . $location['mail'] . '">' . $location['mail'] . '</a><br>' . "\n";
    echo '<b>' . _MD_EXTCAL_URL . ' :</b> <a href="' . $location['site'] . '">' . $location['site'] . '</a>' . '<br>' . "\n";
    echo '<b>' . _MD_EXTCAL_ETABLISSEMENT_MAP . ' :</b> <a href="' . $location['map'] . '">' . _MD_EXTCAL_ETABLISSEMENT_MAP2 . '</a>' . "\n";

    echo '</td>' . "\n";
    echo '</tr>' . "\n";
}
//show images

if (('' != $event['event_picture1']) || ('' != $event['event_picture2'])) {
    echo '<tr>' . "\n";
    if ('' != $event['event_picture1']) {
        echo '<td style="border:1px solid black;">' . "\n";
        echo '<img align=left style="border:1px solid #FFFFFF;margin-right:6px" src=' . XOOPS_URL . '/uploads/extcal/' . $event['event_picture1'] . ' height="100px">' . '' . "\n";
        echo '</td>' . "\n";
    }
    if ('' != $event['event_picture2']) {
        echo '<td style="border:1px solid black;">' . "\n";
        echo '<img align=left style="border:1px solid #FFFFFF;margin-right:6px" src=' . XOOPS_URL . '/uploads/extcal/' . $event['event_picture2'] . ' height="100px">' . '' . "\n";
        echo '</td>' . "\n";
    }
    echo '</tr>' . "\n";
}
//--------------------

echo '</table><br>' . "\n";
echo '<div style="text-align:center; width:640px;">';
echo $xoopsConfig['sitename'] . ' - ' . $xoopsConfig['slogan'] . '<br>';
echo '<a href="' . XOOPS_URL . '/modules/extcal/event.php?event=' . $event['event_id'] . '">' . XOOPS_URL . '/modules/extcal/event.php?event=' . $event['event_id'] . '</a>';
echo '</div>';
echo '</body>' . "\n";
echo '</html>' . "\n";
