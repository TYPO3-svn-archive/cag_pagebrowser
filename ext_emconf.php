<?php

########################################################################
# Extension Manager/Repository config file for ext "cag_pagebrowser".
#
# Auto generated 05-07-2011 23:00
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Page Browser',
	'description' => 'Quickly insert paginations (prev/next menus) in sections of your website',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.2.2',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'pages',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Torsten Schrade',
	'author_email' => 'schradt@uni-mainz.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '3.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:20:{s:13:"CHANGELOG.txt";s:4:"f6f8";s:8:"TODO.txt";s:4:"d41d";s:21:"ext_conf_template.txt";s:4:"30ee";s:12:"ext_icon.gif";s:4:"aea2";s:17:"ext_localconf.php";s:4:"7d5e";s:14:"ext_tables.php";s:4:"dbd1";s:17:"locallang_tca.php";s:4:"22d1";s:15:"pagebrowser.gif";s:4:"d335";s:18:"pagebrowser__h.gif";s:4:"fbf4";s:25:"pagebrowser_notinmenu.gif";s:4:"fbf4";s:17:"pages_browser.gif";s:4:"10ca";s:20:"pages_browser__h.gif";s:4:"7928";s:14:"doc/manual.sxw";s:4:"134d";s:31:"pi1/class.tx_cagpagebrowser.php";s:4:"f432";s:20:"static/constants.txt";s:4:"872b";s:16:"static/setup.txt";s:4:"820b";s:30:"static/entrylink/constants.txt";s:4:"60d2";s:26:"static/entrylink/setup.txt";s:4:"d59b";s:32:"static/pagenumbers/constants.txt";s:4:"e53b";s:28:"static/pagenumbers/setup.txt";s:4:"7739";}',
	'suggests' => array(
	),
);

?>