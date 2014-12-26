/**
 * @subpackage	com_poweradmin (JSN POERADMIN JoomlaShine - http://www.joomlashine.com)
 * @copyright	Copyright (C) 2001 BraveBits,Ltd. All rights reserved.
 **/
(function ($) {
	$(function () {
		var root		= $('.template-item');
		var contextMenu = root.jsnSubmenu({ rebuild:true, rightClick:false });
		var activeItem  = null;
		
		if (contextMenu.isNew()) 
		{
			contextMenu.addItem('Edit').addEventHandler('click', function () {
				var templateId = $(activeItem).attr('sid');
				var editLink   = 'index.php?option=com_templates&task=style.edit&id=' + templateId;
				
				window.open(editLink);
				contextMenu.hide({});
			}).css('font-weight', 'bold');
			
			contextMenu.addItem('Make Default').addEventHandler('click', function () {
				var templateId = $(activeItem).attr('sid');
				var clientId   = $(activeItem).attr('clientId');
				var makeDefaultLink = 'index.php?option=com_poweradmin&task=templates.makeDefault&id=' + templateId + '&clientId=' + clientId + '&tmpl=component';
				
				$.getJSON(makeDefaultLink, function (response) {
					root.removeClass('default');
					activeItem.addClass('default');
				});
				
				contextMenu.hide({});
			});
			
			contextMenu.addItem('Duplicate').addEventHandler('click', function () {
				var templateId = $(activeItem).attr('sid');
				var actionLink = 'index.php?option=com_poweradmin&task=templates.duplicate&id=' + templateId + '&tmpl=component';
				
				$.getJSON(actionLink, function (response) {
					_appendNewStyle(response);
				});
				
				contextMenu.hide({});
			});
			
			contextMenu.addItem('Delete').addEventHandler('click', function () {
				var templateName = $('.template-item-thumb', activeItem).text().trim();
				var answer = confirm ('Are you sure you want to delete template style "'+templateName+'"?');
				if (!answer){
					return;
				}
				$.getJSON('index.php?option=com_poweradmin&task=templates.delete&id=' + $(activeItem).attr('sid'), function (response) {
					if (response.isDeleted == true)
						$(activeItem).remove();
				});
				contextMenu.hide({});
			});
			
			contextMenu.addDivider();
			contextMenu.addItem('Uninstall Template').addEventHandler('click', function () {
				var templateName = $('.template-item-thumb', activeItem).text().trim();
				var answer = confirm ('Are you sure you want to uninstall template "'+templateName+'"?');
				if (!answer) {
					return;
				}

				var templateId = $(activeItem).attr('sid');
				var actionLink = 'index.php?option=com_poweradmin&task=templates.uninstall&id=' + templateId + '&tmpl=component';
				
				$.getJSON(actionLink, function (response) {
					$('div[tid=' + $(activeItem).attr('tid') + ']').remove();
				});
				
				contextMenu.hide({});
			});
			
			// Disable browser context menu
			root.bind('contextmenu', function () {
				return false;
			});
		}
		
		$(document).ajaxStart(function () {
			$(activeItem).find('span').addClass('loading');
		});
		
		$(document).ajaxComplete(function () {
			$(activeItem).find('span').removeClass('loading');
		});
		
		// Handle mouse down event to display context menu
		$(document).click(function (e) {
			var parent = $(e.target);
			var depth  = 0;
			
			while(depth < 3) {
				if (parent.is('div') && (parent.hasClass('template-item') || parent.hasClass('jsnpw-submenu')))
					break;
				
				parent = parent.parent();
				depth++;
			}
			
			if (parent.hasClass('template-item') && e.which == 1) {
				var isDefault 	= parent.hasClass('default');
				var isOne		= root.size() == 1;
				var isLastStyle = $('div[tid=' + parent.attr('tid') + ']').size() == 1;
								
				_setMenuEnabled(contextMenu.getItem('Make Default'), !isDefault, 'The style is already set as default');
				_setMenuEnabled(contextMenu.getItem('Delete'), (!isDefault && !isOne && !isLastStyle), 'Cannot delete default style or last style of a template');
				_setMenuEnabled(contextMenu.getItem('Uninstall Template'), (!isDefault && !isOne), 'Cannot uninstall template with style set as default');
				
				activeItem = parent;
				contextMenu.show({
					x : e.pageX+5, 
					y : e.pageY+10
				});
			}
			else if (!parent.hasClass('jsnpw-submenu')) {
				contextMenu.hide({});
			}
		});

		$('a.template-item-thumb').click(function (e) {
			e.preventDefault();
		});
		
		$('#manage-styles').accordion({
			header: "h3",
			icons: false,
			autoHeight: false
		});
		
		/**
		 * Function to change state of menu item to enable or disable
		 * @param menuItem
		 * @param state
		 * @param disableHint
		 */
		function _setMenuEnabled(menuItem, state, disableHint) {
			if (state == true) {
				menuItem
					.removeClass('disable')
					.enableEventHandler("click")
					.removeAttr('title');
			}
			else {
				menuItem
					.addClass('disable')
					.disableEventHandler('click')
					.attr('title', disableHint);
			}
		}
		
		/**
		 * Append duplicated template style to style list
		 * @param templateStyle
		 */
		function _appendNewStyle(templateStyle) {
			$(activeItem).after(' ');
			$('<div/>', { id: 'jTemplate-' + templateStyle.tid, 'class': 'template-item', tid: templateStyle.tid, sid: templateStyle.id })
				.append(
					$('<a/>', { 'class': 'template-item-thumb', 'href': 'javascript:void(0);' })
						.append($('<img/>', { 'class': 'template-thumbnail', 'align': 'middle', 'alt': templateStyle.title, 'src': templateStyle.thumbnail }))
						.append($('<span/>', { text: templateStyle.title }))
				)
				.insertAfter($(activeItem))
				.effect('highlight');
				
			$('.template-item').after(" ");
			
			// Disable browser context menu
			$('.template-item').bind('contextmenu', function () {
				return false;
			});
		}
	});
	
})(JoomlaShine.jQuery);
