<?php
/**
 * @version     $Id: default_menu.php 14899 2012-08-09 10:22:29Z binhpt $
 * @package     JSN_Framework
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Get input object
$input = JFactory::getApplication()->input;
?>
<div id="jsn-config-menu" class="jsn-page-nav">
<?php
foreach ($config AS $sk => $section) :
?>
	<ul class="nav nav-list">
		<li class="nav-header"><span><?php echo JText::_($section->label); ?></span></li>
<?php
	foreach ($section->groups AS $gk => $group) :
		$link = JRoute::_('index.php?option=' . $input->getCmd('option') . '&view=' . $input->getCmd('view') . '&s=' . $sk . '&g=' . $gk);
?>
		<li<?php echo ($rSection == $sk && $rGroup == $gk) ? ' class="active"' : ''; ?>>
			<a id="link<?php echo $group->name; ?>" href="<?php echo $link; ?>" class="jsn-config-menu-link"<?php echo $group->ajax ? ' ajax-request="yes"' : ''; ?>><i class="jsn-icon32 icon-<?php echo $group->icon; ?>"></i><?php echo JText::_($group->label); ?></a>
		</li>
<?php
	endforeach;
?>
	</ul>
<?php
endforeach;
?>
</div>
