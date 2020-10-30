<?php
echo "<HTML>\n";
echo "<HEAD>\n";
echo "<META NAME=\"ROBOTS\" CONTENT=\"NONE\">\n";
echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$default->encode\">\n";
echo "<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">\n";
echo "<META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">\n";
 echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
echo "<META NAME=\"Copyright\" CONTENT=\"Copyright 2004-2007 - Argxentia SRL\">\n";
echo "<META NAME=\"Author\" CONTENT=\"Argxentia (http://www.argxentia.com.ar)\">\n";
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"jscripts/".strtolower($default->encode)."/functions.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/".strtolower($default->encode)."/validator.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/helpers.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/jquery-2.1.1.min.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/jquery-ui.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/showModalDialog.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/ddsmoothmenu.js\" TYPE=\"text/javascript\"></SCRIPT>\n";
echo "<LINK HREF=\"favicon.ico\" REL=\"Shorcut Icon\" TYPE=\"image/x-icon\" />\n";
echo "<LINK HREF=\"favicon.ico\" REL=\"icon\" TYPE=\"image/x-icon\" />\n";
echo "<link href=\"styles/font-awesome/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "<link href=\"//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"styles/style.css\">\n";
echo "<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"styles/jquery-ui.css\">\n";
echo "<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"styles/ddsmoothmenu.css\">\n";
echo "<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"styles/ddsmoothmenu-v.css\">\n";
if (!isset($glo_title)) {
  $glo_title=$default->web_title; 
}
echo "<TITLE>$glo_title</TITLE>\n";
echo "</HEAD>\n";
if (!isset($glo_onload)) {
	$loc_ONLOAD = "";
} else {
	$loc_ONLOAD = " onload=\"" . $glo_onload . "\"";
	$glo_onload = "";
}
echo "<BODY BGCOLOR=\"$default->body_bgcolor\" $loc_ONLOAD BACKGROUND=\"$default->body_bg\">\n";
?>
