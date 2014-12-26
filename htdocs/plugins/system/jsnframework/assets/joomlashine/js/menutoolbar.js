var JSNMenuToolBar = {
	jsnMenuEffect: function()
	{
		var jsnMenu = $$('#jsn-menu li.menu-name')[0];
		var subMenu = $$('.jsn-submenu')[0];
		
		function hideSubMenu()
		{
			subMenu.style.left = 'auto';
			subMenu.style.right = '0';
			
			setTimeout(function(){
				subMenu.style.left = '';
				subMenu.style.right = '';
			}, 500);
		}
		
		jsnMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
		
		subMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
	}
}