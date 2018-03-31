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
 * @package      comment_delete.php
 * @since
 * @author       XOOPS Development Team
 * @author       Dirk Herrmann <alfred@simple-xoops.de>
 */

use XoopsModules\Gwiki;
/** @var Gwiki\Helper $helper */
$helper = Gwiki\Helper::getInstance();

include __DIR__ . '/../../mainfile.php';
$com_itemid = \Xmf\Request::getInt('com_itemid', 0, 'GET');
if (!$xoopsUser && empty($helper->getConfig('com_anonpost'))) {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}
if ($com_itemid > 0) {
    // Get link title
    $sql            = 'SELECT title FROM ' . $xoopsDB->prefix('info') . ' WHERE info_id=' . $com_itemid . '';
    $result         = $xoopsDB->query($sql);
    $row            = $xoopsDB->fetchArray($result);
    $com_replytitle = $row['title'];
    include XOOPS_ROOT_PATH . '/include/comment_new.php';
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}
