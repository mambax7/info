<?php
if (!defined('_INFO_ADMINTITLE')) {
define('_INFO_ADMINTITLE', 'Content');

define('_INFO_SUBMENU', 'Main Menu?');

define('_INFO_ADDCONTENT', 'Add page');
define('_INFO_EDITCONTENT', 'Save');

define('_INFO_HOMEPAGE', 'Categories');
define('_INFO_POSITION', 'Position');
define('_INFO_LINKNAME', 'Link Name');
define('_INFO_EXTURL',
       'File or URL <br /><small>External URL has to begin with http:// !</small>');
define('_INFO_STORYID', 'ID');
define('_INFO_VISIBLE', 'Navi-Block?');
define('_INFO_CONTENT', 'Text');
define('_INFO_URL', 'Select File');
define('_INFO_UPLOAD', 'Upload');
define('_INFO_VISIBLE_GROUP', 'Select Group visibility');

define('_INFO_LINKID', 'Weight');
define('_INFO_ACTION', 'Action');
define('_INFO_EDIT', 'Edit');
define('_INFO_DELETE', 'Delete');

define('_INFO_DISABLECOM','Disable comments');
define('_INFO_DBUPDATED','Database Updated Successfully!');
define('_INFO_ERRORINSERT','Error while updating database!');
define('_INFO_RUSUREDEL','Are you sure you want to delete this content?');
define('_INFO_DISABLEBREAKS','Disable automatic Linebreak Conversion (Activate when using HTML)');
define('_INFO_URLART','Page Type');
define('_INFO_URL_NORMAL','Website');
define('_INFO_URL_KATEGORIE','Category Title');
define('_INFO_URL_CAT','Add Category');
define('_INFO_URL_INTLINK','New Link to '.XOOPS_URL.'/');
define('_INFO_URL_EXTLINK','External Link');
define('_INFO_URL_INTDATEI','Local File');

define('_INFO_LISTBLOCKCAT','List Blocks');
define('_INFO_ADDBLOCKCAT','Add Blocks');
define('_INFO_EDITBLOCKCAT','Edit Blocks');
define('_INFO_ERROR_NOBLOCKTITLE','Block title required!');
define('_INFO_ERROR_ISSETBLOCKTITLE','Block title already exists!');
define('_INFO_ERROR_NOINSERTDB','Could not update Content!');
define('_INFO_SETDELETE','Are you sure you want to delete?');
define('_INFO_SETDELETE_FRAGE','Are you sure you want to delete this Category with all its pages:<br /><b>%s</b> ?');
define('_INFO_SETDELETE_LIST','This Category has <b>%s</b> Pages(s).');
define('_INFO_DELFLUSH','Delete canceled.');
define('_INFO_ERROR_NODEFAULT','Default-Category can\'t be deleted');
define('_INFO_INFODELETE_FRAGE','Delete this Page:<br /><b>%s</b><br /> ?');
define('_INFO_ADMIN_URLINTERN','Internal Link cannot start with <b>http://</b> !');
define('_INFO_LAST_EDITED','Last Edit');
define('_INFO_LAST_EDITEDTEXT','by <b>%s</b> on <b>%s</b>');

//Added in 1.04
define('_INFO_FRONTPAGE','Frontpage');
define('_INFO_CLICK','Clickable');
define('_INFO_SELF','open in new Window');

//Added in 1.05
define('_AM_HP_SEITE','Module Homepage:');
define('_AM_HP_SEITE_NODEF','none selected');
define('_INFO_AM_GROUP','Group');
define('_INFO_URL_IFRAME','Page with iFrame');
define('_INFO_URL_INTERN','URL of the Page<br />do not enter <b>'.XOOPS_URL.'</b> !<br>For '.XOOPS_URL.' add <b>/</b> only');
define('_INFO_URL_EXTERN','External URL<br />Has to start with http:// or https:// !');
define('_INFO_URL_DATEI','Path of File<br />Must start with <b>XOOPS_ROOT_PATH</b>!');
define('_INFO_URL_FRAME','URL of the Page<br />Has to start with http:// or https:// !');
define('_INFO_URL_FRAME_HEIGHT','Heigth from the iFrame<br />in Pixel !');
define('_INFO_URL_FRAME_BORDER','Border of the iFrame<br />in Pixel (0 -> no Border)!');
define('_INFO_ADMIN_ERRURL','URL has to begin with http:// or https:// !');

define('_MIC_INFO_GOMOD', 'Go to module');
define('_MIC_INFO_ADMENU0', 'Module Defaults');
define('_MIC_INFO_ADMENU1', 'Create/Edit content page');

//Added in 1.06
define('_INFO_TITLESICHT','Show title');
define('_INFO_FOOTERSICHT','Show footnote');
define('_INFO_URL_FRAME_WIDTH','Width of iFrame<br />in % (0 = 100%)');
define('_INFO_URL_FRAME_ALIGN','Align iFrame');


//Added in 2.0
define('_AM_INFO_PERMISSIONS', 'Set Permission');
define('_AM_INFO_CANCREATE', 'Add new page');
define('_AM_INFO_CANUPDATEALL', 'Edit all info pages');
define('_AM_INFO_CANUPDATE', 'Update page');
define('_AM_INFO_CANACCESS', 'Publish page');
define('_AM_INFO_CANDELETE', 'Delete page');

define('_AM_INFO_CANFREEALL', 'Publish all pages');

define('_AM_INFO_CANUPDATE_CAT', 'Select Category');
define('_AM_INFO_CANUPDATE_POSITION', 'Select menu position');
define('_AM_INFO_CANUPDATE_GROUPS', 'Update Groups');
define('_AM_INFO_CANUPDATE_SITEART', 'Set Page options ');
define('_INFO_ADMIN_ERRDATEI','Selected file doesn\'t exist');
define('_INFO_OWNER','Author');
define('_AM_INFO_CANUPDATE_SITEFULL','Publish now');
define('_INFO_LINKVERSION','Current Version');
define('_INFO_VISIBLE_LEFTBLOCK','Show left Blocks');
define('_INFO_VISIBLE_RIGHTBLOCK','Show right Blocks');

define('_AM_INFO_CANFREEPERM','Set Permissions');

// Added in V 2.3
define('_INFO_URL_PHP','PHP-Code Info');

// Added in V 2.5
define('_AM_INFO_MODULEADMIN_MISSING','ModuleAdmin is missing! You can not run.');
define('_AM_INFO_TOCKEN_MISSING','Security tocken halt expired or incorrect. Action can not be executed.');
define('_AM_INFO_SITEUPDATE','Update Settings');
define('_AM_INFO_INAKTIVE','[disabled]');
define('_AM_INFO_NEWADDSITE','new Site');
define('_AM_INFO_INFOBOX_CAT','You have created <b>%s</b> categories.');
define('_AM_INFO_SITEDEL_HP','Should the current Home <br /><b>%s</b><br /> be disabled?');
define('_AM_INFO_INFOBOX_SITE','There are <b>%s</b> sites in these categories.');
define('_INFO_INFODELETE_AENDERUNG','If the change to the side <br /><br />%s discarded really?');
define('_AM_INFO_INFOBOX_WAITSITE','It <b>%s </b> dirty pages waiting for processing.');
define('_AM_INFO_CANALLOWHTML', 'use HTML');

//Added in Version 2.7
define('_AM_INFO_CANALLOWUPLOAD', 'Can upload files');
define('_AM_INFO_UPLOAD', 'upload file to '
                          . XOOPS_URL . '/modules/'
                          . basename(dirname(dirname(__DIR__) ) ) . '/files<br />max. Filesize: %s MB');
}
