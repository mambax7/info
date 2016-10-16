<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  @package print.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: print.php 73 2013-03-19 20:14:02Z alfred $

include '../../mainfile.php';

error_reporting(0);
$xoopsLogger->activated = false;

xoops_loadLanguage( 'modinfo', $xoopsModule->dirname() );
$id = isset($_GET['content']) ? (int)$_GET['content'] : 0;
$infopage = isset($_GET['page']) ? (int)$_GET['page'] : 0;
if ( empty($id) ) {
	redirect_header('index.php');
}

global $xoopsConfig, $xoopsModule, $xoopsDB,$xoopsConfigMetaFooter;
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'._LANGCODE.'" lang="'._LANGCODE.'">
    <head>
    <meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
    <meta http-equiv="content-language" content="'._LANGCODE.'" />
    <meta name="robots" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_robots']).'" />
    <meta name="keywords" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_keywords']).'" />
    <meta name="description" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_desc']).'" />
    <meta name="rating" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_rating']).'" />
    <meta name="author" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_author']).'" />
    <meta name="copyright" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_copyright']).'" />
    <meta name="generator" content="SIMPLE-XOOPS - http://www.simple-xoops.de" />
    <title>'.htmlspecialchars($xoopsConfig['sitename']).'</title>';
echo '</head>';
   

$result = $xoopsDB->query('SELECT info_id, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address FROM '
                          . $xoopsDB->prefix($xoopsModule->dirname()) . " WHERE info_id=$id");
list($info_id,$title,$text,$visible,$nohtml,$nosmiley,$nobreaks,$nocomments,$link,$address) = $xoopsDB->fetchRow($result);
echo '<body bgcolor="#FFFFFF" text="#000000" topmargin="10" style="font:12px arial, helvetica, san serif;" onLoad="window.print()">';
echo '	<table border="0" width="640" cellpadding="10" cellspacing="1" style="border: 1px solid #000000;" align="center">';
echo '		<tr>';
if (file_exists(XOOPS_ROOT_PATH.'/themes/'.$xoopsConfig['theme_set'].'/logo.gif')) 
{
    echo '<td align="left"><img src="'.XOOPS_URL.'/themes/'.$xoopsConfig['theme_set'].'/logo.gif" border="0" alt="'.$xoopsConfig['sitename'].'" title="'.$xoopsConfig['sitename'].'" /></td>';
} 
elseif (file_exists(XOOPS_ROOT_PATH.'/themes/'.$xoopsConfig['theme_set'].'/images/logo.gif')) 
{
    echo '<td align="left"><img src="'.XOOPS_URL.'/themes/'.$xoopsConfig['theme_set'].'/images/logo.gif" border="0" alt="'.$xoopsConfig['sitename'].'" title="'.$xoopsConfig['sitename'].'" /></td>';
} 
else 
{
    echo '<td align="left"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getInfo('dirname') . '/' . trim($xoopsModule->getInfo('image')) . '" alt="" /></td>';
}
echo '<td><strong>'.$title.'</strong></td>';
echo '</tr>';
echo '<tr valign="top">';
echo '<td style="padding-top:0px;">';
$myts =& MyTextSanitizer::getInstance();	
$text = str_replace('{X_XOOPSURL}', XOOPS_URL.'/', $text);
$text = str_replace('{X_SITEURL}', XOOPS_URL.'/', $text);
if (is_object($xoopsUser))
{
    $text = str_replace('{X_XOOPSUSER}', $xoopsUser->getVar('uname'), $text);
    $text = str_replace('{X_XOOPSUSERID}', $xoopsUser->getVar('uid'), $text);
} 
else 
{
    $text = str_replace('{X_XOOPSUSER}',_GUESTS, $text);
    $text = str_replace('{X_XOOPSUSERID}', '0', $text);
}
if ($link==4) 
{
	if (substr($address == '/', 0, 1) || substr($address == "\\", 0, 1)) $address =substr($address, 1);
	$file = XOOPS_ROOT_PATH . '/' . $address;
	if (file_exists($file)) 
    {
		ob_start();
	    include $file;
	    $text = ob_get_contents();
        ob_end_clean();
	}
} 
elseif ( trim($text) != '' ) 
{
	$text = str_replace('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>','[pagebreak]',$text);
    $text = str_replace('<div style="page-break-after: always;"><span style="display: none;"> </span></div>','[pagebreak]',$text);
    $text = str_replace('<div style="page-break-after: always;"><span style="display: none;"></span></div>','[pagebreak]',$text);
	$infotext = explode('[pagebreak]', $text);
	$info_pages = count($infotext);
	if ($info_pages > 0) 
    {
		$text = $infotext[$infopage];
	} 
} 
else 
{
    $text= '';
}
$html = ($nohtml == 1) ? 0 : 1;        
$nobreaks = ($html == 1) ? 0 : 1;
$smiley = ($nosmiley == 1) ? 0 : 1;        
$text = $myts->displayTarea($text,$html,$smiley,1,1,$nobreaks);
echo $text;   
echo '</td>';
echo '</tr>';
echo '</table>';
echo '	<table border="0" width="640" cellpadding="10" cellspacing="1" align="center"><tr><td>';
printf(_INFO_THISCOMESFROM,$xoopsConfig['sitename']);
echo '<br /><a href="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/index.php?content='.$id.'&page='.$infopage.'">'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/index.php?content='.$id.'&page='.$infopage.'</a>';
echo '</td></tr></table></body>';
echo '</html>';

