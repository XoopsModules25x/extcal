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

global $extcalConfig, $xoopsUser;
require_once __DIR__ . '/../include/constantes.php';
// require_once __DIR__ . '/../class/Utility.php';
// require_once __DIR__ . '/../class/tableForm.php';
//---------------------------------------------------------------------------
/**
 * @param $options
 *
 * @return array
 */
function bExtcalMinicalShow($options)
{
    global $extcalConfig, $xoopsUser;

    extcal_getDefautminicalOption($options);

    //    // require_once __DIR__ . '/../class/config.php';

    require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Util/Textual.php';
    require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Month/Weeks.php';
    require_once _EXTCAL_PEAR_CALENDAR_ROOT . '/Day.php';
    //     require_once CALENDAR_ROOT . 'Month/Weeks.php';
    //     require_once CALENDAR_ROOT . 'Day.php';

    // Retriving Image for block if enabled
    if (1 == $options[0]) {
        $imageHandler = xoops_getHandler('image');
        $criteria     = new \Criteria('imgcat_id', $options[1]);
        $criteria->setSort('RAND()');
        $criteria->setLimit($options[6]);
        $images         = $imageHandler->getObjects($criteria);
        $slideShowParam = [
            'images'      => $images,
            'frameHeight' => $options[3],
            'frameWidth'  => $options[2],
            'transTime'   => $options[4],
            'pauseTime'   => $options[5],
        ];
        if (count($images) > 0) {
            _makeXMLSlideshowConf($slideShowParam);
            $imageParam = ['displayImage' => true];
        } else {
            $imageParam = ['displayImage' => false];
        }
    } else {
        $imageParam = ['displayImage' => false];
    }
    $imageParam['frameHeight'] = $options[3];
    $imageParam['frameWidth']  = $options[2];

    // Retriving module config
    //     $extcalConfig = Extcal\Config::getHandler();
    //$xoopsModuleConfig = $extcalConfig->getModuleConfig();
    //----------------------------------------------------
    //recupe de xoopsmoduleConfig
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('extcal');
    $configHandler = xoops_getHandler('config');
    if ($module) {
        $extcalConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    }
    //----------------------------------------------------
    // Getting eXtCal object's handler
    //    $catHandler = xoops_getModuleHandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
    $catHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);

    $cats = $catHandler->getAllCatById($xoopsUser);
    // $t = print_r($cats,true);
    // echo "zzz<pre>{$t}</pre>";

    $eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
    $timeHandler  = Extcal\Time::getHandler();

    // Retriving month and year value according to block options
    //modif JJD
    $offset      = $extcalConfig['offsetMinical'];
    $monthToShow = mktime(0, 0, 0, date('m') + $offset, date('d'), date('Y'));
    $month       = date('n', $monthToShow);
    $year        = date('Y', $monthToShow);

    //----------------------------------------------------
    $month += $options[7]; //ajout  ou retrait du nombre de mois de décalage
    if ($month < 1) {
        $month = 12;
        --$year;
    } elseif ($month > 12) {
        $month = 1;
        ++$year;
    }
    //----------------------------------------------------

    // Saving display link preference
    $displayLink = $options[8];

    // Delete options to keep only categorie data
    $tCatSelected = explode(',', $options[9]);

    /***************************************************************/
    // Retriving events and formatting them
    //$events = $eventHandler->objectToArray($eventHandler->getEventCalendarMonth($month, $year, $tCatSelected));
    if (true) {
        $criteres = [
            'periode'      => _EXTCAL_EVENTS_MONTH,
            'month'        => $month,
            'year'         => $year,
            'category'     => $tCatSelected,
            'externalKeys' => 'cat_id',
        ];
        $events   = $eventHandler->getEventsOnPeriode($criteres);
    } else {
        $events = [];
    }
    //Extcal\Utility::echoArray($events, 'minical');
    /***************************************************************/
    //$eventHandler->formatEventDate($events, "l dS \of F Y h:i A");

    // Calculating timestamp for the begin and the end of the month
    $startMonth = mktime(0, 0, 0, $month, 1, $year);
    $endMonth   = mktime(23, 59, 59, $month + 1, 0, $year);

    /*
     *  Adding all event occuring during this month to an array indexed by day number
     */
    $eventsArray = [];
    foreach ($events as $event) {
        //echo "id->{$event['event_id']}<br>";
        bExtcalMinicalAddEventToArray($event, $eventsArray, $timeHandler, $startMonth, $endMonth, $cats);
        //         if (!$event['event_isrecur']) {
        //             bExtcalMinicalAddEventToArray($event, $eventsArray, $timeHandler, $startMonth, $endMonth, $cats);
        //         } else {
        //
        //             $recurEvents = $eventHandler->getRecurEventToDisplay($event, $startMonth, $endMonth);
        //             foreach ($recurEvents as $recurEvent)
        //             {
        //                 bExtcalMinicalAddEventToArray($recurEvent, $eventsArray, $timeHandler, $startMonth, $endMonth, $cats);
        //             }
        //         }
    }
    //Extcal\Utility::echoArray($eventsArray);

    /*
     *  Making an array to create tabbed output on the template
     */
    // Flag current day
    $selectedDays = [
        new Calendar_Day(
            date('Y', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($GLOBALS['xoopsUser']))),
            date('n', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($GLOBALS['xoopsUser']))),
                         date('j', xoops_getUserTimestamp(time(), $timeHandler->getUserTimeZone($GLOBALS['xoopsUser'])))
        ),
    ];

    // Build calendar object
    $monthCalObj = new Calendar_Month_Weeks($year, $month, $extcalConfig['week_start_day']);
    $monthCalObj->build();

    $tableRows = [];
    $rowId     = 0;
    $cellId    = 0;
    while ($weekCalObj = $monthCalObj->fetch()) {
        $weekCalObj->build($selectedDays);
        $tableRows[$rowId]['weekInfo'] = [
            'week'  => $weekCalObj->thisWeek('n_in_year'),
            'day'   => $weekCalObj->thisDay(),
            'month' => $monthCalObj->thisMonth(),
            'year'  => $monthCalObj->thisYear(),
        ];
        while ($dayCalObj = $weekCalObj->fetch()) {
            $tableRows[$rowId]['week'][$cellId] = [
                'isEmpty'    => $dayCalObj->isEmpty(),
                'number'     => $dayCalObj->thisDay(),
                'isSelected' => $dayCalObj->isSelected(),
            ];
            $day                                = $dayCalObj->thisDay();
            if (isset($eventsArray[$day]) && !$dayCalObj->isEmpty()) {
                $tableRows[$rowId]['week'][$cellId]['haveEvents'] = true;
                $tableRows[$rowId]['week'][$cellId]['color']      = $eventsArray[$day]['color'];
            } else {
                $tableRows[$rowId]['week'][$cellId]['haveEvents'] = false;
            }
            ++$cellId;
        }
        $cellId = 0;
        ++$rowId;
    }

    // Retriving weekdayNames
    //$loc_de = setlocale (LC_ALL, 'Fr');

    $weekdayNames = Calendar_Util_Textual::weekdayNames('one');
    //$weekdayNames=array('D','L','M','M','J','V','S');

    //     echo "<hr>L'identifiant de l'allemand sur ce système est '$loc_de'";
    //     echoArray($weekdayNames,true);

    for ($i = 0; $i < $extcalConfig['week_start_day']; ++$i) {
        $weekdayName    = array_shift($weekdayNames);
        $weekdayNames[] = $weekdayName;
    }

    // Making navig data
    $navig = [
        'page' => $extcalConfig['start_page'],
        'uri'  => 'year=' . $monthCalObj->thisYear() . '&amp;month=' . $monthCalObj->thisMonth(),
        'name' => $timeHandler->getFormatedDate($extcalConfig['nav_date_month'], $monthCalObj->getTimestamp()),
    ];

    $horloge             = [];
    $horloge['display']  = ('' != trim($options[11]));
    $horloge['fullName'] = XOOPS_URL . _EXTCAL_PATH_HORLOGES . $options[11];
    $horloge['width']    = $options[12] . 'px';
    $horloge['height']   = $options[13] . 'px';

    $ret = [
        'imageParam'   => $imageParam,
        'displayLink'  => $displayLink,
        'submitText'   => _MB_EXTCAL_SUBMIT_LINK_TEXT,
        'tableRows'    => $tableRows,
        'weekdayNames' => $weekdayNames,
        'navig'        => $navig,
        'horloge'      => $horloge,
        'bgColor'      => $options[10],
    ];

    // $t = print_r($horloge,true);
    // echo "<pre>{$t}</pre>";
    return $ret;
}

//---------------------------------------------------------------------------
/**
 * @param $options
 *
 * @return string
 */
function bExtcalMinicalEdit($options)
{
    //    // require_once __DIR__ . '/../class/form/spin/formspin.php';
    global $xoopsUser;

    //  $t = print_r(get_defined_vars(),true);
    // // $t = print_r($options,true);
    //  echo "<pre>{$t}</pre>";

    extcal_getDefautminicalOption($options);

    $xfValue = [];

    $form = new Extcal\TableForm(_OPTIONS, '', '');
    //$form->setExtra('enctype="multipart/form-data"');

    //============================================================

    $catHandler      = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
    $cats            = $catHandler->getAllCat($xoopsUser, 'extcal_cat_view');
    $imageCatHandler = xoops_getHandler('imagecategory');

    //=====================================================================
    $form->insertBreak('<div style="text-align: center;font-weight: bold;">' . _MB_EXTCAL_OPT_SLIDE_SHOW . '</div>', 'head');

    $k           = 0;
    $xfValue[$k] = new \XoopsFormRadio(_MB_EXTCAL_DISPLAY_IMG, "options[{$k}]", $options[$k]);
    $xfValue[$k]->addOption(1, _YES);
    $xfValue[$k]->addOption(0, _NO);

    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $imageCats = $imageCatHandler->getObjects();
    $t         = [];
    $t[0]      = _NONE;
    foreach ($imageCats as $cat) {
        $t[$cat->getVar('imgcat_id')] = $cat->getVar('imgcat_name');
    }

    $k           = 1;
    $xfValue[$k] = new \XoopsFormSelect(_MB_EXTCAL_IMG_CAT, "options[{$k}]", $options[$k], 1, false);
    $xfValue[$k]->addOptionArray($t);
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 2;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_SS_WIDTH, "options[{$k}]", $options[$k], 100, 250, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 3;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_SS_HEIGHT, "options[{$k}]", $options[$k], 100, 250, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 4;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_SS_TRANS_TIME, "options[{$k}]", $options[$k], 0, 12, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 5;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_SS_PAUSE_TIME, "options[{$k}]", $options[$k], 0, 12, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 6;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_SS_NB_PHOTOS, "options[{$k}]", $options[$k], 0, 50, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //=====================================================================
    $form->insertBreak('<div style="text-align: center;font-weight: bold;">' . _MB_EXTCAL_OPT_SHOW . '</div>', 'head');

    $t = [
        -1 => _MB_EXTCAL_PREVIEW,
        0  => _MB_EXTCAL_CURRENT,
        1  => _MB_EXTCAL_NEXT,
    ];

    $k           = 7;
    $xfValue[$k] = new \XoopsFormSelect(_MB_EXTCAL_DISPLAY_MONTH, "options[{$k}]", $options[$k], 1, false);
    $xfValue[$k]->addOptionArray($t);
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 8;
    $xfValue[$k] = new \XoopsFormRadio(_MB_EXTCAL_DISPLAY_SUBMIT_LINK, "options[{$k}]", $options[$k]);
    $xfValue[$k]->addOption(1, _YES);
    $xfValue[$k]->addOption(0, _NO);

    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    //for ($h=0;$h<8;++$h) array_shift($options);
    $t = [];
    foreach ($cats as $cat) {
        $t[$cat->getVar('cat_id')] = $cat->getVar('cat_name');
    }

    //function XoopsFormSelect($caption, $name, $value = null, $size = 1, $multiple = false)

    $k           = 9;
    $xfValue[$k] = new \XoopsFormSelect(_MB_EXTCAL_CAT_TO_USE, "options[{$k}]", explode(',', $options[$k]), 8, true);
    $xfValue[$k]->setDescription(_MB_EXTCAL_CAT_TO_USE_DESC);
    $xfValue[$k]->addOptionArray($t);
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 10;
    $xfValue[$k] = new \XoopsFormColorPicker(_MB_EXTCAL_BGCOLOR, "options[{$k}]", $options[$k]);
    $form->addElement($xfValue[$k], false);

    //=====================================================================
    $form->insertBreak('<div style="text-align: center;font-weight: bold;">' . _MB_EXTCAL_HORLOGE_OPT . '</div>', 'head');
    //---------------------------------------------------------------------
    $t = \XoopsLists::getFileListAsArray(XOOPS_ROOT_PATH . _EXTCAL_PATH_HORLOGES);
    $t = array_merge([' ' => _NONE], $t);

    $k           = 11;
    $xfValue[$k] = new \XoopsFormSelect(_MB_EXTCAL_HORLOGE, "options[{$k}]", $options[$k], 1, false);
    $xfValue[$k]->addOptionArray($t);
    $form->addElement($xfValue[$k], false);

    //---------------------------------------------------------------------
    $k           = 12;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_WIDTH, "options[{$k}]", $options[$k], 50, 250, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    $k           = 13;
    $xfValue[$k] = new Extcal\Form\Spin\FormSpin(_MB_EXTCAL_HEIGHT, "options[{$k}]", $options[$k], 50, 250, 1, 0, 8, _MB_EXTCAL_PX, $imgFolder = '');
    $form->addElement($xfValue[$k], false);
    //---------------------------------------------------------------------
    //=====================================================================
    return $form->render();
    /*
              $t = array();
              //while (list($key,$val) = each($xfValue)) {
                foreach ($xfValue as $key => $val) {
               $t[] = $val->render();
              }
             return implode("\n", $t);

            return extcal_buildHtmlArray($xfValue, _OPTIONS);
    */
}

/**************************************************************************/
/**
 * @param $options
 */
function _makeXMLSlideshowConf($options)
{

    // create a  new \XML document
    $doc               = new DomDocument('1.0');
    $doc->formatOutput = true;

    // create root node
    $root = $doc->createElement('slideshow');
    $root = $doc->appendChild($root);

    // Create config node
    $config = $doc->createElement('config');
    $config = $root->appendChild($config);

    // Add config param
    $frameHeight = $doc->createElement('frameHeight');
    $frameHeight = $config->appendChild($frameHeight);
    $value       = $doc->createTextNode($options['frameHeight']);
    $frameHeight->appendChild($value);

    $frameWidth = $doc->createElement('frameWidth');
    $frameWidth = $config->appendChild($frameWidth);
    $value      = $doc->createTextNode($options['frameWidth']);
    $frameWidth->appendChild($value);

    $transTime = $doc->createElement('transTime');
    $transTime = $config->appendChild($transTime);
    $value     = $doc->createTextNode($options['transTime']);
    $transTime->appendChild($value);

    $pauseTime = $doc->createElement('pauseTime');
    $pauseTime = $config->appendChild($pauseTime);
    $value     = $doc->createTextNode($options['pauseTime']);
    $pauseTime->appendChild($value);

    // Add photos node
    $photos = $doc->createElement('photos');
    $photos = $root->appendChild($photos);

    $tempPhoto = $doc->createElement('photo');
    $tempSrc   = $doc->createElement('src');
    foreach ($options['images'] as $images) {
        // $photo = $doc->createElement('photo');
        $photo = clone $tempPhoto;
        $photo = $photos->appendChild($photo);

        // $src   = $doc->createElement('src');
        $src   = clone $tempSrc;
        $src   = $photo->appendChild($src);
        $value = $doc->createTextNode(XOOPS_URL . '/uploads/' . $images->getVar('image_name'));
        $src->appendChild($value);
    }

    // get completed xml document
    $xml_string = $doc->save(XOOPS_ROOT_PATH . '/cache/extcalSlideShowParam.xml');
}

/**************************************************************************/
/**
 * @param array      $event
 * @param array      $eventsArray
 * @param Extcal\Time       $timeHandler
 * @param            $startMonth
 * @param            $endMonth
 * @param            $cats
 *
 * @return bool
 */
function bExtcalMinicalAddEventToArray($event, &$eventsArray, $timeHandler, $startMonth, $endMonth, $cats)
{
    // Extcal\Utility::echoArray($event);
    // exit;
    // $d1 = date("j, m, Y", $startMonth);
    // $d2 = date("j, m, Y", $endMonth);
    // echo "bExtcalMinicalAddEventToArray : {$d1} - {$d2}<br>";
    // $d1 = date("j, m, Y", $event['event_start']);
    // $d2 = date("j, m, Y", $event['event_end']);
    // echo "bExtcalMinicalAddEventToArray : {$d1} - {$d2}<br>";

    //     $color  = $cats[$event['cat_id']]['cat_color'];
    //     $weight = $cats[$event['cat_id']]['cat_weight'];
    $color  = $event['cat']['cat_color'];
    $weight = $event['cat']['cat_weight'];

    // Calculating the start and the end of the event
    $startEvent = xoops_getUserTimestamp($event['event_start'], $timeHandler->getUserTimeZone($GLOBALS['xoopsUser']));
    $endEvent   = xoops_getUserTimestamp($event['event_end'], $timeHandler->getUserTimeZone($GLOBALS['xoopsUser']));
    // Extcal\Utility::echoTsu($event['event_start'],"event['event_start']");
    // Extcal\Utility::echoTsu($event['event_end'],"event['event_end']");

    //---------------------------------------------------------------
    if ($startEvent < $startMonth) {
        $firstDay = 1;
    } elseif ($startEvent > $endEvent) {
        return false;
    } else {
        $firstDay = date('j', $startEvent);
    }

    if ($endEvent < $startMonth) {
        return false;
    } elseif ($endEvent > $endMonth) {
        $lastDay = date('j', $endMonth);
    } else {
        $lastDay = date('j', $endEvent);
    }

    //echo "first dat - last day : {$firstDay} - {$lastDay}<br>";
    //echo $event['event_id'] . '-' . $weight .'<br>';

    $d = date('j', $event['event_start']);
    if (isset($eventsArray[$d])) {
        if ($weight > $eventsArray[$d]['weight']) {
            $eventsArray[$d]['weight'] = $weight;
            $eventsArray[$d]['color']  = $color;
        }
    } else {
        $eventsArray[$d]['hasEvent'] = true;
        $eventsArray[$d]['weight']   = $weight;
        $eventsArray[$d]['color']    = $color;
    }
    /*
            for ($i = $firstDay; $i <= $lastDay; ++$i) {
                }

                $eventsArray[$i]['hasEvent'] = true;
                if (isset($eventsArray[$i]['weight'])) {
                  if ($weight > $eventsArray[$i]['weight']) {
                    $eventsArray[$i]['weight'] = $weight;
                    $eventsArray[$i]['color'] = $color;
                  }
                } else {
                  $eventsArray[$i]['weight'] = $weight;
                  $eventsArray[$i]['color'] = $color;
              }
            }
    */

    // $t = print_r($cats,true);
    // echo "<pre>{$t}</pre><hr>";
    //
    // $t = print_r($eventsArray,true);
    // echo "event id = {$event['event_id']} - weight = {$weight}<br>color = {$color}<br><pre>{$t}</pre><hr>";
}

/**
 * @param $options
 */
function extcal_getDefautminicalOption(&$options)
{
    // 0|0|150|225|1|3|10|0|1|1,2|| |120|120
    for ($h = 0; $h <= 13; ++$h) {
        if (!isset($options[$h])) {
            switch ($h) {
                case  0:
                    $options[$h] = '0';
                    break;
                case  1:
                    $options[$h] = 0;
                    break;
                case  2:
                    $options[$h] = '150';
                    break;
                case  3:
                    $options[$h] = '255';
                    break;
                case  4:
                    $options[$h] = '1';
                    break;
                case  5:
                    $options[$h] = '3';
                    break;
                case  6:
                    $options[$h] = '10';
                    break;
                case  7:
                    $options[$h] = '0';
                    break;
                case  8:
                    $options[$h] = '1';
                    break;
                case  9:
                    $options[$h] = '1,2,3,4,5';
                    break;
                case 10:
                    $options[$h] = '';
                    break;
                case 11:
                    $options[$h] = '';
                    break;
                case 12:
                    $options[$h] = 120;
                    break;
                case 13:
                    $options[$h] = 120;
                    break;
            }
        }
    }
}
