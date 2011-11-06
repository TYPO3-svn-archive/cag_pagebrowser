<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006-2008 Torsten Schrade
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* class.tx_cagpagebrowser_pi1
*
* @author Torsten Schrade <schradt@uni-mainz.de>
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *  43: class tx_cagpagebrowser_pi1 extends tslib_pibase
 *  58: function main ($content, $conf)
 * 108: function checkValueInRootline ($field, $fieldvalue)
 * 130: function entryLink ($content, $conf)
 * 159: function pageNumbers ($menuArr, $conf)
 */

require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_cagpagebrowser extends tslib_pibase {

	var $prefixID = 'tx_cagpagebrowser';
	var $scriptRelPath = 'pi1/class.tx_cagpagebrowser.php';
	var $extKey = 'cag_pagebrowser';


	/**
	 * This function checks if a pagination may be inserted on the current page or not.
	 *
	 * @param	string	The content given to the function
	 * @param	array	TypoScript configuration array
	 *
	 * @return	boolean
	 */
	public function main ($content, $conf) {

		$exludeUids = in_array($this->cObj->getData('page: uid',''), t3lib_div::trimExplode(',',$conf['excludeUidList']));

		if ($conf['browserFunction'] == 'doktype' && $conf['browserMode'] == 'std') {

			($this->cObj->getData('levelfield: -2, doktype','') == '21' && count($GLOBALS['TSFE']->tmpl->rootLine)-1 != 0 && $exludeUids != 1) ? $value = '1' : $value = '0';

		} elseif ($conf['browserFunction'] == 'doktype' && $conf['browserMode'] == 'rec') {

			($this->checkValueInRootline('doktype','21') && $exludeUids != 1) ? $value = '1' : $value = '0';

		} elseif ($conf['browserFunction'] == 'plugin' && $conf['browserMode'] == 'std') {

			($this->cObj->getData('levelfield: -2, module','') == 'pbrowser' && count($GLOBALS['TSFE']->tmpl->rootLine)-1 != 0 && $exludeUids != 1) ? $value = '1' : $value = '0';

		} elseif ($conf['browserFunction'] == 'plugin' && $conf['browserMode'] == 'rec') {

			($this->checkValueInRootline('module','pbrowser') && $exludeUids != 1) ? $value = '1' : $value = '0';

		} elseif ($conf['browserFunction'] == 'both' && $conf['browserMode'] == 'std') {

			($this->cObj->getData('levelfield: -2, doktype','') == '21' && count($GLOBALS['TSFE']->tmpl->rootLine)-1 != 0 && $exludeUids != 1) ? $value = '1' : $value = '0';
			if (!$value) {
				($this->cObj->getData('levelfield: -2, module','') == 'pbrowser' && count($GLOBALS['TSFE']->tmpl->rootLine)-1 != 0 && $exludeUids != 1) ? $value = '1' : $value = '0';
			}

		} elseif ($conf['browserFunction'] == 'both' && $conf['browserMode'] == 'rec') {
			
			($this->checkValueInRootline('doktype','21') && $exludeUids != 1) ? $value = '1' : $value = '0';
			if (!$value) {
				($this->checkValueInRootline('module','pbrowser') && $exludeUids != 1) ? $value = '1' : $value = '0';
			}

		} else {
			$value = 0;
		}

		return $value;
	}


	/**
	 * Check if a field in the rootline of the current page contains a specific value and if yes return TRUE
	 *
	 * @param	string	The fieldname to look for in the rootline
	 * @param	string	The value of the field to be checked
	 *
	 * @return	boolean
	 */
	public function checkValueInRootline ($field, $fieldvalue) {

		$rootLine = $GLOBALS['TSFE']->tmpl->rootLine;

		foreach ($rootLine as $key => $val) {

			if ($rootLine[$key][$field] == $fieldvalue && $rootLine[$key]['uid'] != $GLOBALS['TSFE']->id) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Check if the current page is of the doktype pagebrowser or if the module module field is set to pagebrowser
	 *
	 * @param	The content given to the function
	 * @param	The TypoScript configuration array
	 *
	 * @return	boolean
	 */
	public function entryLink ($content, $conf) {

		$exludeUids = in_array($this->cObj->data['uid'], t3lib_div::trimExplode(',',$conf['excludeUidList']));

		if ($conf['browserFunction'] == 'both') {
			($GLOBALS[GLOBALS][TSFE]->page['doktype'] == '21' && $exludeUids != 1) ? $value = '1' : $value = '0';
			if (!$value) {
				($GLOBALS[GLOBALS][TSFE]->page['module'] == 'pbrowser' && $exludeUids != 1) ? $value = '1' : $value = '0';
			}

		} elseif ($conf['browserFunction'] == 'doktype') {
			($GLOBALS[GLOBALS][TSFE]->page['doktype'] == '21' && $exludeUids != 1) ? $value = '1' : $value = '0';

		} else {
			($GLOBALS[GLOBALS][TSFE]->page['module'] == 'pbrowser' && $exludeUids != 1) ? $value = '1' : $value = '0';
		}

		return $value;
	}

	/**
	 * Used to generate the pagenumbers for a pagebrowser section.
	 *
	 * @param	array	The menu array with all valid items
	 * @param	array	The TypoScript configuration
	 *
	 * @return	array	The modified menu array
	 */
	public function pageNumbers ($menuArr, $conf) {

		$stepSize = $conf['parentObj']->conf['1.']['stepSize'];
		$useNumbering = $conf['parentObj']->conf['1.']['useNumbering'];

		if ($stepSize) {
			$boundaries = array();
			// check at which position we are at the moment
			foreach ($menuArr as $key => $value) {
				if ($menuArr[$key]['uid'] == $GLOBALS['TSFE']->id) {
					$position = $key;
				}
				// get the boundaries
				if (($key % $stepSize) == 0) {
					$boundaries[] = $key;
				}
				// override titles
				if ($useNumbering == 1) {
					$menuArr[$key]['title'] = $key+1;
				}
			}

			// test within which boundary we are and set the offset accordingly
			foreach ($boundaries as $key => $value) {

				if ($position < $value) {
					$offset = $boundaries[$key-1];
					break;
				} else {
					$offset = $boundaries[$key];
				}
			}

			// return only the items within the current boundaries
			$menuArr = array_slice($menuArr, $offset, $stepSize);
		} else {
			if ($useNumbering == 1) {
				foreach ($menuArr as $key => $value) {
					// override titles
					$menuArr[$key]['title'] = $key+1;
					// override navigation titles
					$menuArr[$key]['nav_title'] = $key+1;
				}
			}
		}
		return $menuArr;
	}
	
	/* Makes it possible to loop through a whole pagetree with first/prev/index/next/last regardless of levels.
	 * Shortcuts and external URLs are also supported. Ignored doktypes are 5-n/in menu, 6-BE user, 7-mountpoint, 255-Recycler.
	 * Doktypes that will be skipped in the navigation are 199-Spacer and 254-Sysfolder.
	 * 
	 * @param $content	Content from TypoScript, emtpy in this case
	 * @param $conf		Configuration of the Userfunction in TypoScript
	 * 
	 * @return void
	 * 
	 */
	public function treePrevNext ($content, $conf) {
		
		// first go back to the pagebrowser page ...
		foreach ($GLOBALS['TSFE']->tmpl->rootLine as $ancestor) {
			if ($ancestor['module'] == 'pbrowser' || $ancestor['doktype'] == 21) $entrypoint = $ancestor['uid'];
		}
		
		// ... and set it as index page for the tree branch
		$this->cObj->data['index'] = $entrypoint;
		
		// ... and now collect all pages from this branch (mind: collection will not decend into recyclers or down mountpoint sections)
		$additionalWhere = 'AND doktype NOT IN (7,255)';		
		$tree = t3lib_div::trimExplode(',', $this->cObj->getTreeList($entrypoint, 10, 0, FALSE, '', $additionalWhere), 1);

		// get uids to exclude if any
		if ($conf['excludeUids']) $excludeUids = t3lib_div::trimExplode(',', $conf['excludeUids'], 1);

		// doktypes to skip in the pagebrowser navigation /the following will *always* be skipped)
		$doktypesToSkip = array(0 => 5, 1 => 6, 2 => 7, 3 => 21, 4 => 199, 5 => 254, 6 => 255);
		// if the user has set other doktypes to skip merge them
		if ($conf['excludeDoktypes']) $excludeDoktypes = t3lib_div::trimExplode(',', $conf['excludeDoktypes'], 1);
		if (count($excludeDoktypes) > 0) $doktypesToSkip = array_merge($doktypesToSkip, array_diff($excludeDoktypes, $doktypesToSkip));	
		
		// filter the page array for forbidden doktypes, uids to skip and for later treatment of shortcuts and external if they are allowed
		foreach ($tree as $key => $uid) {
			// get page information		
			$pageArray[$uid] = $GLOBALS['TSFE']->sys_page->getRawRecord('pages', $uid, 'uid,doktype,shortcut,shortcut_mode,url,nav_hide');
			// drop excluded uids, doktypes and pages with nav_hide from page array
			if (in_array($pageArray[$uid]['doktype'], $doktypesToSkip) || in_array($pageArray[$uid]['uid'], $excludeUids)) unset($pageArray[$uid]);
			if ($pageArray[$uid]['nav_hide'] == 1) unset($pageArray[$uid]);
		}		
		
		// reset array keys
		$filteredTree = array_keys($pageArray);
		
		// use pagenumbers?
		if ($conf['pageNumbers'] == 1) $this->cObj->data['treeuids'] = implode(',', $filteredTree);

		// determine position of the current page within the tree
		$currentKey = array_search($GLOBALS['TSFE']->id, $filteredTree);
		$prevUid = $filteredTree[$currentKey-1];
		$nextUid = $filteredTree[$currentKey+1];
		$prevPages = array_reverse(array_slice($filteredTree, 0, $currentKey));
		$nextPages = array_slice($filteredTree, $currentKey+1);

		// previous page is a shortcut
		if ($pageArray[$prevUid]['doktype'] == 4) {
				
			// first determine where this points to
			$shortcutTarget = $GLOBALS['TSFE']->getPageShortcut($pageArray[$prevUid]['shortcut'], $pageArray[$prevUid]['shortcut_mode'], $pageArray[$prevUid]['uid']);

			// if the shortcut target doesn't exist or points 'behind' current page or is the current page...
			if (!$shortcutTarget || in_array($shortcutTarget['uid'], $nextPages) || $shortcutTarget['uid'] == $GLOBALS['TSFE']->id) {
				
				// determine a new valid previous page since the current blocks the way				
				$validPrevUid = $this->getValidTreePrevNextPage($prevPages, $nextPages, $pageArray);
				($validPrevUid) ? $prevUid = $validPrevUid : $prevUid = 0;
				 
			} // else nothing done, shortcut points to valid prev page or outside
		}

		// next page is a shortcut
		if ($pageArray[$nextUid]['doktype'] == 4) {

			// first determine where this points to
			$shortcutTarget = $GLOBALS['TSFE']->getPageShortcut($pageArray[$nextUid]['shortcut'], $pageArray[$nextUid]['shortcut_mode'], $pageArray[$nextUid]['uid']);
			
			// if the shortcut target doesn't exist or points 'behind' current page or is the current page...
			if (!$shortcutTarget || in_array($shortcutTarget['uid'], $prevPages) || $shortcutTarget['uid'] == $GLOBALS['TSFE']->id) {
							
				// determine a new valid next page since the current blocks the way
				$validNextUid = $this->getValidTreePrevNextPage($nextPages, $prevPages, $pageArray);
				($validNextUid) ? $nextUid = $validNextUid : $nextUid = 0;
			}
		} // else nothing done, shortcut points to valid next page or outside
		
		// set values into current cObj->data;
		// if first and last pages are shortcuts... well, this is not tested here		
		$this->cObj->data['first'] = $filteredTree[0];	
		$this->cObj->data['prev'] = $prevUid;		
		$this->cObj->data['next'] = $nextUid;
		$this->cObj->data['last'] = end($filteredTree);			
		
		// if looping is configured, prev/next link to first/last page in branch	
		if (!$this->cObj->data['next'] && $conf['treeLoop'] == 1) {
			$this->cObj->data['next'] = $this->cObj->data['first'];
		// if there is no prev page we are on the very first page; set this
		} elseif (!$this->cObj->data['next'] && $conf['treeLoop'] != 1) {
			$this->cObj->data['next'] = $this->cObj->data['last'];
		}
		
		if (!$this->cObj->data['prev'] && $conf['treeLoop'] == 1) {
			$this->cObj->data['prev'] = $this->cObj->data['last'];
		} elseif (!$this->cObj->data['prev'] && $conf['treeLoop'] != 1) {
			$this->cObj->data['prev'] = $this->cObj->data['first'];			
		}
	
		return $content;
	}
	
	/* Finds the next 'valid' page in the tree for prev/next navigation. Valid means one of the supported doktypes or, if shortcut,
	 * not pointing 'behind' the current page (that would block the prev/next flow).
	 * 
	 * @param array 	Array consisting of uids to check (either the prev/next pages)
	 * @param array		Array with uids that may not be allowed as prev/next targets
	 * @param array		The full page array for the prev/navigation
	 * 
	 * @return integer
	 * 
	 */
	public function getValidTreePrevNextPage($pagesToCheck, $dissallowedPages, $pageArray) {

		foreach($pagesToCheck as $key => $value) {
			
			if ($pageArray[$value]['doktype'] == 4) {
				if ($key == 0) continue; 
				$target = $GLOBALS['TSFE']->getPageShortcut($pageArray[$value]['shortcut'], $pageArray[$value]['shortcut_mode'], $pageArray[$value]['uid']);
				if (!$target || in_array($target['uid'], $dissallowedPages) || $target['uid'] == $GLOBALS['TSFE']->id) {
					// not valid, page points 'behind'
					continue;
				} else {
					$validPageUid = $value;
					break;
				}
			// next valid prev page
			} else {
				$validPageUid = $value;
				break;
			}
		}		

		return $validPageUid;		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cag_pagebrowser/pi1/class.tx_cagpagebrowser.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cag_pagebrowser/pi1/class.tx_cagpagebrowser.php']);
}
?>
