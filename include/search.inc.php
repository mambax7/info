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
//  @package search.inc.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: search.inc.php 74 2013-03-29 20:25:05Z alfred $
      
if( ! defined( 'XOOPS_ROOT_PATH' ) ) die('XOOPS_ROOT_PATH not defined!');

include_once dirname(__DIR__) . '/include/function.php';
$module_name = basename( dirname(__DIR__)) ;
    
eval('
function '.$module_name.'_search($queryarray, $andor, $limit, $offset, $userid) {
    global $xoopsDB, $xoopsConfig,$xoopsUser, $xoopsModuleConfig;
	$module_name = basename( dirname(dirname( __FILE__ ))) ; 
    $smoduleHandler = xoops_gethandler("module");
	$smodule = $smoduleHandler->getByDirname($module_name);
	$sconfigHandler = xoops_gethandler("config");
    $sconfigs = $sconfigHandler->getConfigList($smodule->getVar("mid"));
	if ($userid>0) {
	  if (intval($sconfigs[$module_name."_linklist"]) == 0) return;
	}
    $sgroups = ($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
	xoops_loadLanguage("main", $module_name );	
	$sql = "SELECT info_id,title,text,visible_group,edited_time,cat FROM ".$xoopsDB->prefix("'.$module_name.'")." WHERE link!=3";
	if ( $userid != 0 ) {
		$sql .= " AND edited_user=$userid ";
    }
	
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if ( is_array($queryarray) && $count = count($queryarray) ) {
      $sql .= " AND (text LIKE \'%$queryarray[0]%\' OR title LIKE \'%$queryarray[0]%\'";
      for($i=1;$i<$count;$i++){
        $sql .= ") $andor (";
        $sql .= "text LIKE \'%$queryarray[$i]%\' OR title LIKE \'%$queryarray[$i]%\'";
      }
      $sql .= ")";
    }
    $seo = (!empty($sconfigs[$module_name."_seourl"]) && $sconfigs[$module_name."_seourl"]>0) ? intval($sconfigs[$module_name."_seourl"]) : 0;
    $sql .= " ORDER BY info_id ASC";
	$result = $xoopsDB->query($sql,$limit,$offset);
    $ret = array();
    $i = 0;
	while($myrow = $xoopsDB->fetchArray($result)) {
	  $vsgroup=explode (",", $myrow["visible_group"]);
      $vscount=count($vsgroup)-1;
      $visible=0;			
      while ($vscount > -1) {
        if (in_array($vsgroup[$vscount], $sgroups)) {
	      $visible = 1;
		  $vscount = 0;
	    }
        $vscount--;
      }
      if ($visible == 1) {
        $ret[$i]["image"] = "images/content.gif";
		$mode=array("seo"=>$seo,"id"=>$myrow[\'info_id\'],"title"=>$myrow[\'title\'],"dir"=>$module_name,"cat"=>$myrow[\'cat\']);
		$ret[$i]["link"] = makeSeoUrl($mode);
        $ret[$i]["title"] = $myrow["title"];
	    $ret[$i]["uid"] = "0";
		$ret[$i]["time"] = $myrow["edited_time"];
		$i++;
	  }
    }
    return $ret;
}
');
