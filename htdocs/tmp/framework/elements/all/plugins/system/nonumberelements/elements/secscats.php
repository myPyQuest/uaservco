<?php
// For backward compatibility

// No direct access
defined('_JEXEC') or die;

$nn_file = str_replace('/elements/', '/fields/', str_replace('\\', '/', __FILE__));

if (!file_exists($nn_file)) {
	return;
}

// Redirect to new NoNumber! Framework
require_once $nn_file;

if (version_compare(JVERSION, '1.6.0', 'l')) {
	// For Joomla 1.5
	class JElementSecsCats extends JElementNN_SecsCats
	{
	}
} else {
	// For Joomla 1.6
	class JFormFieldSecsCats extends JFormFieldNN_SecsCats
	{
	}
}