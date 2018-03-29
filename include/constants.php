<?php namespace XoopsModules\Info;

// namespace XoopsModules\Info;
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

if (defined('_CON_INFO_PERMNAME')) {
    return;
}

define('_CON_INFO_PERMNAME', 'InfoPerm');

define('_CON_INFO_CANCREATE', 1);
define('_CON_INFO_CANUPDATE', 2);
define('_CON_INFO_CANUPDATEALL', 3);

//erstellen
define('_CON_INFO_ALLCANUPDATE_CAT', 20);
define('_CON_INFO_ALLCANUPDATE_POSITION', 21);
define('_CON_INFO_ALLCANUPDATE_GROUPS', 22);
define('_CON_INFO_ALLCANUPDATE_SITEART', 23);
define('_CON_INFO_ALLCANUPDATE_SITEFULL', 24);
define('_CON_INFO_ALLCANUPDATE_HTML', 25);
define('_CON_INFO_ALLCANUPLOAD', 26);

//updaten
define('_CON_INFO_CANUPDATE_CAT', 50);
define('_CON_INFO_CANUPDATE_POSITION', 51);
define('_CON_INFO_CANUPDATE_GROUPS', 52);
define('_CON_INFO_CANUPDATE_SITEART', 53);
define('_CON_INFO_CANUPDATE_SITEFULL', 54);
define('_CON_INFO_CANUPDATE_DELETE', 55);
