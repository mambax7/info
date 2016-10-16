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
//  @package xoops_version.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: xoops_version.php 91 2014-04-19 20:09:50Z alfred $

if( ! defined( 'XOOPS_ROOT_PATH' ) )
 die('XOOPS_ROOT_PATH not defined!');

// read the Name of the Folder
$infoname = basename( dirname( __FILE__ )) ;

$modversion['name']		  		  	= _MI_INFO_NAME;
$modversion['version']			  	= 2.7;
$modversion['author']     			= 'Dirk Herrmann';
$modversion['description']			= _MI_INFO_DESC;
$modversion['credits']				  = 'The SIMPLE-XOOPS Project';
$modversion['help']             = 'page=help';
$modversion['license']     			= 'GNU GPL 2.0';
$modversion['license_url'] 			= 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']				  = 0;
$modversion['image']		  		  = 'images/logo.gif';
$modversion['dirname']				  = $infoname;

$modversion['author_realname'] 	= 'Dirk Herrmann';
$modversion['author_email'] 		= 'dhsoft@users.sourceforge.net';
$modversion['status_version'] 	= '2.7';

// Simple-Xoops
$modversion['simple-xoops']= true; 
$modversion['min_sxxoops'] = '1.0.0';
$modversion['sx-modul']	   = 10;

//about
$modversion['release_date']     	  = '2015/02/28';
$modversion['module_website_url'] 	= 'www.simple-xoops.de/';
$modversion['module_website_name'] 	= 'SIMPLE-XOOPS';
$modversion['module_status'] 		    = 'BETA 1';
$modversion['min_php']				      = '5.3';
$modversion['min_xoops']			      = '2.5.5';
$modversion['min_admin']			      = '1.1';
$modversion['min_db']				        = array('mysql'=>'5.0', 'mysqli'=>'5.0');
$modversion['system_menu'] 			    = 1;

$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] 				= 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] 				= 'Frameworks/moduleclasses/icons/32';

$modversion['onInstall']			= 'sql/update.php';
$modversion['onUpdate']				= 'sql/update.php';

// Tables created by sql file (without prefix!)
$modversion['tables'][0]			= $infoname;
$modversion['tables'][1]			= $infoname . '_cat';
$modversion['tables'][2]			= $infoname . '_bak';

// Admin things
$modversion['hasAdmin']				= 1;
$modversion['adminindex']			= 'admin/index.php';
$modversion['adminmenu']			= 'admin/menu.php';

// Smarty
$modversion['use_smarty']			= 1;

// Search
$modversion['hasSearch'] 			= 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = $infoname . '_search';

$modversion['hasMain'] 				= 1;

$infomod_handler =& xoops_getHandler('module');
$infomodul = $infomod_handler->getByDirname($infoname);
include_once dirname(__FILE__) . '/include/constants.php';
include_once dirname(__FILE__) . '/include/function.php';

$info_isactiv = xoops_isActiveModule($infoname);

if ($info_isactiv == true) {
	//Modul ist aktiv
  include_once dirname(__FILE__) . '/class/infotree.php';
  $id = $cat = $pid = $i = 0;

  $config_handler =& xoops_getHandler('config');
  $InfoModulConfig = $config_handler->getConfigsByCat(0, $infomodul->getVar('mid'));
  $seo = (!empty($InfoModulConfig[$infoname.'_seourl']) && $InfoModulConfig[$infoname.'_seourl']>0) ? intval($InfoModulConfig[$infoname.'_seourl']) : 0;
  $info_tree = new InfoTree($GLOBALS['xoopsDB']->prefix($infoname), 'info_id',
                            'parent_id');
	$groups =  is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
	$infoperm_handler = xoops_getHandler('groupperm');
	$show_info_perm = $infoperm_handler->getItemIds('InfoPerm', $groups, $infomodul->getVar('mid'));
	$mod_isAdmin = (is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin()) ? true : false;

	if ( ($mod_isAdmin || in_array(_CON_INFO_CANCREATE,$show_info_perm)) && $InfoModulConfig[$infoname.'_createlink'] == 1 ) {
		$modversion['sub'][$i]['name'] = _MI_INFO_CREATESITE;
    $modversion['sub'][$i]['url'] = 'submit.php';
		$i++;
	}

	$cP = array();
	$sub = array();
	xoops_load('XoopsCache');
  $para = readSeoUrl($_GET, $seo);
  $id 	= intval($para['id']);
  $cat 	= intval($para['cid']);
  $pid 	= intval($para['pid']);
	$key = $key = $infoname . '_' . 'home';
	if ( !$cP = XoopsCache::read($key) ) {
		$cP = $info_tree->getChildTreeArray($pid, 'blockid', array(), $InfoModulConfig[$infoname.'_trenner'] , '');
		XoopsCache::write($key,$cP);
  }
	if ($id > 0 ) {
		$first = $info_tree->getFirstId($id);
		$key = $GLOBALS['xoopsModule']->getVar('dirname') . '_' . 'home-'
               . $first;
		if ( !$sub = XoopsCache::read($key) ) {
			$sub = $info_tree->getAllChildId($first);
			XoopsCache::write($key,$sub);
		}
	}

  foreach ($cP as $l => $tcontent) {
    $visible	= 0;
    $vsgroup	= explode (',', $tcontent['visible_group']);
    $vscount	= count($vsgroup)-1;
    while ($vscount > -1) {
      if (in_array($vsgroup[$vscount], $groups)) $visible = 1;
      $vscount--;
    }

		if ($tcontent['st'] != 1 || $tcontent['submenu'] == 0) $visible = false;

		$data = array();
    if ($visible == 1) {
			if ($tcontent['parent_id'] != 0 && $tcontent['parent_id'] != $id) {
				if ( !in_array(intval($tcontent['info_id']),$sub) ) continue;
			}

			$prefix = (!empty($tcontent['prefix'])) ? $tcontent['prefix'] : '';
      $modversion['sub'][$i]['name'] = $prefix . $tcontent['title'];
      $mode=array(
          'seo' =>$seo, 'id' => $tcontent['info_id'], 'title' => $tcontent['title'], 'dir' =>$infoname,
          'cat' => $tcontent['cat']);
      $ctURL = str_replace(XOOPS_URL . '/modules/' . $infoname . '/', '', makeSeoUrl($mode)); //FIX for MainMenu
      $modversion['sub'][$i]['url'] = $ctURL;
			$i++;
    }
	}
	unset($cP);
}
unset($infomod_handler);


// Templates
$modversion['templates'][1]['file'] 	      = $infoname.'_index.html';
$modversion['templates'][1]['description']  = _MI_INFO_TEMPL1;


// Blocks
$modversion['blocks'][1]['file'] 			  = 'info_navigation.php';
$modversion['blocks'][1]['name'] 			  = _MI_INFO_BLOCK1;
$modversion['blocks'][1]['description'] = _MI_INFO_BLOCK1_DESC;
$modversion['blocks'][1]['show_func'] 	= 'info_block_nav';
$modversion['blocks'][1]['edit_func'] 	= 'info_navblock_edit';
$modversion['blocks'][1]['options'] 		= $infoname . '|1|dynamisch';
$modversion['blocks'][1]['template'] 		= $infoname.'_nav_block.html';
$modversion['blocks'][1]['can_clone']		= true;


$modversion['blocks'][2]['file'] 			  = 'info_freiblock.php';
$modversion['blocks'][2]['name'] 			  = _MI_INFO_BLOCK2;
$modversion['blocks'][2]['description'] = _MI_INFO_BLOCK2_DESC;
$modversion['blocks'][2]['show_func'] 	= 'info_freiblock_show';
$modversion['blocks'][2]['edit_func'] 	= 'info_freiblock_edit';
$modversion['blocks'][2]['options'] 		= $infoname . '|0';
$modversion['blocks'][2]['template'] 		= $infoname.'_freiblock.html';
$modversion['blocks'][2]['can_clone']		= true;


$modversion['config'][1]['name'] 			  = $infoname.'_editors';
$modversion['config'][1]['title'] 			= '_MI_INFO_CONF1';
$modversion['config'][1]['description'] = '_MI_INFO_CONF1_DESC';
$modversion['config'][1]['formtype'] 		= 'yesno';
$modversion['config'][1]['valuetype'] 	= 'int';
$modversion['config'][1]['default'] 		= 1;

$modversion['config'][2]['name'] 			  = $infoname.'_createlink';
$modversion['config'][2]['title'] 			= '_MI_INFO_CONF2';
$modversion['config'][2]['description'] = '_MI_INFO_CONF2_DESC';
$modversion['config'][2]['formtype'] 		= 'yesno';
$modversion['config'][2]['valuetype'] 	= 'int';
$modversion['config'][2]['default'] 		= 1;

$modversion['config'][3]['name'] 			  = $infoname.'_printer';
$modversion['config'][3]['title'] 			= '_MI_INFO_CONF3';
$modversion['config'][3]['description'] = '_MI_INFO_CONF3_DESC';
$modversion['config'][3]['formtype'] 		= 'yesno';
$modversion['config'][3]['valuetype'] 	= 'int';
$modversion['config'][3]['default'] 		= 1;

$modversion['config'][4]['name'] 			  = $infoname.'_last';
$modversion['config'][4]['title'] 			= '_MI_INFO_CONF4';
$modversion['config'][4]['description'] = '_MI_INFO_CONF4_DESC';
$modversion['config'][4]['formtype'] 		= 'select';
$modversion['config'][4]['valuetype'] 	= 'int';
$modversion['config'][4]['options'] 		= array('_MI_INFO_LASTD1'=>1,
                                                '_MI_INFO_LASTD2'=>2,
                                                '_MI_INFO_LASTD3'=>3,
                                                '_MI_INFO_LASTD4'=>4);
$modversion['config'][4]['default'] 		= _MI_INFO_LASTD1;

$modversion['config'][5]['name'] 			  = $infoname.'_showrblock';
$modversion['config'][5]['title'] 			= '_MI_INFO_CONF5';
$modversion['config'][5]['description'] = '_MI_INFO_CONF5_DESC';
$modversion['config'][5]['formtype'] 		= 'select';
$modversion['config'][5]['valuetype'] 	= 'int';
$modversion['config'][5]['default'] 		= 1;
$modversion['config'][5]['options'] 		= array('_MI_INFO_NONE'   => 0,
                                                '_MI_INFO_RECHTS'	=> 1,
                                                '_MI_INFO_LINKS'		=> 2,
                                                '_MI_INFO_BEIDE'   => 3);

$modversion['config'][6]['name'] 			  = $infoname.'_shownavi';
$modversion['config'][6]['title'] 			= '_MI_INFO_CONF6';
$modversion['config'][6]['description'] = '_MI_INFO_CONF6_DESC';
$modversion['config'][6]['formtype'] 		= 'select';
$modversion['config'][6]['valuetype'] 	= 'int';
$modversion['config'][6]['options'] 		= array('_MI_INFO_PAGESNAV'=>1,
                                                '_MI_INFO_PAGESELECT'=>2,
                                                '_MI_INFO_PAGESIMG'=>3);
$modversion['config'][6]['default'] 		= 1;

$modversion['config'][7]['name'] 			  = $infoname.'_linklist';
$modversion['config'][7]['title'] 			= '_MI_INFO_CONF7';
$modversion['config'][7]['description'] = '_MI_INFO_CONF7_DESC';
$modversion['config'][7]['formtype'] 		= 'yesno';
$modversion['config'][7]['valuetype'] 	= 'int';
$modversion['config'][7]['default'] 		= 0;

$modversion['config'][8]['name'] 			  = $infoname.'_seourl';
$modversion['config'][8]['title'] 			= '_MI_INFO_CONF8';
$modversion['config'][8]['description'] = '_MI_INFO_CONF8_DESC';
$modversion['config'][8]['formtype'] 		= 'select';
$modversion['config'][8]['valuetype'] 	= 'int';
$modversion['config'][8]['default'] 		= 0;
$modversion['config'][8]['options'] 		= array('_NONE'				=> 0,
                                                'REWRITING MODUL'	=> 1,
                                                'WRAPPER'			=> 2);

$modversion['config'][9]['name'] 			  = $infoname.'_trenner';
$modversion['config'][9]['title'] 			= '_MI_INFO_CONF9';
$modversion['config'][9]['description'] = '_MI_INFO_CONF9_DESC';
$modversion['config'][9]['formtype'] 		= 'select';
$modversion['config'][9]['valuetype'] 	= 'text';
$modversion['config'][9]['default'] 		= '';
$modversion['config'][9]['options'] 		= array(''			=> '',
                                                '-'			=> '-',
                                                '&#8226;'	=> '&#8226;',
                                                '&#8594;'	=> '&#8594;',
                                                '&#8658;'	=> '&#8658;',
                                                '&#10138;'	=> '&#10138;',
                                                '&#10140;'	=> '&#10140;',
                                                '&#10173;'	=> '&#10173;'
                                                );

$modversion['config'][10]['name'] 			  = $infoname.'_cols';
$modversion['config'][10]['title'] 			= '_MI_INFO_CONF_COLS';
$modversion['config'][10]['description'] = '_MI_INFO_CONF_COLS_DESC';
$modversion['config'][10]['formtype'] 		= 'textbox';
$modversion['config'][10]['valuetype'] 	= 'int';
$modversion['config'][10]['default'] 		= 80;

$modversion['config'][11]['name'] 			= $infoname.'_rows';
$modversion['config'][11]['title'] 			= '_MI_INFO_CONF_ROWS';
$modversion['config'][11]['description']= '_MI_INFO_CONF_ROWS_DESC';
$modversion['config'][11]['formtype'] 	= 'textbox';
$modversion['config'][11]['valuetype'] 	= 'int';
$modversion['config'][11]['default'] 		= 20;

$modversion['config'][12]['name'] 			= $infoname.'_width';
$modversion['config'][12]['title'] 			= '_MI_INFO_CONF_WIDTH';
$modversion['config'][12]['description']= '_MI_INFO_CONF_WIDTH_DESC';
$modversion['config'][12]['formtype'] 	= 'textbox';
$modversion['config'][12]['valuetype'] 	= 'int';
$modversion['config'][12]['default'] 		= 99;

$modversion['config'][13]['name'] 			= $infoname.'_height';
$modversion['config'][13]['title'] 			= '_MI_INFO_CONF_HEIGHT';
$modversion['config'][13]['description']= '_MI_INFO_CONF_HEIGHT_DESC';
$modversion['config'][13]['formtype'] 	= 'textbox';
$modversion['config'][13]['valuetype'] 	= 'int';
$modversion['config'][13]['default'] 		= 300;


// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] 		= 'content';
$modversion['comments']['pageName'] 		= 'index.php';
