// user can choose which KB to choose (only when multiple KB is enabled)
(function() {
	tinymce.PluginManager.add('epkb_shortcodes', function( editor, url ) {
 		editor.addButton( 'epkb_shortcodes', {
			title: 'Knowledge Base Main Page',
			icon: 'icon epkb_shortcode_icon',
			onclick: function() {
				editor.windowManager.open( {

					title: 'Insert Knowledge Base Main Page',

					body: [
						{
							type: 'textbox',
							name: 'id',
							label: 'Knowledge Base ID',
							value: '1'
						},
						{
							type: 'container',
							html: '<a target="_blank" href="http://www.echoknowledgebase.com/documentation/" style="color: #86d700;">Documentation Help</a>'
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[epkb-knowledge-base id="' + e.data.id + '"]' );
					}
				});
			}
		});
	});
})();


