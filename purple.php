<?php

/**
 * Purple Skin
 *
 * @file
 * @ingroup Skins
 * @author James Hare
 * @license BSD-2-Clause
 */

if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'purple' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['purple'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for the Purple skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the Purple skin requires MediaWiki 1.25+' );
}
