var FileBrowse = {
	preInit : function() {
		tinyMCEPopup.requireLangPack();
	},
	//-----------------------------------
	init : function(ed) {
		var f = document.forms[0], ed = tinyMCEPopup.editor, dom = ed.dom;
		this.resetForm();
	},
	//-----------------------------------
	uploadFile : function () {
		var passdir = document.getElementById('passdir').value;
		var type = document.getElementById('type').value;
		var win = tinyMCEPopup.getWindowArg("window");
		var urlp = "upload.php?type=" + type + "&passdir=" + passdir;
		tinyMCE.activeEditor.windowManager.open({
			file : urlp,
			title : 'Subir archivos...',
			width : 400,
			height : 180,
			resizable : "no",
			inline : "yes",
			close_previous : "no"
		}, {
	 	   window: win,
			frame: document.getElementById('filebrowse')
		});
		return false;
	},
	//-----------------------------------
	showPreviewImage : function(u, st) {
		this.showFileInfo(u);
		if (!u) {
			tinyMCEPopup.dom.setHTML('prev', '');
			return;
		}
		u = tinyMCEPopup.editor.documentBaseURI.toAbsolute(u);
		tinyMCEPopup.dom.setHTML('prev', '<img id="fbpreviewImg" src="' + u + '" border="0" />');
	},
	//-----------------------------------
	showFileInfo : function(u) {
		if (!u) {
			tinyMCEPopup.dom.setHTML('fileinfo', '');
			return;
		}
		u = tinyMCEPopup.editor.documentBaseURI.toAbsolute(u);
		tinyMCEPopup.dom.setHTML('fileinfo', "<P><A HREF=\"javascript:FileBrowse.deleteFile('" + u + "');\">Eliminar</A></P><P><A HREF=\"" + u + "\" TARGET=\"_new\">Ver</A></P>");
	},
	//-----------------------------------
	deleteFile : function(u) {
		var ed = tinyMCEPopup.editor;
		var passdir = document.getElementById('passdir').value;
		var type = document.getElementById('type').value;
		var win = tinyMCEPopup.getWindowArg("window");
		var d = document;
		var urlp = "delete.php?type=" + type + "&passdir=" + passdir + "&file=" + u;
		tinyMCE.activeEditor.windowManager.open({
			file : urlp,
			title : 'Eliminar archivo...',
			width : 400,
			height : 150,
			resizable : "no",
			inline : "yes",
			close_previous : "no"
		}, {
	 	   window: win,
			documento: win.document,			
			frame: document.getElementById('filebrowse')
		});
		return;
	},
	//-----------------------------------
	refreshBrowse : function() {
		var passdir = document.getElementById('passdir').value;
		var type = document.getElementById('type').value;
		this.resetForm();
		document.getElementById('filebrowse').src="files.php?type=" + type + "&passdir=" + passdir;
		return false;
	},
	//-----------------------------------
	resetForm : function(u, st) {
		var f = document.forms[0];
		f.file_name.value = '';
		this.showPreviewImage();
	},	   
	//-----------------------------------
	checkForm : function() {
		var f = document.forms[0];
		if (f.file_name.value == "" ) {
			return false;
		}
		return true;
	},
	//-----------------------------------
	close : function(){
		if (this.checkForm()) {
			var f = document.forms[0];
			var URL = f.file_name.value;
			var win = tinyMCEPopup.getWindowArg("window");
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
			// are we an image browser
			if (typeof(win.ImageDialog) != "undefined"){
				// we are, so update image dimensions and preview if necessary
				if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
				if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
			}
			tinyMCEPopup.close();
		}
	}
}
//-----------------------------------------------------------------------------
FileBrowse.preInit();
tinyMCEPopup.onInit.add(FileBrowse.init, FileBrowse);