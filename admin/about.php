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
 * @package      info module
 * @since
 * @author       XOOPS Development Team
 * @author       Dirk Herrmann <alfred@simple-xoops.de>
 */

include  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject  = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayAbout('PBQZ7D6LT6UBC', true);

xoops_cp_footer();
