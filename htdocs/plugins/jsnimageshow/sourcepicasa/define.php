<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Image Source Flickr
 * @version $Id: define.php 14818 2012-08-07 11:27:26Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$jsnImageSourcePicasa = array(
	'name' => 'Picasa',
	'identified_name' => 'picasa',
	'type' => 'external',
	'description' => 'Picasa Description',
	'thumb' => 'plugins/jsnimageshow/sourcepicasa/assets/images/thumb-picasa.png',
	'sync'	=> true,
	'pagination' => true
);

define('JSN_IS_SOURCEPICASA', json_encode($jsnImageSourcePicasa));
