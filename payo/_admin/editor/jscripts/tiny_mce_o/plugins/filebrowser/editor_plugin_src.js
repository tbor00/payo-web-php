/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	tinymce.create('tinymce.plugins.FileBrowserPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceFileBrowser', function() {

				ed.windowManager.open({
					file : url + '/filebrw.php',
					width : 720,
					height : 430,
					inline : true
				}, {
					plugin_url : url
				});
			});

		},

		createControl : function(n, cm) {
			return null;
		},



		getInfo : function() {
			return {
				longname : 'FileBrowser',
				author : 'GGB',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('filebrowser', tinymce.plugins.FileBrowserPlugin);
})();