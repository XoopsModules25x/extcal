<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

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

    $eventHandler = xoops_getModuleHandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

    return $eventHandler->getSearchEvent3($queryarray, $andor, $limit, $offset, $userid, $xoopsUser);
}
