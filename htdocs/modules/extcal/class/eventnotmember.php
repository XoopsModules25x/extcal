<?php

// defined("XOOPS_ROOT_PATH") || exit("XOOPS root path not defined");

include_once XOOPS_ROOT_PATH . '/modules/extcal/class/ExtcalPersistableObjectHandler.php';

/**
 * Class ExtcalEventnotmember
 */
class ExtcalEventnotmember extends XoopsObject
{

    function ExtcalEventnotmember()
    {
        $this->initVar('eventnotmember_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('event_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, true);
    }

}

/**
 * Class ExtcalEventnotmemberHandler
 */
class ExtcalEventnotmemberHandler extends ExtcalPersistableObjectHandler
{

    /**
     * @param $db
     */
    function __construct(&$db)
    {
        parent::__construct($db, 'extcal_eventnotmember', _EXTCAL_CLN_NOT_MEMBER, array('event_id', 'uid'));
    }

    /**
     * @param $varArr
     */
    function createEventnotmember($varArr)
    {
        $eventnotmember = $this->create();
        $eventnotmember->setVars($varArr);

        if ($this->insert($eventnotmember, true)) {

            $eventMemberHandler = xoops_getmodulehandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);
            $eventMemberHandler->delete(array($varArr['event_id'], $varArr['uid']));

        }
    }

    /**
     * @param $key
     *
     * @return bool
     */
    function deleteEventnotmember($key)
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
        $memberHandler  = xoops_gethandler('member');
        $eventNotMember = $this->getObjects(new Criteria('event_id', $eventId));
        $count          = count($eventNotMember);
        if ($count > 0) {
            $in = '(' . $eventNotMember[0]->getVar('uid');
            array_shift($eventNotMember);
            foreach (
                $eventNotMember as $member
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
