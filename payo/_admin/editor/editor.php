<?php
require_once("include/config.inc.php");
require_once("include/functions.lib.php");
require_once("include/login_check.inc.php");
?>
<HTML>
<HEAD>
<TITLE>Editor de HTML</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
var campo =  window.opener.document.forms[0].elements["<?php echo $_GET['campo'] ?>"];
var htmlload = campo.value;
/*
var htmlload = campo.value.replace(/<(\w[^>]*) src="(?!http:)(?!ftp:)(?!javascript:)/gi, '<$1 SRC="../../' );
htmlload = htmlload.replace(/<(\w[^>]*) href="(?!http:)(?!ftp:)(?!javascript:)/gi, '<$1 HREF="../../' );
htmlload = htmlload.replace(/<(\w[^>]*) data="/gi, '<$1 data="../../' );
htmlload = htmlload.replace(/<param name="src" value="/gi, '<param name="src" value="../../' );
htmlload = htmlload.replace(/<param name="url" value="/gi, '<param name="url" value="../../' );
*/
//------------------------------------------------------------------------------
function submitForm() {
	var TextoSRC = new String(document.getElementById('eEditorArea').value);
	//campo.value = TextoSRC;
	//campo.value = TextoSRC.replace(/<?php echo addcslashes(BaseUri('admin/editor/'),'/')?>/gi,'');
	//campo.value = TextoSRC.replace(/\/femeba\//gi,'');

	campo.value = TextoSRC;
	self.close();
}
//------------------------------------------------------------------------------

	tinyMCE.init({
		// General options
		mode : "textareas",
		language : "es",

		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,restoredraft,|,cut,copy,paste,pastetext,pasteword,|,print,|,undo,redo,|,search,replace,|,code,|,preview",
		theme_advanced_buttons2 : "link,unlink,anchor,|,image,media,|,sub,sup,|,charmap,advhr,nonbreaking,|,visualchars,visualaid,removeformat,cleanup",
		theme_advanced_buttons3 : "tablecontrols,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,attribs",
		theme_advanced_buttons4 : "styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		relative_urls : false,
		convert_urls : true,

		theme_advanced_source_editor_height : 600,
		file_browser_callback : 'myFileBrowser',


		// Example content CSS (should be your site CSS)
		content_css : "../../style/style.css",

		// Drop lists for link/image/media/template dialogs
		//template_external_list_url : "lists/template_list.js",
		//external_link_list_url : "lists/link_list.js",
		//external_image_list_url : "lists/image_list.js",
		//media_external_list_url : "lists/media_list.js",


		setup : function(editor) {
			if (htmlload != ""){
				document.getElementById('eEditorArea').value=htmlload;
			}
			if(editor.id == "eEditorArea") {
				editor.onInit.add(function() {
					editor.execCommand("mceFullScreen");
				});
			}
		},

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}

	});

	function myFileBrowser (field_name, url, type, win) {
		var cmsURL = "filebrw.php";    // script URL - use an absolute path!
		if (cmsURL.indexOf("?") < 0) {
		  cmsURL = cmsURL + "?type=" + type;
		} else {
		  cmsURL = cmsURL + "&type=" + type;
		}
		tinyMCE.activeEditor.windowManager.open({
			file : cmsURL,
			title : 'Buscar archivos...',
			width : 720,
			height : 430,
			resizable : "yes",
			inline : "yes",
			close_previous : "no"
		}, {
			window : win,
			input : field_name
		});
		
		return false;
	}
</SCRIPT>
</HEAD>
<BODY BGCOLOR="#E6E6E6" LEFTMARGIN="2" TOPMARGIN="0">
<DIV ID="Loading" ALIGN="CENTER" STYLE="position:absolute;left:290px; top:100 px;display:block">
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0"> 
<TR>
<TD VALIGN="MIDDLE"><IMG SRC="images/progress.gif" BORDER="0" WIDTH="32" HEIGHT="32" ALT="Cargando"></TD><TD STYLE="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;" VALIGN="MIDDLE">Cargando...</TD>
</TR>
</TABLE>
</DIV>
<FORM NAME="RichTextEditor" ACTION="" METHOD="post" ONSUBMIT="submitForm();">
<DIV ID="eEditorDiv" STYLE="display:none">
<TEXTAREA ID="eEditorArea" NAME="eEditorArea" ROWS="40" COLS="80" STYLE="height: 400px; width: 90%"></TEXTAREA>
</DIV>
</FORM>
</BODY>
</HTML>

