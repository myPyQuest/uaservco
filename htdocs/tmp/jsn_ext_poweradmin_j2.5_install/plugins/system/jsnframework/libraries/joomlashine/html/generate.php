<?php
/**
 * @version     $Id: generate.php 16704 2012-10-04 10:23:31Z hiepnv $
 * @package     JSN_Framework
 * @subpackage  Html
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for generating and embedding HTML markup into view.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNHtmlGenerate
{
	/**
	 * Generate HTML markup for about page.
	 *
	 * If a product has sub-product, this method need to be called as below to
	 * check all sub-product for latest version:
	 *
	 * <pre>JSNHtmlGenerate::about(
	 *     array(
	 *         // Core component
	 *         'imageshow' => '4.2.0',
	 *
	 *         // Themes
	 *         'themeclassic' => '1.1.5',
	 *         'themeslider'  => '1.0.4',
	 *         'themegrid'    => '1.0.0',
	 *
	 *         // Sources
	 *         'picasa'      => '1.1.2',
	 *         'flickr'      => '1.1.2',
	 *         'phoca'       => '1.0.1',
	 *         'joomgallery' => '1.0.1',
	 *         'rsgallery2'  => '1.0.1',
	 *         'facebook'    => '1.0.1'
	 *     )
	 * );</pre>
	 *
	 * If a product does not have sub-product, the <b>$products</b> parameter
	 * does not required when calling this method:
	 *
	 * <pre>JSNHtmlGenerate::about();</pre>
	 *
	 * @param   array  $products  Array of product identified name.
	 *
	 * @return  string
	 */
	public static function about($products = array())
	{
		// Get extension manifest cache
		$info = JSNUtilsXml::loadManifestCache();

		// Add assets
		JSNHtmlAsset::loadScript('jsn/about',
			array(
				'language' => JSNUtilsLanguage::getTranslated(array('JSN_EXTFW_ABOUT_SEE_OTHERS_MODAL_TITLE','JSN_EXTFW_GENERAL_CLOSE'))
			)
		);

		// Generate markup
		$html[] = '
<div id="jsn-about" class="jsn-page-about">
<div class="jsn-bootstrap">';
		$html[] = self::aboutInfo($info, $products);
		$html[] = '
	<div class="jsn-product-support">';
		$html[] = self::aboutHelp();
		$html[] = self::aboutFeedback();
		$html[] = '
	</div>
</div>
</div>
<div class="clr"></div>';

		echo implode($html);
	}

	/**
	 * Generate HTML info part for about page.
	 *
	 * If a product has sub-product, this method requires the second parameter
	 * in the format look like below:
	 *
	 * <pre>array(
	 *     // Core component
	 *     'imageshow' => '4.2.0',
	 *
	 *     // Themes
	 *     'themeclassic' => '1.1.5',
	 *     'themeslider'  => '1.0.4',
	 *     'themegrid'    => '1.0.0',
	 *
	 *     // Sources
	 *     'picasa'      => '1.1.2',
	 *     'flickr'      => '1.1.2',
	 *     'phoca'       => '1.0.1',
	 *     'joomgallery' => '1.0.1',
	 *     'rsgallery2'  => '1.0.1',
	 *     'facebook'    => '1.0.1'
	 * )</pre>
	 *
	 * If a product does not have sub-product, the <b>$products</b> parameter
	 * does not required when calling this method:
	 *
	 * <pre>JSNHtmlGenerate::aboutInfo($info);</pre>
	 *
	 * @param   object  $info      JSON decoded extension's manifest cache.
	 * @param   array   $products  Array of product identified name.
	 *
	 * @return  string
	 */
	public static function aboutInfo($info, $products = array())
	{	
		// Check if $info was get from xml file
		// if not, get info from constants.
		if(!isset($info->install))	{
			$name		= $info->name;
			$edition	= JSNUtilsText::getConstant('EDITION');
			$version	= $info->version;
			
			// Initialize links
			$links['info']		= JSNUtilsText::getConstant('INFO_LINK');
			$links['update']	= JSNUtilsText::getConstant('UPDATE_LINK');
			$links['upgrade']	= JSNUtilsText::getConstant('UPGRADE_LINK');
		}else{
			$name		= isset($info->name)    ? (string) $info->name    : '';
			$edition	= isset($info->edition) ? (string) $info->edition : '';
			$version	= isset($info->version) ? (string) $info->version : '';			
			// Initialize links
			$links['info']		= @isset($info->links[0]->info)    ? (string) $info->links[0]->info    : '';
			$links['doc']		= @isset($info->links[0]->doc)     ? (string) $info->links[0]->doc     : '';
			$links['review']	= @isset($info->links[0]->review)  ? (string) $info->links[0]->review  : '';
			$links['update']	= @isset($info->links[0]->update)  ? (string) $info->links[0]->update  : '';
			$links['upgrade']	= @isset($info->links[0]->upgrade) ? (string) $info->links[0]->upgrade : '';
		}
		// Initialize variables
		

		$html[] = '
		<div class="jsn-product-about">
			<div class="jsn-product-intro">
				<div class="jsn-product-thumbnail">
					<img src="' . JURI::root(true) . '/administrator/components/' . JFactory::getApplication()->input->getCmd('option') . '/assets/images/product-thumbnail.png" alt="" />
				</div>
				<div class="jsn-product-details">
					<h2 class="jsn-section-header">JSN ' . preg_replace('/JSN\s*/i', '', $name) . ' ' . $edition . '</h2>';

			if ( ! empty($edition) AND ! empty($links['upgrade']) AND ($pos = strpos('free + pro standard', strtolower($edition))) !== false)
			{
				$html[] = '<a href="' . JRoute::_($links['upgrade']) . '" class="btn" title="' . (($pos)?JText::_('JSN_EXTFW_ABOUT_UPGRADE_TO_PRO_UNLIMITED'):JText::_('JSN_EXTFW_ABOUT_UPGRADE_TO_PRO')) . '"><span class="label label-important">PRO</span>' . JText::_('JSN_EXTFW_GENERAL_UPGRADE') . '</a>';
			}

			$html[] = '
					<dl>
						<dt>' . JText::_('JSN_EXTFW_GENERAL_VERSION') . ':</dt>
						<dd>
							<strong class="jsn-current-version">' . $version . '</strong>&nbsp;-&nbsp;<span id="jsn-check-version-result">';

			try
			{
				$hasUpdate = false;

				foreach (JSNUpdateHelper::check($products) AS $result)
				{
					if ($result)
					{
						$hasUpdate = true;
						break;
					}
				}

				if ($hasUpdate)
				{
					$html[]	= '<span class="jsn-outdated-version">' . JText::_('JSN_EXTFW_GENERAL_UPDATE_AVAILABLE') . '</span>'
							. '&nbsp;<a href="' . JRoute::_($links['update']) . '" class="label label-success">' . JText::_('JSN_EXTFW_GENERAL_UPDATE_NOW') . '</a>';
				}
				else
				{
					$html[] = '<span class="jsn-latest-version"><span class="label label-success">' . JText::_('JSN_EXTFW_GENERAL_LATEST_VERSION') . '</span></span>';
				}
			}
			catch (Exception $e)
			{
				$html[] = '<span class="label label-important">' . $e->getMessage() . '</span>';
			}

			$html[] = '</span>
						</dd>
						<dt>' . JText::_('JSN_EXTFW_GENERAL_AUTHOR') . ':</dt>
						<dd>
							<a href="' . $info->authorUrl . '">' . $info->author . '</a>
						</dd>
						<dt>' . JText::_('JSN_EXTFW_GENERAL_COPYRIGHT') . ':</dt>
						<dd>' . $info->copyright . '</dd>
					</dl>
				</div>
				<div class="clearbreak"></div>
			</div>
			<div class="jsn-product-cta jsn-bgpattern pattern-sidebar">
				<div class="pull-left">
					<ul class="jsn-list-horizontal">';

			if ( ! empty($links['review']))
			{
				$html[] = '
						<li>
							<a href="' . JRoute::_($links['review']) . '" target="_blank" class="btn"><i class="icon-comment"></i>&nbsp;' . JText::_('JSN_EXTFW_ABOUT_REVIEW') . '</a>
						</li>';
			}

			$html[] = '
						<li><a id="jsn-about-promotion-modal" class="btn" href="http://www.joomlashine.com/free-joomla-templates-promo.html"><i class="icon-briefcase"></i>&nbsp;' . JText::_('JSN_EXTFW_ABOUT_SEE_OTHER') . '</a></li>
					</ul>
				</div>
				<div class="pull-right">
					<ul class="jsn-list-horizontal">
						<li>
							<a class="jsn-icon24 icon-social icon-facebook" href="http://www.facebook.com/joomlashine" title="' . JText::_('JSN_EXTFW_ABOUT_FB') . '" target="_blank"></a>
						</li>
						<li>
							<a class="jsn-icon24 icon-social icon-twitter" href="http://www.twitter.com/joomlashine" title="' . JText::_('JSN_EXTFW_ABOUT_TW') . '" target="_blank"></a>
						</li>
						<li>
							<a class="jsn-icon24 icon-social icon-youtube" href="http://www.youtube.com/joomlashine" title="' . JText::_('JSN_EXTFW_ABOUT_YT') . '" target="_blank"></a>
						</li>
					</ul>
				</div>
				<div class="clearbreak"></div>
			</div>
		</div>';
		return implode($html);
	}

	/**
	 * Generate HTML help&support part for about page.
	 *
	 * @param   array  $links  Array of necessary links.
	 *
	 * @return  string
	 */
	public static function aboutHelp($links = array())
	{
		if(!$links){
			$links['doc']		= JSNUtilsText::getConstant('DOC_LINK');
		}
		
		
		$html[] = '
		<div>
			<h3 class="jsn-section-header">' . JText::_('JSN_EXTFW_ABOUT_HELP') . '</h3>
			<p>' . JText::_('JSN_EXTFW_ABOUT_HAVE_PROBLEMS') . ':</p>
			<ul>';

		if ( ! empty($links['doc']))
		{
			$html[] = '
				<li>' . JText::sprintf('JSN_EXTFW_ABOUT_READ_DOCS', JRoute::_($links['doc'])) . '</li>';
		}

		$html[] = '
				<li>' . JText::_('JSN_EXTFW_ABOUT_ASK_FORUM') . '</li>
				<li>' . JText::_('JSN_EXTFW_ABOUT_DEDICATED_SUPPORT') . '</li>
			</ul>
			<p>' . JText::_('JSN_EXTFW_ABOUT_ONLY_AVAILABLE') . '</p>
		</div>';

		return implode($html);
	}

	/**
	 * Generate HTML feedback part for about page.
	 *
	 * @param   array  $links  Array of necessary links.
	 *
	 * @return  string
	 */
	public static function aboutFeedback($links = array())
	{
		if(!$links){
			$links['review']	= JSNUtilsText::getConstant('REVIEW_LINK');
		}
				
		$html[] = '
		<div>
			<h3 class="jsn-section-header">' . JText::_('JSN_EXTFW_ABOUT_FEEDBACK') . '</h3>
			<p>' . JText::_('JSN_EXTFW_ABOUT_LIKE_TO_HEAR') . ':</p>
			<ul>
				<li>' . JText::_('JSN_EXTFW_ABOUT_REPORT_BUG') . '</li>
				<li>' . JText::_('JSN_EXTFW_ABOUT_GIVE_TESTIMONIAL') . '</li>';

		if ( ! empty($links['review']))
		{
			$html[] = '
				<li>' . JText::sprintf('JSN_EXTFW_ABOUT_REVIEW_ON_JED', JRoute::_($links['review'])) . '</li>';
		}

		$html[] = '
			</ul>
		</div>';

		return implode($html);
	}

	/**
	 * Generate HTML markup for footer.
	 *
	 * If a product has sub-product, this method need to be called as below to
	 * check all sub-product for latest version:
	 *
	 * <pre>JSNHtmlGenerate::footer(
	 *     array(
	 *         // Core component
	 *         'imageshow' => '4.2.0',
	 *
	 *         // Themes
	 *         'themeclassic' => '1.1.5',
	 *         'themeslider'  => '1.0.4',
	 *         'themegrid'    => '1.0.0',
	 *
	 *         // Sources
	 *         'picasa'      => '1.1.2',
	 *         'flickr'      => '1.1.2',
	 *         'phoca'       => '1.0.1',
	 *         'joomgallery' => '1.0.1',
	 *         'rsgallery2'  => '1.0.1',
	 *         'facebook'    => '1.0.1'
	 *     )
	 * );</pre>
	 *
	 * If a product does not have sub-product, the <b>$products</b> parameter
	 * does not required when calling this method:
	 *
	 * <pre>JSNHtmlGenerate::footer();</pre>
	 *
	 * @param   array  $products  Array of product identified name.
	 *
	 * @return  string
	 */
	public static function footer($products = array())
	{
		// Get extension manifest cache
		$info = JSNUtilsXml::loadManifestCache();

		// Initialize variables
		$name		= $info->name;
		$edition	= JSNUtilsText::getConstant('EDITION');
		$version	= $info->version;

		// Initialize links
		$links['info']		= JSNUtilsText::getConstant('INFO_LINK');
		$links['doc']		= JSNUtilsText::getConstant('DOC_LINK');
		$links['review']	= JSNUtilsText::getConstant('REVIEW_LINK');
		$links['update']	= JSNUtilsText::getConstant('UPDATE_LINK');
		$links['upgrade']	= JSNUtilsText::getConstant('UPGRADE_LINK');

		// Generate markup
		$html[] = '
<div id="jsn-footer" class="jsn-page-footer jsn-bootstrap">
<ul class="jsn-footer-menu">
	<li class="first">';

		if ( ! empty($links['doc']))
		{
			$html[] = '
		<a href="' . JRoute::_($links['doc']) . '" target="_blank">' . JText::_('JSN_EXTFW_GENERAL_DOCUMENTATION') . '</a>
	</li>
	<li>';
		}

		$html[] = '
		<a href="http://www.joomlashine.com/contact-us/get-support.html" target="_blank">' . JText::_('JSN_EXTFW_GENERAL_SUPPORT') . '</a>
	</li>';

		if ( ! empty($links['review']))
		{
			$html[] = '
	<li>
		<a href="' . JRoute::_($links['review']) . '" target="_blank">' . JText::_('JSN_EXTFW_GENERAL_VOTE') . '</a>
	</li>';
		}

		$html[] = '
	<li class="jsn-iconbar">
		<strong>' . JText::_('JSN_EXTFW_GENERAL_KEEP_IN_TOUCH') . ':</strong>
		<a title="' . JText::_('JSN_EXTFW_GENERAL_FACEBOOK') . '" class="jsn-icon16 icon-social icon-facebook" target="_blank" href="http://www.facebook.com/joomlashine"></a><a title="' . JText::_('JSN_EXTFW_GENERAL_TWITTER') . '" class="jsn-icon16 icon-social icon-twitter" target="_blank" href="http://www.twitter.com/joomlashine"></a><a title="' . JText::_('JSN_EXTFW_GENERAL_YOUTUBE') . '" class="jsn-icon16 icon-social icon-youtube" target="_blank" href="http://www.youtube.com/joomlashine"></a>
	</li>
</ul>
<ul class="jsn-footer-menu">
	<li class="first">';

		if ( ! empty($links['info']))
		{
			$html[] = '
		<a href="' . JRoute::_($links['info']) . '" target="_blank">JSN ' . preg_replace('/JSN\s*/i', '', $name) . ' ' . $edition . ' v' . $version . '</a>';
		}
		else
		{
			$html[] = 'JSN ' . preg_replace('/JSN\s*/i', '', $name) . ' ' . $edition . ' v' . $version;
		}

		$html[] = ' by <a href="http://www.joomlashine.com" target="_blank">JoomlaShine.com</a>';

		if ( ! empty($edition) AND ! empty($links['upgrade']) AND ($pos = strpos('free + pro standard', strtolower($edition))) !== false)
		{
			$html[] = '
		&nbsp;<a class="label label-important" href="' . JRoute::_($links['upgrade']) . '"><strong class="jsn-text-attention">' . JText::_($pos ? 'JSN_EXTFW_GENERAL_UPGRADE_TO_PRO_UNLIMITED' : 'JSN_EXTFW_GENERAL_UPGRADE_TO_PRO') . '</strong></a>';
		}

		$html[] = '
	</li>';

		try
		{
			$hasUpdate = false;

			foreach (JSNUpdateHelper::check($products) AS $result)
			{
				if ($result)
				{
					$hasUpdate = true;
					break;
				}
			}

			if ($hasUpdate)
			{
				$html[] = '
	<li id="jsn-global-check-version-result" class="jsn-outdated-version">
		<span class="jsn-global-outdated-version">' . JText::_('JSN_EXTFW_GENERAL_UPDATE_AVAILABLE') . '</span>
		&nbsp;<a href="' . JRoute::_($links['update']) . '" class="label label-success">' . JText::_('JSN_EXTFW_GENERAL_UPDATE_NOW') . '</a>
	</li>';
			}
		}
		catch (Exception $e)
		{
			// Simply ignore
		}

		$html[] = '
</ul>
</div>
';

		echo implode($html);
	}

	/**
	 * Generate HTML markup for menu tool bar.
	 *
	 * @param   array  $options  Options menu tool bar
	 *
	 * @return  html code
	 */
	public static function menuToolbar($options = array())
	{

		$html		   = '';
		$menuButtonText = JText::_('JSN_EXTFW_GENERAL_MENU');
		$itemHtml	   = '';
		$document	   = JFactory::getDocument();
		$document->addScript(JSN_URL_ASSETS . '/joomlashine/js/menutoolbar.js');
		$document->addScriptDeclaration("
			window.addEvent('domready', function()
			{
				JSNMenuToolBar.jsnMenuEffect();
			})
		");

		if (is_array($options) && count($options) > 0)
		{
			foreach ($options as $index => $item)
			{

				$class	   = isset($item['class']) ? $item['class'] : "";
				$class	   = ($index == 0) ? $class . " first" : $class;
				$class	   = ($index == count($options)) ? $class . " first" : $class;
				$icon		= isset($item['icon']) ? "<span class=\"jsn-icon24 {$item['icon']}\"></span>" : "";
				$title	   = isset($item['title']) ? $item['title'] : "";
				$menuLink	= empty($item['link']) ? $title : "<a href=\"{$item['link']}\">{$icon}{$title}</a>";
				$itemSublink = "";
				$subMenu	 = "";

				if (isset($item['data_sub_menu']))
				{

					$subMenuFieldTitle = isset($item['sub_menu_field_title']) ? $item['sub_menu_field_title'] : "";
					if (is_array($item['data_sub_menu']))
					{
						foreach ($item['data_sub_menu'] as $dataSubMenu)
						{
							if (empty($item['sub_menu_link']))
							{
								$itemSublink .= "<li>{$subMenuFieldTitle}</li>";
							}
							else
							{
								$subLink		 = preg_replace('/\{\$([^\}]+)\}/ie', '@$dataSubMenu->\\1', $item['sub_menu_link']);
								$itemSublink .= "<li><a href=\"{$subLink}\">{$dataSubMenu->$subMenuFieldTitle}</a></li>";
							}
						}
					}
					$subMenu		 = empty($itemSublink) ? "" : $itemSublink . '<li class=\"separator\"></li>';
					$subLinkAddTitle = isset($item['sub_menu_link_add_title']) ? $item['sub_menu_link_add_title'] : "";
					$subLinkAdd	  = empty($item['sub_menu_link_add']) ? $subLinkAddTitle : "<a href=\"{$item['sub_menu_link_add']}\" title=\"{$subLinkAddTitle}\"><span class=\"jsn-icon16 icon-plus\"></span>{$subLinkAddTitle}</a>";
					$subMenu		 = "<ul class=\"jsn-list-items\">{$subMenu}
							 <li class=\"primary\">{$subLinkAdd}</li>
							 </ul>";
				}
				$itemHtml .= "<li class=\"{$class}\">{$menuLink}{$subMenu}</li>";
			}
		}
		$html = "<ul id=\"jsn-menu\" class=\"clearafter\">
					  <li class=\"menu-name\"><a><span class=\"jsn-icon32 icon-menu\"></span>{$menuButtonText}</a>
							<ul class=\"jsn-submenu\">
								 {$itemHtml}
							</ul>
					  </li>
			  </ul>";

		return $html;
	}
}
