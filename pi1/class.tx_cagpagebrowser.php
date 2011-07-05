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
	function main ($content, $conf) {

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
	function checkValueInRootline ($field, $fieldvalue) {

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
	function entryLink ($content, $conf) {

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
	function pageNumbers ($menuArr, $conf) {

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
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cag_pagebrowser/pi1/class.tx_cagpagebrowser.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cag_pagebrowser/pi1/class.tx_cagpagebrowser.php']);
}
?>
