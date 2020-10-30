<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!--
var mn_TopPosition;
var agt=navigator.userAgent.toLowerCase()
if (agt.indexOf('netscape')!=-1 ) {
	mn_TopPosition = 28;
} else if (agt.indexOf('firefox')!=-1 ) {
	mn_TopPosition = 28;
} else {
	mn_TopPosition = 35;
}

if(window.event + "" == "undefined") event = null;
function mn_f_PopUp(){return false};
function mn_f_PopDown(){return false};
popUp = mn_f_PopUp;
popDown = mn_f_PopDown;
//-->
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript1.2" TYPE="text/javascript">
<!--
mn_PG_MenuWidth = 150;
mn_PG_FontFamily = "Arial,sans-serif";
mn_PG_FontSize = 8;
mn_PG_FontBold = 0;
mn_PG_FontItalic = 0;
mn_PG_ItemPadding = 3;
mn_PG_BorderWidth = 2;
mn_PG_BorderStyle = "outset";
mn_PG_SeparatorSize = 2;

mn_PG_FontColor = "<?php echo $default->menu_fontcolor ?>";
mn_PG_FontColorOver = "<?php echo $default->menu_foncolor_over ?>";
mn_PG_BGColor = "<?php echo $default->menu_bgcolor ?>";
mn_PG_BGColorOver = "<?php echo $default->menu_bgcolor_over ?>";
mn_PG_BorderColor = "<?php echo $default->menu_bordercolor ?>";
mn_PG_SeparatorColor = "<?php echo $default->menu_separatorcolor ?>";

mn_PG_ImageSrc = "img/mn_b_right.gif";
mn_PG_ImageSrcLeft = "img/mn_b_left.gif";
mn_PG_ImageSrcOver = "img/mn_w_right.gif";
mn_PG_ImageSrcLeftOver = "img/mn_w_left.gif";

mn_PG_ImageSize = 5;
mn_PG_ImageHorizSpace = 0;
mn_PG_ImageVertSpace = 2;

mn_PG_KeepHilite = true; 
mn_PG_ClickStart = 0;
mn_PG_ClickKill = false;
mn_PG_ChildOverlap = 4;
mn_PG_ChildOffset = 0;
mn_PG_ChildPerCentOver = null;
mn_PG_TopSecondsVisible = .5;
mn_PG_StatusDisplayBuild =0;
mn_PG_StatusDisplayLink = 0;
mn_PG_UponDisplay = "mn_f_ToggleElementList(false,['select'],'tag')";
mn_PG_UponHide = "mn_f_ToggleElementList(true,['select'],'tag')";
mn_PG_RightToLeft = 0;

mn_PG_CreateTopOnly = 0;
mn_PG_ShowLinkCursor = 1;
mn_PG_NSFontOver = true;

//-->
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="./jscripts/mn_loader.js" TYPE="text/javascript"></SCRIPT>
<TABLE WIDTH="100%" BGCOLOR="<?php echo $default->border_color ?>" BORDER="0" CELLPADDING="0" CELLSPACING="1">
<TR><TD>
<TABLE WIDTH='100%' BORDER='0' CELLPADDING='1' CELLSPACING='1' BGCOLOR='<?php echo $default->title_bgcolor ?>'>
<TR><TD VALIGN='MIDDLE' ALIGN="LEFT"><STRONG STYLE="color: <?php echo $default->title_color ?>;"><?php echo $default->web_title ?></STRONG></TD>
<TD VALIGN="MIDDLE" ALIGN="RIGHT"><A HREF="index.php?authen=logout"><IMG SRC="img/close.gif" BORDER="0" ALT="Desconectarse"></A></TD></TR>
</TABLE>
</TD></TR>
<TR><TD>
<TABLE WIDTH='100%' BORDER='0' CELLPADDING='1' CELLSPACING='1' BGCOLOR='<?php echo $default->table_bgcolor ?>'>
<TR><TD VALIGN='MIDDLE'>&nbsp;&nbsp;<IMG SRC="img/b-separador-top.gif" HEIGHT="20" BORDER='0' ALIGN='absmiddle' ALT=''></TD>
<TD ALIGN="RIGHT" VALIGN="MIDDLE"><STRONG>[ <A HREF="<?php echo "$PHP_SELF?accion=misdatos&id=$_SESSION[uid]" ?>"><?php echo $_SESSION["user_name"]?></A> ]</STRONG></TD></TR></TABLE>
</TD></TR></TABLE>

