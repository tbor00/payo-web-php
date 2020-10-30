// xp_progressbar

// xyz - An arbitrary variable name to store the bar 
//		reference and must be unique. This variable will 
//		have a few different methods (explained later) 
//		which can be used to control some of each bar's 
//		behavior. This variable IS REQUIRED if you wish 
//		to use these methods. However, if you do not plan 
//		to use the methods, then the variable assignment 
//		is not necessary, but it won't hurt to use it 
//		anyway. 

// width- Total width of the entire bar in pixels. 

// height- Total height of the entire bar in pixels. 

// backgroundColor- Background color of the bar. Use valid 
//		CSS color or HEX color code value. 

// borderWidth- The width of the border around the bar, 
//		in pixels. 

// borderColor- The color of the border around the bar. 
//		Use valid CSS color or HEX color code value. 

// blockColor- The darkest color of the individual blocks. 
//		The color will progressively become more transparent. 
//		Use valid CSS color or HEX color code value. 

// scrollSpeed- The delay, in milliseconds, between each 
//		scroll step. Use smaller values for faster scroll 
//		speeds.
 
// blockCount- The total number of blocks to use. 

// actionCount - The number of times the bar is to scroll 
//		before actionString is performed. 

// actionString - The javascript function, in string form, to 
//		execute once the bar has scrolled actionCount times. 
//		Set this to an empty string to do nothing. If doing 
//		nothing, you can use any number as actionCount. 
//

// Methods
// var.toggleBar() toggle pause This method will toggle the 
//		pause status of the bar. If it is paused, it will 
//		un-pause it, and vice-versa. The code for the link at left is:
//		<a href="javascript:bar2.togglePause()">toggle pause</a> 

// var.hideBar() Hide Bar 2 This method will hide the bar. If it is 
//		already hidden, it will do nothing. The code for the link 
//		at left is:
//		<a href="javascript:bar2.hideBar()">Hide Bar 2</a> 

// var.showBar() Show Bar 2 This method will show the bar. If it is 
//		already visible, it will do nothing. The code for the link at 
//		left is: 
//		<a href="javascript:bar2.showBar()">Show Bar 2</a> 



// var xyz = createBar(
// total_width,
// total_height,
// background_color,
// border_width,
// border_color,
// block_color,
// scroll_speed,
// block_count,
// scroll_count,
// action_to_perform_after_scrolled_n_times
// )

var w3c=(document.getElementById)?true:false;
var ie=(document.all)?true:false;
var N=-1;

function createBar(w,h,bgc,brdW,brdC,blkC,speed,blocks,count,action){
if(ie||w3c){
var t='<div id="_xpbar'+(++N)+'" style="visibility:visible; position:relative; overflow:hidden; width:'+w+'px; height:'+h+'px; background-color:'+bgc+'; border-color:'+brdC+'; border-width:'+brdW+'px; border-style:solid; font-size:1px;">';
t+='<span id="blocks'+N+'" style="left:-'+(h*2+1)+'px; position:absolute; font-size:1px">';
for(i=0;i<blocks;i++){
t+='<span style="background-color:'+blkC+'; left:-'+((h*i)+i)+'px; font-size:1px; position:absolute; width:'+h+'px; height:'+h+'px; '
t+=(ie)?'filter:alpha(opacity='+(100-i*(100/blocks))+')':'-Moz-opacity:'+((100-i*(100/blocks))/100);
t+='"></span>';
}
t+='</span></div>';
document.write(t);
var bA=(ie)?document.all['blocks'+N]:document.getElementById('blocks'+N);
bA.bar=(ie)?document.all['_xpbar'+N]:document.getElementById('_xpbar'+N);
bA.blocks=blocks;
bA.N=N;
bA.w=w;
bA.h=h;
bA.speed=speed;
bA.ctr=0;
bA.count=count;
bA.action=action;
bA.togglePause=togglePause;
bA.showBar=function(){
this.bar.style.visibility="visible";
}
bA.hideBar=function(){
this.bar.style.visibility="hidden";
}
bA.tid=setInterval('startBar('+N+')',speed);
return bA;
}}

function startBar(bn){
var t=(ie)?document.all['blocks'+bn]:document.getElementById('blocks'+bn);
if(parseInt(t.style.left)+t.h+1-(t.blocks*t.h+t.blocks)>t.w){
t.style.left=-(t.h*2+1)+'px';
t.ctr++;
if(t.ctr>=t.count){
eval(t.action);
t.ctr=0;
}}else t.style.left=(parseInt(t.style.left)+t.h+1)+'px';
}

function togglePause(){
if(this.tid==0){
this.tid=setInterval('startBar('+this.N+')',this.speed);
}else{
clearInterval(this.tid);
this.tid=0;
}}

function togglePause(){
if(this.tid==0){
this.tid=setInterval('startBar('+this.N+')',this.speed);
}else{
clearInterval(this.tid);
this.tid=0;
}}
