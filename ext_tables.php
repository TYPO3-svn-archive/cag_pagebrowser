<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

	// Adding TS objects
t3lib_extMgm::addStaticFile($_EXTKEY,'static/v1','Pagebrowser (v.1): Basics');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/v1/pagenumbers','Pagebrowser (v.1): Pagenumbers');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/v2','Pagebrowser (v.2): Basics');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/v2/treeprevnext','Pagebrowser (v.2): Tree Prev/Next');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/common/entrylink','Pagebrowser (v.1/2): Entry links');

	// unserializing the configuration
$_EXTCONF = unserialize($_EXTCONF);	

	// prepare pagebrowser icon
$icon = t3lib_extMgm::extRelPath($_EXTKEY) . 'res/pagebrowser.gif';

	// load TCA for pages
t3lib_div::loadTCA('pages');

	// introduce the new doctype pagebrowser
if ($_EXTCONF['opMode'] == 'doktype' || $_EXTCONF['opMode'] == 'both')	{
	if (TYPO3_MODE=='BE')	{

			// Inserting new doktype into doktypes array and add icon
		t3lib_SpriteManager::addTcaTypeIcon('pages', '21', $icon);
		$TCA['pages']['types']['21']['allowedTables'] = '*';

			// Merging with CMS doktypes:
		array_splice(
			$TCA['pages']['columns']['doktype']['config']['items'],
			21,
			0,
			array(
				array('LLL:EXT:cag_pagebrowser/lang/locallang_db.xml:pages.doktype.I.21', '21', $icon),
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

if ($_EXTCONF['opMode'] == 'plugin' || $_EXTCONF['opMode'] == 'both')	{
	if (TYPO3_MODE=='BE')	{
		$TCA['pages']['columns']['module']['config']['items']['21']['0'] = 'LLL:EXT:cag_pagebrowser/lang/locallang_db.xml:pages.doktype.I.21';
		$TCA['pages']['columns']['module']['config']['items']['21']['1'] = 'pbrowser';
		$TCA['pages']['columns']['module']['config']['items']['21']['2'] = $icon;
		t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-pbrowser', $icon);
	}
}
?>