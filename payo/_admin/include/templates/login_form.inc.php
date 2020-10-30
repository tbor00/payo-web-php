<SCRIPT LANGUAGE="Javascript1.2" SRC="jscripts/<?php echo $default->encode ?>/hash.js" TYPE="text/javascript"></SCRIPT>
<FORM METHOD="POST" ACTION="index.php" NAME="login_form" ONSUBMIT="return hash(this,'index.php')">
<INPUT TYPE="hidden" NAME="challenge" VALUE="<?php echo $_SESSION["challenge"] ?>">
<INPUT TYPE="hidden" NAME="authen" VALUE="login">
<INPUT TYPE="hidden" NAME="passwd" VALUE=""> 
<TABLE BGCOLOR="<?php echo $default->title_bgcolor ?>" BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="30%" ALIGN="CENTER"> 
<TR>
<TD><STRONG STYLE="color: <?php echo $default->title_color ?>">INGRESO</STRONG></TD>
</TR>
<TR>
<TD>
<TABLE BGCOLOR="<?php echo $default->title_bgcolor ?>" BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="100%"> 
<TR>
<TD BGCOLOR="<?php echo $default->dialog_bgcolor ?>" ALIGN="center"> 
<TABLE BORDER="0" CELLSPACING="6" CELLPADDING="6" BGCOLOR="<?php echo $default->dialog_bgcolor ?>" WIDTH="100%"> 
<TR BGCOLOR="#EEEEEE"><TD ALIGN="center"> 
<TABLE BORDER="0" CELLPADDING="4" CELLSPACING="0"> 
<TR>
<TD ALIGN="right"> 
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0"> 
<TR>
<TD ALIGN="right" NOWRAP="NOWRAP"><FONT FACE="arial" SIZE="-1">Usuario:</FONT></TD> 
<TD><INPUT NAME="login" SIZE="17" VALUE="" CLASS="boxes"></TD> 
</TR> 
<TR> 
<TD ALIGN="right" NOWRAP="NOWRAP"><FONT FACE="arial" SIZE="-1">Contrase&ntilde;a:</FONT></TD> 
<TD><INPUT NAME="npasswd" TYPE="password" SIZE="17" MAXLENGTH="32" CLASS="boxes"></TD>
</TR> 
<TR> 
<TD>&nbsp;</TD> 
<TD><INPUT CLASS="button" NAME=".save" TYPE="submit" VALUE="Ingresar"> </TD> 
</TR> 
</TABLE> 
</TD>
</TR> 
</TABLE> 
</TD>
</TR> 
</TABLE> 
</TD>
</TR> 
</TABLE> 
</TD>
</TR> 
</TABLE>
</FORM> 
<SCRIPT LANGUAGE="JavaScript1.2">
	document.login_form.login.focus();
</SCRIPT>
