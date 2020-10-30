/*mn_scriptIE4.js
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
	["BorderStyle",        "solid"],
	["SeparatorSize",      1,		"number"],
	["SeparatorColor",     "yellow"],
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
	["ShowLinkCursor",     false,	"boolean"]
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

function propertyTransfer(){
	this.obj = eval(this.id + "Obj");
	for (temp in this.obj) {this[temp] = this.obj[temp]}
}

function mn_f_StartIt() {
	if((typeof(document.body) == "undefined") || (document.body == null)) return;
	if(mn_AreCreated) return;
	mn_AreLoaded = true;
	if (mn_ClickKill) {
		mn_f_OtherMouseDown = (document.onmousedown) ? document.onmousedown : new Function;
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
			ItemWidth        : MenuWidth - (mn_BorderWidth*2),
			FontColor        : mn_f_EvalParameters(TreeParams[3],mn_FontColor),
			FontColorOver    : mn_f_EvalParameters(TreeParams[4],mn_FontColorOver),
			BGColor          : mn_f_EvalParameters(TreeParams[5],mn_BGColor),
			BGColorOver      : mn_f_EvalParameters(TreeParams[6],mn_BGColorOver),
			BorderColor      : mn_f_EvalParameters(TreeParams[7],mn_BorderColor),
			SeparatorColor   : mn_f_EvalParameters(TreeParams[8],mn_SeparatorColor),
			TopIsPermanent   : ((MenuLeft == null) || (MenuTop == null)) ? false : mn_f_EvalParameters(TreeParams[9],false,"boolean"),
			TopIsHorizontal  : TopIsHorizontal = mn_f_EvalParameters(TreeParams[10],false,"boolean"),
			TreeIsHorizontal : TreeHasChildren ? mn_f_EvalParameters(TreeParams[11],false,"boolean") : false,
			PositionUnder    : (!TopIsHorizontal || !TreeHasChildren) ? false : mn_f_EvalParameters(TreeParams[12],false,"boolean"),
			TopImageShow     : TreeHasChildren ? mn_f_EvalParameters(TreeParams[13],true,"boolean")  : false,
			TreeImageShow    : TreeHasChildren ? mn_f_EvalParameters(TreeParams[14],true,"boolean")  : false,
			UponDisplay      : mn_f_EvalParameters(TreeParams[15],mn_UponDisplay,"delayed"),
			UponHide         : mn_f_EvalParameters(TreeParams[16],mn_UponHide,"delayed"),
			RightToLeft      : mn_f_EvalParameters(TreeParams[17],mn_RightToLeft,"boolean"),
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
				mn_CurrentTree.treeParent.xPos = eval(mn_CurrentTree.MenuLeft);
				mn_CurrentTree.treeParent.yPos = eval(mn_CurrentTree.MenuTop);
				moveTo(mn_CurrentTree.treeParent.xPos,mn_CurrentTree.treeParent.yPos);
				style.zIndex = mn_ZIndex;
				setTimeout(mn_CurrentTree.treeParent.id + ".fixSize(true)",10);
			}
		}
    }

	if(mn_StatusDisplayBuild) status = mn_TotalTrees + " Hierarchical Menu Trees Created";
    mn_AreCreated = true;
    mn_BeingCreated = false;
}

function mn_f_GetItemDivStr(itemid,disptext,hasmore){
	var WidthValue = mn_CurrentMenu.isHorizontal ? (ItemElement.isLastItem) ? (mn_CurrentTree.MenuWidth - mn_BorderWidth - mn_SeparatorSize) : (mn_CurrentTree.MenuWidth - mn_BorderWidth) : mn_CurrentTree.ItemWidth;
	var TempString = "<DIV ID=" + itemid + " STYLE='position:absolute;width:" + WidthValue + "px'>";
	if(mn_CurrentMenu.showImage) {
		var FullPadding  = (mn_ItemPadding*2) + mn_ImageSize + mn_ImageHorizSpace;
	}
    if(hasmore && mn_CurrentMenu.showImage) {
		var ImgSrc      = mn_CurrentTree.RightToLeft ? mn_ImageSrcLeft : mn_ImageSrc;
		var ImgStyle    = "top:"+ (mn_ItemPadding + mn_ImageVertSpace) + "px;width:"+ mn_ImageSize + "px;";
		var ImgString   = "<IMG ID='mn_ImMore' STYLE='position:absolute;"+ ImgStyle +"' SRC='" + ImgSrc + "' HSPACE=0 VSPACE=0 BORDER=0>";
		TempString += ImgString;
	}
	TempString += disptext + "</DIV>";
	return TempString;
}

function mn_f_SetItemProperties(itemid,itemidsuffix) {
	this.tree        = mn_CurrentTree;
	this.itemsetup   = mn_f_ItemSetup;
	this.index       = mn_CurrentMenu.itemCount - 1;
	this.isLastItem  = (mn_CurrentMenu.itemCount == mn_CurrentMenu.maxItems);
	this.array		 = mn_CurrentMenu.array[mn_CurrentMenu.itemCount];
	this.dispText    = this.array[0];
	this.linkText    = this.array[1];
	this.permHilite  = mn_f_EvalParameters(this.array[3],false,"boolean");
	this.hasRollover = (!this.permHilite && mn_f_EvalParameters(this.array[2],true,"boolean"));
	this.hasMore	 = mn_f_EvalParameters(this.array[4],false,"boolean") && mn_f_ValidateArray(mn_ArrayIDPrefix + itemidsuffix);
	this.childID	 = this.hasMore ? (mn_MenuIDPrefix + itemidsuffix) : null;
	this.child		 = null;
    this.onmouseover = mn_f_ItemOver;
    this.onmouseout  = mn_f_ItemOut;
	this.setItemStyle = mn_f_SetItemStyle;
	this.itemStr	 = mn_f_GetItemDivStr(itemid,this.dispText,this.hasMore);
	this.showChild   = mn_f_ShowChild;
	this.ChildOverlap = null;
}

function mn_f_Make4ItemElement(menucount) {
	var ItemIDSuffix = menucount + "_" + mn_CurrentMenu.itemCount;
	var LayerID  = mn_ItemIDPrefix + ItemIDSuffix;
	var ObjectID = LayerID + "Obj";
	eval(ObjectID + " = new Object()");
	ItemElement = eval(ObjectID);
	ItemElement.setItemProperties = mn_f_SetItemProperties;
	ItemElement.setItemProperties(LayerID,ItemIDSuffix);
	return ItemElement;
}

function mn_f_MakeElement(menuid) {
	var MenuObject;
	var LayerID  = menuid;
	var ObjectID = LayerID + "Obj";
	eval(ObjectID + " = new Object()"); 
	MenuObject = eval(ObjectID);
	return MenuObject;
}

function mn_f_MakeMenu(menucount) {
	if(!mn_f_ValidateArray(mn_ArrayIDPrefix + menucount)) return false;
	mn_CurrentArray = eval(mn_ArrayIDPrefix + menucount);
	NewMenu = document.all(mn_MenuIDPrefix + menucount);
	if(!NewMenu) {
		NewMenu = mn_f_MakeElement(mn_MenuIDPrefix + menucount);
		NewMenu.array = mn_CurrentArray;
		NewMenu.tree  = mn_CurrentTree;

		if(mn_CurrentMenu) {
			NewMenu.parentMenu = mn_CurrentMenu;
			NewMenu.parentItem = mn_CurrentMenu.itemElement;
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
		mn_CurrentMenu.itemCount = 0;
		mn_CurrentMenu.maxItems = mn_CurrentMenu.array.length - 1;
		mn_CurrentMenu.showIt = mn_f_ShowIt;
		mn_CurrentMenu.keepInWindow = mn_f_KeepInWindow;
	    mn_CurrentMenu.onmouseover = mn_f_MenuOver;
	    mn_CurrentMenu.onmouseout = mn_f_MenuOut;
	    mn_CurrentMenu.hideTree = mn_f_HideTree
	    mn_CurrentMenu.hideParents = mn_f_HideParents;
	    mn_CurrentMenu.hideChildren = mn_f_HideChildren;
	    mn_CurrentMenu.hideTop = mn_f_HideTop;
	    mn_CurrentMenu.hideSelf = mn_f_HideSelf;
		mn_CurrentMenu.count = menucount;
	    mn_CurrentMenu.hasChildVisible = false;
	    mn_CurrentMenu.isOn = false;
	    mn_CurrentMenu.hideTimer = null;
	    mn_CurrentMenu.currentItem = null;
		mn_CurrentMenu.setMenuStyle = mn_f_SetMenuStyle;
		mn_CurrentMenu.sizeFixed = false;
		mn_CurrentMenu.fixSize = mn_f_FixSize;
		mn_CurrentMenu.onselectstart = mn_f_CancelSelect;
    	mn_CurrentMenu.moveTo = mn_f_MoveTo;
		mn_CurrentMenu.IsVariableWidth = ((mn_CurrentMenu.hasParent && mn_CurrentTree.TreeIsVariableWidth) || (!mn_CurrentMenu.hasParent && mn_CurrentTree.TopIsVariableWidth));
		mn_CurrentMenu.htmlString = "<DIV ID='" + mn_MenuIDPrefix + menucount +"' STYLE='position:absolute;visibility:hidden;width:"+ mn_CurrentTree.MenuWidth +"'>";
	}

	while (mn_CurrentMenu.itemCount < mn_CurrentMenu.maxItems) {
		mn_CurrentMenu.itemCount++;

		mn_CurrentItem = document.all(mn_ItemIDPrefix + menucount + "_" + mn_CurrentMenu.itemCount);
		if(!mn_CurrentItem) {
			if(mn_StatusDisplayBuild) status = "Creating Hierarchical Menus: " + menucount + " / " + mn_CurrentMenu.itemCount;
			mn_CurrentMenu.itemElement = mn_f_Make4ItemElement(menucount);
			mn_CurrentMenu.htmlString += mn_CurrentMenu.itemElement.itemStr;
		}
		if(mn_CurrentMenu.itemElement.hasMore && (!mn_CreateTopOnly || mn_AreCreated && mn_CreateTopOnly)) {
	        MenuCreated = mn_f_MakeMenu(menucount + "_" + mn_CurrentMenu.itemCount);
            if(MenuCreated) {
				mn_CurrentMenu = mn_CurrentMenu.parentMenu;
			}
        }
    }

	document.body.insertAdjacentHTML("BeforeEnd",mn_CurrentMenu.htmlString + "</DIV>");
	menuLyr = document.all(mn_MenuIDPrefix + menucount);
	menuLyr.propertyTransfer = propertyTransfer;
	menuLyr.propertyTransfer();
	mn_CurrentMenu = menuLyr;
	if(!mn_CurrentMenu.hasParent)mn_CurrentTree.treeParent = mn_CurrentTree.startChild = mn_CurrentMenu;
	mn_CurrentMenu.setMenuStyle();
    mn_CurrentMenu.items = mn_CurrentMenu.children;
	mn_CurrentMenu.lastItem = mn_CurrentMenu.items[mn_CurrentMenu.items.length-1];
    for(var i=0; i<mn_CurrentMenu.items.length; i++) {
        it = mn_CurrentMenu.items[i];
		it.siblingBelow = i>0 ? mn_CurrentMenu.items[i-1] : null;
		it.propertyTransfer = propertyTransfer;
		it.propertyTransfer();
		it.itemsetup(i+1);
	}
	mn_CurrentMenu.moveTo(0,0);
	return mn_CurrentMenu;
}

function mn_f_SetMenuStyle(){
	with(this.style) {
		borderWidth = mn_BorderWidth + "px";
		borderColor = mn_CurrentTree.BorderColor;
		borderStyle = mn_BorderStyle;
		overflow    = "hidden";
		cursor      = "default";
	}
}

function mn_f_SetItemStyle() {
	with(this.style){
		backgroundColor = (this.permHilite) ? mn_CurrentTree.BGColorOver : mn_CurrentTree.BGColor;
		color		= (this.permHilite) ? mn_CurrentTree.FontColorOver : mn_CurrentTree.FontColor;
		font		= ((mn_FontBold) ? "bold " : "normal ") + mn_FontSize + "pt " + mn_FontFamily;
		padding		= mn_ItemPadding + "px";
		fontStyle	= (mn_FontItalic) ? "italic" : "normal";
		overflow	= "hidden";
		pixelWidth	= mn_CurrentTree.ItemWidth;

		if((this.menu.showImage && (!this.menu.IsVariableWidth || (this.menu.IsVariableWidth && this.tree.RightToLeft && !this.menu.isHorizontal))) || (this.menu.IsVariableWidth && this.imgLyr)) {

			var FullPadding  = (mn_ItemPadding*2) + mn_ImageSize + mn_ImageHorizSpace;
			if (this.tree.RightToLeft) paddingLeft = FullPadding + "px";
			else paddingRight = FullPadding + "px";
		}
		if(!this.isLastItem) {
			var SeparatorString = mn_SeparatorSize + "px solid " + this.tree.SeparatorColor;
			if (this.menu.isHorizontal) borderRight = SeparatorString;
			else borderBottom = SeparatorString;
		}

		if(this.menu.isHorizontal){
			pixelTop = 0;
			if(this.menu.IsVariableWidth) {
				this.realWidth = this.scrollWidth;
				if(this.isLastItem) pixelWidth = this.realWidth;
				else pixelWidth = (this.realWidth + mn_SeparatorSize);
				pixelLeft = this.index ? (this.siblingBelow.style.pixelLeft + this.siblingBelow.style.pixelWidth) : 0;
				if(this.isLastItem) {
					LeftAndWidth = pixelLeft + pixelWidth;
					this.menu.style.pixelWidth = LeftAndWidth + (mn_BorderWidth * 2);
				}

			}
			else {
				if(this.isLastItem) pixelWidth = (mn_CurrentTree.MenuWidth - mn_BorderWidth - mn_SeparatorSize);
				else pixelWidth = (mn_CurrentTree.MenuWidth - mn_BorderWidth);
				pixelLeft = (this.index * (mn_CurrentTree.MenuWidth - mn_BorderWidth));
				var LeftAndWidth = pixelLeft + pixelWidth;
				this.menu.style.pixelWidth = LeftAndWidth + (mn_BorderWidth * 2);
			}
		}
		else {
			pixelLeft = 0;
		}
	}
}

function mn_f_FixSize(makevis){
	if(this.isHorizontal) {
		var MaxItemHeight = 0;
	    for(i=0; i<this.items.length; i++) {
	        var TempItem = this.items[i];
		    if (TempItem.index) {
				var SiblingHeight = TempItem.siblingBelow.scrollHeight;
				MaxItemHeight = Math.max(MaxItemHeight,SiblingHeight);
			}
	       	else{
				MaxItemHeight = TempItem.scrollHeight;
			}
		}
	    for(i=0; i<this.items.length; i++) {
	        var TempItem = this.items[i];
			TempItem.style.pixelHeight = MaxItemHeight;
			if(TempItem.imgLyr) {
				if(this.tree.RightToLeft){
					TempItem.imgLyr.style.pixelLeft = (mn_ItemPadding + mn_ImageHorizSpace);
				}
				else {
					TempItem.imgLyr.style.pixelLeft = TempItem.style.pixelWidth - ((TempItem.isLastItem ? 0 : mn_SeparatorSize) + mn_ItemPadding + mn_ImageHorizSpace + mn_ImageSize);
				}
			}
		}
		this.style.pixelHeight = MaxItemHeight + (mn_BorderWidth * 2);
	}
	else {
		if(this.IsVariableWidth) {
			var MaxItemWidth = 0;
	    	for(i=0; i<this.items.length; i++) {
	        	var TempItem = this.items[i];
				TempItem.style.pixelWidth = TempItem.scrollWidth;
				MaxItemWidth = i ? Math.max(MaxItemWidth,TempItem.style.pixelWidth) : TempItem.style.pixelWidth;
			}
	    	for(i=0; i<this.items.length; i++) {
				this.items[i].style.pixelWidth = MaxItemWidth;
			}
			this.style.pixelWidth = MaxItemWidth + (mn_BorderWidth * 2);
		}
	    for(i=0; i<this.items.length; i++) {
	        var TempItem = this.items[i];
		    if (TempItem.index) {
				var SiblingHeight =(TempItem.siblingBelow.scrollHeight + mn_SeparatorSize);
				TempItem.style.pixelTop = TempItem.siblingBelow.style.pixelTop + SiblingHeight;
			}
			else TempItem.style.pixelTop = 0;
			if(TempItem.imgLyr) {
				if(mn_CurrentTree.RightToLeft){
					TempItem.imgLyr.style.pixelLeft = (mn_ItemPadding + mn_ImageHorizSpace);
				}
				else {
					TempItem.imgLyr.style.pixelLeft = TempItem.style.pixelWidth - (mn_ItemPadding + mn_ImageHorizSpace + mn_ImageSize);
				}
			}
		}
		this.style.pixelHeight = TempItem.style.pixelTop + TempItem.scrollHeight + (mn_BorderWidth * 2);
	}
	this.sizeFixed = true;
	if(makevis)this.style.visibility = "visible";
}

function mn_f_ItemSetup(whichItem) {
    this.menu = this.parentElement;
	this.ClickStart = this.hasMore && this.tree.ClickStart && (this.tree.TopIsPermanent && (this.tree.treeParent==this.menu));
	if(this.ClickStart) {
		this.linkText = "";
		this.onclick = this.showChild;
	}

    if (this.hasMore) {
		if(this.menu.showImage){
			this.imgLyr = this.children("mn_ImMore");
			this.hasImageRollover = ((!this.tree.RightToLeft && mn_ImageSrcOver) || (this.tree.RightToLeft && mn_ImageSrcLeftOver));
			if(this.hasImageRollover) {
				this.imageSrc = this.tree.RightToLeft ? mn_ImageSrcLeft : mn_ImageSrc;
				this.imageSrcOver = this.tree.RightToLeft ? mn_ImageSrcLeftOver : mn_ImageSrcOver;
				if(this.permHilite) this.imgLyr.src = this.imageSrcOver;
			}
		}

        this.child = document.all(this.childID);
        if(this.child) {
			this.child.parentMenu = this.menu;
        	this.child.parentItem = this;
		}
    }
	if(this.linkText && !this.ClickStart) {
		this.onclick = mn_f_LinkIt;
		if(mn_ShowLinkCursor)this.style.cursor = "hand";
	}

	this.setItemStyle();
}

function mn_f_PopUp(menuname){
    if (!mn_AreLoaded) return;
	menuname = menuname.replace("elMenu",mn_MenuIDPrefix);
	var TempMenu = document.all(menuname);
	if(!TempMenu) return;
	mn_CurrentMenu = TempMenu;
	if (mn_CurrentMenu.tree.ClickStart) {
		var ClickElement = event.srcElement;
		ClickElement.onclick = mn_f_PopMenu;
    }
	else mn_f_PopMenu();
}

function mn_f_PopMenu(){
    if (!mn_AreLoaded || !mn_AreCreated) return true;
    if (mn_CurrentMenu.tree.ClickStart && event.type != "click") return true;
	var mouse_x_position, mouse_y_position;
    mn_f_HideAll();
    mn_CurrentMenu.hasParent = false;
	mn_CurrentMenu.tree.startChild = mn_CurrentMenu;
	mn_CurrentMenu.mouseX = mouse_x_position = (event.clientX + document.body.scrollLeft);
	mn_CurrentMenu.mouseY = mouse_y_position = (event.clientY + document.body.scrollTop);
	mn_CurrentMenu.xIntended = mn_CurrentMenu.xPos = (mn_CurrentMenu.tree.MenuLeft!=null) ? eval(mn_CurrentMenu.tree.MenuLeft) : mouse_x_position;
	mn_CurrentMenu.yIntended = mn_CurrentMenu.yPos = (mn_CurrentMenu.tree.MenuTop!=null)  ? eval(mn_CurrentMenu.tree.MenuTop)  : mouse_y_position;
	if(!mn_CurrentMenu.sizeFixed) mn_CurrentMenu.fixSize(false);
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
	if(event.srcElement.contains(event.toElement)) return;
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
       	this.child.parentItem = this;
	}

	if(!this.child.sizeFixed) this.child.fixSize(false);

	if (this.tree.PositionUnder && (this.menu == this.tree.treeParent)) {
		this.child.xPos = this.menu.style.pixelLeft + this.style.pixelLeft;
		this.child.yPos = this.menu.style.pixelTop + this.menu.offsetHeight - (mn_BorderWidth);
	}
	else {
		if(this.ChildOverlap==null) {
			this.DistanceToRightEdge = this.style.pixelWidth;
			if (!this.menu.isHorizontal || (this.menu.isHorizontal && this.isLastItem)) this.DistanceToRightEdge += mn_BorderWidth;
			this.DistanceToLeftEdge = (!this.menu.isHorizontal || (this.menu.isHorizontal && this.index==0)) ? mn_BorderWidth : mn_SeparatorSize;
			this.ChildOverlap = (parseInt((mn_ChildPerCentOver != null) ? (mn_ChildPerCentOver  * this.DistanceToRightEdge) : mn_ChildOverlap));
		}

		this.oL = this.menu.style.pixelLeft + this.offsetLeft + mn_BorderWidth;
		this.oT = this.menu.style.pixelTop  + this.offsetTop;
		if(this.tree.RightToLeft) {
			this.child.xPos = ((this.oL - this.DistanceToLeftEdge) + this.ChildOverlap) - this.child.style.pixelWidth;
		}
		else {		
			this.child.xPos = (this.oL + this.DistanceToRightEdge) - this.ChildOverlap;
		}
		this.child.yPos = this.oT + mn_ChildOffset + mn_BorderWidth;
	}
	this.child.xDiff = this.child.xPos - this.menu.style.pixelLeft;
	this.child.yDiff = this.child.yPos - this.menu.style.pixelTop;
	if(!this.tree.PositionUnder || this.menu!=this.tree.treeParent) this.child.keepInWindow();
	this.child.moveTo(this.child.xPos,this.child.yPos);
	this.menu.hasChildVisible = true;
	this.menu.visibleChild = this.child;
	this.child.showIt(true);
}

function mn_f_ItemOver(){
    if (mn_KeepHilite) {
        if (this.menu.currentItem && this.menu.currentItem != this && this.menu.currentItem.hasRollover) {
            with(this.menu.currentItem.style){
				backgroundColor = this.tree.BGColor;
            	color = this.tree.FontColor
			}
			if(this.menu.currentItem.hasImageRollover)this.menu.currentItem.imgLyr.src = this.menu.currentItem.imageSrc;
        }
    }
	if(event.srcElement.id == "mn_ImMore") return;
	if(this.hasRollover) {
		this.style.backgroundColor = this.tree.BGColorOver;
		this.style.color = this.tree.FontColorOver;
		if(this.hasImageRollover)this.imgLyr.src = this.imageSrcOver;
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
	if (event.srcElement.contains(event.toElement)
	  || (event.fromElement.tagName=="IMG" && (event.toElement && event.toElement.contains(event.fromElement))))
		  return;
    if ( (!mn_KeepHilite || ((this.tree.TopIsPermanent && (this.tree.treeParent==this)) && !this.menu.hasChildVisible)) && this.hasRollover) {
		with(this.style) {
			backgroundColor = this.tree.BGColor;
			color = this.tree.FontColor
		}
		if(this.hasImageRollover)this.imgLyr.src = this.imageSrc;

    }
}

function mn_f_MoveTo(xPos,yPos) {
	this.style.pixelLeft = xPos;
	this.style.pixelTop = yPos;
}

function mn_f_ShowIt(on) {
	if (!(this.tree.TopIsPermanent && (this.tree.treeParent==this))) {
		if(!this.hasParent || (this.hasParent && this.tree.TopIsPermanent && (this.tree.treeParent==this.parentMenu))) {
			var IsVisible = (this.style.visibility == "visible");
			if ((on && !IsVisible) || (!on && IsVisible))
				eval(on ? this.tree.UponDisplay : this.tree.UponHide)
		}
		if (on) this.style.zIndex = ++mn_ZIndex;
		this.style.visibility = (on) ? "visible" : "hidden";
	}
    if (mn_KeepHilite && this.currentItem && this.currentItem.hasRollover) {
		with(this.currentItem.style){
			backgroundColor = this.tree.BGColor;
			color = this.tree.FontColor;
		}
		if(this.currentItem.hasImageRollover)this.currentItem.imgLyr.src = this.currentItem.imageSrc;
    }
    this.currentItem = null;
}

function mn_f_KeepInWindow() {
    var ExtraSpace     = 10;
	var WindowLeftEdge = document.body.scrollLeft;
	var WindowTopEdge  = document.body.scrollTop;
	var WindowWidth    = document.body.clientWidth;
	var WindowHeight   = document.body.clientHeight;
	var WindowRightEdge  = (WindowLeftEdge + WindowWidth) - ExtraSpace;
	var WindowBottomEdge = (WindowTopEdge + WindowHeight) - ExtraSpace;

	var MenuLeftEdge = this.xPos;
	var MenuRightEdge = MenuLeftEdge + this.style.pixelWidth;
	var MenuBottomEdge = this.yPos + this.style.pixelHeight;

	if (this.hasParent) {
		var ParentLeftEdge = this.parentItem.oL;
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
			MenuRightEdge = this.xPos + this.style.pixelWidth;
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
    var MenuToHide = document.all(menuname);
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
	if((callingitem && (!callingitem.hasMore || this.visibleChild != callingitem.child)) || (!callingitem && !this.isOn)) {
		this.visibleChild.showIt(false);
		this.hasChildVisible = false;
	}
}

function mn_f_CancelSelect(){return false}

function mn_f_PageClick() {
    if (!mn_UserOverMenu && mn_CurrentMenu!=null && !mn_CurrentMenu.isOn) mn_f_HideAll();
}

popUp = mn_f_PopUp;
popDown = mn_f_PopDown;

function mn_f_ResizeHandler(){
	var mouse_x_position, mouse_y_position;
	for(var i=0; i<mn_TotalTrees; i++) {
        var TopMenu = mn_a_TopMenus[i].tree.startChild;
		if(TopMenu.style.visibility == "visible") {
			TopMenu.oldLeft = TopMenu.xPos;
			TopMenu.oldTop = TopMenu.yPos;
			mouse_x_position = TopMenu.mouseX;
			mouse_y_position = TopMenu.mouseY;
			TopMenu.xPos = eval(TopMenu.tree.MenuLeft);
			TopMenu.yPos = eval(TopMenu.tree.MenuTop);
			if(TopMenu.xPos == null) TopMenu.xPos = TopMenu.xIntended;
			if(TopMenu.yPos == null) TopMenu.yPos = TopMenu.yIntended;
			if(!TopMenu.tree.TopIsPermanent) TopMenu.keepInWindow();
			TopMenu.moveTo(TopMenu.xPos,TopMenu.yPos);
			var TempMenu = TopMenu;
		    while(TempMenu.hasChildVisible) {
				TempParent = TempMenu;
				TempMenu = TempMenu.visibleChild;
				TempMenu.xPos = TempParent.xPos + TempMenu.xDiff;
				TempMenu.yPos = TempParent.yPos + TempMenu.yDiff;
				if(!TopMenu.tree.TopIsPermanent || (TopMenu.tree.TopIsPermanent && !TopMenu.tree.PositionUnder)) TempMenu.keepInWindow();
				TempMenu.moveTo(TempMenu.xPos,TempMenu.yPos);
		    }
		}
    }
	mn_f_OtherResize();
}

mn_f_OtherResize = (window.onresize) ? window.onresize :  new Function;
window.onresize = mn_f_ResizeHandler;

mn_f_OtherOnLoad = (window.onload) ? window.onload :  new Function;
window.onload = function(){setTimeout("mn_f_StartIt()",10)};

//end