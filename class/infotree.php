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
//  @package infotree.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: infotree.php 79 2013-09-13 18:04:49Z alfred $

defined('XOOPS_ROOT_PATH') or die('Restricted access');

if ( !class_exists ( 'InfoTree' ) ) 
{

/**
 * Abstract base class for forms
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author John Neill <catzwolf@xoops.org>
 * @copyright copyright (c) XOOPS.org
 * @package kernel
 * @subpackage XoopsTree
 * @access public
 */
class InfoTree
{
    var $table; //table with parent-child structure
    var $id; //name of unique id for records in table $table
    var $pid; // name of parent id used in table $table
    var $order; //specifies the order of query results
    var $title; // name of a field in table $table which will be used when  selection box and paths are generated
    var $db;

    //constructor of class XoopsTree
    //sets the names of table, unique id, and parend id
    public function __construct($table_name, $id_name, $pid_name)
    {
        $this->db 		= XoopsDatabaseFactory::getDatabaseConnection();
        $this->table 	= $table_name;
        $this->id 		= $id_name;
        $this->pid 		= $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)
    public function getFirstChild($sel_id, $order = '')
    {
        $sel_id = (int)$sel_id;
        $arr = array();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '='
               . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $arr;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            array_push($arr, $myrow);
        }
        return $arr;
    }


    public function getFirstId($sel_id)
    {
        $sel_id = (int)$sel_id;
        $r_id = 0;
        $result = $this->db->query('SELECT ' . $this->pid . ' FROM '
                                   . $this->table . ' WHERE '
                                   . $this->id . '='
                                   . $sel_id . '');
        $count = $this->db->getRowsNum($result);
        list ($r_id) = $this->db->fetchRow($result);
        if ($count == 0 || $r_id ==0) {
            return $sel_id;			
        }
        $r_id = $this->getFirstId($r_id);		
        return $r_id;
    }

	 // returns an array of all FIRST child ids of a given id($sel_id)
    public function getFirstChildId($sel_id)
    {
        $sel_id = (int)$sel_id;
        $idarray = array();
        $result = $this->db->query('SELECT ' . $this->id . ' FROM '
                                   . $this->table . ' WHERE '
                                   . $this->pid . '='
                                   . $sel_id . '');
        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list ($id) = $this->db->fetchRow($result)) {
            array_push($idarray, $id);			
        }
        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)
    public function getAllChildId($sel_id, $order = '', $idarray = array())
    {
        $sel_id = (int)$sel_id;
        $sql = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE '
               . $this->pid . '='
               . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list ($r_id) = $this->db->fetchRow($result)) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }
        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)
    public function getAllParentId($sel_id, $order = '', $idarray = array())
    {
        $sel_id = (int)$sel_id;
        $sql = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE '
               . $this->id . '='
               . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list ($r_id) = $this->db->fetchRow($result);
        if ($r_id == 0) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id, $order, $idarray);
        return $idarray;
    }

	//returns an array of ALL parent title for a given id($sel_id)
    public function getAllParentTitle($sel_id, $order = '', $idarray = array())
    {
        $sel_id = (int)$sel_id;
        $sql = 'SELECT ' . $this->pid . ', title, info_id FROM '
               . $this->table . ' WHERE '
               . $this->id . '='
               . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list ($r_id,$r_title,$r_storyid) = $this->db->fetchRow($result);
        if ($r_id == 0) {
            return $idarray;
        }
        $idarray[$r_storyid] = $r_title;
        $idarray = $this->getAllParentTitle($r_id, $order, $idarray);
        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)
    // the path is delimetered with "/"
    public function getPathFromId($sel_id, $title, $path = '')
    {
        $sel_id = (int)$sel_id;
        $result = $this->db->query('SELECT ' . $this->pid . ', '
                                   . $title . ' FROM '
                                   . $this->table . ' WHERE '
                                   . $this->id . "=$sel_id");
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid, $name) = $this->db->fetchRow($result);
        $myts = & MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = '/' . $name . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);
        return $path;
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '', $extra = null)
    {
        if ($sel_name == '') {
            $sel_name = $this->id;
        }
        $myts = MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != '') {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM '
               . $this->table . ' WHERE '
               . $this->pid . '=0 '
               . $extra;
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>----</option>\n";
        }
        while (list ($cat_id, $name) = $this->db->fetchRow($result)) {
            $sel = '';
            if ($cat_id == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$cat_id'$sel>$name</option>\n";
            $sel = '';
            $arr = $this->getChildTreeArray($cat_id, $order, array(), '', $extra);
            foreach($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);
                $catpath = $option['prefix'] . '&nbsp;'
                           . $myts->htmlspecialchars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = '';
            }
        }
        echo "</select>\n";
    }

	public function makeMySelArray($title, $order = '', $preset_id = 0, $none = 0, $extra = null)
    {
        $ret = array();
        $myts = MyTextSanitizer::getInstance();
        $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM '
               . $this->table . ' WHERE '
               . $this->pid . '=0 '
               . $extra;
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            $ret[0] = '----';
        }
        while (list ($cat_id, $name) = $this->db->fetchRow($result)) {
            $arr = $this->getChildTreeArray($cat_id, $order, array(), '', $extra);
            $ret[$cat_id] = $name;
            foreach($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);
                $catpath = $option['prefix'] . '&nbsp;'
                           . $myts->htmlspecialchars($option['title']);
                $ret[$option[$this->id]] = $catpath;
                unset($option);
            }
        }
        return $ret;
    }

    //generates nicely formatted linked path from the root id to a given id
    public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
    {
        $path = ! empty($path) ? '&nbsp;:&nbsp;' . $path : $path;
        $sel_id = (int)$sel_id;
        $sql = 'SELECT ' . $this->pid . ', ' . $title . ' FROM '
               . $this->table . ' WHERE '
               . $this->id . "=$sel_id";
        $result = $this->db->query($sql);
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid, $name) = $this->db->fetchRow($result);
        $myts = & MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = "<a href='" . $funcURL . '&amp;' . $this->id . '='
                . $sel_id . "'>" . $name . '</a>'
                . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);
        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    public function getIdPathFromId($sel_id, $path = '')
    {
        $sel_id = (int)$sel_id;
        $result = $this->db->query('SELECT ' . $this->pid . ' FROM '
                                   . $this->table . ' WHERE '
                                   . $this->id . "=$sel_id");
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid) = $this->db->fetchRow($result);
        $path = '/' . $sel_id . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);
        return $path;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $sel_id
     * @param unknown_type $order
     * @param unknown_type $parray
     * @return unknown
     */
    public function getAllChild($sel_id = 0, $order = '', $parray = array(), $extra=null)
    {
        $sel_id = (int)$sel_id;
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '='
               . $sel_id . ''
               . $extra;
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            array_push($parray, $row);
            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }
        return $parray;
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $sel_id
     * @param unknown_type $order
     * @param unknown_type $parray
     * @param unknown_type $r_prefix
     * @return unknown
     */
    public function getChildTreeArray($sel_id = 0, $order = '', $parray = array(), $r_prefix = '', $extra = null)
    {
        $sel_id = (int)$sel_id;
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '='
               . $sel_id . ''
               . $extra;
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
			if ($sel_id != 0) {
				$row['prefix'] = $r_prefix . '&nbsp;';
			}
			array_push($parray, $row);
			if ($sel_id == 0) {
				$row['prefix'] = $r_prefix . '&nbsp;';
			}
			$parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix'], $extra);
        }
        return $parray;
    }

	public function checkperm($visiblegroups = array(), $usergroups = array()) {
		if (count($usergroups) > 0 && count($visiblegroups) > 0) {
			$vsgroup	= explode (',', $visiblegroups);
			$vscount	= count($vsgroup)-1;		
			while ($vscount > -1) {
				if (in_array($vsgroup[$vscount], $usergroups)) return true;
				$vscount--;
			}
		}
		return false;
	}

}

}

