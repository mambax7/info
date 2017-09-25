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

require_once __DIR__ . '/admin_header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
if (!in_array($op, ['list', 'blockcat', 'blockcat_insert'])) {
    $op = 'list';
}
//$id  	= ( isset($_REQUEST['id']) )  	? intval($_REQUEST['id']) 	: 0;
$cat = isset($_REQUEST['cat']) ? (int)$_REQUEST['cat'] : 0;

switch ($op) {
    case 'list':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $catlist = $catHandler->getObjects(null, true, false);
        $cate    = [];
        foreach ($catlist as $cats => $catr) {
            $cate[$catr['cat_id']] = $catr['title'];
        }
        $form = new XoopsThemeForm(_INFO_LISTBLOCKCAT, $xoopsModule->getVar('dirname') . '_form_list', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/admin_categorie.php');
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormHidden('op', 'blockcat'));
        $block_select = new XoopsFormSelect(_INFO_HOMEPAGE, 'cat', 0);
        $block_select->addOptionArray($cate);
        $form->addElement($block_select);
        $submit = new XoopsFormElementTray('', '');
        $submit->addElement(new XoopsFormButton('', 'post', _DELETE, 'submit'));
        $submit->addElement(new XoopsFormButton('', 'post', _EDIT, 'submit'));
        $form->addElement($submit);
        $form->display();
        makecat();
        xoops_cp_footer();
        break;
    case 'blockcat':
        $cate = $catHandler->get($cat);
        if (_DELETE == $_REQUEST['post']) {
            xoops_cp_header();
            $adminObject->displayNavigation(basename(__FILE__));
            if (1 == $cat) {
                redirect_header('admin_categorie.php', 3, _INFO_ERROR_NODEFAULT);
            } else {
                $msg     = _INFO_SETDELETE . '<br>' . sprintf(_INFO_SETDELETE_FRAGE, $cate->getVar('title'));
                $hiddens = [
                    'op'   => 'blockcat',
                    'cat'  => $cat,
                    'post' => 'itsdelete'
                ];
                xoops_confirm($hiddens, 'admin_categorie.php', $msg);
            }
            xoops_cp_footer();
        } elseif ('itsdelete' === $_REQUEST['post']) {
            if ($GLOBALS['xoopsSecurity']->check()) {
                if ($catHandler->delete($cate)) {
                    redirect_header('admin_categorie.php', 2, _INFO_DBUPDATED);
                } else {
                    redirect_header('admin_categorie.php', 3, _INFO_ERRORINSERT);
                }
            } else {
                redirect_header('admin_categorie.php', 3, _AM_INFO_TOCKEN_MISSING);
            }
        } else {
            xoops_cp_header();
            $adminObject->displayNavigation(basename(__FILE__));
            makecat($cat);
            xoops_cp_footer();
        }
        break;
    case 'blockcat_insert':
        if ($GLOBALS['xoopsSecurity']->check()) {
            $cate  = $catHandler->get($cat);
            $title = $myts->htmlSpecialChars(trim($_POST['title']));
            $cate->setVar('title', $title);
            if ($catHandler->insert($cate)) {
                redirect_header('admin_categorie.php', 3, _INFO_DBUPDATED);
            } else {
                redirect_header('admin_categorie.php', 3, _INFO_ERRORINSERT);
            }
        } else {
            redirect_header('admin_categorie.php', 3, _AM_INFO_TOCKEN_MISSING);
        }
        break;
}

/**
 * @param int $cat
 */
function makecat($cat = 0)
{
    global $catHandler, $xoopsModule;

    $cate   = $catHandler->get($cat);
    $tueber = (0 == $cat) ? _INFO_ADDBLOCKCAT : _INFO_EDITBLOCKCAT;
    $form   = new XoopsThemeForm($tueber, $xoopsModule->getVar('dirname') . '_form_edit', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/admin_categorie.php', 'post', true);
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormHidden('cat', $cate->getVar('cat_id')));
    $form->addElement(new XoopsFormHidden('op', 'blockcat_insert'));
    $form->addElement(new XoopsFormText(_INFO_HOMEPAGE, 'title', 80, 255, $cate->getVar('title')), true);
    $submit = new XoopsFormButton('', 'post', $tueber, 'submit');
    $form->addElement($submit);
    $form->display();
}
