<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function extcal_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsUser;

    $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);

    return $eventHandler->getSearchEvent3($queryarray, $andor, $limit, $offset, $userid, $xoopsUser);

}