<?php

// defined("XOOPS_ROOT_PATH") || exit("XOOPS root path not defined");

include_once XOOPS_ROOT_PATH . '/modules/extcal/class/ExtcalPersistableObjectHandler.php';

/**
 * Class ExtcalEventmember
 */
class ExtcalEventmember extends XoopsObject
{

    function __construct()
    {
        $this->initVar('eventmember_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('event_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('status', XOBJ_DTYPE_INT, 0, true);
    }

}

/**
 * Class ExtcalEventmemberHandler
 */
class ExtcalEventmemberHandler extends ExtcalPersistableObjectHandler
{
    /**
     * @param $db
     */
    function __construct(&$db)
    {
        parent::__construct($db, 'extcal_eventmember', _EXTCAL_CLN_MEMBER, array('event_id', 'uid'));
    }

    /**
     * @param $varArr
     */
    function createEventmember($varArr)
    {
        $eventmember = $this->create();
        $eventmember->setVars($varArr);
        if ($this->insert($eventmember, true)) {

            $eventNotMemberHandler = xoops_getmodulehandler(_EXTCAL_CLS_NOT_MEMBER, _EXTCAL_MODULE);
            $eventNotMemberHandler->delete(array($varArr['event_id'], $varArr['uid']));

        }
    }

    /**
     * @param $key
     *
     * @return bool
     */
    function deleteEventmember($key)
    {
        return $this->delete($key, true);
    }

    /**
     * @param $eventId
     *
     * @return mixed
     */
    function getMembers($eventId)
    {
        $memberHandler = xoops_gethandler('member');
        $eventMember   = $this->getObjects(new Criteria('event_id', $eventId));
        $count         = count($eventMember);
        if ($count > 0) {
            $in = '(' . $eventMember[0]->getVar('uid');
            array_shift($eventMember);
            foreach (
                $eventMember as $member
            ) {
                $in .= ',' . $member->getVar('uid');
            }
            $in .= ')';
            $criteria = new Criteria('uid', $in, 'IN');
        } else {
            $criteria = new Criteria('uid', '(0)', 'IN');
        }

        return $memberHandler->getUsers($criteria, true);
    }

    /**
     * @param $eventId
     *
     * @return int
     */
    function getNbMember($eventId)
    {
        $criteria = new Criteria('event_id', $eventId);

        return $this->getCount($criteria);
    }

}
