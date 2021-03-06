/**
 * @preserve Galleria Classic Theme 2011-08-01
 * http://galleria.aino.se
 *
 * Copyright (c) 2011, Aino
 * Licensed under the MIT license.
 */

/*global jQuery, Galleria */

Galleria.requires(1.25, 'This version of Classic theme requires Galleria 1.2.5 or later');

(function($) {

Galleria.addTheme({
    name: 'classic',
    author: 'Galleria',
    css: 'galleria.classic.css',
    defaults: {
        transition: 'slide',
        thumbCrop:  'height',

        // set this to false if you want to show the caption all the time:
        _toggleInfo: true
    },
    init: function(options) {

        // add some elements
        this.addElement('info-link','info-close');
        this.append({
            'info' : ['info-link','info-close']
        });

        // cache some stuff
        var info = this.$('info-text'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click';

        // show loader & counter with opacity
        this.$('loader,counter').show().css('opacity', 0.4);

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
            this.addIdleState( this.get('counter'), { opacity:0 });
        }

        // toggle info
        /******************** Joomlashine dev BEGIN ********************/
        this.$('info-link, info-close').hide(); 
        // add some elements
        this.addElement('info-image-link');
        this.append({
            'info-text' : ['info-image-link']
        }); 
        if (options.thumbnails)
        {
        	this._stageHeight = this._stageHeight - options.thumbHeight;     
        }
        
        if (options.thumbnails)
        {
        	if(options.thumbPosition == 'top')
        	{
        		var tmpStageHeight = this._stageHeight*5/100;
        		var top = options.thumbHeight + 15 + tmpStageHeight;
        		this.$('stage').css({top: top, bottom: tmpStageHeight});
        		this.rescale();
        	}
            if (options.thumbPosition == 'bottom')
            {
            	var tmpStageHeight = this._stageHeight*5/100;
            	var top = tmpStageHeight;
            	var bottom = options.thumbHeight + 15 + tmpStageHeight;
            	this.$('stage').css({top: top, bottom: bottom});
            	this.rescale();
            }         	
        } 

        if(!options.thumbnails)
        {
        	this.$('thumbnails-container').hide();
        	//this.$('stage').css({top: 12, bottom: 12});
        }    
        if (options.imageCrop)
        {
        	var width = this.$( 'container' ).width();
        	this.$('stage').css({top: 0, bottom: 0, left:0, right:0});
        	if (!options.thumbnails)
        	{
        		this.rescale(width, options.height);
        	}
        	else
        	{
        		var height = options.height - options.thumbHeight - 10;
        		this.rescale(width, height);
        		var top = options.thumbHeight + 10;
        		if (options.thumbPosition == 'top')
        		{
        			this.$('stage').css({top: top});
        		}
        	}
        }
        /******************** Joomlashine dev END ********************/
        /******************** Disabbled By Joomlashine BEGIN ********************/
        /*if ( options._toggleInfo === true ) {
            info.bind( click, function() {
                info.toggle();
            });
        } else {
            info.show();
            this.$('info-link, info-close').hide();
        }*/
        /******************** Disabbled By Joomlashine END ********************/	
        // bind some stuff
        this.bind('thumbnail', function(e) {
        	
            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if ( e.index === this.getIndex() ) {
                    $(e.thumbTarget).css('opacity',1);
                }
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6);
            }
        });
  
        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);  
            }
            this.$('info').toggle( this.hasInfo() );
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);

            /******************** Joomlashine dev BEGIN ********************/
            /******************** Joomlashine dev END********************/
            
        });

        this.bind('loadfinish', function(e) {
            this.$('loader').fadeOut(200);   
            /******************** Joomlashine dev BEGIN ********************/
        	var index 			= this.getIndex();
       	 	var infoImageLink 	= this.$('info-image-link');
       	 	var dataLength    	= this.getDataLength();

			if (options.showImageLink)
            {
            	 infoImageLink.html(this._data[index].link);        	
            } 
            else
            {
            	infoImageLink.hide();
            }
            
            if (!options.loop)
            {
            	var tmpDataLength = dataLength - 1;
            	if (tmpDataLength == index)
            	{
            		this.pause();
            	}
            }  
            
            if(!options.infoPanelShowTitle)
            {
            	this.$('info-title').hide();
            }  
            
            if(!options.infoPanelShowDescription)
            {
            	this.$('info-description').hide();
            }

            if(options.infoPanelShowTitle || options.infoPanelShowDescription || options.showImageLink)
            {
            	this.$('info-text').show();
            }
            else
            {
            	this.$('info-text').hide();
            }
            /******************** Joomlashine dev END********************/
        });
    }
});

}(jQuery));
