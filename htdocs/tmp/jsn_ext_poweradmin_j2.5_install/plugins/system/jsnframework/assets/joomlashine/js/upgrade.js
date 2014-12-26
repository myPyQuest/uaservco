/**
 * Declare JSN Upgrade Javascript library.
 *
 * @author     JoomlaShine.com
 * @copyright  JoomlaShine.com
 * @link       http://joomlashine.com/
 * @package    JSN Framework
 * @subpackage JSN Upgrade
 * @version    $Id: upgrade.js 16228 2012-09-21 02:41:34Z cuongnm $
 * @license    GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

define([
	'jquery'
],

function ($)
{
	// Declare JSN Upgrade contructor
	JSNUpgrade = function(params) {
		// Object parameters
		this.params = $.extend({}, params);
		this.lang = this.params.language || {};

		// Get update button object
		this.button = document.getElementById(params.button);
		
		// Set event handler to update product
		$(this.button).click($.proxy(function(event) {
			event.preventDefault();
			this.install();
		}, this));
	};
	
	// Declare JSN Upgrade methods
	JSNUpgrade.prototype = {
		install: function() {
			// Mark installation step
			this.step = 1;
			
			// Hide form action
			$('#jsn-upgrade-action').hide();
			
			// Execute current installation step
			this.execute();
		},
	
		execute: function() {
			// Call appropriate method
			this['step' + this.step]();
		},
	
		step1: function() {
			// Show login form
			$('#jsn-upgrade-login').show();
			
			// Setup login form
			$(document.JSNUpgradeLogin).delegate('input[type="text"], input[type="password"]', 'keyup', $.proxy(function() {
				var canLogin = true;
				$('input[type="text"], input[type="password"]', document.JSNUpgradeLogin).each(function() {
					this.value != '' || (canLogin = false);
				});
				canLogin ? $('button', document.JSNUpgradeLogin).removeProp('disabled') : $('button', document.JSNUpgradeLogin).attr('disabled', 'disabled');
			}, this));
	
			$('button', document.JSNUpgradeLogin).click($.proxy(function(event) {
				event.preventDefault();
	
				// Execute next upgrade step
				this.step++;
				this.execute();
			}, this));
		},
		
		step2: function() {
			// Update indicators
			$('#jsn-upgrade-cancel').hide();
			$('#jsn-upgrade-login').hide();
			$('#jsn-upgrade-indicator').show();
			$('#jsn-upgrade-downloading-unsuccessful-message').hide();
			
			// Request server-side to download update package
			$.ajax({
				url: this.button.href,
				type: document.JSNUpgradeLogin.method,
				data: $(document.JSNUpgradeLogin).serialize() + '&tmpl=component&ajax=1',
				context: this
			}).done(function(data) {
				this.clearTimer('#jsn-upgrade-downloading-indicator');
	
				if (data.substr(0, 4) == 'DONE') {
					// Update indicators
					$('#jsn-upgrade-downloading-indicator').removeClass('icon-loading').addClass('icon-ok');
	
					// Update download link to install link
					this.button.href = this.button.href.replace('.download', '.install');
					this.button.data = 'path=' + data.replace(/^DONE:(\s+)?/, '');
	
					// Execute next installation step
					this.step++;
					this.execute();
				} else {
					// Update indicators
					$('#jsn-upgrade-downloading-indicator').removeClass('icon-loading').addClass('icon-remove');
					$('#jsn-upgrade-downloading-unsuccessful-message').html(data.replace(/^FAIL:(\s+)?/, '')).show();
				}
			});
	
			this.setTimer('#jsn-upgrade-downloading-indicator');
		},
	
		step3: function() {
			// Update indicators
			$('#jsn-upgrade-installing').show();
			$('#jsn-upgrade-installing-unsuccessful-message').hide();
			$('#jsn-upgrade-installing-warnings').hide();
			
			// Request server-side to install dowmloaded package
			this.modal = new modal({
	            url: this.button.href + (this.button.href.indexOf('?') ? '&' : '?') + this.button.data + '&tmpl=component&ajax=1',
	            loaded: $.proxy(function() {
	            	var data = this.modal.iframe[0].contentDocument.doctype == null ? this.modal.iframe[0].contentDocument.body.innerHTML : 'DONE';
	            	this.modal.container.parent().css('visibility', 'hidden');
					this.clearTimer('#jsn-upgrade-installing-indicator');
					
					if (data.substr(0, 4) == 'DONE') {
						// Update indicators
						$('#jsn-upgrade-installing-indicator').removeClass('icon-loading').addClass('icon-ok');
		
						// State that installation is completed successfully
						$('#jsn-upgrade-successfully').show();
					} else {
						// Update indicators
						$('#jsn-upgrade-installing-indicator').removeClass('icon-loading').addClass('icon-remove');
						
						// Displaying any error/warning message
						if (data.substr(0, 4) == 'FAIL') {
							$('#jsn-upgrade-installing-unsuccessful-message').html(data.replace(/^FAIL:(\s+)?/, '')).show();
						} else {
							$('#jsn-upgrade-installing-warnings').append(data).show();
						}
					}
	            }, this)
	        });
			this.modal.iframe.attr('src', this.modal.options.url);
	
			this.setTimer('#jsn-upgrade-installing-indicator');
		},
	
		setTimer: function(element) {
			// Schedule still loading notice
			this.timer = setInterval($.proxy(function() {
				if ($(element).hasClass('icon-loading')) {
					var msg = $(element).next('.jsn-processing-message').html();
					if (msg == this.lang['JSN_EXTFW_GENERAL_STILL_WORKING']) {
						$(element).next('.jsn-processing-message').html(this.lang['JSN_EXTFW_GENERAL_PLEASE_WAIT']);
					} else {
						$(element).next('.jsn-processing-message').html(this.lang['JSN_EXTFW_GENERAL_STILL_WORKING']);
					}
				}
			}, this), 3000);
		},
	
		clearTimer: function(element) {
			clearInterval(this.timer);
			$(element).next('.jsn-processing-message').hide();
		}
	};

	return JSNUpgrade;
});
