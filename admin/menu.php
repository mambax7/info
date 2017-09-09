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

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

require_once dirname(__DIR__) . '/include/constants.php';
require_once dirname(__DIR__) . '/class/info.php';
$infowaitHandler = new InfoInfoHandler($GLOBALS['xoopsDB'], $moduleDirName . '_bak');
$wait_site       = $infowaitHandler->getCount();

$adminmenu               = [];
$i                       = 0;
'title' =>  _MI_INFO_INDEX,
'link' =>  'admin/index.php',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/home.png';

'title' =>  _MI_INFO_ADMENU2,
'link' =>  'admin/admin_categorie.php',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/category.png';

'title' =>  _MI_INFO_ADMENU3,
'link' =>  'admin/admin_seiten.php',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/view_detailed.png';

'title' =>  '(' . $wait_site . ') ' . _MI_INFO_ADMENU5,
'link' => approved',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/manage.png';

'title' =>  _MI_INFO_ADMENU4,
'link' =>  'admin/admin_permission.php',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/permissions.png';

'title' =>  _MI_INFO_ADMENU_ABOUT,
'link' =>  'admin/about.php',
$adminmenu[$i++]['icon'] = $pathIcon32 . '/about.png';

unset($i);
