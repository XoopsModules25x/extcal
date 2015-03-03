<?php

// defined("XOOPS_ROOT_PATH") || exit("XOOPS root path not defined");

/**
 * @param $category
 * @param $itemId
 *
 * @return mixed
 */
function extcal_notify_iteminfo($category, $itemId)
{
    if ($category == 'global' || $category == 'cat') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    if ($category == 'event') {
        $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
        $event        = $eventHandler->getEvent($itemId, 0, true);
        $item['name'] = $event->getVar('event_title');
        $item['url']  = XOOPS_URL . '/modules/extcal/event.php?event=' . $event->getVar('event_id');

        return $item;
    }

return null;
}
