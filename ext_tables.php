<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Adding the TS Object
t3lib_extMgm::addStaticFile($_EXTKEY,'static/','Pagebrowser');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/entrylink','Pagebrowser: Entry link');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/pagenumbers','Pagebrowser: Pagenumbers');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/treeprevnext','Pagebrowser: Tree Prev/Next');

$_EXTCONF = unserialize($_EXTCONF);	// unserializing the configuration so we can use it here:

// check if t3skin is loaded
if (t3lib_extMgm::isLoaded('t3skin'))	{
		$icon = 'pagebrowser.gif';
	} else {
		$icon = 'pages_browser.gif';
}

t3lib_div::loadTCA('pages');

// introduce the new doctype pagebrowser
if ($_EXTCONF['opMode'] == 'doktype' || $_EXTCONF['opMode'] == 'both')	{

	if (TYPO3_MODE=='BE')	{
		
		// Inserting new doktype into doktypes array and add icon	
		if (t3lib_div::compat_version('4.4')) {
			t3lib_SpriteManager::addTcaTypeIcon('pages', '21', t3lib_extMgm::extRelPath($_EXTKEY).'pagebrowser.gif');
			$TCA['pages']['types']['21']['allowedTables'] = '*';
		} else {			
			$PAGES_TYPES = t3lib_div::array_merge(array(
				'21' => Array(
					'icon' => t3lib_extMgm::extRelPath($_EXTKEY)."$icon",
					'allowedTables' => '*'
				)
			), $PAGES_TYPES);		
		}	
		
		// if the compat version is less than 4.2 things stay as they were
		if (!t3lib_div::compat_version('4.2')) {

			// Merging with CMS doktypes:
			array_splice(
				$TCA['pages']['columns']['doktype']['config']['items'],
				7,
				0,
				Array(
					Array('LLL:EXT:cag_pagebrowser/locallang_tca.php:pages.doktype.I.21', '21'),
				)
			);

			// Merging into $TCA
			$TCA['pages']['types'] = t3lib_div::array_merge($TCA['pages']['types'],Array(
				'21' => Array('showitem' => 'hidden;;;;1-1-1, doktype;;2;button, title;;3;;2-2-2, subtitle, nav_hide, nav_title, --div--, abstract;;5;;3-3-3, keywords, description, media;;;;4-4-4, --div--, TSconfig;;6;nowrap;5-5-5, storage_pid;;7, l18n_cfg, fe_login_mode, module, content_from_pid'),
			));

		// above the pagetypes and divider2tabs behaviour was changed and needs to be taken into account
		} else {

			// Merging with CMS doktypes:
			array_splice(
				$TCA['pages']['columns']['doktype']['config']['items'],
				21,
				0,
				array(
					array('LLL:EXT:cag_pagebrowser/locallang_tca.php:pages.doktype.I.21', '21', ''.t3lib_extMgm::extRelPath($_EXTKEY).$icon.''),
				)
			);

			// Merging into $TCA
			$TCA['pages']['types'] = t3lib_div::array_merge($TCA['pages']['types'],Array(
			'21' => array('showitem' =>
					'doktype;;2;button;1-1-1, hidden, nav_hide, title;;3;;2-2-2, subtitle, nav_title,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.metadata,
					--palette--;LLL:EXT:lang/locallang_general.xml:LGL.author;5;;3-3-3, abstract, keywords, description,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.files,
					media,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,
					TSconfig;;6;nowrap;6-6-6, storage_pid;;7, l18n_cfg, module, content_from_pid,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access,
					starttime, endtime, fe_login_mode, fe_group, extendToSubpages,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,
			'),));
			
		}
	}
}

if ($_EXTCONF['opMode'] == 'plugin' || $_EXTCONF['opMode'] == 'both')	{

	if (TYPO3_MODE=='BE')	{
		
		$TCA['pages']['columns']['module']['config']['items']['21']['0'] = 'LLL:EXT:cag_pagebrowser/locallang_tca.php:pages.doktype.I.21';	
		$TCA['pages']['columns']['module']['config']['items']['21']['1'] = 'pbrowser';
		$TCA['pages']['columns']['module']['config']['items']['21']['2'] = t3lib_extMgm::extRelPath($_EXTKEY).'pagebrowser.gif';
		
		if (t3lib_div::compat_version('4.4')) {
			t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-pbrowser', t3lib_extMgm::extRelPath($_EXTKEY).'pagebrowser.gif');
		} else {
			$ICON_TYPES['pbrowser'] = array('icon' => t3lib_extMgm::extRelPath($_EXTKEY).'pagebrowser.gif');
		}
	}
}
?>