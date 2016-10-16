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
//  @package update.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: update.php 79 2013-09-13 18:04:49Z alfred $

include dirname(__DIR__) . '/include/function.php';

//Install
eval('function xoops_module_pre_install_' . $module_name . '(&$module) {
  // clear cache
  xoops_load("XoopsCache");
	$key = $module->getInfo("dirname") . "_*";
	clearInfoCache($key);
  // check Templates
  if (!check_infotemplates($module)) return false;  
  if (!check_infotable($module)) return false;
  return true;
}');

//Update
eval('function xoops_module_update_' . $module_name . '($module) {
  // clear cache
  xoops_load("XoopsCache");
	$key = $module->getInfo("dirname") . "_*";
	clearInfoCache($key);
  // check Templates  
	if (!check_infotemplates($module)) return false;
  if ( $module->getVar("version") < 261 ) {
    update_infotable($module);
  }
	if (!check_infotable($module)) return false;	
	return true;
}');

function update_infotable($module)
{
    global $xoopsDB;
    $err = true;

    $tables_cat = array('catid' => 'cat_id int(8) NOT NULL auto_increment');

    $tables_tab = array(
        'storyid'  => 'info_id int(8) NOT NULL auto_increment',
        'bakid'    => "old_id int(8) NOT NULL default '0'",
        'homepage' => "cat int(8) NOT NULL default '0'"
    );

    foreach ($tables_cat as $old => $new) {
        $sql    = 'ALTER TABLE '
                  . $xoopsDB->prefix($module->getInfo('dirname'))
                  . '_cat CHANGE '
                  . $old
                  . ' '
                  . $new
                  . ';';
        $result = $xoopsDB->queryF($sql);
    }

    foreach ($tables_tab as $old => $new) {
        $sql    = 'ALTER TABLE '
                  . $xoopsDB->prefix($module->getInfo('dirname'))
                  . '_bak CHANGE '
                  . $old
                  . ' '
                  . $new
                  . ';';
        $result = $xoopsDB->queryF($sql);
        $sql    = 'ALTER TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . ' CHANGE ' . $old . ' ' . $new . ';';
        $result = $xoopsDB->queryF($sql);
    }

    return $err;
}

function check_infotemplates($module)
{
    $err = true;
    if (!file_exists(XOOPS_ROOT_PATH
                     . '/modules/'
                     . $module->getInfo('dirname')
                     . '/templates/'
                     . $module->getInfo('dirname')
                     . '_index.html')
    ) {
        rename(XOOPS_ROOT_PATH . '/modules/' . $module->getInfo('dirname') . '/templates/info_index.html',
               XOOPS_ROOT_PATH
               . '/modules/'
               . $module->getInfo('dirname')
               . '/templates/'
               . $module->getInfo('dirname')
               . '_index.html');
        if (!file_exists(XOOPS_ROOT_PATH
                         . '/modules/'
                         . $module->getInfo('dirname')
                         . '/templates/'
                         . $module->getInfo('dirname')
                         . '_index.html')
        ) {
            $module->setErrors('Template ' . $module->getInfo('dirname') . '_index.html not exists!');
            $err = false;
        }
    }
    if (!file_exists(XOOPS_ROOT_PATH
                     . '/modules/'
                     . $module->getInfo('dirname')
                     . '/templates/blocks/'
                     . $module->getInfo('dirname')
                     . '_freiblock.html')
    ) {
        rename(XOOPS_ROOT_PATH . '/modules/' . $module->getInfo('dirname') . '/templates/blocks/info_freiblock.html',
               XOOPS_ROOT_PATH
               . '/modules/'
               . $module->getInfo('dirname')
               . '/templates/blocks/'
               . $module->getInfo('dirname')
               . '_freiblock.html');
        if (!file_exists(XOOPS_ROOT_PATH
                         . '/modules/'
                         . $module->getInfo('dirname')
                         . '/templates/blocks/'
                         . $module->getInfo('dirname')
                         . '_freiblock.html')
        ) {
            $module->setErrors('Template ' . $module->getInfo('dirname') . '_freiblock.html not exists!');
            $err = false;
        }
    }
    if (!file_exists(XOOPS_ROOT_PATH
                     . '/modules/'
                     . $module->getInfo('dirname')
                     . '/templates/blocks/'
                     . $module->getInfo('dirname')
                     . '_nav_block.html')
    ) {
        rename(XOOPS_ROOT_PATH . '/modules/' . $module->getInfo('dirname') . '/templates/blocks/info_nav_block.html',
               XOOPS_ROOT_PATH
               . '/modules/'
               . $module->getInfo('dirname')
               . '/templates/blocks/'
               . $module->getInfo('dirname')
               . '_nav_block.html');
        if (!file_exists(XOOPS_ROOT_PATH
                         . '/modules/'
                         . $module->getInfo('dirname')
                         . '/templates/blocks/'
                         . $module->getInfo('dirname')
                         . '_nav_block.html')
        ) {
            $module->setErrors('Template ' . $module->getInfo('dirname') . '_nav_block.html not exists!');
            $err = false;
        }
    }

    return $err;
}

function check_infotable($module)
{
    global $xoopsDB;
    $err = true;

    $tables_cat = array(
        'cat_id'  => 'int(8) NOT NULL auto_increment',//catid
        'visible' => "tinyint(1) NOT NULL default '0'",
        'title'   => "varchar(255) NOT NULL default ''"
    );

    $tables_tab = array(
        'info_id'       => 'int(8) NOT NULL auto_increment',//storyid
        'parent_id'     => "int(8) NOT NULL default '0'",
        'old_id'        => "int(8) NOT NULL default '0'",//bakid
        'cat'           => "int(8) NOT NULL default '0'",//homepage
        'st'            => "int(2) NOT NULL default '0'",
        'owner'         => "int(15) NOT NULL default '0'",
        'blockid'       => "int(8) NOT NULL default '0'",
        'frontpage'     => "tinyint(1) NOT NULL default '0'",
        'visible'       => "tinyint(1) NOT NULL default '0'",
        'nohtml'        => "tinyint(1) NOT NULL default '0'",
        'nosmiley'      => "tinyint(1) NOT NULL default '0'",
        'nobreaks'      => "tinyint(1) NOT NULL default '0'",
        'nocomments'    => "tinyint(1) NOT NULL default '0'",
        'link'          => "tinyint(1) NOT NULL default '0'",
        'address'       => 'varchar(255) default NULL',
        'visible_group' => 'text default NULL',
        'edited_time'   => "int(15) NOT NULL default '0'",
        'edited_user'   => "int(15) NOT NULL default '0'",
        'click'         => "tinyint(1) NOT NULL default '0'",
        'self'          => "tinyint(1) NOT NULL default '0'",
        'frame'         => 'text default NULL',
        'tooltip'       => 'text default NULL',
        'title_sicht'   => "tinyint(1) NOT NULL default '1'",
        'footer_sicht'  => "tinyint(1) NOT NULL default '1'",
        'submenu'       => "tinyint(1) NOT NULL default '0'",
        'bl_left'       => "int(2) NOT NULL default '1'",
        'bl_right'      => "int(2) NOT NULL default '1'",
        'title'         => "varchar(255) NOT NULL default ''",
        'text'          => "text NOT NULL default ''",
        'tags'          => "varchar(255) NOT NULL default ''"
    );

    if (!InfoTableExists($xoopsDB->prefix($module->getInfo('dirname')) . '_cat')) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . '_cat (';
        foreach ($tables_cat as $s => $w) {
            $sql .= ' ' . $s . ' ' . $w . ',';
        }
        $sql .= ' PRIMARY KEY  (cat_id)
                ) ;';
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            $module->setErrors("Can't create Table " . $xoopsDB->prefix($module->getInfo('dirname')) . '_cat');

            return false;
        } else {
            $sql    = 'INSERT INTO '
                      . $xoopsDB->prefix($module->getInfo('dirname'))
                      . "_cat (cat_id,title) VALUES (1,'Default')";
            $result = $xoopsDB->queryF($sql);
        }
    } else {
        foreach ($tables_cat as $s => $w) {
            if (!InfoColumnExists($xoopsDB->prefix($module->getInfo('dirname')) . '_cat', $s)) {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . '_cat ADD '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            } else {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . '_cat CHANGE '
                          . $s
                          . ' '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            }
        }
    }

    if (!InfoTableExists($xoopsDB->prefix($module->getInfo('dirname')))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . " ( \n";
        foreach ($tables_tab as $s => $w) {
            $sql .= ' ' . $s . ' ' . $w . ",\n";
        }
        $sql .= "  PRIMARY KEY  (info_id),\n
             KEY title (title(40))\n
           ) ;";
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            $module->setErrors("Can't create Table " . $xoopsDB->prefix($module->getInfo('dirname')));
            $sql    = 'DROP TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . '_cat';
            $result = $xoopsDB->queryF($sql);

            return false;
        }
    } else {
        foreach ($tables_tab as $s => $w) {
            if (!InfoColumnExists($xoopsDB->prefix($module->getInfo('dirname')), $s)) {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . ' ADD '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            } else {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . ' CHANGE '
                          . $s
                          . ' '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            }
        }
    }
    if (!InfoTableExists($xoopsDB->prefix($module->getInfo('dirname')) . '_bak')) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . '_bak ( ';
        foreach ($tables_tab as $c => $w) {
            $sql .= ' ' . $c . ' ' . $w . ',';
        }
        $sql .= '  PRIMARY KEY  (info_id),
             KEY title (title(40))
           ) ;';
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            $module->setErrors("Can't create Table " . $xoopsDB->prefix($module->getInfo('dirname')) . '_bak');
            $sql    = 'DROP TABLE ' . $xoopsDB->prefix($module->getInfo('dirname')) . '_cat';
            $result = $xoopsDB->queryF($sql);
            $sql    = 'DROP TABLE ' . $xoopsDB->prefix($module->getInfo('dirname'));
            $result = $xoopsDB->queryF($sql);

            return false;
        }
    } else {
        foreach ($tables_tab as $s => $w) {
            if (!InfoColumnExists($xoopsDB->prefix($module->getInfo('dirname')) . '_bak', $s)) {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . '_bak ADD '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            } else {
                $sql    = 'ALTER TABLE '
                          . $xoopsDB->prefix($module->getInfo('dirname'))
                          . '_bak CHANGE '
                          . $s
                          . ' '
                          . $s
                          . ' '
                          . $w
                          . ';';
                $result = $xoopsDB->queryF($sql);
            }
        }
    }

    return true;
}
