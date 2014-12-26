/*  
 * JCE Editor                 2.2.7.2
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    12 September 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
var JCEWindowPopup={setDimensions:function(w,h){$.Plugin.setDimensions(w,h,'window_popup_');}};WFPopups.addPopup('window',{setup:function(){},check:function(n){var ed=tinyMCEPopup.editor;var oc=ed.dom.getAttrib(n,'onclick')||ed.dom.getAttrib(n,'data-mce-onclick');return oc&&/window\.open/.test(oc);},remove:function(n){if(this.check(n)){n.removeAttribute('onclick');n.removeAttribute('data-mce-onclick');}},getAttributes:function(n){var ed=tinyMCEPopup.editor,data={};var click=ed.dom.getAttrib(n,'onclick')||ed.dom.getAttrib(n,'data-mce-onclick');var data=click.replace(/window\.open\((.*?)\);(return false;)?/,function(a,b){return b;});var parts=data.split(",'");var src=parts[0];var query=$.String.query(src);var title=(parts[1]||'').replace("'","");var features=(parts[2]||'').replace(/'$/,"");var data={};if(query.img){data.src=query.img;}
$('#window_popup_title').val(title);features=$.String.query(features.replace(/,/g,'&'));$.each(features,function(k,v){switch(k){case'width':case'height':$('#window_popup_'+k+', #popup_'+k).val(v);break;case'scrollbars':case'resizable':case'location':case'menubar':case'status':case'toolbar':$('#window_popup_'+k).attr('checked',v=='yes');break;case'top':case'left':v=(parseInt(v)==0)?k:v;if(/screen\.avail(Width|Height)/.test(v)){if(/[0-9]+/.test(v)){v='center';}else{v=(k=='top')?'bottom':'right';}}
if($('option[value="'+v+'"]','#window_popup_position_'+k).length==0){$('#window_popup_position_'+k).append('<option value="'+v+'">'+v+'</option>');}
$('#window_popup_position_'+k).val(v);break;}});},setAttributes:function(n,args){var ed=tinyMCEPopup.editor,args=args||{};this.remove(n);var src=ed.dom.getAttrib(n,'href');var title=$('#window_popup_title').val()||args.title||'';var width=args.width||$('#window_popup_width').val();var height=args.height||$('#window_popup_height').val();var href=src;var query='this.href';if(/\.(jpg|jpeg|png|gif|bmp|tiff)$/i.test(src)){var params={img:src,title:title.replace(' ','_','gi')};if(width){params.width=width;}
if(height){params.height=height;}
href='index.php?option=com_jce&view=popup&tmpl=component';query="this.href+'&"+decodeURIComponent($.param(params))+"'";}
var top=$('#window_popup_position_top').val();switch(top){case'top':top=0;break;case'center':top=height?"'+(screen.availHeight/2-"+(height/2)+")+'":0;break;case'bottom':top=height?"'+(screen.availHeight-"+height+")+'":0;break;}
var left=$('#window_popup_position_left').val();switch($('#window_popup_position_left').val()){case'left':left=0;break;case'center':left=width?"'+(screen.availWidth/2-"+(width/2)+")+'":0;break;case'right':left=height?"'+(screen.availWidth-"+width+")+'":0;break;}
var features={'scrollbars':'yes','resizable':'yes','location':'yes','menubar':'yes','status':'yes','toolbar':'yes'};$.each(features,function(k,def){var v=$('#window_popup_'+k).is(':checked')?'yes':'no';if(v==def){return;}
features[k]=v;});$.extend(features,{'left':left,'top':top});if(width){features.width=width;}
if(height){features.height=height;}
ed.dom.setAttrib(n,'href',href);ed.dom.setAttrib(n,'data-mce-onclick',"window.open("+query+",'"+encodeURIComponent(title)+"','"+decodeURIComponent($.param(features)).replace(/&/g,',')+"');return false;");}});