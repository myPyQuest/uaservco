/**
 * Declare JSNConfig Javascript library.
 *
 * @author     JoomlaShine.com
 * @copyright  JoomlaShine.com
 * @link       http://joomlashine.com/
 * @package    JSN Framework
 * @subpackage JSNConfig
 * @version    $Id: config.js 15101 2012-08-15 07:34:12Z binhpt $
 * @license    GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
define([
	'jquery'
],

function ($)
{
	// Declare JSNConfig contructor
	var JSNConfig = function(params)
	{
		// Object parameters
		this.params = params;
		this.menuLinks = $('#jsn-config-menu a');
		this.initialize();
	};

	JSNConfig.prototype = {
		initialize: function () {
			this.menuLinks.unbind('linkBeforeRequest').bind('linkBeforeRequest', function (event) {
				$('i', this).addClass('icon-loading');
			});

			this.menuLinks.unbind('linkRequested').bind('linkRequested', function (event, response) {
				var activeMenu = $('#jsn-config-menu li.active'),
					currentMenu = $(this).closest('li'),
					currentMenuIcon = $('i', this);

				activeMenu.removeClass('active');
				currentMenu.addClass('active');
				currentMenuIcon.removeClass('icon-loading');

				$('#jsn-config-form > div').html(response.content);

				if (this.id == 'linklangs') {
					$('form').bind('formSubmitted', function () {
						$('input:checked', this).attr('disabled', 'disabled');
					});
				}
			});
		}
	}

	return JSNConfig;
});
