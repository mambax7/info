<?php
/**
 * Plugin-Tag for info Module
 *
 * @copyright	The XOOPS Project http://xoops.sf.net
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <phppp@users.sourceforge.net>
 * @version		$Id $
 * @package		module::info
 */


//if (!defined('XOOPS_ROOT_PATH')) { exit(); }

$infoname = basename( dirname (__DIR__) ) ;

/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var		array	$items	associative array of items: [modid][cat_id][itemid]
 *
 * @return	boolean
 * 
 */

eval (' function ' . $infoname . '_tag_iteminfo(&$items)
{
	global $xoopsDB;

	if (empty($items) || !is_array($items)) {
		return false;
	}
	
	$items_id = array();
	foreach (array_keys($items) as $cat_id) {
		foreach (array_keys($items[$cat_id]) as $item_id) {
			$items_id[] = intval($item_id);
		}
	}
	include_once "' . XOOPS_ROOT_PATH . '/modules/' . $infoname . '/class/info.php";
	$itemHandler = new InfoInfoHandler( $xoopsDB, "' . $infoname . '");
	$inids = implode(", ", $items_id);
	$items_obj = $itemHandler->getObjects("", true);	
	foreach(array_keys($items) as $cat_id){
		foreach(array_keys($items[$cat_id]) as $item_id) {
			if(isset($items_obj[$item_id])) {
				$item_obj =& $items_obj[$item_id];
				$icat = $item_obj->getVar("owner");
				$items[$cat_id][$item_id] = array (
					"title"		=> $item_obj->getVar("title"),
					"uid"		=> $item_obj->getVar("owner"),
					"link"		=> "index.php?content={$icat}:{$item_id}",
					"time"		=> $item_obj->getVar("edited_time"),
					"tags"		=> tag_parse_tag($item_obj->getVar("tags", "n")), 
					"content"	=> "",
				);
			}
		}
	}
	unset($items_obj);	
}
');

/**
 * Remove orphan tag-item links
 *
 * @return	boolean
 * 
 */
eval (' function '. $infoname . '_tag_synchronization($mid)
{
	include_once "' . XOOPS_ROOT_PATH . '/modules/' . $infoname . '/class/info.php";
	$itemHandler = new InfoInfoHandler( $xoopsDB, "' . $infoname . '");
	$linkHandler = xoops_getModuleHandler("link", "tag");
        
	/* clear tag-item links */
	if ($linkHandler->mysql_major_version() >= 4):
    $sql =	"	DELETE FROM {$linkHandler->table}".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( tag_itemid NOT IN ".
    		"			( SELECT DISTINCT {$itemHandler->keyName} ".
    		"				FROM {$itemHandler->table} ".
    		"				WHERE {$itemHandler->table}.edited_time > 0".
    		"			) ".
    		"		)";
    else:
    $sql = 	"	DELETE {$linkHandler->table} FROM {$linkHandler->table}".
    		"	LEFT JOIN {$itemHandler->table} AS aa ON {$linkHandler->table}.tag_itemid = aa.{$itemHandler->keyName} ".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( aa.{$itemHandler->keyName} IS NULL".
    		"			OR aa.edited_time < 1".
    		"		)";
	endif;
    if (!$result = $linkHandler->db->queryF($sql)) {
        //xoops_error($linkHandler->db->error());
  	}
}
');
