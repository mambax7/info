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
//  @package submit.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: submit.php 91 2014-04-19 20:09:50Z alfred $

include '../../mainfile.php';
$module_name = basename(__DIR__) ;

include_once 'include/function.php';
include_once 'include/constants.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$module_name.'/class/infotree.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$module_name.'/class/info.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$module_name.'/class/category.php';
xoops_loadLanguage( 'admin', $module_name);
xoops_loadLanguage( 'modinfo', $module_name);

$seo = (!empty($xoopsModuleConfig[$module_name.'_seourl']) && $xoopsModuleConfig[$module_name.'_seourl']>0) ? intval($xoopsModuleConfig[$module_name.'_seourl']) : 0;
$myts = MyTextSanitizer::getInstance();

$info_handler 		  = new InfoInfoHandler($xoopsDB,$module_name);
$infowait_handler 	= new InfoInfoHandler($xoopsDB, $module_name . '_bak');
$cat_handler 		    = new InfoCategoryHandler($xoopsDB,$module_name);
$info_tree 			    = new InfoTree($xoopsDB->prefix($module_name), 'info_id', 'parent_id');

$op  			    = info_cleanVars($_REQUEST,'op','','string');
if ( !in_array($op,array('edit','delete')) ) $op = '';
$id  			    = info_cleanVars($_REQUEST,'id',0,'int');
$cat 			    = info_cleanVars($_REQUEST,'cat',1,'int');
$groupid 		  = info_cleanVars($_REQUEST,'groupid',0,'int');
$mod_isAdmin 	= ( $xoopsUser && $xoopsUser->isAdmin() ) ? true : false;

//Permission
$infothisgroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$infoperm_handler = xoops_getHandler('groupperm');
$show_info_perm = $infoperm_handler->getItemIds('InfoPerm', $infothisgroups, $xoopsModule->getVar('mid'));

$content = $info_handler->get($id);
if ( !empty($_POST) ) $content = setPost($content,$_POST);

$approve = 0;
if ( in_array(_CON_INFO_CANUPDATEALL,$show_info_perm) || $mod_isAdmin ) {
	$approve = 1;
} elseif ( in_array(_CON_INFO_CANCREATE,$show_info_perm) && $id == 0 ) {
	$approve = 1;
} elseif ( $xoopsUser && ( $xoopsUser->uid() == $content->getVar('owner')) ) { // eigene Seite
  if (in_array(_CON_INFO_CANUPDATE,$show_info_perm)) $approve = 1;
} 

if ($approve == 0) {
	$mode=array(
        'seo' =>$seo, 'id' =>$content->getVar('info_id'), 'title' =>$content->getVar('title'), 'dir' =>$module_name,
        'cat' =>$content->getVar('cat'));
	redirect_header(makeSeoUrl($mode),3,_INFO_MA_NOEDITRIGHT);
}

if ($op == 'edit') {
	if (isset($_POST['post'])) {
		if (!$GLOBALS['xoopsSecurity']->check()) {
			//redirect_header("index.php",3,implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
			//exit();
		}   

		$content->setVar('edited_time',time());		
		if (is_object($xoopsUser)) {
			$content->setVar('edited_user',$xoopsUser->uid());
		} else {
			$content->setVar('edited_user','0');
		}
    
    if ( in_array(_CON_INFO_ALLCANUPLOAD,$show_info_perm) || $mod_isAdmin ) {    
      if (isset($_FILES[$_POST['xoops_upload_file'][0]]['name']) && $_FILES[$_POST['xoops_upload_file'][0]]['name'] != '') {
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $allowed_mimetypes = include_once XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
        $maxfilesize = (intval(ini_get('post_max_size')) < 1 ) ? 204800 : intval(ini_get('post_max_size')) * 1024 * 1024;
        // $maxfilewidth = 120;
        // $maxfileheight = 120;
        $upload_dir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/files';
        $uploader = new XoopsMediaUploader( $upload_dir, $allowed_mimetypes, $maxfilesize/*, $maxfilewidth, $maxfileheight */);         
        
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
          if ($uploader->mediaSize < 1) $uploader->setErrors(_ER_UP_INVALIDFILESIZE);        
          if (file_exists($upload_dir . '/'
                          . $uploader->mediaName)) $uploader->setErrors(_ER_UP_INVALIDFILENAME);
        
          if (count($uploader->errors) > 0 ) {
        
            include_once XOOPS_ROOT_PATH.'/header.php';
            $sbl = intval($xoopsModuleConfig[$xoopsModule->getVar('dirname').'_showrblock']);
            if ($sbl == 0) {
            // no blocks
            } elseif ($sbl == 1) {
              $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
            } elseif ($sbl == 2) {
              $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
            } elseif ($sbl == 3) {
              $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
              $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
            }	
            $op = 'edit';
            $ret = 1;
            $errors = $uploader->getErrors();
            include_once 'include/form.php';
            include_once XOOPS_ROOT_PATH.'/footer.php';
            exit();
          }         
          if (!$uploader->upload()) {
            if (count($uploader->errors) > 0 ) {
              include_once XOOPS_ROOT_PATH.'/header.php';
              $sbl = intval($xoopsModuleConfig[$xoopsModule->getVar('dirname').'_showrblock']);
              if ($sbl == 0) {
              // no blocks
              } elseif ($sbl == 1) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
              } elseif ($sbl == 2) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
              } elseif ($sbl == 3) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
                $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
              }	
              $op = 'edit';
              $ret = 1;
              $errors = $uploader->getErrors();
              include_once 'include/form.php';
              include_once XOOPS_ROOT_PATH.'/footer.php';
              exit();
            }
          }            
        } else {
          if (count($uploader->errors) > 0 ) {
              include_once XOOPS_ROOT_PATH.'/header.php';
              $sbl = intval($xoopsModuleConfig[$xoopsModule->getVar('dirname').'_showrblock']);
              if ($sbl == 0) {
              // no blocks
              } elseif ($sbl == 1) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
              } elseif ($sbl == 2) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
              } elseif ($sbl == 3) {
                $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
                $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
              }	
              $op = 'edit';
              $ret = 1;
              $errors = $uploader->getErrors();
              include_once 'include/form.php';
              include_once XOOPS_ROOT_PATH.'/footer.php';
              exit();
          }
        }
      }
    }
  
    if ( (in_array(_CON_INFO_ALLCANUPDATE_SITEFULL,$show_info_perm) && $id == 0) || (in_array(_CON_INFO_CANUPDATE_SITEFULL,$show_info_perm) && $id > 0) || $mod_isAdmin) {	
      $res = $info_handler->insert($content);
      $eintrag = true;
    } else {
			$content->setVar('old_id',$id);
			$content->setVar('info_id',0);
      $content->setNew();
      $eintrag = false;
			$res = $infowait_handler->insert($content);      
		}

		if (intval($_POST['ret']) == 1) {
			$mode=array(
                'seo' =>$seo, 'id' =>0, 'title' =>'', 'dir' =>$module_name,
                'cat' =>0);
		} else {
			$mode=array(
                'seo' =>$seo, 'id' =>$id, 'title' =>$content->getVar('title'), 'dir' =>$module_name,
                'cat' =>$content->getVar('cat'));
		}
    
	  $rurl = makeSeoUrl($mode);				
		if ($res) {
			$key = $xoopsModule->getVar('dirname') . '_' . '*';
			clearInfoCache($key);
      if ($eintrag) {
        redirect_header($rurl, 1, _INFO_DBUPDATED);
      } else {
        redirect_header($rurl, 1, _MA_INFO_WAITTOEDIT);
      }
		} else {
			redirect_header($rurl, 3, _INFO_ERRORINSERT);
		}
	} else {
    if (!$infowait_handler->readbakid($id)) {     
      $ret = 0;
      include_once XOOPS_ROOT_PATH.'/header.php';
      if (intval($xoopsModuleConfig[$xoopsModule->getVar('dirname').'_showrblock']) == 1) {
        $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
      }		
      include_once 'include/form.php';
      include_once XOOPS_ROOT_PATH.'/footer.php';
    } else {
      $mode=array(
          'seo' =>$seo, 'id' =>$content->getVar('info_id'), 'title' =>$content->getVar('title'), 'dir' =>$xoopsModule->dirname(),
          'cat' =>$content->getVar('cat'));
      redirect_header(makeSeoUrl($mode),3,_MA_INFO_WAITTOFREE);
    }
	}
} elseif ($op == 'delete') {
    if ( !in_array(_CON_INFO_CANUPDATE_DELETE,$show_info_perm) ) {
      $mode=array(
          'seo' =>$seo, 'id' =>$content->getVar('info_id'), 'title' =>$content->getVar('title'), 'dir' =>$module_name,
          'cat' =>$content->getVar('cat'));
      redirect_header(makeSeoUrl($mode), 3, _NOPERM);
    } elseif ( !empty($_POST['delok']) && intval($_POST['delok']) == 1) {
      if ( $GLOBALS['xoopsSecurity']->check() ) {        
        if ($info_handler->delete($content)) {
          $key = $xoopsModule->getVar('dirname') . '_' . '*';
          clearInfoCache($key);
          redirect_header(XOOPS_URL, 1, _INFO_DBUPDATED);
        } else {
          redirect_header(XOOPS_URL, 1, _MA_INFO_WAITTOEDIT);
        }
      } else {       
        $mode=array(
            'seo' =>$seo, 'id' =>$content->getVar('info_id'), 'title' =>$content->getVar('title'), 'dir' =>$module_name,
            'cat' =>$content->getVar('cat'));
        redirect_header(makeSeoUrl($mode), 3, _AM_INFO_TOCKEN_MISSING);
      }
    } else {      
      include_once XOOPS_ROOT_PATH.'/header.php';
      $msg = sprintf(_INFO_INFODELETE_FRAGE,$content->getVar('title'));
      $hiddens = array('op'=>'delete','delok'=>1,'id'=>$id);                
      xoops_confirm($hiddens, 'submit.php', $msg, _DELETE, true);
      include_once XOOPS_ROOT_PATH.'/footer.php';
    }
} else {
  include_once XOOPS_ROOT_PATH.'/header.php';
  $sbl = intval($xoopsModuleConfig[$xoopsModule->getVar('dirname').'_showrblock']);
  if ($sbl == 0) {
    // no blocks
  } elseif ($sbl == 1) {
    $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
  } elseif ($sbl == 2) {
    $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
  } elseif ($sbl == 3) {
    $GLOBALS['xoopsTpl']->assign( 'xoops_showrblock', 0 );
    $GLOBALS['xoopsTpl']->assign( 'xoops_showlblock', 0 );
  }	
  $op = 'edit';
  $ret = 1;
  include_once 'include/form.php';
  include_once XOOPS_ROOT_PATH.'/footer.php';  
}
