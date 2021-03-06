<?php
if (!defined('_MIC_INFO_NAME')) {
    define('_MI_INFO_NAME', 'DH-INFO');
    define('_MI_INFO_DESC', 'Creating static content for your site.');
    define('_MI_INFO_PRINTER', 'Printer Friendly Page');

    define('_MI_INFO_BLOCK1', 'Menu Block');
    define('_MI_INFO_BLOCK1_DESC', 'Builds a navigation menu from content pages');
    define('_MI_INFO_BLOCK2', 'Content Block');
    define('_MI_INFO_BLOCK2_DESC', 'Builds a Block for a content pages');

    define('_MI_INFO_CONF1', 'WYSIWYG - Editor');
    define('_MI_INFO_CONF1_DESC', 'YES for Editor selection in the Form, NO for the default Editor');
    define('_MI_INFO_CONF2', "Show Link 'Create Page'");
    define('_MI_INFO_CONF2_DESC', 'If you have create permission for hand, a link in the main menu to be displayed.');
    define('_MI_INFO_CONF3', 'Generate Printer friendly pages');
    define('_MI_INFO_CONF3_DESC', 'This setting will create an Iconlink on the page, which will then generate a printer friendly page.');
    define('_MI_INFO_CONF4', 'View last edit');
    define('_MI_INFO_CONF4_DESC', '');
    define('_MI_INFO_CONF5', 'Display the blocks prevent writing');
    define('_MI_INFO_CONF5_DESC', 'Settings of the left and right blocks when calling the submit.php');
    define('_MI_INFO_TEMPL1', 'Page Layout');
    define('_MI_INFO_LASTD1', 'no Date');
    define('_MI_INFO_LASTD2', 'short Date (=> ' . formatTimestamp(time(), 's') . ')');
    define('_MI_INFO_LASTD3', 'medium Date (=> ' . formatTimestamp(time(), 'm') . ')');
    define('_MI_INFO_LASTD4', 'long Date (=> ' . formatTimestamp(time(), 'l') . ')');

    //Added in 1.04
    define('_MI_INFO_BLOCKADMIN', 'Blocksadmin');
    define('_MI_INFO_ADMENU2', 'Category Management');
    define('_MI_INFO_ADMENU3', 'Page Management');
    define('_MI_INFO_ADMENU4', 'Permissions ');

    //Added in 2.0
    define('_INFO_TOOLTIP', 'Tooltip');
    define('_MI_INFO_CONF6', 'Show Navigation as:');
    define('_MI_INFO_CONF6_DESC', '');
    define('_MI_INFO_CONF7', 'Show the page links in the Profile');
    define('_MI_INFO_CONF7_DESC', 'YES to display page links in the Profile');
    define('_MI_INFO_PAGESNAV', 'as Page number');
    define('_MI_INFO_PAGESELECT', 'as selection');
    define('_MI_INFO_PAGESIMG', 'as Image');
    define('_MI_INFO_SENDEMAIL', 'Send per E-Mail');
    define('_MI_INFO_ARTICLE', 'Interesting article on %s');
    define('_MI_INNFO_ARTFOUND', "Here is an interesting article that I've found on %s ");
    define('_MI_INFO_GUEST', 'Guest writer');
    define('_INFO_FREIGABEART', 'Release Status');
    define('_INFO_FREIGABEART_YES', 'Publish');
    define('_INFO_FREIGABEART_NO', 'Offline');
    define('_MI_INFO_ADMENU5', 'Waiting postings');
    define('_MI_INFO_ADMENU6', 'Offline postings');
    define('_MI_INFO_GESPERRT', '[Offline]');
    define('_AM_INFO_NOFRAMEOREDITOR', "<span style='font-weight: bold; color: red; '>Framework und/oder XoopsEditorPack nicht installiert!</span>");
    define('_INFO_NEW', 'NEW');
    define('_INFO_UPDATE', 'UPDATE');
    define('_MI_INFO_CONF8', 'SEO-Optimization');
    define('_MI_INFO_CONF8_DESC', 'Convert URL into SEO-friendly. You need to set mod_rewrite!');
    define('_MI_INFO_CONF9', 'Module own links');
    define('_MI_INFO_CONF9_DESC', 'if YES, external links are not visible');

    //Added in 2.5
    define('_MI_INFO_ADMENU_ABOUT', 'About');
    define('_MI_INFO_INDEX', 'Index');
    define('_MI_INFO_CREATESITE', 'Create Site');

    //Added in 2.6
    define('_MI_INFO_VIEWSITE', 'Show all pages in this category');
    define('_MI_INFO_CONF_COLS', 'Number of columns of the non-HTML Editor (min. 10)');
    define('_MI_INFO_CONF_COLS_DESC', 'Number of column (width) of the Editor (only for non-HTML editor)');
    define('_MI_INFO_CONF_ROWS', 'Number of rows in the non-HTML Editor of (at least 10)');
    define('_MI_INFO_CONF_ROWS_DESC', 'Returns the number of rows (height) of the Editor (only for non-HTML editor)');
    define('_MI_INFO_CONF_WIDTH', 'Width of the HTML Editor in percentage (10-100)');
    define('_MI_INFO_CONF_WIDTH_DESC', 'Sets the width of the Editor in percent (only for HTML-Editors)');
    define('_MI_INFO_CONF_HEIGHT', 'Height  of the HTML Editor in pixels (at least 100)');
    define('_MI_INFO_CONF_HEIGHT_DESC', 'The height of the input field of the HTML Editor in pixels.');

    define('_MI_INFO_ADMENU_HELP', 'Help');
    define('_MI_INFO_NONE', 'Show all blocks');
    define('_MI_INFO_RECHTS', 'Disable right blocks');
    define('_MI_INFO_LINKS', 'Disable left');
    define('_MI_INFO_BEIDE', 'Disabled right and left blocks');

    //2.7
    //Help
    define('_MI_INFO_DIRNAME', basename(dirname(dirname(__DIR__))));
    define('_MI_INFO_HELP_HEADER', __DIR__.'/help/helpheader.tpl');
    define('_MI_INFO_BACK_2_ADMIN', 'Back to Administration of ');
    define('_MI_INFO_OVERVIEW', 'Overview');

    //define('_MI_INFO_HELP_DIR', __DIR__);

    //help multi-page
    define('_MI_INFO_DISCLAIMER', 'Disclaimer');
    define('_MI_INFO_LICENSE', 'License');
    define('_MI_INFO_SUPPORT', 'Support');
}
