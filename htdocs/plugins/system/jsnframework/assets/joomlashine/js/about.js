/**
 * Declare JSNAbout Javascript library.
 *
 * @author     JoomlaShine.com
 * @copyright  JoomlaShine.com
 * @link       http://joomlashine.com/
 * @package    JSN Framework
 * @subpackage JSNAbout
 * @version    $Id: about.js 15380 2012-08-23 02:03:20Z cuongnm $
 * @license    GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
define([
	'jquery',
	'jsn/libs/modal'
],

function ($, modal)
{
	// Declare JSNAbout contructor
	var JSNAbout = function(params)
	{
		// Object parameters
		this.params = $.extend({}, params);
		this.lang = this.params.language || {};

		this.buttons = {};
		this.buttons[this.lang['JSN_EXTFW_GENERAL_CLOSE']] = $.proxy(function() { this.modal.close(); }, this);
		
		// Set event handler
		$(document).ready($.proxy(function() {
			this.modalLink = $('#jsn-about-promotion-modal');
			this.initialize();
		}, this));
	};

	JSNAbout.prototype = {
		initialize: function () {
			// Register event to show modal window
			this.modalLink.click($.proxy(function(event) {
				event.preventDefault();
				this.modal = this.modal || new modal({
		            url: this.modalLink.attr('href'),
		            title: this.lang['JSN_EXTFW_ABOUT_SEE_OTHERS_MODAL_TITLE'],
		            width: 640,
		            height: 575,
		            buttons: this.buttons
		        });
				this.modal.show();
			}, this));
		}
	}

	return JSNAbout;
});
