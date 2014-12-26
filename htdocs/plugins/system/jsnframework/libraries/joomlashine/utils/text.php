<?php
/**
 * @version     $Id: text.php 15971 2012-09-11 10:28:31Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Utils
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
 * Helper class for text manipulation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUtilsText
{
	/**
	 * Get constant value.
	 *
	 * @param   string  $name       Raw constant name.
	 * @param   string  $component  Component folder name.
	 *
	 * @return  mixed  Constant value or null if constant is not defined.
	 */
	public static function getConstant($name, $component = '')
	{
		// Initialize component
		! empty($component) OR $component = JFactory::getApplication()->input->getCmd('option');
		$component = preg_replace('/^com_/i', '', $component);

		// Generate constant name
		$const = strtoupper("jsn_{$component}_{$name}");

		// Get constant value
		if (defined($const))
		{
			eval('$const = ' . $const . ';');
		}
		else
		{
			$const = null;
		}

		return $const;
	}

	/**
	 * Truncate text to given number of word.
	 *
	 * This method keeps HTML code structure while truncation. For example, the
	 * following text:
	 *
	 * <code>&lt;div class="message"&gt;
	 *     &lt;blockquote class="testimonial"&gt;
	 *         &lt;dl&gt;
	 *             &lt;dt&gt;John says:&lt;/dt&gt;
	 *             &lt;dd&gt;
	 * Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula...
	 *             &lt;/dd&gt;
	 *         &lt;/dl&gt;
	 *     &lt;/blockquote&gt;
	 * &lt;/div&gt;</code>
	 *
	 * is the truncated result of:
	 *
	 * <code>&lt;div class="message"&gt;
	 *     &lt;blockquote class="testimonial"&gt;
	 *         &lt;dl&gt;
	 *             &lt;dt&gt;John says:&lt;/dt&gt;
	 *             &lt;dd&gt;
	 * Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
	 *             &lt;/dd&gt;
	 *         &lt;/dl&gt;
	 *     &lt;/blockquote&gt;
	 * &lt;/div&gt;</code>
	 *
	 * @param   string   $text     Text to be truncated.
	 * @param   integer  $maxWord  Word limitation.
	 *
	 * @return  string
	 */
	public static function getWords($text, $maxWord = 25)
	{
		// Get all words
		$words = preg_split("/[\s\t\n]+/", str_replace('><', ">\n<", $text));

		if (count($words) > $maxWord)
		{
			// Preset some variables
			$max      = count($words);
			$openTag  = array();
			$text     = '';
			$counting = 0;
			$i        = 0;

			while ($counting < $maxWord AND $i < $max)
			{
				// Append word
				$text .= ($text == '' ? '' : ' ') . $words[$i];

				if (preg_match("/^.*<\/[^>]+>.*$/", $words[$i]))
				{
					// Found close tag, e.g. </b>, </i>, </strong>, </em>
					array_pop($openTag);

					// Increase words count also if the close tag is prefixed or suffixed with any word
					if (strpos($words[$i], '<') > 0 OR strpos($words[$i], '>') < (strlen($words[$i]) - 1))
					{
						$counting++;
					}
				}
				elseif (preg_match("/^.*<[^>]+>.*$/", $words[$i]))
				{
					// Found a single word open tag, e.g. <b>, <i>, <strong>, <em>
					$openTag[] = $words[$i];

					// Found self-closed tag, e.g. <br/>
					if (preg_match("/^.*<[^\/^>]+\/>.*$/", $words[$i]))
					{
						array_pop($openTag);
					}

					// Increase words count also if the open / self-closed tag is prefixed or suffixed with any word
					if (strpos($words[$i], '<') > 0 OR strpos($words[$i], '>') < (strlen($words[$i]) - 1))
					{
						$counting++;
					}
				}
				elseif (preg_match("/^.*<[^\/^>]+$/", $words[$i]))
				{
					// Found starting part of multi-words open tag, e.g. <a, <table
					$openTag[] = $words[$i];

					// Increase words count also if the open tag is prefixed with any word
					if (strpos($words[$i], '<') > 0)
					{
						$counting++;
					}

					// Get all remaining parts of the tag
					do
					{
						$i++;
						$text .= ' ' . $words[$i];
					}
					while ( ! preg_match("/^.*>.*$/", $words[$i]));

					// Increase words count if the final part of the tag is suffixed with any word
					if (strpos($words[$i], '>') < (strlen($words[$i]) - 1))
					{
						$counting++;
					}

					// Found self-closed tag or the final part of the tag also contains close tag
					if (preg_match("/^.*\/>.*$/", $words[$i]) OR preg_match("/^.*<\/[^>]+>.*$/", $words[$i]))
					{
						array_pop($openTag);
					}
				}
				else
				{
					// Not a tag, increase words count
					$counting++;
				}

				$i++;
			}

			// Finalize the truncated text
			$text .= $i < count($words) ? '...' : '';

			if (count($openTag))
			{
				// The truncated text has tag(s) that is/are not closed, close now
				for ($i = count($openTag) - 1; $i >= 0; $i--)
				{
					$text .= '</' . preg_replace("/(.*<)|(>.*)/", '', $openTag[$i]) . '>';
				}
			}
		}

		return $text;
	}
}
