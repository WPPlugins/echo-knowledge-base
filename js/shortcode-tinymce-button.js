
(function() {
	tinymce.PluginManager.add('epkb_shortcodes', function( editor, url ) {
 		editor.addButton( 'epkb_shortcodes', {
			title: 'Knowledge Base Main Page',
			icon: 'icon epkb_shortcode_icon',
			onclick: function() {
				editor.insertContent(
					'[epkb-knowledge-base id="1"]');
			}
		});
	});
})();


