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
//  @package index.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: index.php 69 2013-01-06 18:35:44Z alfred $

include_once "admin_header.php";
xoops_cp_header();        

$anz_cat 	  = $cat_handler->getCount();
$anz_site 	= $info_handler->getCount();
$wait_site 	= $infowait_handler->getCount();

$indexAdmin->addInfoBox(_INFO_ADMINTITLE) ;

$indexAdmin->addInfoBoxLine(_INFO_ADMINTITLE, "<infotext>" . sprintf(_AM_INFO_INFOBOX_CAT,$anz_cat) ."</infotext>") ;
$indexAdmin->addInfoBoxLine(_INFO_ADMINTITLE, "<infotext>" . sprintf(_AM_INFO_INFOBOX_SITE,$anz_site) ."</infotext>") ;
$indexAdmin->addInfoBoxLine(_INFO_ADMINTITLE, "<infotext></infotext>") ;
$indexAdmin->addInfoBoxLine(_INFO_ADMINTITLE, "<infotext>" . _AM_INFO_INFOBOX_WAITSITE ."</infotext>", $wait_site, 'Red') ;

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

xoops_cp_footer();
?>
