<?
require_once("include/config.inc.php");
?>
<HTML>
<HEAD> 
<TITLE>Selecci&oacute;n de Archivos</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/utils/mctabs.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/utils/form_utils.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/utils/validate.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/utils/editable_selects.js"></SCRIPT>
<?
 /// <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/filebrowse.js"></SCRIPT>
?>
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<SCRIPT LANGUAGE="JavaScript">
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
//FileBrowse.preInit();
tinyMCEPopup.onInit.add(FileBrowse.init, FileBrowse);

</SCRIPT>
</HEAD> 
<BODY>
<FORM ID="flbrw" NAME="flbrw" STYLE="margin-top: 0pt; margin-bottom: 0pt" ACTION="#">
<INPUT TYPE="HIDDEN" ID="passdir" NAME="passdir" VALUE="<? echo $passdir ?>">
<INPUT TYPE="HIDDEN" ID="type" NAME="type" VALUE="<? echo $type ?>">
<DIV> 
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="2" WIDTH="100%"> 
<TR>
<TD ALIGN="LEFT">
<SPAN ID="uploadbutton" OnClick="javascript:FileBrowse.uploadFile();" CLASS="btnImage"><IMG SRC="images/add.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Subir Archivo"></SPAN>
&nbsp;
<SPAN ID="refreshbutton" OnClick="javascript:FileBrowse.refreshBrowse();" CLASS="btnImage"><IMG SRC="images/reload.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Actualizar"></SPAN>
</TD>
<TD ALIGN="RIGHT"></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="2" CELLSPACING="1"> 
<TR> 
<TD WIDTH="20%" VALIGN="TOP">
<?
if ($type == 'image'){ 
	echo "<FIELDSET><DIV ID=\"prev\"></DIV></FIELDSET>";
}
?>
<FIELDSET><DIV ID="fileinfo"></DIV></FIELDSET>
</TD> 
<TD WIDTH="80%">
<IFRAME HEIGHT="305" WIDTH="100%" SRC="files.php?type=<? echo $type ?>&passdir=<? echo $passdir ?>" NAME="filebrowse" ID="filebrowse"></IFRAME>
</TD> 
</TR> 
</TABLE>
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD COLSPAN="2">Archivo: <INPUT TYPE="TEXT" NAME="file_name" SIZE="60" ID="file_name"></TD> 
</TR> 
<TR> 
<TD><INPUT TYPE="BUTTON" ID="insert" NAME="insert" VALUE="Aceptar" ONCLICK="FileBrowse.close();"></TD> 
<TD><INPUT ID="cancel" TYPE="button" name="cancel" VALUE="Cancelar" ONCLICK="tinyMCEPopup.close();" ></TD> 	   
</TR> 
</TABLE>
</DIV>
</FORM>
</BODY>
</HTML>
