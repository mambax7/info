<?php
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

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/include/constants.php';
require_once __DIR__ . '/include/function.php';

xoops_loadLanguage('modinfo', $module_name);
xoops_loadLanguage('main', $module_name);

xoops_load('XoopsCache');
$myts = \MyTextSanitizer::getInstance();

$seo  = (!empty($xoopsModuleConfig[$module_name . '_seourl'])
         && $xoopsModuleConfig[$module_name . '_seourl'] > 0) ? (int)$xoopsModuleConfig[$module_name . '_seourl'] : 0;
$para = readSeoUrl($_GET, $seo);

$id  = (int)$para['id'];
$cat = (int)$para['cid'];
$pid = (int)$para['pid'];

$sgroups  = $xoopsUser ? $xoopsUser->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
$infopage = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$GLOBALS['xoopsOption']['template_main'] = $module_name . '_index.tpl';
require_once $GLOBALS['xoops']->path('/header.php');

if (0 != $id) {
    $sql    = 'SELECT info_id, parent_id, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address,visible_group,edited_time,cat,self,frame,title_sicht,footer_sicht,bl_left,bl_right,st,owner,submenu FROM '
              . $xoopsDB->prefix($module_name)
              . ' WHERE info_id='
              . $id
              . ' AND (st=1 || frontpage=1)';
    $result = $xoopsDB->query($sql);
    list($info_id, $parent, $title, $text, $visible, $nohtml, $nosmiley, $nobreaks, $nocomments, $link, $address, $vgroups, $etime, $cat, $self, $iframe, $title_sicht, $footer_sicht, $bl_left, $bl_right, $st, $ownerid, $submenu) = $xoopsDB->fetchRow($result);
    if (3 == $link) {
        $mode = [
            'seo'   => $seo,
            'id'    => $info_id,
            'title' => $title,
            'dir'   => $module_name,
            'cat'   => 'p' . $cat
        ];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . makeSeoUrl($mode));
        exit();
    }
} else {
    $sql = 'SELECT info_id,parent_id,cat,visible_group,title FROM ' . $xoopsDB->prefix($xoopsModule->getVar('dirname')) . ' WHERE ';
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
            $sql    = 'SELECT info_id,parent_id,cat,visible_group,title FROM ' . $xoopsDB->prefix($xoopsModule->getVar('dirname')) . ' WHERE st=1 AND ( submenu =1 || visible =1 ) ORDER BY cat,blockid ASC';
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
    if (1 == $visible) {
        if (!empty($row2) && count($row2) > 0) {
            $row = $row2;
        }
        $mode = [
            'seo'   => $seo,
            'id'    => $row['info_id'],
            'title' => $row['title'],
            'dir'   => $xoopsModule->dirname(),
            'cat'   => $row['cat']
        ];
        header('Location: ' . makeSeoUrl($mode));
    } else {
        redirect_header(XOOPS_URL, 3, _INFO_FILENOTFOUND);
    }
    exit();
}

if ($id <= 0 || (0 == $visible && 0 == $submenu)) {
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
if (1 != $st) {
    $visible = 0;
}

if (0 == $visible) {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

$xoopsTpl->assign('xoops_showrblock', $bl_right);
$xoopsTpl->assign('xoops_showlblock', $bl_left);
$xoopsTpl->assign('footersicht', (int)$footer_sicht);
$xoTheme->addMeta('meta', 'pagemodule', 'http://www.simple-xoops.de');
$infothisgroups  = is_object($xoopsUser) ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
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
    } elseif (0 == (int)$ownerid) {
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
    if (4 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last']) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 'l'));
    }
    if (3 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last']) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 'm'));
    }
    if (2 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_last']) {
        $xoopsTpl->assign('last_update', formatTimestamp($etime, 's'));
    }
}

$xoopsTpl->assign('modules_url', XOOPS_URL . '/modules/' . $module_name);
$content = '';
if ('' != $address && 1 == $link) {
    if (0 === strpos($address, '/')) {
        $address = substr($address, 1);
    }
    header('Location: ' . XOOPS_URL . '/' . $address);
    exit();
} elseif ('' != $address && 2 == $link) {
    if (0 === stripos($address, 'http')
        || 0 === stripos($address, 'ftp')) {
        if (1 == $self) {
            if (1 == $title_sicht) {
                $xoopsTpl->assign('title', $title);
            }
            $content = '<script type="text/javascript">';
            $content .= 'window.open("' . $address . '");';
            $content .= '</script>';
            $content .= '<br><br><div class="center;">';
            $content .= sprintf(_MIC_INFO_EXTERNLINK, $address);
            $content .= '</div><br><br>';
            $xoopsTpl->assign('content', $content);
            $xoopsTpl->assign('xoops_module_header', '<meta http-equiv="Refresh" content="10; url=\'' . XOOPS_URL . '\'">');
        } else {
            header('Location: ' . $address);
            exit();
        }
    } else {
        redirect_header(XOOPS_URL, 3, _NOPERM);
    }
} elseif ('' != $address && 4 == $link) {
    if (1 == $title_sicht) {
        $xoopsTpl->assign('title', $title);
    }
    if (0 === strpos($address, '/')) {
        $address = substr($address, 1);
    }
    $includeContent = XOOPS_ROOT_PATH . '/' . $address;
    if (file_exists($includeContent)) {
        $extension = pathinfo($includeContent, PATHINFO_EXTENSION);
        $allowed   = require_once __DIR__ . '/include/mimes.php';
        if (isset($allowed[$extension])) {
            $includeContent = '../../' . $address;
            $iframe         = unserialize($iframe);
            if (!isset($iframe['width'])
                || $iframe['width'] < 1
                || $iframe['width'] > 100) {
                $iframe['width'] = 100;
            }
            $content = '<object data="' . $includeContent . '" type="' . $allowed[$extension] . '" width="' . $iframe['width'] . '%" height="' . $iframe['height'] . '">Plugin Not installed!</object>';
        } elseif (0 === strpos($extension, 'php') || 'phtml' === $extension) {
            $includeContent = XOOPS_URL . '/' . $address;
            $iframe         = unserialize($iframe);
            if (!isset($iframe['width'])
                || $iframe['width'] < 1
                || $iframe['width'] > 100) {
                $iframe['width'] = 100;
            }
            $content = "<div align='center'>";
            $content .= "<iframe width='" . $iframe['width'] . "%' height='" . $iframe['height'] . "' name='" . $title . "' scrolling='auto' frameborder='" . $iframe['border'] . "' src='" . $includeContent . "'></iframe>";
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
    if (1 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_printer']) {
        $xoopsTpl->assign('print', 1);
    }
    $xoopsTpl->assign('print_title', _MI_INFO_PRINTER);
    $xoopsTpl->assign('email_title', _MI_INFO_SENDEMAIL);
    if (0 != $xoopsModuleConfig['com_rule']) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
} elseif ('' != $address && 5 == $link) {
    if (1 == $title_sicht) {
        $xoopsTpl->assign('title', $title);
    }
    $iframe = unserialize($iframe);
    if (!isset($iframe['width']) || $iframe['width'] < 1
        || $iframe['width'] > 100) {
        $iframe['width'] = 100;
    }
    $content = "<div align='" . $iframe['align'] . "'>";
    $content .= "<iframe width='" . $iframe['width'] . "%' height='" . $iframe['height'] . "' name='" . $title . "' scrolling='auto' frameborder='" . $iframe['border'] . "' src='" . $address . "'></iframe>";
    $content .= '</div>';
    $xoopsTpl->assign('content', $content);
    $xoopsTpl->assign('nocomments', $nocomments);
    $xoopsTpl->assign('id', $id);
    $xoopsTpl->assign('info_add', _ADD);
    $xoopsTpl->assign('info_edit', _EDIT);
    if (0 != $xoopsModuleConfig['com_rule']) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
} else {
    if (6 == $link) {
        ob_start();
        echo eval($text);
        $text = ob_get_contents();
        ob_end_clean();
        $nohtml = 0;
    }
    $html   = (1 == $nohtml) ? 0 : 1;
    $br     = (1 == $html) ? 0 : 1;
    $smiley = (1 == $nosmiley) ? 0 : 1;

    $text = str_replace('{X_XOOPSURL}', XOOPS_URL . '/', $text);
    if (is_object($xoopsUser)) {
        $text = str_replace('{X_XOOPSUSER}', $xoopsUser->getVar('uname'), $text);
        $text = str_replace('{X_XOOPSUSERID}', $xoopsUser->getVar('uid'), $text);
    } else {
        $text = str_replace('{X_XOOPSUSER}', _GUESTS, $text);
        $text = str_replace('{X_XOOPSUSERID}', '0', $text);
    }
    $text = str_replace('{X_SITEURL}', XOOPS_URL . '/', $text);

    if (1 == $title_sicht) {
        $xoopsTpl->assign('title', $title);
    }
    if ('' != trim($text)) {
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>', '[pagebreak]', $text);
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;"> </span></div>', '[pagebreak]', $text);
        $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;"></span></div>', '[pagebreak]', $text);
        $text       = $myts->displayTarea($text, $html, $smiley, 1, 1, $br);
        $infotext   = explode('[pagebreak]', $text);
        $info_pages = count($infotext);
        if ($info_pages > 1) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new XoopsPageNav($info_pages, 1, $infopage, 'page', 'content=' . $cat . ':' . $id);
            if (2 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_shownavi']) {
                $xoopsTpl->assign('pagenav', $pagenav->renderSelect());
            } elseif (3 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_shownavi']) {
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
    if (1 == $xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_printer']) {
        $xoopsTpl->assign('print', 1);
    }
    $xoopsTpl->assign('print_title', _MI_INFO_PRINTER);
    $xoopsTpl->assign('email_title', _MI_INFO_SENDEMAIL);
    if (0 != $xoopsModuleConfig['com_rule']) {
        $xoopsTpl->assign('comments', 1);
        include __DIR__ . '/comment_view.php';
    }
}
$mode      = [
    'seo'   => $seo,
    'id'    => $id,
    'title' => $title,
    'dir'   => $xoopsModule->dirname(),
    'cat'   => $cat
];
$mail_link = 'mailto:?subject=' . sprintf(_MI_INFO_ARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_MI_INNFO_ARTFOUND, $xoopsConfig['sitename']) . ':  ' . makeSeoUrl($mode);
$xoopsTpl->assign('email_link', $mail_link);
$xoopsTpl->assign('info_totop', _INFO_TOTOP);
$xoopsTpl->assign('info_cat', $cat);
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . strip_tags($title));

require_once $GLOBALS['xoops']->path('/footer.php');
