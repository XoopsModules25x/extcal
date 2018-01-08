<?php
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

include __DIR__ . '/../../mainfile.php';
$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    // Get link title
    $sql            = 'SELECT event_title, event_desc FROM ' . $xoopsDB->prefix('extcal_event') . ' WHERE event_id=' . $com_itemid . ' ';
    $result         = $xoopsDB->query($sql);
    $row            = $xoopsDB->fetchArray($result);
    $com_replytitle = $row['event_title'];
    $com_replytext  = $row['event_desc'];
    include XOOPS_ROOT_PATH . '/include/comment_new.php';
}
