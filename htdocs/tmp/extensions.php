<?php
/**
 * Extension Install File
 * Does the stuff for the specific extensions
 *
 * @package			Sourcerer
 * @version			2.11.4
 *
 * @author			Peter van Westen <peter@nonumber.nl>
 * @link			http://www.nonumber.nl
 * @copyright		Copyright © 2011 NoNumber! All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die;

$name = 'Sourcerer';
$alias = 'sourcerer';
$ext = $name.' (system plugin & editor button plugin)';

// SYSTEM PLUGIN
$states[] = installExtension( $alias, 'System - '.$name, 'plugin', array( 'folder'=> 'system' ) );

// EDITOR BUTTON PLUGIN
$states[] = installExtension( $alias, 'Editor Button - '.$name, 'plugin', array( 'folder'=> 'editors-xtd' ) );

// Stuff to do after installation / update
function afterInstall_j1( &$db )
{
	// FIX STUFF FROM OLDER VERSIONS
	updateOldParams( $db );

	$queries = array();

	// Rename old plugin name
	$queries[] = "UPDATE `#__plugins`
		SET `name` = 'System - Sourcerer'
		WHERE `name` = 'System - Sourcerer!'";

	// Rename old plugin name
	$queries[] = "UPDATE `#__plugins`
		SET `name` = 'Editor Button - Sourcerer'
		WHERE `name` = 'Editor Button - Sourcerer!'";

	foreach ( $queries as $query ) {
		$db->setQuery( $query );
		$db->query();
	}
}

function updateOldParams( &$db )
{
	// REMOVE MODULE
	// Rename old module name
	$query = "UPDATE `#__modules`
		SET `title` = 'Sourcerer Module'
		WHERE `title` = 'Sourcerer! Module'";
	$db->setQuery( $query );
	$db->query();
	// Change old Sourcerer modules to normal custom HTML modules (because Sourcerer modules won't work anymore!)
	$query = "UPDATE `#__modules`
		SET `module` = 'mod_custom',
		`content` = replace( replace( `params`, 'text=', '' ), '".'\\\\n'."', '".'\\n'."' ),
		`params` = ''
		WHERE `module` = 'mod_sourcerer'";
	$db->setQuery( $query );
	$db->query();

	// Make sure we delete the folders
	if ( is_dir( JPATH_SITE.'/modules/mod_sourcerer' ) ) {
		JFolder::delete( JPATH_SITE.'/modules/mod_sourcerer' );
	}
	// Delete module language files
	$file_orginal_lang_path = JPATH_SITE.'/language';
	$dir_folders = JFolder::folders( $file_orginal_lang_path );
	foreach ( $dir_folders as $lang_name ) {
		$file_lang_file = $file_orginal_lang_path.'/'.$lang_name.'/'.$lang_name.'.mod_sourcerer.ini';
		if ( is_file( $file_lang_file ) ) {
			JFile::delete( $file_lang_file );
		}
	}
}
