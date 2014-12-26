<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
class JSNISThemeDisplay extends JObject
{
	var $_themename 	= 'themeclassic';
	var $_themetype 	= 'jsnimageshow';
	var $_assetsPath 	= 'plugins/jsnimageshow/themeclassic/assets/';
	function JSNISThemeDisplay() {}

	function standardLayout($args)
	{
		JHTML::script('swfobject.js', $this->_assetsPath.'js/');
		$path = JPATH_PLUGINS.DS.$this->_themetype.DS.$this->_themename.DS.'models';
		JModel::addIncludePath($path);
		$model 				= JModel::getInstance($this->_themename);
		$themeData  		= $model->getData($args->theme_id);
		$backgroundColor	= ($themeData->general_background_color != '')?$themeData->general_background_color:'#ffffff';
		$html  = '<div class="jsn-'.$this->_themename.'-gallery">'."\n";
		// fix error: click back browser, no event onclick of flash
		$html  .= '<script type="text/javascript"> window.onbeforeunload = function() {} </script>'."\n";
		if ($args->swf)
		{
			$showcaseURL = '';
			$showlistURL = '';
			if(!$args->showlist_id && !$args->showcase_id)
			{
				$showcaseURL = $args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showcase';
				$showlistURL = $args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showlist';
			}
			else
			{
				$showcaseURL = $args->uri.'index.php?option=com_imageshow%26view=show%26showcase_id='.$args->showcase_id.'%26format=showcase';
				$showlistURL = $args->uri.'index.php?option=com_imageshow%26view=show%26showlist_id='.$args->showlist_id.'%26format=showlist';
			}
			$html .= '<script type="text/javascript">'."\n";
			$html .= 'swfobject.embedSWF('."\n";
			$html .= '"'.$args->url.'Gallery.swf'.'",'."\n";
			$html .= '"jsn-imageshow-'.$args->random_number.'",'."\n";
			$html .= '"'.$args->width.'",'."\n";
			$html .= '"'.$args->height.'",'."\n";
			$html .= '"9.0.45",'."\n";
			$html .= '"'.$args->url.'assets/js/expressInstall.swf",'."\n";
			$html .= '{'."\n";
			$html .= 'baseurl:"'.$args->url.'",'."\n";
			$html .= 'showcase:"'.$showcaseURL.'",'."\n";
			$html .= 'showlist:"'.$showlistURL.'",'."\n";
			$html .= 'language:"'.$args->language.'",'."\n";
			$html .= 'edition:"'.$args->edition.'"'."\n";
			$html .= '},'."\n";
			$html .= '{'."\n";
			$html .= 'wmode:"opaque",'."\n";
			$html .= 'bgcolor:"'.$backgroundColor.'",'."\n";
			$html .= 'menu:"false",'."\n";
			$html .= 'allowFullScreen:"true"'."\n";
			$html .= '});'."\n";
			$html .= '</script>'."\n";
			$html .= '<div id="jsn-imageshow-'.$args->random_number.'">'.$this->displayAlternativeContent().'</div>'."\n";
		}
		else
		{
			$strParameter 	= '';
			$strEmbed 		= '<embed src="'.$args->url.'Gallery.swf" menu="false" bgcolor="'.$backgroundColor.'" width="'.$args->width.'" height="'.$args->height.'" name="jsn-imageshow-'.$args->random_number.'" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" wmode="opaque"';
			if(!$args->showlist_id && !$args->showcase_id)
			{
				$strParameter  = '<param name="flashvars" value="baseurl='.$args->url.'&amp;showcase='.$args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showcase&amp;showlist='.$args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showlist&amp;language='.$args->language.'&amp;edition='.$args->edition.'"/>'."\n";
				$strEmbed	  .= 'flashvars="baseurl='.$args->url.'&amp;showcase='.$args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showcase&amp;showlist='.$args->uri.'index.php?option=com_imageshow%26view=show%26Itemid='.$args->item_id.'%26format=showlist&amp;language='.$args->language.'&amp;edition='.$args->edition.'"/>'."\n";
			}
			else
			{
				$strParameter  = '<param name="flashvars" value="baseurl='.$args->url.'&amp;showcase='.$args->uri.'index.php?option=com_imageshow%26view=show%26showcase_id='.$args->showcase_id.'%26format=showcase&amp;showlist='.$args->uri.'index.php?option=com_imageshow%26view=show%26showlist_id='.$args->showlist_id.'%26format=showlist&amp;language='.$args->language.'&amp;edition='.$args->edition.'"/>'."\n";
				$strEmbed	  .= 'flashvars="baseurl='.$args->url.'&amp;showcase='.$args->uri.'index.php?option=com_imageshow%26view=show%26showcase_id='.$args->showcase_id.'%26format=showcase&amp;showlist='.$args->uri.'index.php?option=com_imageshow%26view=show%26showlist_id='.$args->showlist_id.'%26format=showlist&amp;language='.$args->language.'&amp;edition='.$args->edition.'"/>'."\n";
			}
			$html  .= '<object height="'.$args->height.'" class="jsn-flash-object" width="'.$args->width.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="jsn-imageshow-'.$args->random_number.'" align="middle">'."\n";
			$html  .= '<param name="bgcolor" value="'.$backgroundColor.'"/>'."\n";
			$html  .= '<param name="wmode" value="opaque"/>'."\n";
			$html  .= '<param name="menu" value="false"/>'."\n";
			$html  .= '<param name="allowFullScreen" value="true"/>'."\n";
			$html  .= '<param name="allowScriptAccess" value="sameDomain" />'."\n";
			$html  .= '<param name="movie" value="'.$args->url.'Gallery.swf"/>'."\n";
			$html  .= $strParameter;
			$html  .= $strEmbed;
			$html  .= '</object>'."\n";
		}
			$html .= '</div>'."\n";
		return $html;
	}

	function displayAlternativeContent()
	{
		$html    = '<div class="jsn-'.$this->_themename.'-msgnonflash">'."\n";
		$html   .= '<p>'.JText::_('SITE_SHOW_YOU_NEED_FLASH_PLAYER').'</p>'."\n";
		$html   .= '<p>'."\n";
		$html   .= '<a href="http://www.adobe.com/go/getflashplayer">'."\n";
		$html   .= JText::_('SITE_SHOW_GET_FLASH_PLAYER')."\n";
		$html   .='</a>'."\n";
		$html   .='</p>'."\n";
		$html   .='</div>'."\n";
		return $html;
	}

	function displaySEOContent($args)
	{
		$html    = '<div class="jsn-'.$this->_themename.'-seocontent">'."\n";
		if ($args->edition == 'free')
		{
			$html	.= '<p><a href="http://www.joomlashine.com" title="Joomla gallery">Joomla gallery</a> by joomlashine.com</p>'."\n";
		}
		if (count($args->images))
		{
			$html .= '<div>';
			$html .= '<p>'.@$args->showlist['showlist_title'].'</p>';
			$html .= '<p>'.@$args->showlist['description'].'</p>';
			$html .= '<ul>';

			for ($i = 0, $n = count($args->images); $i < $n; $i++)
			{
				$row 	=& $args->images[$i];
				$html  .= '<li>';
				if ($row->image_title != '')
				{
					$html .= '<p>'.$row->image_title.'</p>';
				}
				if ($row->image_description != '')
				{
					$html .= '<p>'.$row->image_description.'</p>';
				}
				if ($row->image_link != '')
				{
					$html .= '<p><a href="'.htmlspecialchars($row->image_link).'">'.htmlspecialchars($row->image_link).'</a></p>';
				}
				$html .= '</li>';
			}
			$html .= '</ul></div>';
		}
		$html   .='</div>'."\n";
		return $html;
	}

	function mobileLayout($args)
	{
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistInfo 		= $objJSNShowlist->getShowListByID($args->showlist['showlist_id'], true);
		$dataObj 			= $objJSNShowlist->getShowlist2JSON($args->uri, $args->showlist['showlist_id']);
		$images				= $dataObj->showlist->images->image;
		if (!count($images)) return '';

		switch ($showlistInfo['image_loading_order'])
		{
			case 'backward':
				krsort($images);
				$tmpImageArray = $images;
				$images = array_values($images);
				break;
			case 'random':
				shuffle($images);
				break;
			case 'forward':
			default:
				ksort($images);
				break;
		}

		JHTML::script('jquery.min.js','https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/');
		JHTML::script('jsn_is_conflict.js', $this->_assetsPath.'js/');
		JHTML::script('galleria-1.2.5.js', $this->_assetsPath.'js/galleria/');
		JHTML::script('galleria.classic.js', $this->_assetsPath.'js/galleria/themes/classic/');

		$path = JPATH_PLUGINS.DS.$this->_themetype.DS.$this->_themename.DS.$this->_themename.DS.'models';
		JModel::addIncludePath($path);
		$model 		= JModel::getInstance($this->_themename);
		$themeData  = $model->getData($args->theme_id);

		$jsImagePanelDefaultPresentationMode 	= '';

		$jsWidth 						 		= '';
		$jsAutoPlay 					 		= '';
		$jsShowThumbnail 						= '';
		$jsToolBarpanelPresentation		 		= '';
		$jsPauseOnInteraction			 		= '';
		$jsSlideshowLooping				 		= '';

		$jsInformationPanelPresentation  		= '';
		$jsInformationPanelShowTitle			= '';
		$jsInformationPanelShowDescription		= '';
		$jsInformationPanelImageShowLink 		= '';
		$jsInformationpanelPopupLinks			= '';
		$jsInformationPanelClickAction			= '';

		$jsThumbnailHeight 						= '';
		$jsThumbnailPosition					= '';

		$jsPopupLinks					 		= '';
		$jsImagePanelImageClickAction	 		= '';
		$css							 		= '';
		$cssInformationPanelPosition			= '';
		$cssThumbnailPanelPosition				= '';

		$cssGeneralRoundCornerRadius			= ($themeData->general_round_corner_radius != '')?$themeData->general_round_corner_radius:'0';
		$cssGeneralBorderStroke					= ($themeData->general_border_stroke != '')?$themeData->general_border_stroke:'2';
		$cssGeneralBorderColor					= ($themeData->general_border_color != '')?$themeData->general_border_color:'#000000';
		$cssGeneralBackgroundColor				= ($themeData->general_background_color != '')?$themeData->general_background_color:'#ffffff';

		$cssToolBarpanelPresentation			= '';

		$imagePanelBackground					= '';
		$doc									= JFactory::getDocument();

		// Parameters of Theme
		$percent  						= strpos($args->width, '%');
		$autoPlay		 				= $this->_convertToBool($themeData->slideshow_auto_play);
		$slideshowLooping		 		= $this->_convertToBool($themeData->slideshow_looping);

		$showThumbnail 							= $this->_convertToBool($themeData->thumbpanel_show_panel);
		$toolBarpanelPresentation 				= $this->_convertToBool($themeData->toolbarpanel_presentation);
		$normalStateColor		 				= $this->_hex2rgb($themeData->thumbpanel_thumnail_normal_state);
		$activeStateColor		 				= $themeData->thumbpanel_active_state_color;
		$panelThumbnailPanelBackgroundColor		= $themeData->thumbpanel_thumnail_panel_color;
		$panelInfoPanelBackgroundColor			= $themeData->infopanel_bg_color_fill;
		$thumbnailWidth		 		    		= (int) $themeData->thumbpanel_thumb_width;
		$thumbnailHeight	 		   	 		= (int) $themeData->thumbpanel_thumb_height;
		$thumbnailBorder	 		    		= (int) $themeData->thumbpanel_border;
		$thumbnailPanelPosition					= trim($themeData->thumbpanel_panel_position);

		$panelInfoPanelBackgroundColor			= $this->_hex2rgb($panelInfoPanelBackgroundColor);
		$informationPanelPresentation			= $this->_convertToBool($themeData->infopanel_presentation);
		$informationPanelShowTitle				= $this->_convertToBool($themeData->infopanel_show_title);
		$informationPanelShowDescription		= $this->_convertToBool($themeData->infopanel_show_des);
		$informationPanelTitleCSS				= trim($themeData->infopanel_title_css);
		$informationPanelDescriptionCSS			= trim($themeData->infopanel_des_css);
		$informationPanelDescriptionLenghtLimit	= (int) trim($themeData->infopanel_des_lenght_limitation);
		$informationPanelImageShowLink			= $this->_convertToBool($themeData->infopanel_show_link);
		$informationPanelLinkCSS				= trim($themeData->infopanel_link_css);
		$informationPanelPosition				= trim($themeData->infopanel_panel_position);
		$informationPanelClickAction			= trim($themeData->infopanel_panel_click_action);
		$informationPanelOpenLink				= trim($themeData->infopanel_open_link_in);

		$imagePanelBackgroundType				= trim($themeData->imgpanel_bg_type);
		$imagePanelBackgroundValue				= trim($themeData->imgpanel_bg_value);
		$imagePanelDefaultPresentationMode		= trim($themeData->imgpanel_presentation_mode);

		if ($thumbnailPanelPosition == 'bottom')
		{
			if($informationPanelPosition == 'top')
			{
				if($showThumbnail)
				{
					$cssInformationPanelPosition = 'top: 0;';
				}
				else
				{
					$cssInformationPanelPosition = 'top: 0;';
				}

			}
			elseif ($informationPanelPosition == 'bottom')
			{
				if($showThumbnail)
				{
					$cssInformationPanelPosition = 'bottom:'. ($thumbnailHeight + 15).'px;';
				}
				else
				{
					$cssInformationPanelPosition = 'bottom:0;';
				}
			}
			$cssThumbnailPanelPosition = 'bottom: 0;';
		}
		elseif ($thumbnailPanelPosition == 'top')
		{
			$cssThumbnailPanelPosition 	= 'top: 0;';
			if($informationPanelPosition == 'top')
			{
				if($showThumbnail)
				{
					$cssInformationPanelPosition = 'top: '. ($thumbnailHeight + 15).'px;';
				}
				else
				{
					$cssInformationPanelPosition = 'top: 0;';
				}

			}
			elseif ($informationPanelPosition == 'bottom')
			{
				if($showThumbnail)
				{
					$cssInformationPanelPosition = 'bottom: 0;';
				}
				else
				{
					$cssInformationPanelPosition = 'bottom:0;';
				}
			}
		}

		switch ($imagePanelDefaultPresentationMode)
		{
			case 'fit-in':
				$imagePanelImageClickAction	= trim($themeData->imgpanel_img_click_action_fit);
				$imagePanelOpenLinkIn		= trim($themeData->imgpanel_img_open_link_in_fit);
				$jsImagePanelDefaultPresentationMode = 'imageCrop: false,';
				break;
			case 'expand-out':
				$imagePanelImageClickAction	= trim($themeData->imgpanel_img_click_action_expand);
				$imagePanelOpenLinkIn		= trim($themeData->imgpanel_img_open_link_in_expand);
				$jsImagePanelDefaultPresentationMode = 'imageCrop: true,';
				break;
		}

		if ($imagePanelImageClickAction == 'open-image-link')
		{
			if ($imagePanelOpenLinkIn == 'current-browser')
			{
				$jsPopupLinks = 'popupLinks:false,';
			}
			elseif ($imagePanelOpenLinkIn == 'new-browser')
			{
				$jsPopupLinks = 'popupLinks:true,';
			}
			$jsImagePanelImageClickAction = 'imageClickAction:true,';
		}
		else
		{
			$jsImagePanelImageClickAction = 'imageClickAction:false,';
		}

		if ($informationPanelClickAction == 'open-image-link')
		{
			if ($informationPanelOpenLink == 'current-browser')
			{
				$jsInformationpanelPopupLinks = 'infoPanelPopupLinks:false,';
			}
			elseif ($informationPanelOpenLink == 'new-browser')
			{
				$jsInformationpanelPopupLinks = 'infoPanelPopupLinks:true,';
			}
			$jsInformationPanelClickAction = 'informationPanelClickAction:true,';
		}
		else
		{
			$jsInformationPanelClickAction = 'informationPanelClickAction:false,';
		}
		switch ($imagePanelBackgroundType)
		{
			case 'solid-color':
					$imagePanelBackground = 'background: '.$imagePanelBackgroundValue.';';
				break;
			case 'linear-gradient':
			case 'radial-gradient':
					$tmpImagePanelBackgroundValue 	= @explode(',', $imagePanelBackgroundValue);
					$imagePanelBackground 			= 'background: '.@$tmpImagePanelBackgroundValue[0].';';
				break;
			case 'pattern':
					$tmpImagePanelBackgroundValue 	= $args->uri.$imagePanelBackgroundValue;
					$imagePanelBackground 			= 'background: url('.$tmpImagePanelBackgroundValue.') repeat left center;';
				break;
			case 'image':
					$tmpImagePanelBackgroundValue 	= $args->uri.$imagePanelBackgroundValue;
					$imagePanelBackground 			= 'background: url('.$tmpImagePanelBackgroundValue.');';
					$imagePanelBackground 			.= 'background-position: center center;'."\n";
					$imagePanelBackground 			.= 'background-repeat: no-repeat;'."\n";
					$imagePanelBackground 			.= 'background-size: cover;'."\n";
				break;
		}
		// Parameters of JS Gallery
		$jsShowThumbnail 					= 'thumbnails: '.$this->_convertFromBoolToString($showThumbnail).',';
		$jsToolBarpanelPresentation 		= 'showImagenav: '.$this->_convertFromBoolToString($toolBarpanelPresentation).',';
		$jsPauseOnInteraction			    = 'pauseOnInteraction: false,';
		$jsInformationPanelPresentation		= 'showInfo: '.$this->_convertFromBoolToString($informationPanelPresentation).',';
		$jsInformationPanelShowTitle		= 'infoPanelShowTitle: '.$this->_convertFromBoolToString($informationPanelShowTitle).',';
		$jsInformationPanelShowDescription	= 'infoPanelShowDescription: '.$this->_convertFromBoolToString($informationPanelShowDescription).',';
		$jsInformationPanelImageShowLink	= 'showImageLink:'.$this->_convertFromBoolToString($informationPanelImageShowLink).',';
		$jsSlideshowLooping					= 'loop:'.$this->_convertFromBoolToString($slideshowLooping).',';
		$jsThumbnailHeight					= 'thumbHeight:'.$thumbnailHeight.',';
		$jsThumbnailPosition				= 'thumbPosition:"'.$thumbnailPanelPosition.'",';

		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.'{
	    			width: '.$args->width.((!$percent)?'px':'').';
	    			height: '.$args->height.'px;
	    			background-color: '.$cssGeneralBackgroundColor.';
	    			display:inline-table;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-container {
	    			margin: 0 auto;
	    			padding: 0;
	    			'.$imagePanelBackground.'
	    			border: '.$cssGeneralBorderStroke.'px solid '.$cssGeneralBorderColor.';
					-webkit-border-radius: '.$cssGeneralRoundCornerRadius.'px;
					-moz-border-radius: '.$cssGeneralRoundCornerRadius.'px;
					border-radius: '.$cssGeneralRoundCornerRadius.'px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-container .galleria-stage{
	    			position: absolute;
				    top:5%;
				    bottom: 5%;
				    left: 5%;
				    right: 5%;
				    overflow:hidden;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-container .galleria-image-nav{
				    position: absolute;
				    top: 50%;
				    margin-top: -62px;
				    width: 100%;
				    height: 62px;
				    left: 0;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails .galleria-image {
	    			border: '.$thumbnailBorder.'px solid rgba('.$normalStateColor['red'].', '.$normalStateColor['green'].', '.$normalStateColor['blue'].', 0.3);
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails .galleria-image:hover {
	    			border: '.$thumbnailBorder.'px solid '.$activeStateColor.';
	    			filter: alpha(opacity=100);
					-moz-opacity: 1;
					-khtml-opacity: 1;
					opacity: 1;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails .active {
	    			border: '.$thumbnailBorder.'px solid '.$activeStateColor.';
	    			filter: alpha(opacity=100);
					-moz-opacity: 1;
					-khtml-opacity: 1;
					opacity: 1;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails  {
					height: '.($thumbnailHeight +4).'px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container{
	    			background-color: '.$panelThumbnailPanelBackgroundColor.';
	    			left: 0;
				    right: 0;
				    width: 100%;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-list {
    				margin-top: 5px;
    				margin-left: 10px;
    				margin-bottom: 5px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-carousel .galleria-thumbnails-list {
   	 				margin-left: 30px;
   					margin-right: 30px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails .galleria-image {
    				width: '.$thumbnailWidth.'px;
    				height: '.$thumbnailHeight.'px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container {
					height: '.($thumbnailHeight + 15).'px;
					'.$cssThumbnailPanelPosition.'
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-info {
					color: #FFFFFF;
				    display: none;
				    position: absolute;
				    text-align: left;
				    '.$cssInformationPanelPosition.'
				    width: 100%;
				    z-index: 4;
				    left:0;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-info .galleria-info-text {
				    background: none repeat scroll 0 0 rgba('.$panelInfoPanelBackgroundColor['red'].', '.$panelInfoPanelBackgroundColor['green'].', '.$panelInfoPanelBackgroundColor['blue'].', 0.7);
				    padding: 12px;
				    height: auto;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-info .galleria-info-text .galleria-info-title{
					'.$informationPanelTitleCSS.'
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-info .galleria-info-text .galleria-info-description{
					'.$informationPanelDescriptionCSS.'
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-info .galleria-info-text .galleria-info-image-link{
					'.$informationPanelLinkCSS.'
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container .galleria-thumb-nav-right{
					  background-position: -578px '.(($thumbnailHeight - 20)/2).'px;
					  height: '.($thumbnailHeight + 15).'px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container .galleria-thumb-nav-left{
					  background-position: -495px '.(($thumbnailHeight - 20)/2).'px;
					  height: '.($thumbnailHeight + 15).'px;
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container .galleria-thumb-nav-left:hover{
					   background-color: rgba(255, 255, 255, 0.3);
				}'."\n";
		$css .= '#jsn-themeclassic-jsgallery-'.$args->random_number.' .galleria-thumbnails-container .galleria-thumb-nav-right:hover{
					   background-color: rgba(255, 255, 255, 0.3);
				}'."\n";
		if ($autoPlay)
		{
			$jsAutoPlay = 'autoplay:'.(int) $themeData->slideshow_slide_timing.'000,';
		}
		else
		{
			$jsAutoPlay = 'autoplay:false,';
		}

		if (!$percent)
		{
			$jsWidth = 'width:'.$args->width.',';
		}

		$doc->addStyleDeclaration($css);
		$html  = '<div id="jsn-themeclassic-jsgallery-'.$args->random_number.'"><div id="jsn-themeclassic-galleria-'.$args->random_number.'">'."\n";
		for($i = 0, $counti = count($images); $i < $counti; $i++)
		{
			$image = $images[$i];
			$desc  = $this->_wordLimiter($image->description, $informationPanelDescriptionLenghtLimit);
			$html .= '<a href="'.$image->image.'"><img title="'.$image->title.'" alt="'.$desc.'" src="'.$image->thumbnail.'" longdesc="'.$image->link.'" /></a>'."\n";
		}
		$html .= '</div></div>'."\n";
		$html .= '<script type="text/javascript">jsnThemeClassicjQuery(function() {jsnThemeClassicjQuery("#jsn-themeclassic-galleria-'.$args->random_number.'").galleria({'.$jsWidth.$jsAutoPlay.$jsShowThumbnail.$jsToolBarpanelPresentation.$jsPauseOnInteraction.$jsInformationPanelPresentation.$jsInformationPanelShowTitle.$jsInformationPanelShowDescription.$jsPopupLinks.$jsImagePanelImageClickAction.$jsInformationPanelImageShowLink.$jsSlideshowLooping.$jsThumbnailHeight.$jsThumbnailPosition.$jsImagePanelDefaultPresentationMode.$jsInformationPanelClickAction.$jsInformationpanelPopupLinks.'height:'.$args->height.', initialTransition: "fade", transition: "slide", thumbCrop: false, thumbFit: false, thumbQuality: false, showCounter: false, imageTimeout: 300000});});</script>';

		return $html;
	}

	function display($args)
	{
		$objUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$device     = $objUtils->checkSupportedFlashPlayer();
		$string		= '';
		$path 		= JPATH_PLUGINS.DS.$this->_themetype.DS.$this->_themename.DS.'models';
		JModel::addIncludePath($path);
		$model 		= JModel::getInstance($this->_themename);
		$parameters	= $model->getParameters();
		$args->swf	= $parameters->general_swf_library;
		$args->uri	= $this->_overwriteURL($parameters);
		$args->url	= $args->uri.'plugins/'.$this->_themetype.'/'.$this->_themename.'/assets/swf/';

		if ($device == 'iphone' || $device == 'ipad' || $device == 'ipod' || $device == 'android')
		{
			$string	   .= $this->mobileLayout($args);
		}
		else
		{
			$string  	.= $this->standardLayout($args);
			//$string 	.= $this->displayAlternativeContent();
			$string 	.= $this->displaySEOContent($args);
		}
		return $string;
	}

    function _convertToBool($str)
	{
        $str = (string) $str;

		if ($str != '')
		{
            return ((strcasecmp($str, 'yes') == 0) || (strcasecmp($str, 'on') == 0) || ($str == '1') || (strcasecmp($str, 'auto') == 0));
        }
        return false;
    }

    function _convertFromBoolToString($bool)
	{
        $bool = (boolean) $bool;
		if ($bool)
		{
            return 'true';
        }
        return 'false';
    }

    function _hex2rgb($hexVal = "")
    {
        if (0 === strpos($hexVal, '#'))
        {
            $hexVal = substr($hexVal, 1);
        }
    	$hexVal = preg_replace("[^a-fA-F0-9]", "", $hexVal);
        if (strlen($hexVal) != 6) {return array();}
        $arrTmp = explode(" ", chunk_split($hexVal, 2, " "));
        $arrTmp = array_map("hexdec", $arrTmp);
        return array("red" => $arrTmp[0], "green" => $arrTmp[1], "blue" => $arrTmp[2]);
    }

	function _wordLimiter($str, $limit = 100, $endChar = '&#8230;')
	{
		if (trim($str) == '')
		{
		    return $str;
		}
		$str = strip_tags($str);
		preg_match('/\s*(?:\S*\s*){'.(int) $limit.'}/', $str, $matches);
		if (strlen($matches[0]) == strlen($str))
		{
			$endChar = '';
		}
	   return rtrim($matches[0]).$endChar;
	}

	function _overwriteURL($parameters)
	{
		if (!is_null($parameters) && $parameters->root_url == 2)
		{
			return JURI::base();
		}
		else
		{
			$pathURL 			= array();
			$uri				= JURI::getInstance();
			$pathURL['prefix'] 	= $uri->toString( array('scheme', 'host', 'port'));

			if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
			{
				$pathURL['path'] =  rtrim(dirname(str_replace(array('"', '<', '>', "'"), '', $_SERVER["PHP_SELF"])), '/\\');
			}
			else
			{
				$pathURL['path'] =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}
			return $pathURL['prefix'].$pathURL['path'].'/';
		}
	}
}