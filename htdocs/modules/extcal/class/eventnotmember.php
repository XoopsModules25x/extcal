<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/modules/extcal/class/ExtcalPersistableObjectHandler.php';

/**
 * Class ExtcalEventNotMember
 */
class ExtcalEventNotMember extends XoopsObject
{

    /**
     * ExtcalEventNotMember constructor.
     */
    public function __construct()
    {
        $this->initVar('eventnotmember_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('event_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, true);
    }
}

/**
 * Class ExtcalEventNotMemberHandler
 */
class ExtcalEventNotMemberHandler extends ExtcalPersistableObjectHandler
{

    /**
     * @param $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'extcal_eventnotmember', _EXTCAL_CLN_NOT_MEMBER, array('event_id', 'uid'));
    }

    /**
     * @param $varArr
     */
    public function createEventNotMember($varArr)
    {
        $eventnotmember = $this->create();
        $eventnotmember->setVars($varArr);

        if ($this->insert($eventnotmember, true)) {
            $eventMemberHandler = xoops_getModuleHandler(_EXTCAL_CLS_MEMBER, _EXTCAL_MODULE);
            $eventMemberHandler->delete(array($varArr['event_id'], $varArr['uid']));
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteEventNotMember($id)
    {
        return $this->delete($id, true);
    }

    /**
     * @param $eventId
     *
     * @return mixed
     */
    public function getMembers($eventId)
    {
        $memberHandler  = xoops_getHandler('member');
        $eventNotMember =& $this->getObjects(new Criteria('event_id', $eventId));
        $count          = count($eventNotMember);
        if ($count > 0) {
            $in = '(' . $eventNotMember[0]->getVar('uid');
            array_shift($eventNotMember);
            foreach ($eventNotMember as $member) {
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
    public function getNbMember($eventId)
    {
        $criteria = new Criteria('event_id', $eventId);

        return $this->getCount($criteria);
    }
}
