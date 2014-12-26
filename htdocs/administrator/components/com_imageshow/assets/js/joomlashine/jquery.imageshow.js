(function($){ 
	$.JQJSNISImageShow = function(options){
		
		this.options  = $.extend({}, options);
		/**
		 * Make a Simple Slide, used at 2 pages: Showlist settings, Showcase settings
		 */
		this.simpleSlide = function (clickID, slideID, childTagName ,duration, cookie, cookieName){
			var self = this;
			//Set default value
			$("#" + slideID).addClass('expand');
			$("#" + clickID).click(function () {
			    $("#" + slideID).slideToggle(duration);
				if ($("#" + slideID).hasClass('expand'))
				{
					$("#" + slideID).removeClass('expand').addClass('collapse');
					$(childTagName, this).removeClass('icon-chevron-up').addClass('icon-chevron-down');
					self.cookie.set(cookieName, 'collapse');
				} 
				else
				{
					$("#" + slideID).removeClass('collapse').addClass('expand');
					$(childTagName, this).removeClass('icon-chevron-down').addClass('icon-chevron-up');
					self.cookie.set(cookieName, 'expand');
				} 
			});
		};
		
		/**
		 *  Get/Set Cookie
		 */
		this.cookie = {
			set : function(name, value){
				$.cookie(name, value);
			},
			get : function(name, type){
				switch(type){
					case 'int':
						return parseInt($.cookie(name));
					case 'float': 
						return parseFloat($.cookie(name));
					default:
						return $.cookie(name);
				}
			},
			exists : function(name){
				return $.cookie(name) == null ? false : true;
			}
		};				
	}
})(jQuery);