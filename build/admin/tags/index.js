(()=>{"use strict";var e={n:t=>{var o=t&&t.__esModule?()=>t.default:()=>t;return e.d(o,{a:o}),o},d:(t,o)=>{for(var n in o)e.o(o,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:o[n]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)},t=window.hulk_woo_tmcecd_settings,o=window.jQuery,n=e.n(o);const a=(0,window.wp.hooks.createHooks)();window.hulk_woo_tmcecd_hooks=a;var r=a;const l=window.wp;l.hooks.addAction("hulk_woo_tmcecd_after_tiny_mce","hulk_woo_tmcecd",(function(){const e=t.editorId,o=document.getElementById(e);l.editor.initialize(e,{tinymce:{height:200,wpautop:!0,statusbar:!0,plugins:"charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv",toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",menubar:!1,tabfocus_elements:":prev,:next",body_class:"locale-en-us",teeny:!1,indent:!1,fix_list_elements:!0,elementpath:!0,setup(e){e.on("change keyup NodeChange",(()=>{o.value=e.getContent()})),r.addAction("hulk_woo_tmcecd_ajaxComplete","hulk_woo_tmcecd",(function(){e.setContent("")}))}},quicktags:{buttons:"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"},mediaButtons:!0,drag_drop_upload:!1,_content_editor_dfw:!1,teeny:!1})})),n()(document).ajaxComplete((function(e,t,o){if(t&&4===t.readyState&&200===t.status&&o.data&&0<=o.data.indexOf("action=add-tag")){const e=window.wpAjax.parseAjaxResponse(t.responseXML,"ajax-response");if(!e||e.errors)return;r.doAction("hulk_woo_tmcecd_ajaxComplete")}}))})();