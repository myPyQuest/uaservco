<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

class TableThemeClassicParameter extends JTable
{
	var $id 								= null;
	var $general_swf_library				= 0;
	var $root_url							= 1;

	function __construct(&$db)
	{
		parent::__construct('#__imageshow_theme_classic_parameters', 'id', $db);
	}
}
?>