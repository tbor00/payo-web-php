/*mn_scriptNS4.js
*/

mn_a_Parameters = [
	["MenuWidth",          150,		"number"],
	["FontFamily",         "Arial,sans-serif"],
	["FontSize",           10,		"number"],
	["FontBold",           false,	"boolean"],
	["FontItalic",         false,	"boolean"],
	["FontColor",          "black"],
	["FontColorOver",      "white"],
	["BGColor",            "white"],
	["BGColorOver",        "black"],
	["ItemPadding",        3,		"number"],
	["BorderWidth",        2,		"number"],
	["BorderColor",        "red"],
	["SeparatorSize",      1,		"number"],
	["ImageSrc",           "mn_More_black_right.gif"],
	["ImageSrcOver",       null],
	["ImageSrcLeft",       "mn_More_black_left.gif"],
	["ImageSrcLeftOver",   null],
	["ImageSize",          5,		"number"],
	["ImageHorizSpace",    0,		"number"],
	["ImageVertSpace",     0,		"number"],
	["KeepHilite",         false,	"boolean"],
	["ClickStart",         false,	"boolean"],
	["ClickKill",          true,	"boolean"],
	["ChildOverlap",       20,		"number"],
	["ChildOffset",        10,		"number"],
	["ChildPerCentOver",   null,	"number"],
	["TopSecondsVisible",  .5,		"number"],
	["ChildSecondsVisible",.3,		"number"],
	["StatusDisplayBuild", 1,		"boolean"],
	["StatusDisplayLink",  1,		"boolean"],
	["UponDisplay",        null,	"delayed"],
	["UponHide",           null,	"delayed"],
	["RightToLeft",        false,	"boolean"],
	["CreateTopOnly",      0,		"boolean"],
	["ShowLinkCursor",     false,	"boolean"],
	["NSFontOver",		   true,	"boolean"]
]

mn_MenuIDPrefix = "mn_Menu";
mn_ItemIDPrefix = "mn_Item";
mn_ArrayIDPrefix = "mn_Array";

Function.prototype.isFunction = true;
Function.prototype.isString = false;
String.prototype.isFunction = false;
String.prototype.isString = true;
String.prototype.isBoolean = false;
String.prototype.isNumber = false;
Number.prototype.isString = false;
Number.prototype.isFunction = false;
Number.prototype.isBoolean = false;
Number.prototype.isNumber = true;
Boolean.prototype.isString = false;
Boolean.prototype.isFunction = false;
Boolean.prototype.isBoolean = true;
Boolean.prototype.isNumber = false;
Array.prototype.itemValidation = false;
Array.prototype.isArray = true;


function mn_f_AssignParameters(paramarray){
	var ParamName = paramarray[0];
	var DefaultValue = paramarray[1];
	var FullParamName = "mn_" + ParamName;

	if (typeof eval("window.mn_PG_" + ParamName) == "undefined") {
		if (typeof eval("window.mn_GL_" + ParamName) == "undefined") {
			eval(FullParamName + "= DefaultValue");
		}
		else {
			eval(FullParamName + "= mn_GL_" + ParamName);
		}
	}
	else {
		eval(FullParamName + "= mn_PG_" + ParamName);
	}

	paramarray[0] = FullParamName;
	paramarray[1] = eval(FullParamName);
}

function mn_f_EvalParameters(valuenew,valueold,valuetype){
	var TestString, ParPosition;

	if(typeof valuenew == "undefined" || valuenew == null || (valuenew.isString && valuenew.length == 0)){
		return valueold;
	}

	if(valuetype != "delayed"){
		while(valuenew.isString) {
			ParPosition = valuenew.indexOf("(");
			if(ParPosition !=-1) {
				TestString = "window." + valuenew.substr(0,ParPosition);
				if (typeof eval(TestString) != "undefined" && eval(TestString).isFunction) {
					valuenew = eval(valuenew);
				}
			}
			else break
		}
	}

	while(valuenew.isFunction) {valuenew = valuenew()}

	switch(valuetype){	
		case "number":
			while (valuenew.isString) {valuenew = eval(valuenew)}
			break;
		case "boolean":
			while (!valuenew.isBoolean) {
				valuenew = (valuenew.isNumber) ? valuenew ? true : false : eval(valuenew);
			}
			break;
	}

	return valuenew;
}

for (i=0;i<mn_a_Parameters.length;i++) {
	mn_f_AssignParameters(mn_a_Parameters[i]);
	eval(mn_a_Parameters[i][0] + "= mn_f_EvalParameters("+ mn_a_Parameters[i][0] +",null,mn_a_Parameters[i][2])")
}

mn_ChildPerCentOver = (isNaN(parseFloat(mn_ChildPerCentOver))) ? null : parseFloat(mn_ChildPerCentOver)/100;

mn_ChildMilliSecondsVisible = mn_ChildSecondsVisible * 1000;

function mn_f_ValidateArray(arrayname){
	var MenuArrayIsValid = false;
	var MenuArrayIsObject = (typeof eval("window." + arrayname) == "object");
	if(MenuArrayIsObject) { 
		var TheMenuArray = eval(arrayname);
		if(TheMenuArray.isArray && TheMenuArray.length > 1) {
			MenuArrayIsValid = true;
			if(!TheMenuArray.itemValidation) {
				while((typeof TheMenuArray[TheMenuArray.length-1] != "object") || (!TheMenuArray[TheMenuArray.length-1].isArray)) {
					TheMenuArray.length--;
				}
				TheMenuArray.itemValidation = true;
			}
		}
	}
	return MenuArrayIsValid;
}

if(!window.mn_a_TreesToBuild) {
	mn_a_TreesToBuild = [];
	for(i=1; i<100; i++){
		if(mn_f_ValidateArray(mn_ArrayIDPrefix + i)) mn_a_TreesToBuild[mn_a_TreesToBuild.length] = i;
	}
}

mn_CurrentArray = null;
mn_CurrentTree  = null;
mn_CurrentMenu  = null;
mn_CurrentItem  = null;
mn_a_TopMenus = [];
mn_AreLoaded = false;
mn_AreCreated = false;
mn_BeingCreated = false;
mn_UserOverMenu = false;
mn_HideAllTimer = null;
mn_TotalTrees = 0;
mn_ZIndex = 5000;

function mn_f_Initialize() {
    if(mn_AreCreated) {
		for(var i=0; i<mn_TotalTrees; i++) {
			var TopMenu = mn_a_TopMenus[i];
			clearTimeout(TopMenu.hideTimer);
			TopMenu.hideTimer = null;
        }
        clearTimeout(mn_HideAllTimer);
    }
	mn_AreCreated = false;
	mn_BeingCreated = false;
	mn_UserOverMenu = false;
	mn_CurrentMenu = null;
	mn_HideAllTimer = null;
	mn_TotalTrees = 0;
	mn_a_TopMenus = [];
}

Layer.prototype.showIt = mn_f_ShowIt;
Layer.prototype.keepInWindow = mn_f_KeepInWindow;
Layer.prototype.hideTree = mn_f_HideTree
Layer.prototype.hideParents = mn_f_HideParents;
Layer.prototype.hideChildren = mn_f_HideChildren;
Layer.prototype.hideTop = mn_f_HideTop;
Layer.prototype.hideSelf = mn_f_HideSelf;
Layer.prototype.hasChildVisible = false;
Layer.prototype.isOn = false;
Layer.prototype.hideTimer = null;
Layer.prototype.currentItem = null;
Layer.prototype.itemSetup = mn_f_ItemSetup;
Layer.prototype.itemCount = 0;
Layer.prototype.child = null;
Layer.prototype.isWritten = false;

mn_NS_OrigWidth  = window.innerWidth;
mn_NS_OrigHeight = window.innerHeight;

window.onresize = function (){
    if (window.innerWidth == mn_NS_OrigWidth && window.innerHeight == mn_NS_OrigHeight) return;
    mn_f_Initialize();
	window.history.go(0);
}

function mn_f_StartIt() {
	if(mn_AreCreated) return;
	mn_AreLoaded = true;
	if (mn_ClickKill) {
		mn_f_OtherMouseDown = (document.onmousedown) ? document.onmousedown :  new Function;
		document.captureEvents(Event.MOUSEDOWN);
    	document.onmousedown = function(){mn_f_PageClick();mn_f_OtherMouseDown()}
    }
	else {
		mn_TopMilliSecondsVisible = mn_TopSecondsVisible * 1000;
	}
    mn_f_MakeTrees();
	mn_f_OtherOnLoad();
}

function mn_f_MakeTrees(){
    mn_BeingCreated = true;
	var TreeParams = null;
	var TreeHasChildren = false;
	var ItemArray = null;

	for(var t=0; t<mn_a_TreesToBuild.length; t++) {
		if(!mn_f_ValidateArray(mn_ArrayIDPrefix + mn_a_TreesToBuild[t])) continue;
		mn_CurrentArray = eval(mn_ArrayIDPrefix + mn_a_TreesToBuild[t]);

		TreeParams = mn_CurrentArray[0];
		TreeHasChildren = false;

		for(var i=1; i<mn_CurrentArray.length; i++) {
			ItemArray = mn_CurrentArray[i];
			if(ItemArray[ItemArray.length-1]) {TreeHasChildren = true; break}
		}

		mn_CurrentTree = {
			MenuWidth        : MenuWidth = mn_f_EvalParameters(TreeParams[0],mn_MenuWidth,"number"),
			MenuLeft         : MenuLeft = mn_f_EvalParameters(TreeParams[1],null,"delayed"),
			MenuTop          : MenuTop = mn_f_EvalParameters(TreeParams[2],null,"delayed"),
			ItemWidth        : ItemWidth = MenuWidth - (mn_BorderWidth*2),
			ItemTextWidth    : TreeHasChildren ? (ItemWidth - (mn_ImageSize + mn_ImageHorizSpace + mn_ItemPadding)) : ItemWidth,
			FontColor        : FontColor = mn_f_EvalParameters(TreeParams[3],mn_FontColor),
			FontColorOver    : FontColorOver = mn_f_EvalParameters(TreeParams[4],mn_FontColorOver),
			BGColor          : mn_f_EvalParameters(TreeParams[5],mn_BGColor),
			BGColorOver      : mn_f_EvalParameters(TreeParams[6],mn_BGColorOver),
			BorderColor      : mn_f_EvalParameters(TreeParams[7],mn_BorderColor),
			TopIsPermanent   : ((MenuLeft == null) || (MenuTop == null)) ? false : mn_f_EvalParameters(TreeParams[9],false,"boolean"),
			TopIsHorizontal  : TopIsHorizontal = mn_f_EvalParameters(TreeParams[10],false,"boolean"),
			TreeIsHorizontal : TreeHasChildren ? mn_f_EvalParameters(TreeParams[11],false,"boolean") : false,
			PositionUnder    : (!TopIsHorizontal || !TreeHasChildren) ? false : mn_f_EvalParameters(TreeParams[12],false,"boolean"),
			TopImageShow     : TreeHasChildren ? mn_f_EvalParameters(TreeParams[13],true,"boolean")  : false,
			TreeImageShow    : TreeHasChildren ? mn_f_EvalParameters(TreeParams[14],true,"boolean")  : false,
			UponDisplay      : mn_f_EvalParameters(TreeParams[15],mn_UponDisplay,"delayed"),
			UponHide         : mn_f_EvalParameters(TreeParams[16],mn_UponHide,"delayed"),
			RightToLeft      : mn_f_EvalParameters(TreeParams[17],mn_RightToLeft,"boolean"),
			NSFontOver		 : mn_NSFontOver ? (FontColor != FontColorOver) : false,
			ClickStart		 : mn_f_EvalParameters(TreeParams[18],mn_ClickStart,"boolean"),
			TopIsVariableWidth  : mn_f_EvalParameters(TreeParams[19],false,"boolean"),
			TreeIsVariableWidth  : mn_f_EvalParameters(TreeParams[20],false,"boolean")
		}

		mn_CurrentMenu = null;
		mn_f_MakeMenu(mn_a_TreesToBuild[t]);
		mn_a_TopMenus[mn_TotalTrees] = mn_CurrentTree.treeParent;
		mn_TotalTrees++;
		if(mn_CurrentTree.TopIsPermanent){
			with(mn_CurrentTree.treeParent) {
				moveTo(eval(mn_CurrentTree.MenuLeft),eval(mn_CurrentTree.MenuTop));
				zIndex = mn_ZIndex;
				visibility = "show";
			}
		}
    }

	if(mn_StatusDisplayBuild) status = mn_TotalTrees + " Hierarchical Menu Trees Created";
    mn_AreCreated = true;
    mn_BeingCreated = false;
}

function mn_f_GetItemHtmlStr(arraystring){
	var TempString = arraystring;
	if (mn_FontBold) TempString = TempString.bold();
	if (mn_FontItalic) TempString = TempString.italics();
	TempString = "<FONT FACE='" + mn_FontFamily + "' POINT-SIZE=" + mn_FontSize + ">" + TempString + "</FONT>";
	var TempStringOver = TempString.fontcolor(mn_CurrentTree.FontColorOver);
	TempString = TempString.fontcolor(mn_CurrentTree.FontColor);
	return [TempString,TempStringOver];
}

function mn_f_MakeMenu(menucount) {
	if(!mn_f_ValidateArray(mn_ArrayIDPrefix + menucount)) return false;
	mn_CurrentArray = eval(mn_ArrayIDPrefix + menucount);

	NewMenu = eval("window." + mn_MenuIDPrefix + menucount);
	if(!NewMenu) {
		eval(mn_MenuIDPrefix + menucount + " = new Layer(mn_CurrentTree.MenuWidth,window)");
		NewMenu = eval(mn_MenuIDPrefix + menucount);
	
		if(mn_CurrentMenu) {
			NewMenu.parentMenu = mn_CurrentMenu;
			NewMenu.parentItem = mn_CurrentItem;
			NewMenu.parentItem.child = NewMenu;
			NewMenu.hasParent = true;
			NewMenu.isHorizontal = mn_CurrentTree.TreeIsHorizontal;
			NewMenu.showImage = mn_CurrentTree.TreeImageShow;
		}
		else {
			NewMenu.isHorizontal = mn_CurrentTree.TopIsHorizontal;
			NewMenu.showImage = mn_CurrentTree.TopImageShow;
		}
	
		mn_CurrentMenu = NewMenu;
		mn_CurrentMenu.count = menucount;
		mn_CurrentMenu.tree  = mn_CurrentTree;
		mn_CurrentMenu.array = mn_CurrentArray;
		mn_CurrentMenu.maxItems = mn_CurrentArray.length - 1;
		mn_CurrentMenu.bgColor = mn_CurrentTree.BorderColor;
		mn_CurrentMenu.IsVariableWidth = ((mn_CurrentMenu.hasParent && mn_CurrentTree.TreeIsVariableWidth) || (!mn_CurrentMenu.hasParent && mn_CurrentTree.TopIsVariableWidth));
	    mn_CurrentMenu.onmouseover = mn_f_MenuOver;
	    mn_CurrentMenu.onmouseout = mn_f_MenuOut;
		mn_CurrentMenu.moveTo(0,0);
	}

	if(!mn_CurrentTree.treeParent) mn_CurrentTree.treeParent = mn_CurrentTree.startChild = mn_CurrentMenu;

	while (mn_CurrentMenu.itemCount < mn_CurrentMenu.maxItems) {
		mn_CurrentMenu.itemCount++;
		mn_CurrentItem = eval("window." + mn_ItemIDPrefix + menucount + "_" + mn_CurrentMenu.itemCount);
		if(!mn_CurrentItem) {
			eval(mn_ItemIDPrefix + menucount + "_" + mn_CurrentMenu.itemCount + " = new Layer(mn_CurrentTree.ItemWidth - (mn_ItemPadding*2),mn_CurrentMenu)")
			if(mn_StatusDisplayBuild) status = "Creating Hierarchical Menus: " + menucount + " / " + mn_CurrentMenu.itemCount;
			mn_CurrentItem = eval(mn_ItemIDPrefix + menucount + "_" + mn_CurrentMenu.itemCount);
			mn_CurrentItem.itemSetup(menucount + "_" + mn_CurrentMenu.itemCount);
		}
		if(mn_CurrentItem.hasMore && (!mn_CreateTopOnly || mn_AreCreated && mn_CreateTopOnly)) {
	       	MenuCreated = mn_f_MakeMenu(menucount + "_" + mn_CurrentMenu.itemCount);
           	if(MenuCreated) {
				mn_CurrentMenu =  mn_CurrentMenu.parentMenu;
				mn_CurrentArray = mn_CurrentMenu.array;
			}
		}
    }
	mn_CurrentMenu.itemCount = 0;
	if (mn_CurrentMenu.isHorizontal) {
	    mn_CurrentMenu.clip.right = mn_CurrentMenu.lastItem.left + mn_CurrentMenu.lastItem.clip.right + mn_BorderWidth;
	}
	else {
	    mn_CurrentMenu.clip.right = mn_CurrentMenu.lastItem.clip.width + (mn_BorderWidth*2);
	}
    mn_CurrentMenu.clip.bottom = mn_CurrentMenu.lastItem.top + mn_CurrentMenu.lastItem.clip.bottom + mn_BorderWidth;
	return mn_CurrentMenu;
}

function mn_f_ItemSetup(itemidsuffix) {
	this.menu = mn_CurrentMenu;
	this.tree = mn_CurrentTree;
	this.index = mn_CurrentMenu.itemCount - 1;
	this.array = mn_CurrentArray[mn_CurrentMenu.itemCount];
	this.dispText = this.array[0];
	this.linkText = this.array[1];
	this.permHilite  = mn_f_EvalParameters(this.array[3],false,"boolean");
	this.hasRollover = (!this.permHilite && mn_f_EvalParameters(this.array[2],true,"boolean"));
	this.hasMore	 = mn_f_EvalParameters(this.array[4],false,"boolean") && mn_f_ValidateArray(mn_ArrayIDPrefix + itemidsuffix);
	var HtmlStrings = mn_f_GetItemHtmlStr(this.dispText);
	this.htmStr = HtmlStrings[0];
	this.htmStrOver = HtmlStrings[1];
	this.visibility = "inherit";
    this.onmouseover = mn_f_ItemOver;
	this.onmouseout  = mn_f_ItemOut;
	this.menu.lastItem = this;
	this.showChild = mn_f_ShowChild;

	this.ClickStart = this.hasMore && this.tree.ClickStart && (this.tree.TopIsPermanent && (this.tree.treeParent==this.menu));
	if(this.ClickStart) {
		this.captureEvents(Event.MOUSEUP);
		this.onmouseup = this.showChild;
		this.linkText = "";
	}
	else {
	    if (this.linkText) {
			this.captureEvents(Event.MOUSEUP);
			this.onmouseup = mn_f_LinkIt;
	    }
	}

	this.txtLyrOff = new Layer(mn_CurrentTree.ItemTextWidth - (mn_ItemPadding*2),this);
	with(this.txtLyrOff) {
		document.write(this.permHilite ? this.htmStrOver : this.htmStr);
		document.close();
		if (mn_CurrentTree.RightToLeft && this.menu.showImage && (!this.menu.isHorizontal || (this.menu.isHorizontal && (!this.menu.IsVariableWidth || (this.menu.IsVariableWidth && this.hasMore))) )) left = mn_ItemPadding + mn_ImageSize + mn_ImageHorizSpace;
		visibility = "inherit";
	}

	if(this.menu.IsVariableWidth){
		this.ItemTextWidth = this.txtLyrOff.document.width;
		this.ItemWidth = this.ItemTextWidth + (mn_ItemPadding*2);
		if(mn_CurrentMenu.showImage) {
			if(!this.menu.isHorizontal || this.hasMore) {
				this.ItemWidth += (mn_ItemPadding + mn_ImageSize + mn_ImageHorizSpace);
			}
		}
	}
	else {
		this.ItemWidth = this.tree.ItemWidth;
		this.ItemTextWidth = this.tree.ItemTextWidth;
	}
	if (this.menu.isHorizontal) {
    	if (this.index) this.left = this.siblingBelow.left + this.siblingBelow.clip.width + mn_SeparatorSize;
		else this.left = (mn_BorderWidth + mn_ItemPadding);
		this.top = (mn_BorderWidth + mn_ItemPadding);
	}
	else {
		this.left = (mn_BorderWidth + mn_ItemPadding);
	    if (this.index) this.top = this.siblingBelow.top + this.siblingBelow.clip.height + mn_SeparatorSize;
    	else this.top = (mn_BorderWidth + mn_ItemPadding)
	}
    this.clip.top = this.clip.left = -mn_ItemPadding;
    this.clip.right = this.ItemWidth - mn_ItemPadding;
	this.bgColor = this.permHilite ? this.tree.BGColorOver : this.tree.BGColor;

	if(this.tree.NSFontOver) {
		if(!this.permHilite){
			this.txtLyrOn = new Layer(this.ItemTextWidth,this);
			with(this.txtLyrOn) {
				if (mn_CurrentTree.RightToLeft && this.menu.showImage && (!this.menu.isHorizontal || (this.menu.isHorizontal && (!this.menu.IsVariableWidth || (this.menu.IsVariableWidth && this.hasMore))) ))  left = mn_ItemPadding + mn_ImageSize + mn_ImageHorizSpace;
				visibility = "hide";
			}
		}
	}

	this.fullClip = this.txtLyrOff.document.height + (mn_ItemPadding * 2);
	if(this.menu.isHorizontal) {
		if(this.index) {
			var SiblingHeight = this.siblingBelow.clip.height;
			this.fullClip = Math.max(SiblingHeight,this.fullClip);
			if(this.fullClip > SiblingHeight) {
				var SiblingPrevious = this.siblingBelow;
				while(SiblingPrevious != null) {
					SiblingPrevious.clip.height = this.fullClip;
					SiblingPrevious = SiblingPrevious.siblingBelow;
				}
			}
		}
	}
	this.clip.height = this.fullClip;

	if(!this.menu.isHorizontal && this.menu.IsVariableWidth) {
		this.fullWidth = this.clip.width;
		if(this.index) {
			var SiblingWidth = this.siblingBelow.clip.width;
			this.fullWidth = Math.max(SiblingWidth,this.fullWidth);
			SiblingPrevious = this.siblingBelow;
			while(SiblingPrevious != null) {
				SiblingPrevious.clip.width = this.fullWidth;
				SiblingPrevious.dummyLyr.clip.width = this.fullWidth;
				if(SiblingPrevious.hasMore) {
					SiblingPrevious.DistanceToRightEdge = SiblingPrevious.clip.right + SiblingPrevious.WhatsOnRight;
					SiblingPrevious.DistanceToLeftEdge = mn_ItemPadding + SiblingPrevious.WhatsOnLeft;
					SiblingPrevious.CompleteWidth = SiblingPrevious.ItemWidth + SiblingPrevious.WhatsOnLeft + SiblingPrevious.WhatsOnRight;
					SiblingPrevious.ChildOverlap = (parseInt((mn_ChildPerCentOver != null) ? (mn_ChildPerCentOver  * SiblingPrevious.CompleteWidth) : mn_ChildOverlap));
				}
				if(SiblingPrevious.imgLyr && !mn_CurrentTree.RightToLeft) {
					SiblingPrevious.imgLyr.left = this.fullWidth - (mn_ItemPadding * 2) - mn_ImageSize - mn_ImageHorizSpace;
				}
				SiblingPrevious = SiblingPrevious.siblingBelow;
			}
		}
		this.clip.width = this.fullWidth;
	}

	this.dummyLyr = new Layer(100,this);
	with(this.dummyLyr) {
		left = top = -mn_ItemPadding;
		clip.width = this.clip.width;
		clip.height = this.clip.height;
		visibility = "inherit";
	}

	if(this.hasMore && mn_CurrentMenu.showImage) {
		this.imageSrc = this.tree.RightToLeft ? mn_ImageSrcLeft : mn_ImageSrc;
		this.hasImageRollover = ((!this.tree.RightToLeft && mn_ImageSrcOver) || (this.tree.RightToLeft && mn_ImageSrcLeftOver));
		if(this.hasImageRollover) {
			this.imageSrcOver = this.tree.RightToLeft ? mn_ImageSrcLeftOver : mn_ImageSrcOver;
			if(this.permHilite) this.imageSrc = this.imageSrcOver;
		}
		this.imgLyr = new Layer(mn_ImageSize,this);

		with(this.imgLyr) {
			document.write("<IMG SRC='" + this.imageSrc + "' WIDTH=" + mn_ImageSize + " VSPACE=0 HSPACE=0 BORDER=0>");
			document.close();
			moveBelow(this.txtLyrOff);
			left = (mn_CurrentTree.RightToLeft) ? mn_ImageHorizSpace : this.ItemWidth - (mn_ItemPadding * 2) - mn_ImageSize - mn_ImageHorizSpace;
			top = mn_ImageVertSpace;
			visibility = "inherit";
		}
		this.imageElement = this.imgLyr.document.images[0];
	}

	if(this.hasMore) {
		this.WhatsOnRight = (!this.menu.isHorizontal || (this.menu.isHorizontal && this.isLastItem)) ?  mn_BorderWidth : mn_SeparatorSize;
		this.DistanceToRightEdge = this.clip.right + this.WhatsOnRight;
		this.WhatsOnLeft = (!this.menu.isHorizontal || (this.menu.isHorizontal && this.index==0)) ? mn_BorderWidth : mn_SeparatorSize;
		this.DistanceToLeftEdge = mn_ItemPadding + this.WhatsOnLeft;
		this.CompleteWidth = this.ItemWidth + this.WhatsOnLeft + this.WhatsOnRight;
		this.ChildOverlap = (parseInt((mn_ChildPerCentOver != null) ? (mn_ChildPerCentOver  * this.CompleteWidth) : mn_ChildOverlap));
	}
}

function mn_f_PopUp(menuname,e){
    if (!mn_AreLoaded) return;
	menuname = menuname.replace("elMenu",mn_MenuIDPrefix);
	var TempMenu = eval("window."+menuname);
	if(!TempMenu)return;
	mn_CurrentMenu = TempMenu;
	if (mn_CurrentMenu.tree.ClickStart) {
		var ClickElement = e.target;
		ClickElement.onclick = mn_f_PopMenu;
    }
	else mn_f_PopMenu(e);
}

function mn_f_PopMenu(e){
    if (!mn_AreLoaded || !mn_AreCreated) return true;
    if (mn_CurrentMenu.tree.ClickStart && e.type != "click") return true;
    mn_f_HideAll();
    mn_CurrentMenu.hasParent = false;
	mn_CurrentMenu.tree.startChild = mn_CurrentMenu;
	var mouse_x_position = e.pageX;
	var mouse_y_position = e.pageY;
	mn_CurrentMenu.xPos = (mn_CurrentMenu.tree.MenuLeft!=null) ? eval(mn_CurrentMenu.tree.MenuLeft) : mouse_x_position;
	mn_CurrentMenu.yPos = (mn_CurrentMenu.tree.MenuTop!=null)  ? eval(mn_CurrentMenu.tree.MenuTop)  : mouse_y_position;

    mn_CurrentMenu.keepInWindow();
    mn_CurrentMenu.moveTo(mn_CurrentMenu.xPos,mn_CurrentMenu.yPos);
    mn_CurrentMenu.isOn = true;
    mn_CurrentMenu.showIt(true);
    return false;
}

function mn_f_MenuOver() {
	if(!this.tree.startChild){this.tree.startChild = this}
	if(this.tree.startChild == this) mn_f_HideAll(this)
    this.isOn = true;
    mn_UserOverMenu = true;
    mn_CurrentMenu = this;
    if (this.hideTimer) clearTimeout(this.hideTimer);
}

function mn_f_MenuOut() {
    this.isOn = false;
    mn_UserOverMenu = false;
    if(mn_StatusDisplayLink) status = "";
    if(!mn_ClickKill) {
		clearTimeout(mn_HideAllTimer);
		mn_HideAllTimer = null;
		mn_HideAllTimer = setTimeout("mn_CurrentMenu.hideTree()",mn_ChildMilliSecondsVisible);
	}
}

function mn_f_ShowChild(){
	if(!this.child) {
		mn_CurrentTree = this.tree;
		mn_CurrentMenu = this.menu;
		mn_CurrentItem = this;
		this.child = mn_f_MakeMenu(this.menu.count + "_"+(this.index+1));
		this.tree.treeParent = this.menu;
		this.tree.startChild = this.menu;
	}
	if (this.tree.PositionUnder && (this.menu == this.tree.treeParent)) {
		this.child.xPos = this.pageX + this.clip.left - mn_BorderWidth;
		this.child.yPos = this.menu.top + this.menu.clip.height - mn_BorderWidth;
	}
	else {
		this.oL = this.pageX;
		this.child.offsetWidth = this.child.clip.width;
		this.oT = this.pageY + this.clip.top - mn_BorderWidth;
		if(this.tree.RightToLeft) {
			this.child.xPos = ((this.oL - this.DistanceToLeftEdge) + this.ChildOverlap) - this.child.offsetWidth;
		}
		else {		
			this.child.xPos = (this.oL + this.DistanceToRightEdge) - this.ChildOverlap;
		}
		this.child.yPos = this.oT + mn_ChildOffset + mn_BorderWidth;
	}
	if(!this.tree.PositionUnder || this.menu!=this.tree.treeParent) this.child.keepInWindow();
	this.child.moveTo(this.child.xPos,this.child.yPos);
	this.menu.hasChildVisible = true;
	this.menu.visibleChild = this.child;
	this.child.showIt(true);
}

function mn_f_ItemOver(){
    if (mn_KeepHilite) {
        if (this.menu.currentItem && this.menu.currentItem != this && this.menu.currentItem.hasRollover) {
            with(this.menu.currentItem){
				bgColor = this.tree.BGColor;
				if(this.tree.NSFontOver) {
    	    	    txtLyrOff.visibility = "inherit";
					txtLyrOn.visibility = "hide";
				}
			}
			if(this.menu.currentItem.hasImageRollover)this.menu.currentItem.imageElement.src = this.menu.currentItem.imageSrc;
        }
    }
	if(this.hasRollover) {
	    this.bgColor = this.tree.BGColorOver;
		if(this.tree.NSFontOver) {
			if(!this.txtLyrOn.isWritten){
				this.txtLyrOn.document.write(this.htmStrOver);
				this.txtLyrOn.document.close();
				this.txtLyrOn.isWritten = true;
			}
			this.txtLyrOff.visibility = "hide";
			this.txtLyrOn.visibility = "inherit";
		}
		if(this.hasImageRollover)this.imageElement.src = this.imageSrcOver;
	}

    if(mn_StatusDisplayLink) status = this.linkText;
    this.menu.currentItem = this;
	if (this.menu.hasChildVisible) {
		if(this.menu.visibleChild == this.child && this.menu.visibleChild.hasChildVisible) this.menu.visibleChild.hideChildren(this);
		else this.menu.hideChildren(this);
    }

    if (this.hasMore && !this.ClickStart) this.showChild();
}

function mn_f_ItemOut() {
    if ( (!mn_KeepHilite || ((this.tree.TopIsPermanent && (this.tree.treeParent==this)) && !this.menu.hasChildVisible)) && this.hasRollover) {
		with(this){
			bgColor = this.tree.BGColor;
			if(this.tree.NSFontOver) {
				txtLyrOff.visibility = "inherit";
				txtLyrOn.visibility = "hide";
			}
			if(this.hasImageRollover)this.imageElement.src = this.imageSrc;
		}
    }
	if(!mn_ClickKill && !mn_UserOverMenu) {
		clearTimeout(mn_HideAllTimer);
		mn_HideAllTimer = null;
        mn_HideAllTimer = setTimeout("mn_CurrentMenu.hideTree()",mn_ChildMilliSecondsVisible);
    }
}

function mn_f_ShowIt(on) {
	if (!(this.tree.TopIsPermanent && (this.tree.treeParent==this))) {
		if(!this.hasParent || (this.hasParent && this.tree.TopIsPermanent && (this.tree.treeParent==this.parentMenu)    )) {
			if (on == this.hidden)
				eval(on ? this.tree.UponDisplay : this.tree.UponHide)
		}
		if (on) this.zIndex = ++mn_ZIndex;
		this.visibility = on ? "show" : "hide";
	}
    if (mn_KeepHilite && this.currentItem && this.currentItem.hasRollover) {
        with(this.currentItem){
			bgColor = this.tree.BGColor;
			if(this.tree.NSFontOver) {
				txtLyrOff.visibility = "inherit";
				txtLyrOn.visibility = "hide";
			}
		}
		if(this.currentItem.hasImageRollover)this.currentItem.imageElement.src = this.currentItem.imageSrc;
	}
    this.currentItem = null;
}

function mn_f_KeepInWindow() {
    var ExtraSpace     = 10;
	var WindowLeftEdge = window.pageXOffset;
	var WindowTopEdge  = window.pageYOffset;
	var WindowWidth    = window.innerWidth;
	var WindowHeight   = window.innerHeight;
	var WindowRightEdge  = (WindowLeftEdge + WindowWidth) - ExtraSpace;
	var WindowBottomEdge = (WindowTopEdge + WindowHeight) - ExtraSpace;

	var MenuLeftEdge = this.xPos;
	var MenuRightEdge = MenuLeftEdge + this.clip.width;
	var MenuBottomEdge = this.yPos + this.clip.height;

	if (this.hasParent) {
		var ParentLeftEdge = this.parentItem.pageX;
		this.offsetWidth = this.clip.width;
	}
	if (MenuRightEdge > WindowRightEdge) {
		if (this.hasParent) {
			this.xPos = ((ParentLeftEdge - this.parentItem.DistanceToLeftEdge) + this.parentItem.ChildOverlap) - this.offsetWidth;
		}
		else {
			dif = MenuRightEdge - WindowRightEdge;
			this.xPos -= dif;
		}
		this.xPos = Math.max(5,this.xPos);
	}

	if (MenuBottomEdge > WindowBottomEdge) {
		dif = MenuBottomEdge - WindowBottomEdge;
		this.yPos -= dif;
	}

	if (MenuLeftEdge < WindowLeftEdge) {
		if (this.hasParent) {
			this.xPos = (ParentLeftEdge + this.parentItem.DistanceToRightEdge) - this.parentItem.ChildOverlap;
			MenuRightEdge = this.xPos + this.offsetWidth;
			if(MenuRightEdge > WindowRightEdge) this.xPos -= (MenuRightEdge - WindowRightEdge);
		}
		else {this.xPos = 5}
	}       
}

function mn_f_LinkIt() {
    if (this.linkText.indexOf("javascript:")!=-1) eval(this.linkText)
    else {
		mn_f_HideAll();
		location.href = this.linkText;
	}
}

function mn_f_PopDown(menuname){
    if (!mn_AreLoaded || !mn_AreCreated) return;
	menuname = menuname.replace("elMenu",mn_MenuIDPrefix);
    var MenuToHide = eval("window."+menuname);
	if(!MenuToHide)return;
    MenuToHide.isOn = false;
    if (!mn_ClickKill) MenuToHide.hideTop();
}

function mn_f_HideAll(callingmenu) {
	for(var i=0; i<mn_TotalTrees; i++) {
        var TopMenu = mn_a_TopMenus[i].tree.startChild;
		if(TopMenu == callingmenu)continue
        TopMenu.isOn = false;
        if (TopMenu.hasChildVisible) TopMenu.hideChildren();
        TopMenu.showIt(false);
    }    
}

function mn_f_HideTree() { 
    mn_HideAllTimer = null;
    if (mn_UserOverMenu) return;
    if (this.hasChildVisible) this.hideChildren();
    this.hideParents();
}

function mn_f_HideTop() {
	TopMenuToHide = this;
    (mn_ClickKill) ? TopMenuToHide.hideSelf() : (this.hideTimer = setTimeout("TopMenuToHide.hideSelf()",mn_TopMilliSecondsVisible));
}

function mn_f_HideSelf() {
    this.hideTimer = null;
    if (!this.isOn && !mn_UserOverMenu) this.showIt(false);
}

function mn_f_HideParents() {
    var TempMenu = this;
    while(TempMenu.hasParent) {
        TempMenu.showIt(false);
        TempMenu.parentMenu.isOn = false;        
        TempMenu = TempMenu.parentMenu;
    }
    TempMenu.hideTop();
}

function mn_f_HideChildren(callingitem) {
    var TempMenu = this.visibleChild;
    while(TempMenu.hasChildVisible) {
        TempMenu.visibleChild.showIt(false);
        TempMenu.hasChildVisible = false;
        TempMenu = TempMenu.visibleChild;
    }

    if (!this.isOn || !callingitem.hasMore || this.visibleChild != callingitem.child) {
        this.visibleChild.showIt(false);
        this.hasChildVisible = false;
    }
}

function mn_f_PageClick() {
    if (!mn_UserOverMenu && mn_CurrentMenu!=null && !mn_CurrentMenu.isOn) mn_f_HideAll();
}

popUp = mn_f_PopUp;
popDown = mn_f_PopDown;

mn_f_OtherOnLoad = (window.onload) ? window.onload :  new Function;
window.onload = mn_f_StartIt;


//end