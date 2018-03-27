<?php namespace XoopsModules\Extcal;

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

//use Punic\Exception;
use XoopsModules\Extcal;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/../include/constantes.php';

/**
 * Class EventHandler.
 */
class EventHandler extends ExtcalPersistableObjectHandler
{
    private $extcalPerm;
    private $extcalTime;
//    private $extcalConfig;

    /**
     * @param $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        $this->extcalPerm = Extcal\Perm::getHandler();
        $this->extcalTime = Extcal\Time::getHandler();
//        $this->extcalConfig = Extcal\Config::getHandler();
        parent::__construct($db, 'extcal_event', Event::class, 'event_id');
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function createEvent($data)
    {
        $event = $this->create();
        $this->checkDate($data);
        $this->userTimeToServerTime($data);
        $this->addRecurValue($data);
        $event->setVars($data);

        return $this->insert($event, true);
    }

    /**
     * @param $data
     *
     * @return \XoopsObject
     */
    public function createEventForPreview($data)
    {
        $event = $this->create();
        $this->checkDate($data);
        $this->addRecurValue($data);
        $event->setVars($data);

        return $event;
    }

    /**
     * @param $eventId
     * @param $data
     *
     * @return bool
     */
    public function modifyEvent($eventId, $data)
    {
        $event = $this->get($eventId);
        $this->checkDate($data);
        $this->userTimeToServerTime($data);
        $this->addRecurValue($data);
        $event->setVars($data);

        return $this->insert($event);
    }

    /**
     * @param $eventId
     */
    public function deleteEvent($eventId)
    {
        /* TODO :
           - Delete who's going
           - Delete who's not going
           - Delete comment
           - Delete notifications
          */
        $this->deleteById($eventId, true);
    }

    /**
     * @param null $criteria
     * @param bool $force
     * @param bool $asObject
     */
    public function deleteAllEvents($criteria = null, $force = true, $asObject = false)
    {
        /* TODO :
           - Delete who's going
           - Delete who's not going
           - Delete comment
           - Delete notifications
          */
        $this->deleteAll($criteria, $force, $asObject);
    }

    /**
     * @param null $criteria
     * @param bool $asObject
     *
     * @return array
     */
    public function getAllEvents($criteria = null, $asObject = false)
    {
        $rst =& $this->getObjects($criteria, $asObject);
        if ($asObject) {
            return $rst;
        } else {
            return $this->objectToArray($rst);
        }
    }

    // Return one approved event selected by his id

    /**
     * @param      $eventId
     * @param bool $skipPerm
     *
     * @return bool
     */
    public function getEvent($eventId, $skipPerm = false)
    {
        $user = $GLOBALS['xoopsUser'];

        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('event_id', $eventId));
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        if (!$skipPerm) {
            $this->addCatPermCriteria($criteriaCompo, $user);
        }
        $ret =& $this->getObjects($criteriaCompo);
        if (isset($ret[0])) {
            return $ret[0];
        } else {
            return false;
        }
    }

    // Return one event selected by his id (approve or not)

    /**
     * @param      $eventId
     * @param bool $skipPerm
     *
     * @return bool
     */
    public function getEventWithNotApprove($eventId, $skipPerm = false)
    {
        $user = $GLOBALS['xoopsUser'];

        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('event_id', $eventId));
        if (!$skipPerm) {
            $this->addCatPermCriteria($criteriaCompo, $user);
        }
        $ret =& $this->getObjects($criteriaCompo);
        if (isset($ret[0])) {
            return $ret[0];
        } else {
            return false;
        }
    }

    /**
     * @param $events
     * @param $pattern
     */
    public function formatEventsDate(&$events, $pattern)
    {
        //        $max = count($events);
        //        for ($i = 0; $i < $max; ++$i) {
        //            $this->formatEventDate($events[$i], $pattern);
        //        }
        foreach ($events as $i => $iValue) {
            $this->formatEventDate($events[$i], $pattern);
        }
    }

    //  function getPicture1(&$event) {
    //      return $event['event_picture1'];
    //  }
    //  function getPicture2(&$event) {
    //      return $event['event_picture2'];
    //  }
    //  function getDesc(&$event) {
    //      return $event['event_desc'];
    //  }

    /**
     * @param $event
     * @param $pattern
     */
    public function formatEventDate(&$event, $pattern)
    {
        if (!$event['event_isrecur']) {
            $event['formated_event_start'] = $this->extcalTime->getFormatedDate($pattern, $event['event_start']);
            $event['formated_event_end']   = $this->extcalTime->getFormatedDate($pattern, $event['event_end']);
        } else {
            $event['formated_event_start'] = $this->extcalTime->getFormatedDate($pattern, $event['event_start']);
            $event['formated_event_end']   = $this->extcalTime->getFormatedDate($pattern, $event['event_end']);
            $event['formated_reccur_rule'] = $this->extcalTime->getFormatedReccurRule($event['event_recur_rules']);
        }
        $event['formated_event_submitdate'] = $this->extcalTime->getFormatedDate($pattern, $event['event_submitdate']);
    }

    //JJD - to valid modif
    //     function checkDate(&$data)
    //     {
    //
    //         list($year, $month, $day) = explode("-", $data['event_start']['date']);
    //         $data['event_start']
    //             =
    //             mktime(0, 0, 0, $month, $day, $year) + $data['event_start']['time'];
    //         list($year, $month, $day) = explode("-", $data['event_end']['date']);
    //         $data['event_end']
    //             = mktime(0, 0, 0, $month, $day, $year) + $data['event_end']['time'];
    //
    //         if ($data['have_end'] == 0 || $data['event_start'] > $data['event_end']
    //) {
    //             $data['event_end'] = $data['event_start'];
    //         }
    //
    //     }

    /**
     * @param $data
     */
    public function checkDate(&$data)
    {
        $data['event_start'] = strtotime($data['event_start']['date']) + $data['event_start']['time'];
        $data['event_end']   = strtotime($data['event_end']['date']) + $data['event_end']['time'];

        if (0 == $data['have_end'] || $data['event_start'] > $data['event_end']) {
            $data['event_end'] = $data['event_start'];
        }
    }

    /**
     * @param $data
     */
    private function userTimeToServerTime(&$data)
    {
        $user = $GLOBALS['xoopsUser'];

        $data['event_start'] = userTimeToServerTime($data['event_start'], $this->extcalTime->getUserTimeZone($user));
        $data['event_end']   = userTimeToServerTime($data['event_end'], $this->extcalTime->getUserTimeZone($user));
    }

    /**
     * @param $data
     */
    public function serverTimeToUserTime(&$data)
    {
        $user = $GLOBALS['xoopsUser'];

        $data['event_start']      = xoops_getUserTimestamp($data['event_start'], $this->extcalTime->getUserTimeZone($user));
        $data['event_end']        = xoops_getUserTimestamp($data['event_end'], $this->extcalTime->getUserTimeZone($user));
        $data['event_submitdate'] = xoops_getUserTimestamp($data['event_submitdate'], $this->extcalTime->getUserTimeZone($user));
    }

    /**
     * @param $events
     */
    public function serverTimeToUserTimes(&$events)
    {
        foreach ($events as $i => $iValue) {
            $this->serverTimeToUserTime($events[$i]);
        }
    }

    /**
     * @param $data
     */
    public function addRecurValue(&$data)
    {
        $data['event_isrecur']     = $this->getIsRecur($_POST);
        $data['event_recur_rules'] = $this->getRecurRules($_POST);
        $data['event_recur_start'] = $this->getRecurStart($data, $_POST);
        $data['event_recur_end']   = $this->getRecurEnd($data, $_POST);
    }

    /***************************************************************
     * Return events on perioe
     **************************************************************
     *
     * @param $criteres
     *
     * @return array
     */
    public function getEventsOnPeriode($criteres)
    {
        //Extcal\Utility::echoArray($criteres);
        $myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

        $eventsU = $this->getEventsUniques($criteres);
        $eventsR = $this->getEventsRecurents($criteres);
        $events  = array_merge($eventsU, $eventsR);

        //      $events = $eventsU;

        //Extcal\Utility::echoArray($events);

        //Tri des evennement par date ascendante
        $ordre      = [];
        $eventArray = [];

        //        while (list($k, $v) = each($events)) {
        foreach ($events as $k => $v) {
            $ordre[] = (int)$v['event_start'];
            $this->formatEventDate($v, Extcal\Helper::getInstance()->getConfig('event_date_week'));
            //$v['cat']['cat_light_color'] = $v['cat']['cat_color'];
            $v['cat']['cat_light_color'] = Extcal\Utility::getLighterColor($v['cat']['cat_color'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);
            if ('' == $v['event_icone']) {
                $v['event_icone'] = $v['cat']['cat_icone'];
            }
            $v['event_desc'] = html_entity_decode($v['event_desc']);
            $eventArray[]    = $v;
        }
        array_multisort($eventArray, SORT_ASC, SORT_NUMERIC, $ordre, SORT_ASC, SORT_NUMERIC);

        return $eventArray;
    }

    /*****************************************************************
     *
     ****************************************************************
     * @param $criteres
     * @return array
     */
    public function getEventsUniques($criteres)
    {
        $cat = 0;
        //        while (list($k, $v) = each($criteres)) {
        foreach ($criteres as $k => $v) {
            $$k = $v;
        }
        if (!isset($nbDays)) {
            $nbDays = 7;
        }
        if (!isset($sens)) {
            $sens = 'ASC';
        }
        if (!isset($externalKeys)) {
            $externalKeys = ['cat_id'];
        }
        //------------------------------------------------------
        switch ($periode) {

            case _EXTCAL_EVENTS_CALENDAR_WEEK:
                $criteriaCompo = $this->getEventWeekCriteria($day, $month, $year, $cat, $nbDays);
                if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_cal')) {
                    $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
                }
                break;

            case _EXTCAL_EVENTS_WEEK:
            case _EXTCAL_EVENTS_AGENDA_WEEK:
                $criteriaCompo = $this->getEventWeekCriteria($day, $month, $year, $cat, $nbDays);
                if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_list')) {
                    $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
                }
                break;

            case _EXTCAL_EVENTS_CALENDAR_MONTH:
                $criteriaCompo = $this->getEventMonthCriteria($month, $year, $cat);

                if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_cal')) {
                    $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
                }
                break;

            case _EXTCAL_EVENTS_MONTH:
                $criteriaCompo = $this->getEventMonthCriteria($month, $year, $cat);

                if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_list')) {
                    $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
                }
                break;

            case _EXTCAL_EVENTS_DAY:
                $criteriaCompo = $this->getEventDayCriteria($day, $month, $year, $cat);

                break;

            case _EXTCAL_EVENTS_YEAR:
                $criteriaCompo = $this->getEventYearCriteria($year, $cat);
                break;

            case _EXTCAL_EVENTS_UPCOMING:
                $criteriaCompo = $this->getEventWeekCriteria($day, $month, $year, $cat, $nbDays);
                break;

        }
        //--------------------------------------------------------------------------
        $criteriaCompo->add(new \Criteria('event_isrecur', 0, '='));
        $criteriaCompo->setOrder($sens);

        $result =& $this->getObjects($criteriaCompo);
        $events = $this->objectToArray($result, $externalKeys);
        $this->serverTimeToUserTimes($events);

        return $events;
    }

    /*****************************************************************
     * evennement récurents
     ****************************************************************
     * @param $criteres
     * @return array
     */

    public function getEventsRecurents($criteres)
    {
        //        while (list($k, $v) = each($criteres)) {
        foreach ($criteres as $k => $v) {
            $$k = $v;
        }
        if (!isset($nbDays)) {
            $nbDays = 7;
        }
        if (!isset($sens)) {
            $sens = 'ASC';
        }
        if (!isset($externalKeys)) {
            $externalKeys = ['cat_id'];
        }
        $user = $GLOBALS['xoopsUser'];
        //------------------------------------------------------

        $criteriaCompo = new \CriteriaCompo();

        switch ($periode) {
            case _EXTCAL_EVENTS_WEEK:
            case _EXTCAL_EVENTS_CALENDAR_WEEK:
            case _EXTCAL_EVENTS_AGENDA_WEEK:
            case _EXTCAL_EVENTS_UPCOMING:
                $start = userTimeToServerTime(mktime(0, 0, 0, $month, $day, $year), $this->extcalTime->getUserTimeZone($user));
                $end   = userTimeToServerTime(mktime(0, 0, 0, $month, $day + $nbDays + 1, $year), $this->extcalTime->getUserTimeZone($user));
                //$end = $start + (($nbDays + 1 )* _EXTCAL_TS_DAY);
                //$end = userTimeToServerTime(mktime(0, 0, 0, $month, $day+(($nbJours)+1 * _EXTCAL_TS_DAY), $year), $this->extcalTime->getUserTimeZone($user));;
                break;

            case _EXTCAL_EVENTS_MONTH:
            case _EXTCAL_EVENTS_CALENDAR_MONTH:
                $start = userTimeToServerTime(mktime(0, 0, 0, $month, 1, $year), $this->extcalTime->getUserTimeZone($user));
                $end   = userTimeToServerTime(mktime(23, 59, 59, $month + 1, 1, $year) - _EXTCAL_TS_DAY, $this->extcalTime->getUserTimeZone($user));

                $criteriaCompo->add(new \Criteria('event_start', $end, '<='));
                //$criteriaCompo->add( new \Criteria('event_end', $start, '>='));

                break;

            case _EXTCAL_EVENTS_DAY:
                $start = userTimeToServerTime(mktime(0, 0, 0, $month, $day, $year), $this->extcalTime->getUserTimeZone($user));
                $end   = userTimeToServerTime(mktime(0, 0, 0, $month, $day + 1, $year), $this->extcalTime->getUserTimeZone($user));
                //$criteriaCompo->add( new \Criteria('event_start', $end, '<='));

                break;

            case _EXTCAL_EVENTS_YEAR:
                $start = userTimeToServerTime(mktime(0, 0, 0, 1, 1, $year), $this->extcalTime->getUserTimeZone($user));
                $end   = userTimeToServerTime(mktime(0, 0, 0, 12, 31, $year), $this->extcalTime->getUserTimeZone($user));
                break;

        }
        $formatDate = Extcal\Helper::getInstance()->getConfig('event_date_week');
        //--------------------------------------------------------------------------
        $criteriaCompo->add(new \Criteria('event_isrecur', 1, '='));
        $criteriaCompo->setOrder($sens);

        $result =& $this->getObjects($criteriaCompo);
        $events = $this->objectToArray($result, $externalKeys);
        $this->serverTimeToUserTimes($events);

        //Balyage de tous les evennements récurrents et creation de toutes le events
        $eventsR = [];
        //        while (list($k, $event) = each($events)) {
        foreach ($events as $k => $event) {
            //$te = $this->GetInterval($event, $start, $end);
            //$eventsR = array_merge($eventsR, $te);
            //echo 'event : ' . $event['event_id'] . '<br>';
            //Extcal\Utility::echoArray($event);
            $recurEvents = $this->getRecurEventToDisplay($event, $start, $end);
            if (count($recurEvents) > 0) {
                $eventsR = array_merge($eventsR, $recurEvents);
            }

            // Formating date
            //$eventsR = array_merge($eventsArray, $recurEvents);
        }

        return $eventsR;
    }

    /*****************************************************************
     *
     ****************************************************************
     * @param        $period
     * @param string $caption
     */
    public function echoDateArray($period, $caption = '')
    {
        if ('' != $caption) {
            echo "<hr>echoDateArray -> {$caption}<br>";
        } else {
            echo '<hr>echoDateArray<br>';
        }

        reset($period);
        foreach ($period as $dt) {
            echo $dt->format("l d-m-Y H:i:s\n") . '<br>';
        }
    }

    /*****************************************************************
     * Criteria
     ****************************************************************
     * @param     $day
     * @param     $month
     * @param     $year
     * @param int $cat
     * @return \CriteriaCompo
     */
    // Return the criteria compo object for a day
    public function getEventDayCriteria($day, $month, $year, $cat = 0)
    {
        $user = $GLOBALS['xoopsUser'];

        $dayStart      = userTimeToServerTime(mktime(0, 0, 0, $month, $day, $year), $this->extcalTime->getUserTimeZone($user));
        $dayEnd        = userTimeToServerTime(mktime(23, 59, 59, $month, $day, $year), $this->extcalTime->getUserTimeZone($user));
        $criteriaCompo = $this->getListCriteriaCompo($dayStart, $dayEnd, $cat, $user);

        return $criteriaCompo;
    }

    // Return the criteria compo object for a week

    /**
     * @param     $day
     * @param     $month
     * @param     $year
     * @param int $cat
     * @param int $nbDays
     *
     * @return \CriteriaCompo
     */
    public function getEventWeekCriteria($day, $month, $year, $cat = 0, $nbDays = 7)
    {
        $user = $GLOBALS['xoopsUser'];

        $userStartTime = mktime(0, 0, 0, $month, $day, $year);
        $userEndTime   = $userStartTime + (_EXTCAL_TS_DAY * $nbDays);
        $weekStart     = userTimeToServerTime($userStartTime, $this->extcalTime->getUserTimeZone($user));
        $weekEnd       = userTimeToServerTime($userEndTime, $this->extcalTime->getUserTimeZone($user));
        $criteriaCompo = $this->getCriteriaCompo($weekStart, $weekEnd, $cat, $user);

        return $criteriaCompo;
    }

    // Return the criteria compo object for a month

    /**
     * @param     $month
     * @param     $year
     * @param int $cat
     *
     * @return \CriteriaCompo
     */
    public function getEventMonthCriteria($month, $year, $cat = 0)
    {
        $user = $GLOBALS['xoopsUser'];

        $userStartTime = mktime(0, 0, 0, $month, 1, $year);
        $userEndTime   = mktime(23, 59, 59, $month + 1, 0, $year);
        $monthStart    = userTimeToServerTime($userStartTime, $this->extcalTime->getUserTimeZone($user));
        $monthEnd      = userTimeToServerTime($userEndTime, $this->extcalTime->getUserTimeZone($user));
        $criteriaCompo = $this->getCriteriaCompo($monthStart, $monthEnd, $cat, $user);

        return $criteriaCompo;
    }

    // Return the criteria compo object for event occuring on a given year

    /**
     * @param     $year
     * @param int $cat
     *
     * @return \CriteriaCompo
     */
    public function getEventYearCriteria($year, $cat = 0)
    {
        $user = $GLOBALS['xoopsUser'];

        $userStartTime = mktime(0, 0, 0, 1, 1, $year);
        $userEndTime   = mktime(23, 59, 59, 12, 31, $year);
        $yearStart     = userTimeToServerTime($userStartTime, $this->extcalTime->getUserTimeZone($user));
        $yearEnd       = userTimeToServerTime($userEndTime, $this->extcalTime->getUserTimeZone($user));
        $criteriaCompo = $this->getListCriteriaCompo($yearStart, $yearEnd, $cat, $user);

        return $criteriaCompo;
    }

    /**********************************************************************
     * Debut de - A virer in fine
     **********************************************************************/

    /**********************************************************************
     * FIN de  - A virer in fine
     **********************************************************************/

    /**********************************************************************
     * Construction des criteres en fonction de la période
     *********************************************************************
     * @param $start
     * @param $end
     * @param $cat
     * @param $user
     * @return \CriteriaCompo
     */

    public function getCriteriaCompo($start, $end, $cat = 0, $user)
    {
        $criteriaNoRecur = new \CriteriaCompo();
        $criteriaNoRecur->add(new \Criteria('event_start', $end, '<='));
        $criteriaNoRecur->add(new \Criteria('event_end', $start, '>='));
        $criteriaNoRecur->add(new \Criteria('event_isrecur', 0));

        $criteriaRecur = new \CriteriaCompo();
        $criteriaRecur->add(new \Criteria('event_recur_start', $end, '<='));
        $criteriaRecur->add(new \Criteria('event_recur_end', $start, '>='));
        $criteriaRecur->add(new \Criteria('event_isrecur', 1));

        $criteriaCompoDate = new \CriteriaCompo();
        $criteriaCompoDate->add($criteriaNoRecur, 'OR');
        $criteriaCompoDate->add($criteriaRecur, 'OR');

        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add($criteriaCompoDate);

        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $this->addCatSelectCriteria($criteriaCompo, $cat);
        $this->addCatPermCriteria($criteriaCompo, $user);
        $criteriaCompo->setSort('event_start');

        return $criteriaCompo;
    }

    /**
     * @param     $start
     * @param     $end
     * @param int $cat
     * @param     $user
     *
     * @return \CriteriaCompo
     */
    public function getCalendarCriteriaCompo($start, $end, $cat = 0, $user)
    {
        $criteriaCompo = $this->getCriteriaCompo($start, $end, $cat, $user);
        if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_cal')) {
            $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
        }

        return $criteriaCompo;
    }

    /**
     * @param     $start
     * @param     $end
     * @param int $cat
     * @param     $user
     *
     * @return \CriteriaCompo
     */
    public function getListCriteriaCompo($start, $end, $cat = 0, $user)
    {
        $criteriaCompo = $this->getCriteriaCompo($start, $end, $cat, $user);
        if (!Extcal\Helper::getInstance()->getConfig('diplay_past_event_list')) {
            $criteriaCompo->add(new \Criteria('event_end', time(), '>'));
        }

        return $criteriaCompo;
    }

    // Return upcomming event

    /**
     * @param     $nbEvent
     * @param int $cat
     *
     * @return array
     */
    public function getUpcommingEvent($nbEvent, $cat = 0)
    {
        $now = time();

        $criteriaNoRecur = new \CriteriaCompo();
        $criteriaNoRecur->add(new \Criteria('event_start', $now, '>='));
        $criteriaNoRecur->add(new \Criteria('event_isrecur', 0));

        $criteriaRecur = new \CriteriaCompo();
        $criteriaRecur->add(new \Criteria('event_recur_start', $now, '>='));
        $criteriaRecur->add(new \Criteria('event_isrecur', 1));

        $criteriaCompoDate = new \CriteriaCompo();
        $criteriaCompoDate->add($criteriaNoRecur, 'OR');
        $criteriaCompoDate->add($criteriaRecur, 'OR');

        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add($criteriaCompoDate);

        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $this->addCatSelectCriteria($criteriaCompo, $cat);
        $this->addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);

        //mb ---------- TESTING ---------------------------
        //        $eventsU = $this->getEventsUniques($criteriaNoRecur);
        //        $eventsR = $this->getEventsRecurents($criteriaRecur);
        //        $events  = array_merge($eventsU, $eventsR);

        //var_dump($events);

        $criteriaCompo->setSort('event_start');
        $criteriaCompo->setLimit($nbEvent);

        //var_dump($this->getObjects($criteriaCompo));
        //mb -------------------------------------
        return $this->getObjects($criteriaCompo);
    }

    // Return event occuring this day

    /**
     * @param     $nbEvent
     * @param int $cat
     *
     * @return array
     */
    public function getThisDayEvent($nbEvent, $cat = 0)
    {
        $day   = date('j');
        $month = date('n');
        $year  = date('Y');

        $dayStart = mktime(0, 0, 0, $month, $day, $year);
        $dayEnd   = mktime(0, 0, 0, $month, $day + 1, $year);

        $criteriaCompo = new \CriteriaCompo();
        $this->addCatSelectCriteria($criteriaCompo, $cat);
        $this->addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);
        $criteriaCompo->add(new \Criteria('event_end', $dayStart, '>='));
        $criteriaCompo->add(new \Criteria('event_start', $dayEnd, '<'));
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $criteriaCompo->setSort('event_start');
        $criteriaCompo->setLimit($nbEvent);

        return $this->getObjects($criteriaCompo);
    }

    // Return last added event

    /**
     * @param      $start
     * @param      $limit
     * @param int  $cat
     * @param bool $skipPerm
     *
     * @return array
     */
    public function getNewEvent($start, $limit, $cat = 0, $skipPerm = false)
    {
        $criteriaCompo = new \CriteriaCompo();
        $this->addCatSelectCriteria($criteriaCompo, $cat);
        if (!$skipPerm) {
            $this->addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);
        }
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $criteriaCompo->setSort('event_id');
        $criteriaCompo->setOrder('DESC');
        $criteriaCompo->setStart($start);
        $criteriaCompo->setLimit($limit);

        return $this->getObjects($criteriaCompo);
    }

    /**
     * @return int
     */
    public function getCountNewEvent()
    {
        $criteriaCompo = new \CriteriaCompo();
        $this->addCatSelectCriteria($criteriaCompo, 0);
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $criteriaCompo->setSort('event_id');

        return $this->getCount($criteriaCompo);
    }

    // Return random upcomming event

    /**
     * @param     $nbEvent
     * @param int $cat
     *
     * @return array
     */
    public function getRandomEvent($nbEvent, $cat = 0)
    {
        $criteriaCompo = new \CriteriaCompo();
        $this->addCatSelectCriteria($criteriaCompo, $cat);
        $this->addCatPermCriteria($criteriaCompo, $GLOBALS['xoopsUser']);
        $criteriaCompo->add(new \Criteria('event_start', time(), '>='));
        $criteriaCompo->add(new \Criteria('event_approved', 1));
        $criteriaCompo->setSort('RAND()');
        $criteriaCompo->setLimit($nbEvent);

        return $this->getObjects($criteriaCompo);
    }

    /**
     * @return array
     */
    public function getPendingEvent()
    {
        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('event_approved', 0));
        $criteriaCompo->setSort('event_start');

        return $this->getObjects($criteriaCompo);
    }

    /**
     * @param \CriteriaElement $criteria
     * @param $user
     */
    public function addCatPermCriteria(\CriteriaElement $criteria, $user)
    {
        $authorizedAccessCats = $this->extcalPerm->getAuthorizedCat($user, 'extcal_cat_view');
        $count                = count($authorizedAccessCats);
        if ($count > 0) {
            $in = '(' . $authorizedAccessCats[0];
            array_shift($authorizedAccessCats);
            foreach ($authorizedAccessCats as $authorizedAccessCat) {
                $in .= ',' . $authorizedAccessCat;
            }
            $in .= ')';
            $criteria->add(new \Criteria('cat_id', $in, 'IN'));
        } else {
            $criteria->add(new \Criteria('cat_id', '(0)', 'IN'));
        }
    }

    /**
     * @param $criteria
     * @param $cats
     */
    public function addCatSelectCriteria(&$criteria, $cats = null)
    {
        if (!is_array($cats) && $cats > 0) {
            $criteria->add(new \Criteria('cat_id', $cats));
        }
        if (is_array($cats)) {
            if (false === array_search(0, $cats)) {
                $in = '(' . current($cats);
                array_shift($cats);
                foreach ($cats as $cat) {
                    $in .= ',' . $cat;
                }
                $in .= ')';
                $criteria->add(new \Criteria('cat_id', $in, 'IN'));
            }
        }
    }

    /**********************************************************************
     * formulaire d'edition des evennements*
     *********************************************************************
     * @param string $siteSide
     * @param string $mode
     * @param null   $data
     * @return \XoopsModules\Extcal\Form\ThemeForm
     */
    public function getEventForm($siteSide = 'user', $mode = 'new', $data = null)
    {
        global $xoopsModuleConfig;
        $catHandler  = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_CAT);
        $fileHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_FILE);

        /***************************************************/
        //        require_once __DIR__ . '/etablissement.php';
        if ('admin' === $siteSide) {
            $action = 'event.php?op=enreg';
            $cats   = $catHandler->getAllCat($GLOBALS['xoopsUser'], 'all');
        } else {
            $action = 'post.php';
            $cats   = $catHandler->getAllCat($GLOBALS['xoopsUser']);
        }
        /***************************************************/
        $reccurOptions = [];

        if ('edit' === $mode || 'clone' === $mode) {
            if (!$event = $this->getEventWithNotApprove($data['event_id'])) {
                return false;
            }
            if ('clone' === $mode) {
                $data['event_id'] = 0;
                $event->setVar('event_id', 0);
                $newTitle = $event->getVar('event_title') . ' (' . _MD_EXTCAL_CLONE_OF . $data['event_id'] . ')';
                $event->setVar('event_title', $newTitle);
            }

            $formTitle           = _MD_EXTCAL_EDIT_EVENT;
            $formName            = 'modify_event';
            $title               = $event->getVar('event_title', 'e');
            $cat                 = $event->getVar('cat_id');
            $desc                = $event->getVar('event_desc', 'e');
            $nbMember            = $event->getVar('event_nbmember', 'e');
            $organisateur        = $event->getVar('event_organisateur');
            $contact             = $event->getVar('event_contact', 'e');
            $url                 = $event->getVar('event_url', 'e');
            $email               = $event->getVar('event_email', 'e');
            $event_address       = $event->getVar('event_address', 'e');
            $startDateValue      = xoops_getUserTimestamp($event->getVar('event_start'), $this->extcalTime->getUserTimeZone($GLOBALS['xoopsUser']));
            $endDateValue        = xoops_getUserTimestamp($event->getVar('event_end'), $this->extcalTime->getUserTimeZone($GLOBALS['xoopsUser']));
            $event_picture1      = $event->getVar('event_picture1');
            $event_picture2      = $event->getVar('event_picture2');
            $event_price         = $event->getVar('event_price');
            $event_etablissement = $event->getVar('event_etablissement');
            $event_icone         = $event->getVar('event_icone');

            // Configuring recurring form
            $eventOptions = explode('|', $event->getVar('event_recur_rules'));
            $reccurMode   = $eventOptions[0];
            array_shift($eventOptions);
            switch ($reccurMode) {

                case 'daily':

                    $reccurOptions['rrule_freq']           = 'daily';
                    $reccurOptions['rrule_daily_interval'] = $eventOptions[0];

                    break;

                case 'weekly':

                    $reccurOptions['rrule_freq']            = 'weekly';
                    $reccurOptions['rrule_weekly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_weekly_bydays'] = $eventOptions;

                    break;

                case 'monthly':

                    $reccurOptions['rrule_freq']             = 'monthly';
                    $reccurOptions['rrule_monthly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    if (0 !== strpos($eventOptions[0], 'MD')) {
                        $reccurOptions['rrule_monthly_byday'] = $eventOptions[0];
                    } else {
                        $reccurOptions['rrule_bymonthday'] = substr($eventOptions[0], 2);
                    }

                    break;

                case 'yearly':

                    $reccurOptions['rrule_freq']            = 'yearly';
                    $reccurOptions['rrule_yearly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_yearly_byday'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_yearly_bymonths'] = $eventOptions;

                    break;

            }

            $files = $fileHandler->objectToArray($fileHandler->getEventFiles($data['event_id']));
            $fileHandler->formatFilesSize($files);
        } elseif ('preview' === $mode) {
            $formTitle           = _MD_EXTCAL_SUBMIT_EVENT;
            $formName            = 'submit_event';
            $title               = $data['event_title'];
            $cat                 = $data['cat_id'];
            $desc                = $data['event_desc'];
            $nbMember            = $data['event_nbmember'];
            $organisateur        = $data['event_organisateur'];
            $contact             = $data['event_contact'];
            $url                 = $data['event_url'];
            $email               = $data['event_email'];
            $event_address       = $data['event_address'];
            $startDateValue      = $data['event_start'];
            $endDateValue        = $data['event_end'];
            $eventEndOk          = $data['have_end'];
            $event_picture1      = $data['event_picture1'];
            $event_picture2      = $data['event_picture2'];
            $event_price         = $data['event_price'];
            $event_etablissement = $data['event_etablissement'];
            $event_icone         = $data['event_icone'];

            // Configuring recurring form
            $eventOptions = explode('|', $this->getRecurRules($_POST));
            $reccurMode   = $eventOptions[0];
            array_shift($eventOptions);
            switch ($reccurMode) {

                case 'daily':

                    $reccurOptions['rrule_freq']           = 'daily';
                    $reccurOptions['rrule_daily_interval'] = $eventOptions[0];

                    break;

                case 'weekly':

                    $reccurOptions['rrule_freq']            = 'weekly';
                    $reccurOptions['rrule_weekly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_weekly_bydays'] = $eventOptions;

                    break;

                case 'monthly':

                    $reccurOptions['rrule_freq']             = 'monthly';
                    $reccurOptions['rrule_monthly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    if (0 !== strpos($eventOptions[0], 'MD')) {
                        $reccurOptions['rrule_monthly_byday'] = $eventOptions[0];
                    } else {
                        $reccurOptions['rrule_bymonthday'] = substr($eventOptions[0], 2);
                    }

                    break;

                case 'yearly':

                    $reccurOptions['rrule_freq']            = 'yearly';
                    $reccurOptions['rrule_yearly_interval'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_yearly_byday'] = $eventOptions[0];
                    array_shift($eventOptions);
                    $reccurOptions['rrule_yearly_bymonths'] = $eventOptions;

                    break;

            }

            $files = $fileHandler->objectToArray($fileHandler->getEventFiles($data['event_id']));
            $fileHandler->formatFilesSize($files);
        } else {
            $formTitle           = _MD_EXTCAL_SUBMIT_EVENT;
            $formName            = 'submit_event';
            $title               = '';
            $cat                 = '';
            $desc                = '';
            $nbMember            = 0;
            $organisateur        = '';
            $contact             = '';
            $url                 = '';
            $email               = '';
            $event_address       = '';
            $startDateValue      = 0;
            $endDateValue        = 0;
            $eventEndOk          = 0;
            $event_picture1      = '';
            $event_picture2      = '';
            $event_price         = '';
            $event_etablissement = '';
            $files               = [];
            $event_icone         = '';
        }

        // Create XoopsForm Object
        $form = new Extcal\Form\ThemeForm($formTitle, 'event_form', $action, 'post', true);
        // Add this extra to allow file upload
        $form->setExtra('enctype="multipart/form-data"');

        //-----------------------------------------------
        // Title
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_TITLE, 'event_title', 80, 255, $title), true);
        //-----------------------------------------------
        // Category select
        $catSelect = new \XoopsFormSelect(_MD_EXTCAL_CATEGORY, 'cat_id', $cat);
        foreach ($cats as $cat) {
            $catSelect->addOption($cat->getVar('cat_id'), $cat->getVar('cat_name'));
        }
        $form->addElement($catSelect, true);
        //-----------------------------------------------------------

        $file_path = __DIR__ . '/../assets/css/images';
        $tf        = \XoopsLists::getImgListAsArray($file_path);
        array_unshift($tf, _MD_EXTCAL_NONE);
        $xfIcones = new \XoopsFormSelect(_MD_EXTCAL_ICONE, 'event_icone', $event_icone, '');
        $xfIcones->addOptionArray($tf);
        $form->addElement($xfIcones, false);
        //-----------------------------------------------------------
        //etablissement
        $etablissementHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_ETABLISSEMENT);
        $etablissement_select = new \XoopsFormSelect(_MD_EXTCAL_ETABLISSEMENT, 'event_etablissement', $event_etablissement);
        $criteria             = new \CriteriaCompo();
        $criteria->setSort('nom');
        $criteria->setOrder('ASC');

        //$lstEtablissement = $etablissementHandler->getList($criteria);
        $etablissement_arr = $etablissementHandler->getAll($criteria);
        $tEts              = [];
        $tEts[0]           = _MD_EXTCAL_NONE;
        foreach (array_keys($etablissement_arr) as $i) {
            $tEts[$etablissement_arr[$i]->getVar('id')] = $etablissement_arr[$i]->getVar('nom');
            //            $tEts[$etablissement_arr[$i]['id']] = $etablissement_arr[$i]['nom'];
        }
        //array_unshift($tEts, _MD_EXTCAL_NONE);

        $etablissement_select->addOptionArray($tEts);
        $form->addElement($etablissement_select, true);

        //-----------------------------------------------------------

        // Start and end
        new Extcal\Form\FormDateTime($form, $startDateValue, $endDateValue); //mb

        global $xoopsUser, $xoopsModule;
        $isAdmin = false;
        if (is_object($xoopsUser)) {
            $isAdmin = $xoopsUser->isAdmin($xoopsModule->getVar('mid'));
        }

        // Description
        if (class_exists('XoopsFormEditor')) {
            $options['name']   = 'event_desc';
            $options['value']  = $desc;
            $options['rows']   = 5;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor(_MD_EXTCAL_DESCRIPTION, $xoopsModuleConfig['editorAdmin'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor(_MD_EXTCAL_DESCRIPTION, $xoopsModuleConfig['editorUser'], $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(_MD_EXTCAL_DESCRIPTION, 'event_desc', $desc, '100%', '100%');
        }
        $form->addElement($descEditor);

        // Max registered member for this event
        $nbMemberElement = new \XoopsFormText(_MD_EXTCAL_NBMEMBER, 'event_nbmember', 4, 4, $nbMember);
        $nbMemberElement->setDescription(_MD_EXTCAL_NBMEMBER_DESC);
        $form->addElement($nbMemberElement, false);

        //Price and monnaie
        $monnaie_price = new \XoopsFormElementTray(_MD_EXTCAL_PRICE, '');
        //price
        $monnaie_price->addElement(new \XoopsFormText('', 'event_price', 20, 255, $event_price));
        //monnaie
        $monnaie = new \XoopsFormLabel(_MD_EXTCAL_DEVISE2, '');
        $monnaie_price->addElement($monnaie);
        $form->addElement($monnaie_price);
        //----------------------------------------------------------------
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_ORGANISATEUR, 'event_organisateur', 80, 255, $organisateur), false);
        // Contact
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_CONTACT, 'event_contact', 80, 255, $contact), false);
        // Url
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_URL, 'event_url', 80, 255, $url), false);
        // Email
        $form->addElement(new \XoopsFormText(_MD_EXTCAL_EMAIL, 'event_email', 80, 255, $email), false);

        // Address
        if (class_exists('XoopsFormEditor')) {
            $options['name']   = 'event_address';
            $options['value']  = $event_address;
            $options['rows']   = 5;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            if ($isAdmin) {
                $addressEditor = new \XoopsFormEditor(_MD_EXTCAL_DESCRIPTION, $xoopsModuleConfig['editorAdmin'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $addressEditor = new \XoopsFormEditor(_MD_EXTCAL_DESCRIPTION, $xoopsModuleConfig['editorUser'], $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $addressEditor = new \XoopsFormDhtmlTextArea(_MD_EXTCAL_DESCRIPTION, 'event_address', $event_address, '100%', '100%');
        }
        $form->addElement($addressEditor);

        // Recurence form
        $form->addElement(new Extcal\Form\FormRecurRules($reccurOptions));
        // File attachement
        $fileElmtTray = new \XoopsFormElementTray(_MD_EXTCAL_FILE_ATTACHEMENT, '<br>');

        // If they are attached file to this event
        if (count($files) > 0) {
            $eventFiles = new Extcal\Form\FormFileCheckBox('', 'filetokeep');
            foreach ($files as $file) {
                $name = $file['file_nicename'] . ' (<i>' . $file['file_mimetype'] . '</i>) ' . $file['formated_file_size'];
                $eventFiles->addOption($file['file_id'], $name);
            }
            $fileElmtTray->addElement($eventFiles);
        }
        $fileElmtTray->addElement(new \XoopsFormFile(_MD_EXTCAL_FILE_ATTACHEMENT, 'event_file', 3145728));
        $form->addElement($fileElmtTray);

        if (isset($data['event_id'])) {
            $form->addElement(new \XoopsFormHidden('event_id', $data['event_id']), false);
        }
        //Hack Kraven0
        ///////////////////////////////////////////////////////////////////////////////
        //Picture1
        $file_tray = new \XoopsFormElementTray(sprintf(_MD_EXTCAL_FORM_IMG, 1), '');
        if (!empty($event_picture1)) {
            $file_tray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/uploads/extcal/' . $event_picture1 . "' name='image' id='image' alt=''><br><br>"));
            $check_del_img = new \XoopsFormCheckBox('', 'delimg_1');
            $check_del_img->addOption(1, _MD_EXTCAL_DEL_IMG);
            $file_tray->addElement($check_del_img);
            $file_img = new \XoopsFormFile(_MD_EXTCAL_IMG, 'attachedimage1', 2145728);
            unset($check_del_img);
        } else {
            $file_img = new \XoopsFormFile('', 'attachedimage1', 2145728);
        }
        $file_img->setExtra("size ='40'");
        $file_tray->addElement($file_img);
        $msg        = sprintf(_MD_EXTCAL_IMG_CONFIG, (int)(400728 / 1000), 500, 500);
        $file_label = new \XoopsFormLabel('', '<br>' . $msg);
        $file_tray->addElement($file_label);
        $form->addElement($file_tray);
        $form->addElement(new \XoopsFormHidden('file1', $event_picture1));
        unset($file_img, $file_tray);
        //Picture2
        $file_tray = new \XoopsFormElementTray(sprintf(_MD_EXTCAL_FORM_IMG, 2), '');
        if (!empty($event_picture2)) {
            $file_tray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/uploads/extcal/' . $event_picture2 . "' name='image' id='image' alt=''><br><br>"));
            $check_del_img = new \XoopsFormCheckBox('', 'delimg_2');
            $check_del_img->addOption(1, _MD_EXTCAL_DEL_IMG);
            $file_tray->addElement($check_del_img);
            $file_img = new \XoopsFormFile(_MD_EXTCAL_IMG, 'attachedimage2', 2145728);
            unset($check_del_img);
        } else {
            $file_img = new \XoopsFormFile('', 'attachedimage2', 2145728);
        }
        $file_img->setExtra("size ='40'");
        $file_tray->addElement($file_img);
        $msg        = sprintf(_MD_EXTCAL_IMG_CONFIG, (int)(400728 / 1000), 500, 500);
        $file_label = new \XoopsFormLabel('', '<br>' . $msg);
        $file_tray->addElement($file_label);
        $form->addElement($file_tray);
        $form->addElement(new \XoopsFormHidden('file2', $event_picture2));
        unset($file_img, $file_tray);
        ///////////////////////////////////////////////////////////////////////////////

        $buttonElmtTray = new \XoopsFormElementTray('', '&nbsp;');
        $buttonElmtTray->addElement(new \XoopsFormButton('', 'form_submit', _SUBMIT, 'submit'), false);
        if ('user' === $siteSide) {
            $buttonElmtTray->addElement(new \XoopsFormButton('', 'form_preview', _MD_EXTCAL_PREVIEW, 'submit'), false);
        }
        $form->addElement($buttonElmtTray);

        return $form;
    }

    /********************************************************************/

    /**
     * @param $parm
     *
     * @return bool
     */
    public function getIsRecur($parm)
    {
        $recurFreq = ['daily', 'weekly', 'monthly', 'yearly'];

        return in_array($parm['rrule_freq'], $recurFreq);
    }

    /**
     * @param $parm
     *
     * @return string
     */
    public function getRecurRules($parm)
    {
        //Extcal\Utility::echoArray($parm);exit;

        // If this isn't a reccuring event
        if (!$this->getIsRecur($parm)) {
            return '';
        }

        $recurRules = '';

        $recurFreq = $parm['rrule_freq'];

        switch ($recurFreq) {

            case 'daily':
                if (!isset($parm['rrule_daily_interval'])) {
                    $parm['rrule_daily_interval'] = 0;
                }
                $recurRules = 'daily|';
                $recurRules .= $parm['rrule_daily_interval'];

                break;

            case 'weekly':
                if (!isset($parm['rrule_weekly_interval'])) {
                    $parm['rrule_weekly_interval'] = 0;
                }
                $recurRules = 'weekly|';
                $recurRules .= $parm['rrule_weekly_interval'];
                foreach ($parm['rrule_weekly_bydays'] as $day) {
                    $recurRules .= '|' . $day;
                }

                break;

            case 'monthly':
                if (!isset($parm['rrule_monthly_interval'])) {
                    $parm['rrule_monthly_interval'] = 0;
                }
                $recurRules = 'monthly|';
                $recurRules .= $parm['rrule_monthly_interval'] . '|';
                if ('' != $parm['rrule_monthly_byday']) {
                    $recurRules .= $parm['rrule_monthly_byday'];
                } else {
                    $recurRules .= 'MD' . $parm['rrule_bymonthday'];
                }

                break;

            case 'yearly':
                //JJD - to valid modif
                //
                //                 if ($parm['rrule_yearly_byday'] == "") {
                //                     list($year, $month, $day) = explode("-", $parm['event_start']['date']);
                //                     $parm['rrule_yearly_byday'] = date("j", mktime(0, 0, 0, $month, $day, $year));
                //                 }
                //
                //                 $recurRules = 'yearly|';
                //                 $recurRules .= $parm['rrule_yearly_interval'];
                //                 $recurRules .= '|' . $parm['rrule_yearly_byday'];
                //                 foreach (
                //                     $parm['rrule_yearly_bymonths'] as $month
                //) {
                //                     $recurRules .= '|' . $month;
                //                 }
                //
                //                 break;

                if (!isset($parm['rrule_yearly_interval'])) {
                    $parm['rrule_yearly_interval'] = 0;
                }
                if ('' == $parm['rrule_yearly_byday']) {
                    $time                       = strtotime($parm['event_start']['date']);
                    $parm['rrule_yearly_byday'] = date('j', mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time)));
                }

                $recurRules = 'yearly|';
                $recurRules .= $parm['rrule_yearly_interval'];
                $recurRules .= '|' . $parm['rrule_yearly_byday'];
                foreach ($parm['rrule_yearly_bymonths'] as $month) {
                    $recurRules .= '|' . $month;
                }

                break;

        }

        return $recurRules;
    }

    /**
     * @param $data
     * @param $parm
     *
     * @return int
     */
    public function getRecurStart($data, $parm)
    {

        // If this isn't a reccuring event
        if (!$this->getIsRecur($parm)) {
            return 0;
        }

        return $data['event_start'];
    }

    /**
     * @param $data
     * @param $parm
     *
     * @return int
     */
    public function getRecurEnd($data, $parm)
    {
        if (!$this->getIsRecur($parm)) {
            return 0;
        }

        $recurFreq = $parm['rrule_freq'];

        $recurStart = $this->getRecurStart($data, $parm);

        switch ($recurFreq) {

            case 'daily':
                $interval = $parm['rrule_daily_interval'];
                $recurEnd = $recurStart + ($interval * _EXTCAL_TS_DAY) - 1;

                break;

            case 'weekly':
                  // Getting the first weekday TS
                $startWeekTS = mktime(0, 0, 0, date('n', $data['event_recur_start']), date('j', $data['event_recur_start']), date('Y', $data['event_recur_start']));
                $offset      = date('w', $startWeekTS) - Extcal\Helper::getInstance()->getConfig('week_start_day');
                $startWeekTS -= ($offset * _EXTCAL_TS_DAY);

                $recurEnd = $startWeekTS + ($parm['rrule_weekly_interval'] * _EXTCAL_TS_WEEK) - 1;

                break;

            case 'monthly':
                $recurEnd = $recurStart + ($parm['rrule_monthly_interval'] * 2678400) - 1;

                break;

            case 'yearly':
                $recurEnd = $recurStart + ($parm['rrule_yearly_interval'] * 32140800) - 1;

                break;

        }

        return $recurEnd;
    }

    /*******************************************************************
     *
     ******************************************************************
     * @param $event
     * @param $periodStart
     * @param $periodEnd
     * @return array
     */
    public function getRecurEventToDisplay($event, $periodStart, $periodEnd)
    {
        $recuEvents   = [];
        $eventOptions = explode('|', $event['event_recur_rules']);

        switch ($eventOptions[0]) {

            case 'daily':
                array_shift($eventOptions);
                $rRuleInterval = $eventOptions[0];
                if ('' == $rRuleInterval || 0 == $rRuleInterval) {
                    $rRuleInterval = 54;
                }

                $occurEventStart = $event['event_recur_start'];
                $occurEventEnd   = $event['event_recur_start'] + ($event['event_end'] - $event['event_start']);

                $nbOccur = 0;
                // This variable is used to stop the loop after we add all occur on the view to keep good performance
                $isOccurOnPeriod = false;
                // Parse all occurence of this event
                while ($nbOccur < $rRuleInterval) {
                    // Add this event occurence only if it's on the period view
                    if // Event start falls within search period
                    ($occurEventStart <= $periodEnd
                     && // Event end falls within search period
                     $occurEventEnd >= $periodStart) {
                        $event['event_start'] = $occurEventStart;
                        $event['event_end']   = $occurEventEnd;

                        $recuEvents[]    = $event;
                        $isOccurOnPeriod = true;
                    } elseif ($isOccurOnPeriod) {
                        break;
                    }

                    $occurEventStart += _EXTCAL_TS_DAY;
                    $occurEventEnd   += _EXTCAL_TS_DAY;

                    ++$nbOccur;
                }

                break;

            case 'weekly':

                array_shift($eventOptions);
                $rRuleInterval = $eventOptions[0];
                if ('' == $rRuleInterval || 0 == $rRuleInterval) {
                    $rRuleInterval = 54;
                }
                array_shift($eventOptions);

                // Getting the first weekday TS
                $startWeekTS = mktime(0, 0, 0, date('n', $event['event_recur_start']), date('j', $event['event_recur_start']), date('Y', $event['event_recur_start']));
                $offset      = date('w', $startWeekTS) - Extcal\Helper::getInstance()->getConfig('week_start_day');
                $startWeekTS = $startWeekTS - ($offset * _EXTCAL_TS_DAY) + _EXTCAL_TS_WEEK;

                $occurEventStart = $event['event_recur_start'];
                $occurEventEnd   = $event['event_recur_start'] + ($event['event_end'] - $event['event_start']);

                $dayArray = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];

                $nbOccur = 0;

                // Parse all occurence of this event
                while ($nbOccur < $rRuleInterval) {
                    // Add this event occurence only if it's on the period view and according to day
                    if ($occurEventStart <= $periodEnd // Event start falls within search period
                        && $occurEventEnd >= $periodStart // Event end falls within search period
                        && in_array($dayArray[date('w', $occurEventStart)], $eventOptions)) {
                        // This week day is selected

                        $event['event_start'] = $occurEventStart;
                        $event['event_end']   = $occurEventEnd;

                        $recuEvents[] = $event;
                    }

                    $occurEventStart += _EXTCAL_TS_DAY;
                    $occurEventEnd   += _EXTCAL_TS_DAY;

                    if ($occurEventStart >= $startWeekTS) {
                        ++$nbOccur;
                        $startWeekTS += _EXTCAL_TS_WEEK;
                    }
                }

                break;

            case 'monthly':
                array_shift($eventOptions);
                $rRuleInterval = $eventOptions[0];
                if ('' == $rRuleInterval || 0 == $rRuleInterval) {
                    $rRuleInterval = 100;
                }
                array_shift($eventOptions);

                $day   = date('j', $event['event_recur_start']);
                $month = date('n', $event['event_recur_start']);
                $year  = date('Y', $event['event_recur_start']);

                $nbOccur = 0;

                $eventHourOccurStart = $event['event_recur_start'] - mktime(0, 0, 0, $month, $day, $year);
                $eventHourOccurEnd   = $event['event_end'] - $event['event_start'];

                // Parse all occurence of this event
                while ($nbOccur < $rRuleInterval) {
                    $eventDayOccurStart = $this->_getOccurTS($month, $year, $eventOptions[0]);
                    if (!$eventDayOccurStart) {
                        $eventDayOccurStart = mktime(0, 0, 0, $month, $day, $year);
                    }

                    $occurEventStart = $eventDayOccurStart + $eventHourOccurStart;
                    $occurEventEnd   = $occurEventStart + $eventHourOccurEnd;

                    if // Event start falls within search period
                    ($occurEventStart <= $periodEnd
                     && // Event end falls within search period
                     $occurEventEnd >= $periodStart
                     && // This occur is after start reccur date
                     $occurEventStart >= $event['event_recur_start']) {
                        $event['event_start'] = $occurEventStart;
                        $event['event_end']   = $occurEventEnd;

                        $recuEvents[] = $event;
                    } elseif ($occurEventStart > $periodEnd) {
                        break;
                    }

                    if (13 == ++$month) {
                        $month = 1;
                        ++$year;
                    }

                    ++$nbOccur;
                }

                break;

            case 'yearly':
                array_shift($eventOptions);
                $rRuleInterval = $eventOptions[0];
                if ('' == $rRuleInterval || 0 == $rRuleInterval) {
                    $rRuleInterval = 10;
                }
                array_shift($eventOptions);
                $dayCode = $eventOptions[0];
                array_shift($eventOptions);

                $day   = date('j', $event['event_recur_start']);
                $month = date('n', $event['event_recur_start']);
                $year  = date('Y', $event['event_recur_start']);

                $nbOccur = 0;

                $eventHourOccurStart = $event['event_recur_start'] - mktime(0, 0, 0, $month, $day, $year);
                $eventHourOccurEnd   = $event['event_end'] - $event['event_start'];

                // If recurring month not specified, make it starting month
                if (!count($eventOptions)) {
                    $eventOptions[] = $month;
                }

                // Parse all occurence of this event
                while ($nbOccur < $rRuleInterval) {
                    $eventDayOccurStart = $this->_getOccurTS($month, $year, $dayCode);
                    if (!$eventDayOccurStart) {
                        $eventDayOccurStart = mktime(0, 0, 0, $month, $day, $year);
                    }

                    $occurEventStart = $eventDayOccurStart + $eventHourOccurStart;
                    $occurEventEnd   = $eventDayOccurStart + $eventHourOccurEnd;

                    if // Event start falls within search period
                    (($occurEventStart <= $periodEnd)
                     && // Event end falls within search period
                     ($occurEventEnd >= $periodStart)
                     && // This week day is selected
                     in_array($month, $eventOptions)) {
                        $event['event_start'] = $occurEventStart;
                        $event['event_end']   = $occurEventEnd;

                        $recuEvents[] = $event;
                    } elseif ($occurEventStart > $periodEnd) {
                        break;
                    }

                    if (13 == ++$month) {
                        $month = 1;
                        ++$year;
                        ++$nbOccur;
                    }
                }

                break;

        }

        return $recuEvents;
    }

    //-----------------------------------------------------------------

    /**
     * @param $month
     * @param $year
     * @param $dayCode
     *
     * @return int
     */
    public function getOccurTS($month, $year, $dayCode)
    {
        if (0 === strpos($dayCode, 'MD')) {
            if ('' != substr($dayCode, 2)) {
                return mktime(0, 0, 0, $month, substr($dayCode, 2), $year);
            } else {
                return 0;
            }
        } else {
            switch ($dayCode) {

                case '1SU':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (0 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (0 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1MO':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (1 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (1 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1TU':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (2 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (2 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1WE':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (3 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (3 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1TH':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (4 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (4 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1FR':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (5 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (5 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '1SA':

                    $ts        = mktime(0, 0, 0, $month, 1, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (6 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (6 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2SU':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (0 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (0 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2MO':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (1 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (1 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2TU':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (2 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (2 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2WE':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (3 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (3 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2TH':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (4 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (4 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2FR':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (5 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (5 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '2SA':

                    $ts        = mktime(0, 0, 0, $month, 7, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (6 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (6 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3SU':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (0 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (0 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3MO':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (1 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (1 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3TU':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (2 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (2 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3WE':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (3 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (3 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3TH':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (4 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (4 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3FR':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (5 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (5 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '3SA':

                    $ts        = mktime(0, 0, 0, $month, 14, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (6 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (6 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4SU':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (0 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (0 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4MO':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (1 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (1 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4TU':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (2 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (2 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4WE':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (3 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (3 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4TH':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (4 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (4 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4FR':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (5 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (5 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '4SA':

                    $ts        = mktime(0, 0, 0, $month, 21, $year);
                    $dayOfWeek = date('w', $ts);
                    $ts        = (6 == date('w', $ts)) ? $ts + (_EXTCAL_TS_DAY * 7) : $ts;
                    $i         = 0;
                    while (6 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }

                    return $ts + (_EXTCAL_TS_DAY * $i);

                    break;

                case '-1SU':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (0 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1MO':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (1 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1TU':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (2 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1WE':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (3 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1TH':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (4 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1FR':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (5 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                case '-1SA':

                    $ts        = mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year);
                    $dayOfWeek = date('w', $ts);
                    $i         = 0;
                    while (6 != $dayOfWeek % 7) {
                        ++$dayOfWeek;
                        ++$i;
                    }
                    if (0 == $i) {
                        return $ts;
                    }

                    return $ts + (_EXTCAL_TS_DAY * ($i - 7));

                    break;

                default:
                    return 0;

                    break;

            }
        }
    }

    /*************************************************************************
     *
     ************************************************************************
     * @param $year
     * @param $month
     * @param $day
     * @param $cat
     * @param $searchExp
     * @param $andor
     * @param $orderBy
     * @return array
     */
    public function getSearchEvent2($year, $month, $day, $cat, $searchExp, $andor, $orderBy)
    {
        global $xoopsDB, $xoopsUser;

        if (isset($xoopsUser)) {
            $userId = $xoopsUser->getVar('uid');
            $result = $this->getSearchEvents($year, $month, $day, $cat, $searchExp, $andor, $orderBy, 0, 0, $userId, $xoopsUser);
        } else {
            $result = $this->getSearchEvents($year, $month, $day, $cat, $searchExp, $andor, $orderBy, 0, 0);
        }

        $ret = [];
        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $myrow['cat']['cat_name']        = $myrow['cat_name'];
            $myrow['cat']['cat_color']       = $myrow['cat_color'];
            $myrow['cat']['cat_light_color'] = Extcal\Utility::getLighterColor($myrow['cat']['cat_color'], _EXTCAL_INFOBULLE_RGB_MIN, _EXTCAL_INFOBULLE_RGB_MAX);
            if ('' == $myrow['event_icone']) {
                $myrow['event_icone'] = $myrow['cat']['cat_icone'];
            }
            $ret[] = $myrow;
        }

        return $ret;
    }

    //-----------------------------------------------------------

    /**
     * @param int    $year
     * @param int    $month
     * @param int    $day
     * @param int    $cat
     * @param        $queryarray
     * @param        $andor
     * @param        $orderBy
     * @param int    $limit
     * @param int    $offset
     * @param int    $userId
     * @param string $user
     *
     * @return mixed
     */
    public function getSearchEvents(
        $year = 0,
        $month = 0,
        $day = 0,
        $cat = 0,
        $queryarray,
        $andor,
        $orderBy,
        $limit = 0,
        $offset = 0,
        $userId = 0,
        $user = ''
    ) {
        global $xoopsDB;

        //echo "<hr>{$andor}-{$limit}-{$offset}-{$userId}-{$user}<br>{$criteresPlus}";
        $tEvent = $xoopsDB->prefix('extcal_event') . ' AS te';
        $tCat   = $xoopsDB->prefix('extcal_cat') . ' AS tc';

        $sql = 'SELECT te.*, tc.cat_name , tc.cat_color, ' . 'year(FROM_UNIXTIME(event_start)) AS year,' . 'month(FROM_UNIXTIME(event_start)) AS month,' . 'day(FROM_UNIXTIME(event_start)) AS day' . " FROM {$tEvent}, {$tCat}";
        //---------------------------------------------------
        $tw   = [];
        $tw[] = 'te.cat_id = tc.cat_id';
        $tw[] = 'event_approved = 1';

        $authorizedAccessCats = $this->extcalPerm->getAuthorizedCat($user, 'extcal_cat_view');
        $inCat                = 'te.cat_id IN (0)';
        if (count($authorizedAccessCats) > 0) {
            $inCat = 'te.cat_id IN (' . implode(',', $authorizedAccessCats) . ')';
        }
        //echo $tw[count($tw)-1];

        if (0 != $userId) {
            $tw[] .= "({$inCat} OR event_submitter = {$userId} )";
        } else {
            $tw[] = $inCat;
        }
        //--------------------------------------------------------
        if ($cat > 0) {
            $tw[] .= "te.cat_id = {$cat}";
        }
        if ($year > 0) {
            $tw[] .= "year(FROM_UNIXTIME(event_start)) = {$year}";
        }
        if ($month > 0) {
            $tw[] .= "month(FROM_UNIXTIME(event_start)) = {$month}";
        }
        if ($day > 0) {
            $tw[] .= "day(FROM_UNIXTIME(event_start)) = {$day}";
        }

        //echoArray($queryarray,false);
        if (!is_array($queryarray)) {
            $queryarray = (('' != $queryarray) ? explode(' ', $queryarray) : '');
        }

        if (is_array($queryarray)) {
            $tFields = [
                'te.event_title',
                'te.event_desc',
                'te.event_contact',
                'te.event_address',
                'tc.cat_name',
            ];
            $t       = [];
            foreach ($queryarray as $i => $iValue) {
                $t1[] = " %1\$s LIKE '#{$queryarray[$i]}#' ";
            }

            $flt = '(' . implode(" {$andor} ", $t1) . ')';

            $t = [];
            foreach ($tFields as $h => $hValue) {
                $t[] = sprintf($flt, $tFields[$h]);
            }

            $filtre = implode(' OR ', $t);
            $filtre = str_replace('#', '%', $filtre);
            $tw[]   = '(' . $filtre . ')';
        }

        $sql .= ' WHERE ' . implode(' AND ', $tw);
        //------------------------------------------------------------
        if (count($orderBy) > 0) {
            $t = [];
            foreach ($orderBy as $hValue) {
                if ('' != $hValue) {
                    $t[] = $hValue;
                }
            }
            if (count($t) > 0) {
                $sql .= ' ORDER BY ' . implode(',', $t);
            }
        }

        //----------------------------------------------------------------

        $result = $xoopsDB->query($sql, $limit, $offset);

        // echo "<hr>{$sql}<hr>";
        return $result;
    }

    //-----------------------------------------------------------

    /**
     * @param $queryarray
     * @param $andor
     * @param $limit
     * @param $offset
     * @param $userId
     * @param $user
     *
     * @return mixed
     */
    public function getSearchEvent($queryarray, $andor, $limit, $offset, $userId, $user)
    {
        global $xoopsDB;

        $result = $this->getSearchEvents(0, 0, 0, 0, $queryarray, $andor, ['event_id DESC']);

        $i = 0;
        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $ret[$i]['image'] = 'assets/images/icons/extcal.gif';
            $ret[$i]['link']  = 'event.php?event=' . $myrow['event_id'];
            $ret[$i]['title'] = $myrow['event_title'];
            $ret[$i]['time']  = $myrow['event_submitdate'];
            $ret[$i]['uid']   = $myrow['event_submitter'];
            ++$i;
        }

        return $ret;
    }

    /**
     * @param        $queryarray
     * @param        $andor
     * @param        $limit
     * @param        $offset
     * @param        $userId
     * @param        $user
     * @param string $criteresPlus
     * @param bool   $xoopsSearch
     *
     * @return array
     */
    public function getSearchEvent3(
        $queryarray,
        $andor,
        $limit,
        $offset,
        $userId,
        $user,
        $criteresPlus = '',
        $xoopsSearch = true
    ) {
        global $xoopsDB;
        //echo "<hr>{$andor}-{$limit}-{$offset}-{$userId}-{$user}<br>{$criteresPlus}";

        //        if ($cols == '') {
        //            $cols = 'event_id, event_title, event_submitter, event_submitdate';
        //        }
        $tEvent = $xoopsDB->prefix('extcal_event');
        $tCat   = $xoopsDB->prefix('extcal_cat');
        $sql    = "SELECT {$tEvent}.*, {$tCat}.cat_name AS categorie, {$tCat}.cat_color " . " FROM {$tEvent}, {$tCat}" . " WHERE {$tEvent}.cat_id = {$tCat}.cat_id AND event_approved = '1'";

        $authorizedAccessCats = $this->extcalPerm->getAuthorizedCat($user, 'extcal_cat_view');
        $count                = count($authorizedAccessCats);
        if ($count > 0) {
            $in = '(' . $authorizedAccessCats[0];
            array_shift($authorizedAccessCats);
            foreach ($authorizedAccessCats as $authorizedAccessCat) {
                $in .= ',' . $authorizedAccessCat;
            }
            $in .= ')';
        } else {
            $in = '(0)';
        }
        $sql .= " AND {$tEvent}.cat_id IN " . $in . '';
        if (0 != $userId) {
            $sql .= " AND event_submitter = '" . $userId . "'";
        }

        //echoArray($queryarray,false);
        if (is_array($queryarray)) {
            /*
            $sql .= " AND ((event_title LIKE '%$queryarray[0]%' OR event_desc LIKE '%$queryarray[0]%' OR event_contact LIKE '%$queryarray[0]%' OR event_address LIKE '%$queryarray[0]%')";
            for ($i = 1; $i < $count; ++$i) {
                $sql .= " $andor ";
                $sql .= "(event_title LIKE '%$queryarray[0]%' OR event_desc LIKE '%$queryarray[0]%' OR event_contact LIKE '%$queryarray[0]%' OR event_address LIKE '%$queryarray[0]%')";
            }
            $sql .= ") ";
            */

            $tFields = ['event_title', 'event_desc', 'event_contact', 'event_address', 'cat_name'];
            $t       = [];
            foreach ($queryarray as $i => $iValue) {
                $t1[] = " %1\$s LIKE '#{$queryarray[$i]}#' ";
            }

            $flt = '(' . implode(" {$andor} ", $t1) . ')';

            $t = [];
            foreach ($tFields as $h => $hValue) {
                $t[] = sprintf($flt, $tFields[$h]);
            }

            $filtre = implode(' OR ', $t);
            $filtre = str_replace('#', '%', $filtre);
            $sql    .= " AND ($filtre)";
        }

        if ('' != $criteresPlus) {
            $sql .= ' AND ' . $criteresPlus;
        }
        $sql .= ' ORDER BY event_id DESC';

        $result = $xoopsDB->query($sql, $limit, $offset);
        $ret    = [];
        $i      = 0;
        if ($xoopsSearch) {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                $ret[$i]['image'] = 'assets/images/icons/extcal.gif';
                $ret[$i]['link']  = 'event.php?event=' . $myrow['event_id'];
                $ret[$i]['title'] = $myrow['event_title'];
                $ret[$i]['time']  = $myrow['event_submitdate'];
                $ret[$i]['uid']   = $myrow['event_submitter'];
                ++$i;
            }
        } else {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                $myrow['cat']['cat_name']  = $myrow['cat_name'];
                $myrow['cat']['cat_color'] = $myrow['cat_color'];
                $ret[]                     = $myrow;
                ++$i;
            }
        }

        return $ret;
    }

    /**
     * @param $event
     * @param $eventsArray
     * @param $startPeriod
     * @param $endPeriod
     */
    public function addEventToCalArray(&$event, &$eventsArray, $startPeriod, $endPeriod)
    {
        global $timeHandler, $xoopsUser, $month, $year;

        // Calculating the start and the end of the event
        $startEvent = $event['event_start'];
        $endEvent   = $event['event_end'];

        // This event start before this month and finish after
        if ($startEvent < $startPeriod && $endEvent > $endPeriod) {
            $endFor = date('t', mktime(0, 0, 0, $month, 1, $year));
            for ($i = 1; $i <= $endFor; ++$i) {
                $event['status']   = 'middle';
                $eventsArray[$i][] = $event;
            }
            // This event start before this month and finish during
        } else {
            if ($startEvent < $startPeriod) {
                $endFor = date('j', $endEvent);
                for ($i = 1; $i <= $endFor; ++$i) {
                    $event['status']   = ($i != $endFor) ? 'middle' : 'end';
                    $eventsArray[$i][] = $event;
                }
                // This event start during this month and finish after
            } else {
                if ($endEvent > $endPeriod) {
                    $startFor = date('j', $startEvent);
                    $endFor   = date('t', mktime(0, 0, 0, $month, 1, $year));
                    for ($i = $startFor; $i <= $endFor; ++$i) {
                        $event['status']   = ($i == $startFor) ? 'start' : 'middle';
                        $eventsArray[$i][] = $event;
                    }
                    // This event start and finish during this month
                } else {
                    $startFor = date('j', $startEvent);
                    $endFor   = date('j', $endEvent);
                    for ($i = $startFor; $i <= $endFor; ++$i) {
                        if ($startFor == $endFor) {
                            $event['status'] = 'single';
                        } else {
                            if ($i == $startFor) {
                                $event['status'] = 'start';
                            } else {
                                if ($i == $endFor) {
                                    $event['status'] = 'end';
                                } else {
                                    $event['status'] = 'middle';
                                }
                            }
                        }
                        $eventsArray[$i][] = $event;
                    }
                }
            }
        }
    }

    //-------------------------------------------------
} // -------- Fin e la classe ---------------------
