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

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$anz_cat     = $catHandler->getCount();
$anz_site    = $infoHandler->getCount();
$wait_site   = $infowaitHandler->getCount();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->addInfoBox(_INFO_ADMINTITLE);

$adminObject->addInfoBoxLine(_INFO_ADMINTITLE, '<infotext>' . sprintf(_AM_INFO_INFOBOX_CAT, $anz_cat) . '</infotext>');
$adminObject->addInfoBoxLine(_INFO_ADMINTITLE, '<infotext>' . sprintf(_AM_INFO_INFOBOX_SITE, $anz_site) . '</infotext>');
$adminObject->addInfoBoxLine(_INFO_ADMINTITLE, '<infotext></infotext>');
$adminObject->addInfoBoxLine(_INFO_ADMINTITLE, '<infotext>' . _AM_INFO_INFOBOX_WAITSITE . '</infotext>', $wait_site, 'Red');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

xoops_cp_footer();
