<?php
require_once("include/config.inc.php");
require_once("include/file.lib.php");
require_once("include/functions.lib.php");
require_once("include/login_check.inc.php");

$type = $_GET['type'];
$passdir = $_GET['passdir'];

if ($type!=""){
	if ($type=="image"){
		$accepted_files = array('images');
		$overwrite = $img_overwrite;
		$dirname = $img_dir;
		$max_size = $img_max_size;
	}
	if ($type=="media"){
		$accepted_files = array('adobe_flash','windows_media','real_media','mp3_media');
		$overwrite = $mm_overwrite;
		$dirname = $mm_dir;
		$max_size = $mm_max_size;
	}
	if ($type=="file"){
		$accepted_files = array('images','html','ms_excel','ms-word','ms_powerpoint','adobe_pdf','text');
		$overwrite = $file_overwrite;
		$dirname = $file_dir;
		$max_size = $file_max_size;
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML XMLNS="http://www.w3.org/1999/xhtml">
<HEAD> 
<TITLE>Selecci&oacute;n de Archivos</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<?php
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
		var f = document.forms[0], ed = tinyMCEPopup.editor, dom = ed.dom, n = ed.selection.getNode();;
		this.resetForm();
	},
	//-----------------------------------
	uploadFile : function () {
		var passdir = document.getElementById('passdir').value;
		var type = document.getElementById('type').value;
		var win = tinyMCEPopup.getWindowArg("window");
		var urlp = "upload.php?type=" + type + "&passdir=" + passdir;
		tinyMCEPopup.restoreSelection();
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
			objFunc: this
		});
		return;
	},
	//-----------------------------------
	deleteFile : function() {
		var u = document.getElementById('file_name').value;
		if (u==''){
			return;
		}
		var win = tinyMCEPopup.getWindowArg("window");
		var passdir = document.getElementById('passdir').value;
		var type = document.getElementById('type').value;
		var urlp = "delete.php?type=" + type + "&passdir=" + passdir + "&file2delete=" + u;
		tinyMCEPopup.restoreSelection();
		tinyMCE.activeEditor.windowManager.open({
			file : urlp,
			title : 'Eliminar archivo...',
			width : 400,
			height : 150,
			resizable : "no",
			inline : "no",
			close_previous : "no"
		}, {
			window: win,
			objFunc: this
		});
		return;
	},
	//-----------------------------------
	showPreviewImage : function(u) {
		if (!u) {
			tinyMCEPopup.dom.setHTML('prev', '');
			return;
		}
		u = tinyMCEPopup.editor.documentBaseURI.toAbsolute(u);
		tinyMCEPopup.dom.setHTML('prev', '<img id="fbpreviewImg" src="' + u + '" border="0" />');
	},
	//-----------------------------------
	showFile : function() {
		var win = tinyMCEPopup.getWindowArg("window");
		var u = document.getElementById('file_name').value;
		if (u==''){
			return;
		}
		u = tinyMCEPopup.editor.documentBaseURI.toAbsolute(u);
		win.open(u,'FileView');
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
	resetForm : function() {
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
			tinyMCEPopup.restoreSelection();
			var f = document.forms[0];
			var URL = f.file_name.value;
			var win = tinyMCEPopup.getWindowArg("window");
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
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
<INPUT TYPE="HIDDEN" ID="passdir" NAME="passdir" VALUE="<?php echo $_GET['passdir'] ?>">
<INPUT TYPE="HIDDEN" ID="type" NAME="type" VALUE="<?php echo $_GET['type'] ?>">
<DIV> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%"> 
<TR>
<TD ALIGN="LEFT">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="2">
<TR>
<TD>
<SPAN ID="uploadbutton" OnClick="javascript:FileBrowse.uploadFile();" CLASS="btnImage"><IMG SRC="images/add.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Subir Archivo"></SPAN>
</TD>
<TD>
<SPAN ID="uploadbutton" OnClick="javascript:FileBrowse.deleteFile();" CLASS="btnImage"><IMG SRC="images/delete.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Eliminar Archivo"></SPAN>
</TD>
<TD>
<SPAN ID="showbutton" OnClick="javascript:FileBrowse.showFile();" CLASS="btnImage"><IMG SRC="images/view.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Ver Archivo"></SPAN>
</TD>
<TD>
<SPAN CLASS="separator"></SPAN>
</TD>
<TD>
<SPAN ID="refreshbutton" OnClick="javascript:FileBrowse.refreshBrowse();" CLASS="btnImage"><IMG SRC="images/reload.png" BORDER="0" WIDTH="16" HEIGHT="16" ALT="Actualizar"></SPAN>
</TD>
</TR>
</TABLE>
</TD>
<TD ALIGN="RIGHT"></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="2" CELLSPACING="1"> 
<TR> 
<TD WIDTH="20%" VALIGN="TOP">
<FIELDSET><DIV ID="prev"></DIV></FIELDSET>
</TD> 
<TD WIDTH="80%">
<IFRAME HEIGHT="305" WIDTH="100%" SRC="files.php?type=<?php echo $_GET['type'] ?>&passdir=<?php echo $_GET['passdir'] ?>" NAME="filebrowse" ID="filebrowse"></IFRAME>
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
