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

use XoopsModules\Info;

$moduleDirName = basename(dirname(__DIR__));

// require_once  dirname(__DIR__) . '/class/Helper.php';
//require_once  dirname(__DIR__) . '/include/common.php';
$helper = info\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

require_once dirname(__DIR__) . '/include/constants.php';
$infowaitHandler = new Info\MyInfoHandler($GLOBALS['xoopsDB'], $moduleDirName . '_bak');
$wait_site       = $infowaitHandler->getCount();

$adminmenu[] = [
    'title' => _MI_INFO_INDEX,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_INFO_ADMENU2,
    'link'  => 'admin/admin_categorie.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _MI_INFO_ADMENU3,
    'link'  => 'admin/admin_seiten.php',
    'icon'  => $pathIcon32 . '/view_detailed.png',
];

$adminmenu[] = [
    'title' => '(' . $wait_site . ') ' . _MI_INFO_ADMENU5,
    'link'  => 'admin/admin_seiten.php?op=approved',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_INFO_ADMENU4,
    'link'  => 'admin/admin_permission.php',
    'icon'  => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_INFO_ADMENU_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
