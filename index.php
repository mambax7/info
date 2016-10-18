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
//  @version $Id $

require_once __DIR__ . '/../../mainfile.php';
include_once __DIR__ . '/include/constants.php';
include_once __DIR__ . '/include/function.php';

xoops_loadLanguage('modinfo', $module_name);
xoops_loadLanguage('main', $module_name);

xoops_load('XoopsCache');
$myts = MyTextSanitizer::getInstance();

$seo  = (!empty($xoopsModuleConfig[$module_name . '_seourl'])
         && $xoopsModuleConfig[$module_name . '_seourl'] > 0) ? (int)$xoopsModuleConfig[$module_name . '_seourl'] : 0;
$para = readSeoUrl($_GET, $seo);

$id  = (int)$para['id'];
$cat = (int)$para['cid'];
$pid = (int)$para['pid'];

$sgroups  = $xoopsUser ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
$infopage = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$GLOBALS['xoopsOption']['template_main'] = $module_name . '_index.tpl';
include_once $GLOBALS['xoops']->path('/header.php');

if ($id != 0) {
    $sql    = 'SELECT info_id, parent_id, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address,visible_group,edited_time,cat,self,frame,title_sicht,footer_sicht,bl_left,bl_right,st,owner,submenu FROM '
              . $xoopsDB->prefix($module_name)
              . ' WHERE info_id='
              . $id
              . ' AND (st=1 || frontpage=1)';
    $result = $xoopsDB->query($sql);
    list($info_id, $parent, $title, $text, $visible, $nohtml, $nosmiley, $nobreaks, $nocomments, $link, $address, $vgroups, $etime, $cat, $self, $iframe, $title_sicht, $footer_sicht, $bl_left, $bl_right, $st, $ownerid, $submenu) = $xoopsDB->fetchRow($result);
    if ($link == 3) {
        $mode = array(
            'seo'   => $seo,
            'id'    => $info_id,
            'title' => $title,
            'dir'   => $module_name,
            'cat'   => 'p' . $cat
        );
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . makeSeoUrl($mode));
        exit();
    }
} else {
    $sql = 'SELECT info_id,parent_id,cat,visible_group,title FROM '
           . $xoopsDB->prefix($xoopsModule->getVar('dirname'))
           . ' WHERE ';
    if ($pid > 0) {
        $sql .= 'parent_id=' . $pid . ' AND st=1 ORDER BY blockid ASC';
    } else {
        $sql .= 'frontpage=1 LIMIT 1';
    }
    $result  = $xoopsDB->query($sql);
    $visible = 0;
    if ($result && $xoopsDB->getRowsNum($result) > 0) {
        $row     = $xoopsDB->fetchArray($result);
        $row2    = $row;
        $vsgroup = explode(',', $row['visible_group']);
        $vscount = count($vsgroup) - 1;
        while ($vscount > -1) {
            if (in_array($vsgroup[$vscount], $sgroups)) {
                $visible = 1;
                $vscount = 0;
            }
            $vscount--;
        }
    } else {
        // Alternative Start-Seite suchen
        // ist erste Seite auf die der User Zugriff hat, geordnet nach info_id
        if ($pid < 1) {
            $sql    = 'SELECT info_id,parent_id,cat,visible_group,title FROM '
                      . $xoopsDB->prefix($xoopsModule->getVar('dirname'))
                      . ' WHERE st=1 AND ( submenu =1 || visible =1 ) ORDER BY cat,blockid ASC';
            $result = $xoopsDB->query($sql);
            if ($result && $xoopsDB->getRowsNum($result) > 0) {
                while ($row = $xoopsDB->fetchArray($result)) {
                    if ($visible > 0) {
                        continue;
                    }
                    $row2    = $row;
                    $vsgroup = explode(',', $row['visible_group']);
                    $vscount = count($vsgroup) - 1;
                    while ($vscount > -1) {
                        if (in_array($vsgroup[$vscount], $sgroups)) {
                            $visible = 1;
                            $vscount = 0;
                        }
                        $vscount--;
                    }
                }
            }
        }
    }
    if ($visible == 1) {
        if (!empty($row2) && count($row2) > 0) {
            $row = $row2;
        }
        $mode = array(
            'seo'   => $seo,
            'id'    => $row['info_id'],
            'title' => $row['title'],
            'dir'   => $xoopsModule->dirname(),
            'cat'   => $row['cat']
        );
        header('Location: ' . makeSeoUrl($mode));
    } else {
        redirect_header(XOOPS_URL, 3, _INFO_FILENOTFOUND);
    }
    exit();
}

if ($id <= 0 || ($visible == 0 && $submenu == 0)) {
    redirect_header(XOOPS_URL, 3, _INFO_FILENOTFOUND);
}

$vsgroup = explode(',', $vgroups);
$vscount = count($vsgroup) - 1;
$visible = 0;
while ($vscount > -1) {
    if (in_array($vsgroup[$vscount], $sgroups)) {
        $visible = 1;
        $vscount = 0;
    }
    $vscount--;
}
if ($st != 1) {
    $visible = 0;
}

if ($visible == 0) {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

$xoopsTpl->assign('xoops_showrblock', $bl_right);
$xoopsTpl->assign('xoops_showlblock', $bl_left);
$xoopsTpl->assign('footersicht', (int)$footer_sicht);
$xoTheme->addMeta('meta', 'pagemodule', 'http://www.simple-xoops.de');
$infothisgroups  = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$infopermHandler = xoops_getHandler('groupperm');
$show_info_perm  = $infopermHandler->getItemIds('InfoPerm', $infothisgroups, $xoopsModule->getVar('mid'));
$canedit         = false;
if (in_array(_CON_INFO_CANUPDATEALL, $show_info_perm)) {
    $canedit = true;
} elseif (in_array(_CON_INFO_CANUPDATE, $show_info_perm)) {
    if ($xoopsUser) {
        if ((int)$ownerid == $xoopsUser->getVar('uid')) {
            $canedit = true;
        }
    } elseif ((int)$ownerid == 0) {
        $canedit = true;
    }
}
$xoopsTpl->assign('info_contedit', $canedit);
$candelete = false;
if (in_array(_CON_INFO_CANUPDATE_DELETE, $show_info_perm)) {
    $candelete = true;
}
$xoopsTpl->assign('info_contdel', $candelete);
if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last'] > 1) {
    $xoopsTpl->assign('last', _INFO_LAST_UPDATE);
    if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last'] == 4) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 'l'));
    }
    if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last'] == 3) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 'm'));
    }
    if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last'] == 2) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 's'));
    }
}

$xoopsTpl->assign('modules_url', XOOPS_URL . '/modules/' . $module_name);
$content = '';
if ($address != '' && $link == 1) {
    if (0 === strpos($address, '/')) {
        $address = substr($address, 1);
    }
    header('Location: ' . XOOPS_URL . '/' . $address);
    exit();
} elseif ($address != '' && $link == 2) {
    if (0 === stripos($address, 'http')
        || 0 === stripos($address, 'ftp')
    ) {
        if ($self == 1) {
            if ($title_sicht == 1) {
                $xoopsTpl->assign('title', $title);
            }
            $content = '<script type="text/javascript">';
            $content .= 'window.open("' . $address . '");';
            $content .= '</script>';
            $content .= '<br><br><center>';
            $content .= sprintf(_MIC_INFO_EXTERNLINK, $address);
            $content .= '</center><br><br>';
            $xoopsTpl->assign('content', $content);
            $xoopsTpl->assign('xoops_module_header',
                              '<meta http-equiv="Refresh" content="10; url=\'' . XOOPS_URL . '\'" />');
        } else {
            header('Location: ' . $address);
            exit();
        }
    } else {
        redirect_header(XOOPS_URL, 3, _NOPERM);
        exit();
    }
} elseif ($address != '' && $link == 4) {
    if ($title_sicht == 1) {
        $xoopsTpl->assign('title', $title);
    }
    if (0 === strpos($address, '/')) {
        $address = substr($address, 1);
    }
    $includeContent = XOOPS_ROOT_PATH . '/' . $address;
    if (file_exists($includeContent)) {
        $extension = pathinfo($includeContent, PATHINFO_EXTENSION);
        $allowed   = include_once __DIR__ . '/include/mimes.php';
        if (isset($allowed[$extension])) {
            $includeContent = '../../' . $address;
            $iframe         = unserialize($iframe);
            if (!isset($iframe['width'])
                || $iframe['width'] < 1
                || $iframe['width'] > 100
            ) {
                $iframe['width'] = 100;
            }
            $content = '<object data="'
                       . $includeContent
                       . '" type="'
                       . $allowed[$extension]
                       . '" width="'
                       . $iframe['width']
                       . '%" height="'
                       . $iframe['height']
                       . '">Plugin Not installed!</object>';
        } elseif (0 === strpos($extension, 'php') || $extension === 'phtml') {
            $includeContent = XOOPS_URL . '/' . $address;
            $iframe         = unserialize($iframe);
            if (!isset($iframe['width'])
                || $iframe['width'] < 1
                || $iframe['width'] > 100
            ) {
                $iframe['width'] = 100;
            }
            $content = "<div align='center'>";
            $content .= "<iframe width='"
                        . $iframe['width']
                        . "%' height='"
                        . $iframe['height']
                        . "' name='"
                        . $title
                        . "' scrolling='auto' frameborder='"
                        . $iframe['border']
                        . "' src='"
                        . $includeContent
                        . "'></iframe>";
            $content .= '</div>';
        } else {
            $content = _MA_INFO_NOEXTENSION;
        }
    } else {
        $content = _INFO_FILENOTFOUND;
    }
    $xoopsTpl->assign('content', $content);
    $xoopsTpl->assign('nocomments', $nocomments);
    $xoopsTpl->assign('id', $id);
    $xoopsTpl->assign('info_add', _ADD);
    $xoopsTpl->assign('info_edit', _EDIT);
    if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_printer'] == 1) {
        $xoopsTpl->assign('print', 1);
    }
    $xoopsTpl->assign('print_title', _MI_INFO_PRINTER);
    $xoopsTpl->assign('email_title', _MI_INFO_SENDEMAIL);
    if ($xoopsModuleConfig['com_rule'] != 0) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
} elseif ($address != '' && $link == 5) {
    if ($title_sicht == 1) {
        $xoopsTpl->assign('title', $title);
    }
    $iframe = unserialize($iframe);
    if (!isset($iframe['width']) || $iframe['width'] < 1
        || $iframe['width'] > 100
    ) {
        $iframe['width'] = 100;
    }
    $content = "<div align='" . $iframe['align'] . "'>";
    $content .= "<iframe width='"
                . $iframe['width']
                . "%' height='"
                . $iframe['height']
                . "' name='"
                . $title
                . "' scrolling='auto' frameborder='"
                . $iframe['border']
                . "' src='"
                . $address
                . "'></iframe>";
    $content .= '</div>';
    $xoopsTpl->assign('content', $content);
    $xoopsTpl->assign('nocomments', $nocomments);
    $xoopsTpl->assign('id', $id);
    $xoopsTpl->assign('info_add', _ADD);
    $xoopsTpl->assign('info_edit', _EDIT);
    if ($xoopsModuleConfig['com_rule'] != 0) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
} else {
    if ($link == 6) {
        ob_start();
        echo eval($text);
        $text = ob_get_contents();
        ob_end_clean();
        $nohtml = 0;
    }
    $html   = ($nohtml == 1) ? 0 : 1;
    $br     = ($html == 1) ? 0 : 1;
    $smiley = ($nosmiley == 1) ? 0 : 1;

    $text = str_replace('{X_XOOPSURL}', XOOPS_URL . '/', $text);
    if (is_object($xoopsUser)) {
        $text = str_replace('{X_XOOPSUSER}', $xoopsUser->getVar('uname'), $text);
        $text = str_replace('{X_XOOPSUSERID}', $xoopsUser->getVar('uid'), $text);
    } else {
        $text = str_replace('{X_XOOPSUSER}', _GUESTS, $text);
        $text = str_replace('{X_XOOPSUSERID}', '0', $text);
    }
    $text = str_replace('{X_SITEURL}', XOOPS_URL . '/', $text);

    if ($title_sicht == 1) {
        $xoopsTpl->assign('title', $title);
    }
    if (trim($text) != '') {
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>',
                                  '[pagebreak]', $text);
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;"> </span></div>',
                                  '[pagebreak]', $text);
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;"></span></div>',
                                  '[pagebreak]', $text);
        $text       = $myts->displayTarea($text, $html, $smiley, 1, 1, $br);
        $infotext   = explode('[pagebreak]', $text);
        $info_pages = count($infotext);
        if ($info_pages > 1) {
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new XoopsPageNav($info_pages, 1, $infopage, 'page', 'content=' . $cat . ':' . $id);
            if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_shownavi'] == 2) {
                $xoopsTpl->assign('pagenav', $pagenav->renderSelect());
            } elseif ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_shownavi'] == 3) {
                $xoopsTpl->assign('pagenav', $pagenav->renderImageNav());
            } else {
                $xoopsTpl->assign('pagenav', $pagenav->renderNav());
            }
            $text = $infotext[$infopage];
        }
    }
    $xoopsTpl->assign('page', $infopage);
    $xoopsTpl->assign('content', $text);
    $xoopsTpl->assign('nocomments', $nocomments);
    $xoopsTpl->assign('id', $id);
    $xoopsTpl->assign('info_add', _ADD);
    $xoopsTpl->assign('info_edit', _EDIT);
    if ($xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_printer'] == 1) {
        $xoopsTpl->assign('print', 1);
    }
    $xoopsTpl->assign('print_title', _MI_INFO_PRINTER);
    $xoopsTpl->assign('email_title', _MI_INFO_SENDEMAIL);
    if ($xoopsModuleConfig['com_rule'] != 0) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
}
$mode      = array(
    'seo'   => $seo,
    'id'    => $id,
    'title' => $title,
    'dir'   => $xoopsModule->dirname(),
    'cat'   => $cat
);
$mail_link = 'mailto:?subject='
             . sprintf(_MI_INFO_ARTICLE, $xoopsConfig['sitename'])
             . '&amp;body='
             . sprintf(_MI_INNFO_ARTFOUND, $xoopsConfig['sitename'])
             . ':  '
             . makeSeoUrl($mode);
$xoopsTpl->assign('email_link', $mail_link);
$xoopsTpl->assign('info_totop', _INFO_TOTOP);
$xoopsTpl->assign('info_cat', $cat);
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . strip_tags($title));

include_once $GLOBALS['xoops']->path('/footer.php');
