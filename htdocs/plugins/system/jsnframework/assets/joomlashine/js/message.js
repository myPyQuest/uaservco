/**
 * Declare JSN Config Javascript library.
 *
 * @author     JoomlaShine.com
 * @copyright  JoomlaShine.com
 * @link       http://joomlashine.com/
 * @package    JSN Framework
 * @subpackage JSN Message
 * @version    $Id: message.js 16254 2012-09-21 07:54:34Z binhpt $
 * @license    GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

define([
	'jquery',
	'jsn/core'
], 
function ($) {
	return function (params) {
		$(function () {
			$('#jsn-button-refresh')
				.unbind('linkBeforeRequest')
				.bind('linkBeforeRequest', function () {
					var form = $(this).closest('form'),
						url = 'index.php?option=' + params.option + '&view=configuration&s=configuration&g=msgs&msg_screen=' + form.find('#msg_screen').val();

					$(this).attr('href', url);
				});

			$('#msg_screen').change(function () {
				$('#jsn-button-refresh').trigger('click');
			});

			$('a.jsn-close-message[data-message-id]').on('click', function () {
				$.ajax({ url: 'index.php?option=' + params.option + '&view=configuration&tmpl=component&task=hideMsg&msgId=' + $(this).attr('data-message-id') });
			});
		});
	};
});