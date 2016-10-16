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
//  @package admin_categorie.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: admin_categorie.php 74 2013-03-29 20:25:05Z alfred $

include_once "admin_header.php";

$op  	= ( isset($_REQUEST['op']) )  	? $_REQUEST['op'] 			: 'list';
if (!in_array( $op, array('list','blockcat','blockcat_insert') )) $op = 'list'; 
//$id  	= ( isset($_REQUEST['id']) )  	? intval($_REQUEST['id']) 	: 0;
$cat 	= ( isset($_REQUEST['cat']) ) 	? intval($_REQUEST['cat']) 	: 0;

switch ($op) {
    case "list":
    default:
		xoops_cp_header();        
    echo $indexAdmin->addNavigation('admin_categorie.php');		
		$catlist = $cat_handler->getObjects(null,true,false);
    $cate = array();
    foreach ( $catlist as $cats => $catr ) 
		{
      $cate[$catr['cat_id']] = $catr['title'];
    }
    $form = new XoopsThemeForm(_INFO_LISTBLOCKCAT, $xoopsModule->getVar('dirname')."_form_list", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/admin_categorie.php');
	  $form->setExtra('enctype="multipart/form-data"');  
		$form->addElement(new XoopsFormHidden('op', 'blockcat'));	
		$block_select = new XoopsFormSelect(_INFO_HOMEPAGE, "cat",0);
		$block_select->addOptionArray($cate);
	  $form->addElement($block_select);
		$submit = new XoopsFormElementTray("", "");
	  $submit->addElement(new XoopsFormButton('', 'post', _DELETE, 'submit'));
		$submit->addElement(new XoopsFormButton('', 'post', _EDIT, 'submit'));
    $form->addElement($submit);
		$form->display();
		makecat();        
    xoops_cp_footer();
    break;
	case "blockcat":
    $cate = $cat_handler->get($cat);
		if ($_REQUEST['post'] == _DELETE) {
			xoops_cp_header();        
			echo $indexAdmin->addNavigation('admin_categorie.php');       
			if ($cat == 1) {
        redirect_header('admin_categorie.php', 3, _INFO_ERROR_NODEFAULT);
			} else {        
				$msg = _INFO_SETDELETE . "<br />".sprintf(_INFO_SETDELETE_FRAGE,$cate->getVar('title'));
        $hiddens = array('op'=>'blockcat','cat'=>$cat,'post'=>'itsdelete');                
				xoops_confirm($hiddens, 'admin_categorie.php', $msg);
      }
			xoops_cp_footer();
		} elseif ($_REQUEST['post'] == 'itsdelete') {
			if ( $GLOBALS['xoopsSecurity']->check() ) {
				if ($cat_handler->delete($cate)) {
					redirect_header('admin_categorie.php', 2, _INFO_DBUPDATED);
				} else {
					redirect_header('admin_categorie.php', 3, _INFO_ERRORINSERT);
				}
			} else {
				redirect_header('admin_categorie.php', 3, _AM_INFO_TOCKEN_MISSING);
			}
		} else {
			xoops_cp_header();        
			echo $indexAdmin->addNavigation('admin_categorie.php');    
			makecat($cat);
			xoops_cp_footer();
		}		
    break;
	case "blockcat_insert":		
		if ( $GLOBALS['xoopsSecurity']->check() ) {
			$cate = $cat_handler->get($cat);
			$title = $myts->htmlSpecialChars(trim($_POST['title']));
			$cate->setVar('title',$title);
			if ($cat_handler->insert($cate)) {
				redirect_header('admin_categorie.php', 3, _INFO_DBUPDATED);
			} else {
				redirect_header('admin_categorie.php', 3, _INFO_ERRORINSERT);
			}
		} else {
			redirect_header('admin_categorie.php', 3, _AM_INFO_TOCKEN_MISSING);
		}		
    break;
}

function makecat($cat=0) {
  global $cat_handler,$xoopsModule;

	$cate = $cat_handler->get($cat);
	$tueber = ($cat == 0) ? _INFO_ADDBLOCKCAT : _INFO_EDITBLOCKCAT;
	$form = new XoopsThemeForm($tueber, $xoopsModule->getVar('dirname')."_form_edit", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/admin_categorie.php', 'post', true);
	$form->setExtra('enctype="multipart/form-data"');  
	$form->addElement(new XoopsFormHidden('cat', $cate->getVar('cat_id')));	
	$form->addElement(new XoopsFormHidden('op', 'blockcat_insert'));
	$form->addElement(new XoopsFormText(_INFO_HOMEPAGE, "title", 80, 255,$cate->getVar('title')),true); 
	$submit = new XoopsFormButton('', 'post', $tueber, 'submit');
	$form->addElement($submit);
	$form->display();
}

?>