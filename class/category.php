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
//  @package category.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: category.php 73 2013-03-19 20:14:02Z alfred $

if ( !class_exists ( 'InfoCategory' ) ) 
{

	class InfoCategory extends XoopsObject {

		public function __construct()
		{
			$this->initVar('cat_id'	, XOBJ_DTYPE_INT, NULL, false);
			$this->initVar('visible', XOBJ_DTYPE_INT, 1, false);
			$this->initVar('title'	, XOBJ_DTYPE_TXTBOX, NULL, true, 255, true);
		}
	}

}

if ( !class_exists ( 'InfoCategoryHandler' ) ) 
{

	class InfoCategoryHandler extends XoopsPersistableObjectHandler
	{

		public function __construct($db, $mname) 
		{
			parent::__construct($db, $mname."_cat", 'InfoCategory', 'cat_id', 'title');
		}
    }    

}
