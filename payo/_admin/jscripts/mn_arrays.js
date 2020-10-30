var agt=navigator.userAgent.toLowerCase()
if (agt.indexOf('netscape')==-1 ) {
	top_position = 30;
} else {
	top_position = 25;
}

mn_Array1 = [
[170,						// menu_width
11, 						// left_position
top_position,			// top_position
,                    // font_color
,                    // mouseover_font_color
,                    // background_color
,                    // mouseover_background_color
,                    // border_color
,                    // separator_color
1,                   // top_is_permanent
1,                   // top_is_horizontal
0,                   // tree_is_horizontal
1,                   // position_under
0,                   // top_more_images_visible
1,                   // tree_more_images_visible
"mn_f_ToggleElementList(false,['select'],'tag')",  // evaluate_upon_tree_show
"mn_f_ToggleElementList(true,['select'],'tag')",   // evaluate_upon_tree_hide
,							// right_to_left
,						   // display_on_click
true,						// top_is_variable_width
,						   // tree_is_variable_width
],
["&nbsp;&nbsp;Administraci&oacute;n&nbsp;&nbsp;","",1,0,1],
["&nbsp;&nbsp;Contenido&nbsp;&nbsp;","",1,0,1],
["&nbsp;&nbsp;Clientes&nbsp;&nbsp;","",1,0,1],
["&nbsp;&nbsp;Productos&nbsp;&nbsp;","",1,0,1],
["&nbsp;&nbsp;Pedidos&nbsp;&nbsp;","",1,0,1],
["&nbsp;&nbsp;Tablas&nbsp;&nbsp;","",1,0,1],
]

mn_Array1_1 = [
[],
["General","index.php?accion=general",1,0,0],
["Tel&eacute;fonos","index.php?accion=telefonos",1,0,0],
["Administradores","index.php?accion=usuarios",1,0,0],
]

mn_Array1_2 = [
[],
["Men&uacute; Superior","index.php?accion=menues",1,0,0],
["Men&uacute; Inferior","index.php?accion=menues_sup",1,0,0],
["","",0,0,0],
["Novedades","index.php?accion=novedades",1,0,0],
["Slide Banners","index.php?accion=carrousel",1,0,0],
["","",0,0,0],
["Mensajes del Sistema","index.php?accion=mensajes",1,0,0],
]


mn_Array1_3 = [
[],
["Rubros","index.php?accion=rubrosw",1,0,0],
["Gestiones","index.php?accion=gestiones",1,0,0],
["Vendedores","index.php?accion=vendedores",1,0,0],
["","",0,0,0],
["Usuarios","index.php?accion=webusers",1,0,0],
["","",0,0,0],
["Cuentas Corrientes","index.php?accion=ctasctes",1,0,0],
]

mn_Array1_4 = [
[],
["Productos","index.php?accion=pproductos",1,0,0],
["","",0,0,0],
["Marcas","index.php?accion=marcas",1,0,0],
["Proveedores","index.php?accion=proveedores",1,0,0],
["","",0,0,0],
["Ofertas","index.php?accion=ofertas",1,0,0],
["","",0,0,0],
["Historial de Actualizaciones","index.php?accion=historial",1,0,0],
]

mn_Array1_5 = [
[],
["Pedidos","index.php?accion=pedidos",1,0,0],
["Descuentos","index.php?accion=descuentos",1,0,0],
]

mn_Array1_6 = [
[],
["Provincias","index.php?accion=provincias",1,0,0],
["Keywords","index.php?accion=keys",1,0,0],
]



