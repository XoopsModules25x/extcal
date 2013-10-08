<?php


include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
include_once ('header.php');



include_once XOOPS_ROOT_PATH . '/class/template.php';
$eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
if (!isset($_GET['cat'])) {
    $cat = 0;
} else {
    $cat = intval($_GET['cat']);
}
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');
$tpl = new XoopsTpl();
$tpl->caching=0;
$tpl->xoops_setCacheTime($xoopsModuleConfig['rss_cache_time'] * _EXTCAL_TS_MINUTE);
if (!$tpl->is_cached('db:extcal_rss.html', $cat)) {
    $events = $eventHandler->getUpcommingEvent($xoopsModuleConfig['rss_nb_event'], $cat);
    if (is_array($events)) {
        $tpl->assign('channel_title', xoops_utf8_encode(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
        $tpl->assign('channel_link', XOOPS_URL . '/');
        $tpl->assign('channel_desc', xoops_utf8_encode(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
        $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
        $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
        $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
        $tpl->assign('channel_category', 'Event');
        $tpl->assign('channel_generator', 'XOOPS');
        $tpl->assign('channel_language', _LANGCODE);
        $tpl->assign(
            'image_url', XOOPS_URL . '/modules/extcal/images/extcal_logo.png'
        );
        $tpl->assign('image_width', 92);
        $tpl->assign('image_height', 52);
        foreach (
            $events as $event
        ) {
            $tpl->append(
                'items', array(
                              'title' => xoops_utf8_encode(htmlspecialchars($event->getVar('event_title'), ENT_QUOTES)), 'link'
                    => XOOPS_URL . '/modules/extcal/event.php?event='
                        . $event->getVar('event_id'), 'guid'
                    => XOOPS_URL . '/modules/extcal/event.php?event='
                        . $event->getVar('event_id'), 'pubdate' => formatTimestamp($event->getVar('event_start'), 'rss'), 'description' => xoops_utf8_encode(htmlspecialchars($event->getVar('event_desc'), ENT_QUOTES))
                         )
            );
        }
    }
}
$tpl->display('db:extcal_rss.html', $cat);
?>
