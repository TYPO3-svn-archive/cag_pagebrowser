<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$_EXTCONF = unserialize($_EXTCONF);	// unserializing the configuration so we can use it here:

if ($_EXTCONF['opMode'] == 'doktype')	{

	// include doktype field in rootline array so that we can get it later with levelfield
	$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'].=',doktype';

	t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
		plugin.tx_cagpagebrowser.stdWrap.if.equals.cObject.browserFunction = doktype
		plugin.tx_cagpagebrowser.entryLink.if.equals.cObject.browserFunction = doktype
	');
}

if ($_EXTCONF['opMode'] == 'plugin')	{

	// include pagebrowser field in rootline array so that we can get it later with levelfield
	$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'].=',module';

	t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
		plugin.tx_cagpagebrowser.stdWrap.if.equals.cObject.browserFunction = plugin
		plugin.tx_cagpagebrowser.entryLink.if.equals.cObject.browserFunction = plugin
	');
}

if ($_EXTCONF['opMode'] == 'both')	{

	// include pagebrowser field in rootline array so that we can get it later with levelfield
	$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'].=',doktype,module';

	t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
		plugin.tx_cagpagebrowser.stdWrap.if.equals.cObject.browserFunction = both
		plugin.tx_cagpagebrowser.entryLink.if.equals.cObject.browserFunction = both
	');
}
?>