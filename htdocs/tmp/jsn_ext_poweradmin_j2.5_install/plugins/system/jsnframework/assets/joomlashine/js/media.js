/**
 * Declare JSNMedia Javascript library.
 *
 * @author     JoomlaShine.com
 * @copyright  JoomlaShine.com
 * @link       http://joomlashine.com/
 * @package    JSN_Framework
 * @subpackage Config
 * @version    $Id: media.js 15511 2012-08-27 03:01:49Z cuongnm $
 * @license    GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

define([
	'jquery',
	'jsn/libs/modal'
],

function ($, modal)
{
	// Declare JSNMedia contructor
	var JSNMedia = function(params)
	{
		// Object parameters
		this.params = $.extend({}, params);
		this.lang = this.params.language || {};

		this.buttons = {};
		this.buttons[this.lang['JSN_EXTFW_GENERAL_CLOSE']] = $.proxy(function() { this.modal.close(); }, this);
		
		// Set event handler
		$(document).ready($.proxy(function() {
			this.modalLink = $(this.params.field).next();
			this.initialize();
		}, this));
	};

	JSNMedia.prototype = {
		initialize: function() {
			// Register event to show modal window
			this.modalLink.click($.proxy(function(event) {
				event.preventDefault();
				this.modal = new modal({
		            title: this.lang['JSN_EXTFW_CONFIG_CLICK_TO_SELECT'],
		            url: this.params.url.replace(/current=[^&]*/, 'current=' + $(this.params.field).attr('value')),
		            width: 640,
		            height: 575,
		            buttons: this.buttons,
		            loaded: function(modal) {
		            	modal.options.loaded = null;
		            	modal.iframe[0].contentWindow.location.reload();
		            }
		        });
				this.modal.show();
			}, this));
			
			// Setup clear button
			if (this.modalLink.next('button')) {
				this.modalLink.next('button').click($.proxy(function() {
					this.update('');
				}, this));
			}

			// Create selection update function
			window.JSNMediaUpdateField = $.proxy(this.update, this);
		},
		
		update: function(selected, field) {
			field = field || this.params.field;
			$(field).attr('value', selected);
			$(field).trigger('change');
			this.modal && this.modal.close();
		}
	}

	return JSNMedia;
});
