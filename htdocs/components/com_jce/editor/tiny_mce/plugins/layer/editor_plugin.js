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
(function(){function findParentLayer(node){do{if(node.className&&node.className.indexOf('mceItemLayer')!=-1){return node;}}while(node=node.parentNode);};tinymce.create('tinymce.plugins.Layer',{init:function(ed,url){var t=this;t.editor=ed;ed.addCommand('mceInsertLayer',t._insertLayer,t);ed.addCommand('mceMoveForward',function(){t._move(1);});ed.addCommand('mceMoveBackward',function(){t._move(-1);});ed.addCommand('mceMakeAbsolute',function(){t._toggleAbsolute();});ed.addButton('moveforward',{title:'layer.forward_desc',cmd:'mceMoveForward'});ed.addButton('movebackward',{title:'layer.backward_desc',cmd:'mceMoveBackward'});ed.addButton('absolute',{title:'layer.absolute_desc',cmd:'mceMakeAbsolute'});ed.addButton('insertlayer',{title:'layer.insertlayer_desc',cmd:'mceInsertLayer'});ed.onInit.add(function(){var dom=ed.dom;if(tinymce.isIE)
ed.getDoc().execCommand('2D-Position',false,true);});ed.onMouseUp.add(function(ed,e){var layer=findParentLayer(e.target);if(layer){ed.dom.setAttrib(layer,'data-mce-style','');}});ed.onMouseDown.add(function(ed,e){var node=e.target,doc=ed.getDoc(),parent;if(tinymce.isGecko){if(findParentLayer(node)){if(doc.designMode!=='on'){doc.designMode='on';node=doc.body;parent=node.parentNode;parent.removeChild(node);parent.appendChild(node);}}else if(doc.designMode=='on'){doc.designMode='off';}}});ed.onNodeChange.add(t._nodeChange,t);ed.onVisualAid.add(t._visualAid,t);},getInfo:function(){return{longname:'Layer',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/layer',version:tinymce.majorVersion+"."+tinymce.minorVersion};},_nodeChange:function(ed,cm,n){var le,p;le=this._getParentLayer(n);p=ed.dom.getParent(n,'DIV,P,IMG');if(!p){cm.setDisabled('absolute',1);cm.setDisabled('moveforward',1);cm.setDisabled('movebackward',1);}else{cm.setDisabled('absolute',0);cm.setDisabled('moveforward',!le);cm.setDisabled('movebackward',!le);cm.setActive('absolute',le&&le.style.position.toLowerCase()=="absolute");}},_visualAid:function(ed,e,s){var dom=ed.dom;tinymce.each(dom.select('div,p',e),function(e){if(/^(absolute|relative|fixed)$/i.test(e.style.position)){if(s)
dom.addClass(e,'mceItemVisualAid');else
dom.removeClass(e,'mceItemVisualAid');dom.addClass(e,'mceItemLayer');}});},_move:function(d){var ed=this.editor,i,z=[],le=this._getParentLayer(ed.selection.getNode()),ci=-1,fi=-1,nl;nl=[];tinymce.walk(ed.getBody(),function(n){if(n.nodeType==1&&/^(absolute|relative|static)$/i.test(n.style.position))
nl.push(n);},'childNodes');for(i=0;i<nl.length;i++){z[i]=nl[i].style.zIndex?parseInt(nl[i].style.zIndex):0;if(ci<0&&nl[i]==le)
ci=i;}
if(d<0){for(i=0;i<z.length;i++){if(z[i]<z[ci]){fi=i;break;}}
if(fi>-1){nl[ci].style.zIndex=z[fi];nl[fi].style.zIndex=z[ci];}else{if(z[ci]>0)
nl[ci].style.zIndex=z[ci]-1;}}else{for(i=0;i<z.length;i++){if(z[i]>z[ci]){fi=i;break;}}
if(fi>-1){nl[ci].style.zIndex=z[fi];nl[fi].style.zIndex=z[ci];}else
nl[ci].style.zIndex=z[ci]+1;}
ed.execCommand('mceRepaint');},_getParentLayer:function(n){return this.editor.dom.getParent(n,function(n){return n.nodeType==1&&/^(absolute|relative|static)$/i.test(n.style.position);});},_insertLayer:function(){var ed=this.editor,dom=ed.dom,p=dom.getPos(dom.getParent(ed.selection.getNode(),'*')),body=ed.getBody();var le=ed.dom.add(body,'div',{style:{position:'absolute',left:p.x,top:(p.y>20?p.y:20),width:100,height:100},'class':'mceItemVisualAid mceItemLayer'},ed.selection.getContent()||ed.getLang('layer.content'));ed.dom.setAttrib(le,'data-mce-style','');if(tinymce.isIE)
dom.setHTML(body,body.innerHTML);},_toggleAbsolute:function(){var ed=this.editor,le=this._getParentLayer(ed.selection.getNode());if(!le)
le=ed.dom.getParent(ed.selection.getNode(),'DIV,P,IMG');if(le){if(le.style.position.toLowerCase()=="absolute"){ed.dom.setStyles(le,{position:'',left:'',top:'',width:'',height:''});ed.dom.removeClass(le,'mceItemVisualAid');ed.dom.removeClass(le,'mceItemLayer');}else{if(le.style.left=="")
le.style.left=20+'px';if(le.style.top=="")
le.style.top=20+'px';if(le.style.width=="")
le.style.width=le.width?(le.width+'px'):'100px';if(le.style.height=="")
le.style.height=le.height?(le.height+'px'):'100px';le.style.position="absolute";ed.addVisual(ed.getBody());}
ed.dom.setAttrib(le,'data-mce-style','');ed.execCommand('mceRepaint');ed.nodeChanged();}}});tinymce.PluginManager.add('layer',tinymce.plugins.Layer);})();