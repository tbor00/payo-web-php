/*mn_loader.js
*/
mn_DOM = (document.getElementById) ? true : false;
mn_NS4 = (document.layers) ? true : false;
mn_IE = (document.all) ? true : false;
mn_IE4 = mn_IE && !mn_DOM;
mn_Mac = (navigator.appVersion.indexOf("Mac") != -1);
mn_IE4M = mn_IE4 && mn_Mac;
//4.1
mn_Opera = (navigator.userAgent.indexOf("Opera")!=-1);
mn_Konqueror = (navigator.userAgent.indexOf("Konqueror")!=-1);
mn_IsMenu = !mn_Opera && !mn_Konqueror && !mn_IE4M && (mn_DOM || mn_NS4 || mn_IE4);
mn_BrowserString = mn_NS4 ? "NS4" : mn_DOM ? "DOM" : "IE4";

if(window.event + "" == "undefined") event = null;
function mn_f_PopUp(){return false};
function mn_f_PopDown(){return false};

popUp								= mn_f_PopUp;
popDown							= mn_f_PopDown;

mn_GL_MenuWidth				= 170;
mn_GL_FontFamily				= "Arial,sans-serif";
mn_GL_FontSize					= 10;
mn_GL_FontBold					= true;
mn_GL_FontItalic				= false;
mn_GL_FontColor				= "black";
mn_GL_FontColorOver			= "white";
mn_GL_BGColor					= "transparent";
mn_GL_BGColorOver				= "transparent";
mn_GL_ItemPadding				= 3;

mn_GL_BorderWidth				= 2;
mn_GL_BorderColor				= "red";
mn_GL_BorderStyle				= "solid";
mn_GL_SeparatorSize			= 2;
mn_GL_SeparatorColor			= "yellow";

mn_GL_ImageSrc					= "mn_More_black_right.gif";
mn_GL_ImageSrcLeft			= "mn_More_black_left.gif";

mn_GL_ImageSrcOver 			= "mn_More_white_right.gif";
mn_GL_ImageSrcLeftOver 		= "mn_More_white_left.gif";

mn_GL_ImageSize				= 5;
mn_GL_ImageHorizSpace		= 5;
mn_GL_ImageVertSpace			= 5;

mn_GL_KeepHilite				= false;
mn_GL_ClickStart				= false;
mn_GL_ClickKill				= 0;
mn_GL_ChildOverlap			= 40;
mn_GL_ChildOffset				= 10;
mn_GL_ChildPerCentOver		= null;
mn_GL_TopSecondsVisible		= .5;
mn_GL_ChildSecondsVisible	= .3;
mn_GL_StatusDisplayBuild	= 0;
mn_GL_StatusDisplayLink		= 0;
mn_GL_UponDisplay				= "mn_f_ToggleElementList(false,['select'],'tag')";
mn_GL_UponHide					= "mn_f_ToggleElementList(true,['select'],'tag')";

//mn_GL_RightToLeft			= true;
mn_GL_CreateTopOnly			= mn_NS4 ? true : false;
mn_GL_ShowLinkCursor			= true;

// the following function is included to illustrate the improved JS expression handling of
// the left_position and top_position parameters
// you may delete if you have no use for it
//-----------------------------------------------------------------------------------
function mn_f_CenterMenu(topmenuid) {
	var MinimumPixelLeft = 0;
	var TheMenu = mn_DOM ? document.getElementById(topmenuid) : mn_IE4 ? document.all(topmenuid) : eval("window." + topmenuid);
	var TheMenuWidth = mn_DOM ? parseInt(TheMenu.style.width) : mn_IE4 ? TheMenu.style.pixelWidth : TheMenu.clip.width;
	var TheWindowWidth = mn_IE ? document.body.clientWidth : window.innerWidth;
	return Math.max(parseInt((TheWindowWidth-TheMenuWidth) / 2),MinimumPixelLeft);
}
//-----------------------------------------------------------------------------------
function mn_f_ToggleElementList(show,elList,toggleBy) {
	if(!(mn_DOM||mn_IE||mn_NS4)) return true;
	if(mn_NS4&&(toggleBy=="tag")) return true;
	for(var i=0; i<elList.length; i++) {
		var ElementsToToggle = [];
		switch(toggleBy) {
			case "tag":
				ElementsToToggle = (mn_DOM) ? document.getElementsByTagName(elList[i]) : document.all.tags(elList[i]);
				break;
			case "id":
				ElementsToToggle[0] = (mn_DOM) ? document.getElementById(elList[i]) : (mn_IE) ? document.all(elList[i]) : document.layers[elList[i]];
				break;
		}
		for(var j=0; j<ElementsToToggle.length; j++) {
			var theElement = ElementsToToggle[j];
			if(!theElement) continue;
			if(mn_DOM||mn_IE) {
				theElement.style.visibility = show ? "inherit" : "hidden";
			} else {
				theElement.visibility = show ? "inherit" : "hide";
			}
		}
	}
	return true;
}
//-----------------------------------------------------------------------------------
function new_mnwin(url,width,heigth,status,resizable,scrollbars,center) {
	win1=window.open(url,"","width="+width+", height="+heigth+", status="+status+", resizable="+resizable+", scrollbars="+scrollbars);
	if (center=='yes'){
		win1.moveTo((screen.width-width)/2,(screen.height-heigth)/2);
	}
}
//-----------------------------------------------------------------------------------
function mn_f_newwindow(surl,swinname,swidth,sheight,sposition,sresizable,sscrollbars,smenubar,stoolbar,sstatus,sreturnwin) {
	var remote = null;
	if (sposition == 'center'){
		var altoPantalla = window.screen.height;
		var anchoPantalla = window.screen.width;
		var left = (anchoPantalla / 2) - (swidth / 2);
		var top = (altoPantalla / 2) - (sheight / 2);
		sposition = ',top='+top+',left='+left;
	}
	if (sresizable == '' || sresizable == null || sresizable != 'yes'){ sresizable = 'no'; }
	if (sscrollbars == '' || sscrollbars == null || sscrollbars != 'yes'){ sscrollbars = 'no'; }
	if (smenubar == '' || smenubar == null || smenubar != 'yes'){ smenubar = 'no'; }
	if (stoolbar == '' || stoolbar == null || stoolbar != 'yes'){ stoolbar = 'no'; }
	if (sstatus == '' || sstatus == null || sstatus != 'yes'){ sstatus = 'no'; }
	var args = 'width='+swidth+',height='+sheight+sposition+',resizable='+sresizable+',scrollbars='+sscrollbars+',menubar='+smenubar+',toolbar='+stoolbar+',status='+sstatus+',location=no,directories=no';
	remote = window.open(surl,swinname,args);
	if (remote != null) {
		if (remote.opener == null)
			remote.opener = self;
	}
	if (sreturnwin == true) { return remote; }
}
//-----------------------------------------------------------------------------------

if(mn_IsMenu) {
	document.write("<SCR" + "IPT LANGUAGE='JavaScript1.2' SRC='jscripts/mn_arrays.js' TYPE='text/javascript'><\/SCR" + "IPT>");
	document.write("<SCR" + "IPT LANGUAGE='JavaScript1.2' SRC='jscripts/mn_script"+ mn_BrowserString +".js' TYPE='text/javascript'><\/SCR" + "IPT>");
}

//end