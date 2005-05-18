<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2001 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/


/**
* Class ilObjFileBasedLMListGUI
*
* @author Alex Killing <alex.killing@gmx.de>
* $Id$
*
* @extends ilObjectListGUI
*/


include_once "classes/class.ilObjectListGUI.php";

class ilObjFileBasedLMListGUI extends ilObjectListGUI
{
	/**
	* constructor
	*
	*/
	function ilObjFileBasedLMListGUI()
	{
		$this->ilObjectListGUI();
	}

	/**
	* initialisation
	*
	* this method should be overwritten by derived classes
	*/
	function init()
	{
		$this->delete_enabled = true;
		$this->cut_enabled = true;
		$this->subscribe_enabled = true;
		$this->link_enabled = true;
		$this->payment_enabled = true;
		$this->type = "htlm";
		$this->gui_class_name = "ilobjfilebasedlmgui";

		// general commands array
		$this->commands = array
		(
			array("permission" => "read", "cmd" => "view", "lang_var" => "show"),
			array("permission" => "write", "cmd" => "edit", "lang_var" => "edit"),
		);
	}

	/**
	* Overwrite this method, if link target is not build by ctrl class
	* (e.g. "lm_presentation.php", "forum.php"). This is the case
	* for all links now, but bringing everything to ilCtrl should
	* be realised in the future.
	*
	* @param	string		$a_cmd			command
	*
	*/
	function getCommandLink($a_cmd)
	{
		switch($a_cmd)
		{
			case "view":
				$cmd_link = "content/fblm_presentation.php?ref_id=".$this->ref_id;
				break;

			case "edit":
				$cmd_link = "content/fblm_edit.php?ref_id=".$this->ref_id;
				break;

			default:
				$cmd_link = "repository.php?ref_id=".$this->ref_id."&cmd=$a_cmd";
				break;
		}

		return $cmd_link;
	}


	/**
	* Get command target frame
	*
	* @param	string		$a_cmd			command
	*
	* @return	string		command target frame
	*/
	function getCommandFrame($a_cmd)
	{
		global $ilias;

		switch($a_cmd)
		{
			case "view":
				$showViewInFrameset = $ilias->ini->readVariable("layout","view_target") == "frame";
				$isBuyable = ilPaymentObject::_isBuyable($this->ref_id);
				if (($isBuyable && ilPaymentObject::_hasAccess($this->ref_id) == false) ||
					$showViewInFrameset)
				{
					$frame = ilFrameTargetInfo::_getFrame("MainContent");
				}
				else
				{
					$frame = "ilContObj".$this->obj_id;
				}
				break;

			case "edit":
				$frame = ilFrameTargetInfo::_getFrame("MainContent");
				break;

			default:
				$frame = "";
				break;
		}

		return $frame;
	}


	/**
	* Get item properties
	*
	* @return	array		array of property arrays:
	*						"alert" (boolean) => display as an alert property (usually in red)
	*						"property" (string) => property name
	*						"value" (string) => property value
	*/
	function getProperties()
	{
		global $lng, $rbacsystem;

		$props = array();

		include_once("content/classes/class.ilObjFileBasedLMAccess.php");

		if (!ilObjFileBasedLMAccess::_lookupOnline($this->obj_id))
		{
			$props[] = array("alert" => true, "property" => $lng->txt("status"),
				"value" => $lng->txt("offline"));
		}

		if ($rbacsystem->checkAccess($this->ref_id, "write"))
		{
			$props[] = array("alert" => false, "property" => $lng->txt("type"),
				"value" => $lng->txt("htlm"));
		}

		return $props;
	}


} // END class.ilObjCategoryGUI
?>
