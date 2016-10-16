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
//  @package admin_permission.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: admin_permission.php 74 2013-03-29 20:25:05Z alfred $

include __DIR__ . '/admin_header.php';

include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
xoops_cp_header();
echo $indexAdmin->addNavigation('admin_permission.php');

$form = new XoopsGroupPermForm(_AM_INFO_PERMISSIONS, $xoopsModule->mid(), _CON_INFO_PERMNAME, '',
                               '/admin/admin_permission.php', false);
$form->addItem(_CON_INFO_CANCREATE, _AM_INFO_CANCREATE, 0);
$form->addItem(_CON_INFO_CANUPDATE, _AM_INFO_CANUPDATE, 0);

$form->addItem(_CON_INFO_ALLCANUPDATE_CAT, _AM_INFO_CANUPDATE_CAT, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPDATE_POSITION, _AM_INFO_CANUPDATE_POSITION, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPDATE_GROUPS, _AM_INFO_CANUPDATE_GROUPS, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPDATE_SITEART, _AM_INFO_CANUPDATE_SITEART, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPDATE_SITEFULL, _AM_INFO_CANUPDATE_SITEFULL, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPDATE_HTML, _AM_INFO_CANALLOWHTML, _CON_INFO_CANCREATE);
$form->addItem(_CON_INFO_ALLCANUPLOAD, _AM_INFO_CANALLOWUPLOAD, _CON_INFO_CANCREATE);

$form->addItem(_CON_INFO_CANUPDATE_CAT, _AM_INFO_CANUPDATE_CAT, _CON_INFO_CANUPDATE);
$form->addItem(_CON_INFO_CANUPDATE_POSITION, _AM_INFO_CANUPDATE_POSITION, _CON_INFO_CANUPDATE);
$form->addItem(_CON_INFO_CANUPDATE_GROUPS, _AM_INFO_CANUPDATE_GROUPS, _CON_INFO_CANUPDATE);
$form->addItem(_CON_INFO_CANUPDATE_SITEART, _AM_INFO_CANUPDATE_SITEART, _CON_INFO_CANUPDATE);
$form->addItem(_CON_INFO_CANUPDATE_SITEFULL, _AM_INFO_CANUPDATE_SITEFULL, _CON_INFO_CANUPDATE);
$form->addItem(_CON_INFO_CANUPDATE_DELETE, _AM_INFO_CANDELETE, _CON_INFO_CANUPDATE);

echo $form->render();
unset($form);
xoops_cp_footer();
