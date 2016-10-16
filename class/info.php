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
//  @package info.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: info.php 73 2013-03-19 20:14:02Z alfred $

if (!class_exists('InfoInfo')) {
    class InfoInfo extends XoopsObject
    {
        public function __construct()
        {
            $this->initVar('info_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('old_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('parent_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('owner', XOBJ_DTYPE_INT, -1, false);
            $this->initVar('st', XOBJ_DTYPE_INT, 2, false);
            $this->initVar('frontpage', XOBJ_DTYPE_INT, 0, false);
            $fr = array(
                'height' => '250',
                'border' => '0',
                'width'  => '100',
                'align'  => 'center'
            );
            $this->initVar('frame', XOBJ_DTYPE_OTHER, serialize($fr), true);
            $this->initVar('click', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('self', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('blockid', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 255, true);
            $this->initVar('text', XOBJ_DTYPE_OTHER, null, true, null, true);
            $this->initVar('visible', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('nohtml', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('nobreaks', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('nosmiley', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('dobr', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('nocomments', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('cat', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('submenu', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('link', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('address', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
            $this->initVar('visible_group', XOBJ_DTYPE_OTHER, '1,2,3', true);
            $this->initVar('edited_time', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('edited_user', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('title_sicht', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('footer_sicht', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('tooltip', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
            $this->initVar('bl_left', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('bl_right', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('tags', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
        }
    }
}

if (!class_exists('InfoInfoHandler')) {
    class InfoInfoHandler extends XoopsPersistableObjectHandler
    {
        public function __construct($db, $mname)
        {
            parent::__construct($db, $mname, 'InfoInfo', 'info_id', 'parent_id');
        }

        public function read_startpage()
        {
            $frontpage = false;
            $sql       = 'SELECT info_id,title FROM ' . $this->table . ' WHERE frontpage=1';
            $res       = $this->db->fetchArray($this->db->query($sql));
            if ($res) {
                $frontpage = array($res['info_id'], $res['title']);
            }

            return $frontpage;
        }

        public function del_startpage($id = 0)
        {
            if ($id > 0) {
                $sql = 'UPDATE ' . $this->table . ' SET frontpage=0 WHERE info_id=' . $id;
                $res = $this->db->query($sql);
                if ($res) {
                    return true;
                }
            }

            return false;
        }

        public function insert($object, $force = true)
        {
            if (parent::insert($object, $force)) {
                if ($object->getVar('tags', 'n') != '') {
                    include_once XOOPS_ROOT_PATH . '/modules/tag/include/functions.php';
                    if ($tagHandler = tag_getTagHandler()) {
                        $module_name = basename(dirname(__DIR__));
                        $tagHandler->updateByItem($object->getVar('tags', 'n'), $object->getVar('info_id'),
                                                  $module_name);
                    }
                }

                return true;
            }

            return false;
        }

        public function readbakid($id = 0)
        {
            if ((int)$id <= 0) {
                return false;
            }
            $ret = false;
            $sql = 'SELECT old_id FROM ' . $this->table . ' WHERE old_id=' . $id;
            $res = $this->db->fetchArray($this->db->query($sql));
            if ($res) {
                if ($res['old_id'] > 0 && $res['old_id'] == $id) {
                    $ret = true;
                }
            }

            return $ret;
        }
    }
}
