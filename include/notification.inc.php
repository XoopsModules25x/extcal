<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

use XoopsModules\Extcal;

/**
 * @param $category
 * @param $itemId
 *
 * @return mixed
 */
function extcal_notify_iteminfo($category, $itemId)
{
    if ('global' === $category || 'cat' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    if ('event' === $category) {
        $eventHandler = Extcal\Helper::getInstance()->getHandler(_EXTCAL_CLN_EVENT);
        $event        = $eventHandler->getEvent($itemId, 0, true);
        $item['name'] = $event->getVar('event_title');
        $item['url']  = XOOPS_URL . '/modules/extcal/event.php?event=' . $event->getVar('event_id');

        return $item;
    }

    return '';
}
