<?php



use XoopsModules\Extcal\{
    Helper,
    EventHandler
};

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return mixed
 */
function extcal_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsUser;

    $eventHandler = Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);

    return $eventHandler->getSearchEvent3($queryarray, $andor, $limit, $offset, $userid, $xoopsUser);
}
