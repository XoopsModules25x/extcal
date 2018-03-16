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

use XoopsModules\Extcal;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// // require_once __DIR__ . '/ExtcalPersistableObjectHandler.php';

/**
 * Class EventmemberHandler.
 */
class EventmemberHandler extends ExtcalPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'extcal_eventmember', Eventmember::class, ['event_id', 'uid']);
    }

    /**
     * @param $varArr
     */
    public function createEventmember($varArr)
    {
        $eventmember = $this->create();
        $eventmember->setVars($varArr);
        if ($this->insert($eventmember, true)) {
            $eventNotMemberHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_NOT_MEMBER);
            $eventNotMemberHandler->deleteById([$varArr['event_id'], $varArr['uid']]);
        }
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function deleteEventmember($key)
    {
        return $this->deleteById($key, true);
    }

    /**
     * @param $eventId
     *
     * @return mixed
     */
    public function getMembers($eventId)
    {
        $memberHandler = xoops_getHandler('member');
        $eventMember   =& $this->getObjects(new \Criteria('event_id', $eventId));
        $count         = count($eventMember);
        if ($count > 0) {
            $in = '(' . $eventMember[0]->getVar('uid');
            array_shift($eventMember);
            foreach ($eventMember as $member) {
                $in .= ',' . $member->getVar('uid');
            }
            $in       .= ')';
            $criteria = new \Criteria('uid', $in, 'IN');
        } else {
            $criteria = new \Criteria('uid', '(0)', 'IN');
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
        $criteria = new \Criteria('event_id', $eventId);

        return $this->getCount($criteria);
    }
}
