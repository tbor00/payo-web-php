<footer class="bg-dark">
<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<?php
			$query = "SELECT * from e_menues where tipo=4 and activo=1 and destino<=$destiny order by posicion";
			$result = $db->Execute($query);
			if ($result && !$result->EOF){
				while(!$result->EOF){
					$informations[] = array(
						'href'=>$_SERVER['PHP_SELF']."?op=".$result->fields['id_menu'].$result->fields['ejecutar'],
						'titulo'=>$result->fields['titulo'],
					);
					$result->MoveNext();
				}
			}
			if ( sizeof($informations) > 0 ) {?>
			<h5>Informaci&oacute;n</h5>
			<ul class="list-unstyled">
			<?php foreach ($informations as $information) { ?>
			<li><a href="<?php echo $information['href']; ?>"><?php echo $information['titulo']; ?></a></li>
			<?php } ?>
			</ul>
			<?php } ?>
		</div>
		<?php
			$query = "SELECT * from e_parametros where id_param=1";
			$result = $db->Execute($query);
			if ($result && !$result->EOF){
				if ($result->fields['data_fiscal']){
					echo "<div class=\"col-sm-6\">\n";
					echo "</div>\n";
					echo "<div class=\"col-sm-2\">\n";
					echo "<ul class=\"list-unstyled\">\n";
					echo "<li>".$result->fields['pie_1']."</li>\n";
					echo "<li>".$result->fields['pie_2']."</li>\n";
					echo "<li>".$result->fields['pie_3']."</li>\n";
					echo "<li>Lun. a Vier. de 8:30 a 18:00hs</li>\n";
					echo "</ul>\n";
					echo "</div>\n";
					echo "<div class=\"col-sm-1\">\n";
					echo "<span class=\"data_fiscal\" id=\"data_fiscal\">\n";
					echo $result->fields['data_fiscal']."\n";
					echo "</span>\n";
					echo "</div>\n";
				} else {
					echo "<div class=\"col-sm-7\">\n";
					echo "</div>\n";
					echo "<div class=\"col-sm-2\">\n";
					echo "<ul class=\"list-unstyled\">\n";
					echo "<li>".$result->fields['pie_1']."</li>\n";
					echo "<li>".$result->fields['pie_2']."</li>\n";
					echo "<li>".$result->fields['pie_3']."</li>\n";
					echo "<li>Lun. a Vier. de 8:30 a 18:00hs</li>\n";
					echo "</ul>\n";
					echo "</div>\n";
				}
			} else {
				echo "<div class=\"col-sm-7\">\n";
				echo "</div>\n";
				echo "<div class=\"col-sm-2\">\n";
				echo "</div>\n";
			}
		?>
	</div>
</div>




<a href="" class="float" target="__blank">
    <i class="fa fa-whatsapp " style="font-size: 30px;"></i>
	</a>
	
	<style>



.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:25px;
	right:20px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
 	 font-size:80px !important;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}
.float:hover {
	text-decoration: none;
	color: #25d366;
  background-color:#fff;
}

.my-float{
	margin-top:16px;
}
	</style>


</footer>