<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function extcal_notify_iteminfo($category, $itemId)
{
    if ($category == 'global' || $category == 'cat') {
        $item['name'] = '';
        $item['url'] = '';
        return $item;
    }

    if ($category == 'event') {
        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $event = $eventHandler->getEvent($itemId, 0, true);
        $item['name'] = $event->getVar('event_title');
        $item['url'] = XOOPS_URL . '/modules/extcal/event.php?event='
            . $event->getVar('event_id');
        return $item;
    }
}
?>
