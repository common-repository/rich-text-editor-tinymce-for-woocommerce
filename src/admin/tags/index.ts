import "./index.scss";
import settings from "../../utils/settings";
import jQuery from "jquery";
import hooks from "../../utils/hooks";

const wp = (window as any).wp;

function initialize() {

	// Editor ID
	const editorId = settings.editorId;

	const hiddenField = document.getElementById(editorId) as HTMLTextAreaElement

	// Initialize the wp.editor
	wp.editor.initialize(editorId, {
		tinymce: {
			height: 200,
			wpautop: true,
			statusbar: true,
			plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
			toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv',
			toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
			menubar: false,
			tabfocus_elements: ':prev,:next',
			body_class: 'locale-en-us',
			teeny: false,
			indent: false,
			fix_list_elements: true,
			elementpath: true,
			setup(editor: any) {
				editor.on('change keyup NodeChange', () => {
					hiddenField.value = editor.getContent();
				});
				// Reset the editor content
				hooks.addAction(
					'hulk_woo_tmcecd_ajaxComplete',
					'hulk_woo_tmcecd',
					function () {
						editor.setContent('');
					}
				)
			}
		},
		quicktags: {
			buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
		},
		mediaButtons: true,
		drag_drop_upload: false,
		_content_editor_dfw: false,
		teeny: false
	});
}

wp.hooks.addAction(
	'hulk_woo_tmcecd_after_tiny_mce',
	'hulk_woo_tmcecd',
	initialize
);

(function ($) {
	"use strict";

	$(document).ajaxComplete(function (_event, request, options) {
		if (
			request &&
			4 === request.readyState &&
			200 === request.status &&
			options.data &&
			0 <= options.data.indexOf('action=add-tag')
		) {
			const wpAjax = (window as any).wpAjax;
			const res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
			if (!res || res.errors) {
				return;
			}
			hooks.doAction("hulk_woo_tmcecd_ajaxComplete");
		}
	});

})(jQuery);
