<?php
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
class upload {
	var $error_msg = '';
	var $files_ext = '';
//--------------------------------------------------------------------------
	/**
	* Constructor
	*
	* @param string $input_field_name form field name of uploaded file
	*/
	function upload($input_field_name){
		$this->input_field_name = $input_field_name;
	}
//--------------------------------------------------------------------------
	function set_file_type($file_type){
		$a_accepted_mime_types = array();

		if (! is_array($file_type)){
		 	$file_type = array($file_type);
		}
		$this->file_type = $file_type;

		foreach($this->file_type as $filetype){
			if ($filetype == "midi_media"){
				array_push($a_accepted_mime_types, 'audio/mid', 'audio/m', 'audio/midi', 'audio/x-midi', 'application/x-midi');
			} elseif ($filetype == "windows_media") {
				array_push($a_accepted_mime_types, 'audio/asf', 'application/asx', 'video/x-ms-asf-plugin', 'application/x-mplayer2', 
												'video/x-ms-asf', 'application/vnd.ms-asf ', 'video/x-ms-asf-plugin', 'video/x-ms-wm', 'video/x-ms-wmx', 
												'audio/x-ms-wma', 'video/x-ms-wmv', 'video/x-ms-asf', 'video/x-ms-asf');
			} elseif ($filetype == "real_media") {
				array_push($a_accepted_mime_types, 'application/vnd.rn-realmedia', 'audio/vnd.rn-realaudio', 'audio/x-pn-realaudio', 'audio/x-realaudio', 
												'audio/x-pm-realaudio-plugin', 'audio/x-pn-realvideo', 'audio/x-realaudio', 'video/x-pn-realvideo');
			} elseif ($filetype == "mp3_media") {
				array_push($a_accepted_mime_types, 'audio/x-mpeg', 'audio/mpeg', 'audio/mpegurl', 'audio/x-mpegurl','audio/mp3'); 
			} elseif ($filetype == "ms_word") {
				array_push($a_accepted_mime_types, 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'); 
			} elseif ($filetype == "ms_excel") {
				array_push($a_accepted_mime_types, 'application/vnd.ms-excel','application/x-msexcel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
			} elseif ($filetype == "ms_powerpoint") {
				array_push($a_accepted_mime_types, 'application/vnd.ms-powerpoint','application/mspowerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation'); 
			} elseif ($filetype == "text") {
				array_push($a_accepted_mime_types, 'text/plain'); 
			} elseif ($filetype == "html") {
				array_push($a_accepted_mime_types, 'text/html');
			} elseif ($filetype == "adobe_pdf") {
				array_push($a_accepted_mime_types, 'application/pdf','application/x-pdf');
			} elseif ($filetype == "adobe_flash") {
				array_push($a_accepted_mime_types, 'application/x-shockwave-flash');
			} elseif ($filetype == "images") {
				array_push($a_accepted_mime_types,'image/jpg','image/jpeg','image/x-png','image/png','image/pjpeg','image/gif','image/bmp','image/pjpeg');
			} elseif ($filetype == "compressed") {
				array_push($a_accepted_mime_types,'application/x-zip-compressed','application/x-gzip');
			}
		}
		$a_accepted_mime_types = array_unique($a_accepted_mime_types);
		$this->set_accepted_mime_types($a_accepted_mime_types);
	}
//--------------------------------------------------------------------------
	/**
	* Set the maximum file size
	*
	* @param array $accepted_mime_types Accepted MIME-types
	*/
	function set_accepted_mime_types($accepted_mime_types){
		$this->accepted_mime_types = $accepted_mime_types;
	}
//--------------------------------------------------------------------------
    /**
     * Set the maximum file size
     *
     * @param int $max_size Maximum file size in bytes
     */
    function set_max_file_size($max_size){
        $this->max_file_size = $max_size;
    }
//--------------------------------------------------------------------------
	/**
	* Sets the maximum pixel dimensions for image uploads
	*
	* @param int $width Maximum width of uploaded images (pixels)
	* @param int $height Maximum height of uploaded images uploads
	*/
	function set_max_image_size($width, $height){
		$this->max_image_width = $width;
		$this->max_image_height = $height;
	}

//--------------------------------------------------------------------------
	/**
	* Draw a simple upload-form (all elements in one line).
	*
	* @param string $title
	* @param string $action Value for the "action" attribute of the <form> tag
	*/
	function draw_simple_form($title = "Upload", $action = ''){
		if (isset($this->max_file_size)){
		   $maxlenght = " maxlength=\"{$this->max_file_size}\"";
		}else{
		   $maxlenght = '';
		}
		
		if (isset($this->accepted_mime_types)){
		   $accept = ' accept="' . implode(',', $this->accepted_mime_types) . '"';
		}else{
		   $accepted = '';
		}
		
		echo "<form enctype=\"multipart/form-data\" action=\"{$action}\" method=\"post\">";
		echo $title . ": <input name=\"{$this->input_field_name}\" type=\"file\"$accept$maxlenght>";
		echo "<input type=\"submit\" value=\"Send File\">";
		echo "</form>";
	}
//--------------------------------------------------------------------------
    /**
     * Draw an upload-form
     *
     * @param string $title
     * @param string $action Value for the "action" attribute of the <form> tag
     */
    function draw_form($title = "Upload", $action = ''){
        if (isset($this->max_file_size)){
            $maxlenght = " maxlength=\"{$this->max_file_size}\"";
        }else{
            $maxlenght = '';
        }

        if (isset($this->accepted_mime_types)){
            $accept = ' accept="' . implode(',', $this->accepted_mime_types) . '"';
        }else{
            $accept = '';
        }
        echo "<SCRIPT>\n";
        echo "function uploading(){\n";
        echo "   document.getElementById('FM1').style.visibility='hidden';\n";
        echo "   document.getElementById('FM2').style.visibility='visible';\n";
        echo "   return true;\n";
        echo "}\n";
        echo "</SCRIPT>";
        echo "<DIV ID='FM1' STYLE='visibility=visible'>";
        echo "<form enctype=\"multipart/form-data\" action=\"{$action}\" method=\"post\" onsubmit=\"return uploading();\">";
        echo "<B>" . $title . ":</B> <br>";
        echo "<input size='30' name=\"{$this->input_field_name}\" type=\"file\"$accept$maxlenght>";
		  echo "<br><br><input type=\"submit\" value=\"Enviar\" class=\"button\">";
		  if ($this->max_file_size > 0 ){
		  		echo "<BR><BR>Tama&ntilde;o m&aacute;ximo: ". round($this->max_file_size / 1024, 2) . "Kb";
		  }
		  if ($this->max_image_width > 0 ) {
		  		if ($this->max_file_size > 0 ){
					echo " &oacute; ";
				} else {
					echo "<BR><BR>Tama&ntilde;o m&aacute;ximo: ";
				}
		   	echo "". $this->max_image_width . "x" . $this->max_image_height . " pixeles";
		  }
        echo "</form></DIV>\n";
        echo "<DIV ID='FM2' ALIGN='CENTER' STYLE='position:absolute;left:120 px;top:60 px;visibility:hidden;' >\n";
        echo "<P ALIGN='CENTER'><STRONG STYLE='color:red'>Espere por favor...</STRONG></P>\n";
        echo "</DIV>\n";

    }
//--------------------------------------------------------------------------
	/**
	* Make some security-checks (e.g. file-size, MIME-type,...)
	*/
	function security_check(){
		if (is_uploaded_file($_FILES[$this->input_field_name][tmp_name])){
		   $this->file = $_FILES[$this->input_field_name];
		}else{
		   $this->error_msg = "El archivo no se ha subido correctamente!";
		   return false;
		}

		if(isset($this->max_file_size) && ($this->file["size"] > $this->max_file_size)){
		   $this->error_msg .= "Tamaño maximo superado . El tamaño del archivo no debe ser mayor que " . round($this->max_file_size / 1024, 2) . " KB";
		   return false;
		}

		if (isset($this->set_max_image_size)){
			if(preg_match("/image/", $this->file["type"])){
				$image_size = getimagesize($this->file["tmp_name"]);
			    	if(isset($this->max_image_width) && isset($this->max_image_height) &&
				       ($image_size[0] > $this->max_image_width) || ($image_size[1] > $this->max_image_height)){
				       $this->error_msg .= "Tamaño maximo excedido . La imagen no debe ser mayor que " . $this->max_image_width . " x " . $this->max_image_height . " pixeles";
				       return false;
				}
			}
		}			



        /**
         * If the class should only allow some specific MIME-types,
         * it will now check if the MIME-type is allowed.
         */
		if(isset($this->accepted_mime_types) && !in_array($this->file["type"], $this->accepted_mime_types)){
			$this->error_msg = "Este tipo de archivo no es correcto - ";
			$this->error_msg .= "Ingresado: {$this->file["type"]} - ";
			$this->error_msg .= "Esperado: " . implode(', ', $this->accepted_mime_types);
			return false;
		}
		return true;
	 }
//--------------------------------------------------------------------------
	/**
	* Moves the uploaded file
	*
	* @param string $destination_folder
	* @param boolean $overwrite
	*/
	function move($destination_folder, $overwrite = false){

		if ($this->security_check() == false){
		   return false;
		}
		$filename = $this->file['tmp_name'];
		$destination = $destination_folder . $this->file['name'];
		if (file_exists($destination) && $overwrite != true){
		   $this->error_msg = "El archivo que desea guardar ya existe";
		   return false;
		}elseif (move_uploaded_file ($filename, $destination)){
		   chmod($destination, 0664);
		   return true;
		}else{
		   $this->error_msg = "Error moviendo el archivo!";
		   return false;
		}
	}
//--------------------------------------------------------------------------
	/**
	* Read the content from the file and return it as a binary string.
	*
	* @return string The (binary) content of the file
	*/
	function read(){
		if ($this->security_check() == false){
		   return false;
		}
	
		$filename = $this->file['tmp_name'];
		$fd = fopen ($filename, "rb");
		$contents = fread ($fd, filesize ($filename));
		fclose ($fd);
		
		return $contents;
	}
}
//--------------------------------------------------------------------------
function convert_image($sourcepic,$destpic,$res,$quality){
	$thumb_generator = chkgd2();
	if(preg_match("/gd/i",$thumb_generator)) {
		if (preg_match("/(.jpg|.jpeg)$/i",$sourcepic)) {
			$type="jpg";
			$im=imagecreatefromjpeg($sourcepic);
		} elseif (preg_match("/.png$/i",$sourcepic)) {
			$type="png";
			$im=imagecreatefrompng($sourcepic);
		} elseif (preg_match("/.gif$/i",$sourcepic)) {
			$type="gif";
			$im=imagecreatefromgif($sourcepic);
		} 
		if ($im != "") {
			$dims=explode("x",$res);
			$newh=$dims[1];
			$neww=$newh/imagesy($im) * imagesx($im);
      	if ($neww > imagesx($im)) {
				$neww=imagesx($im);
				$newh=imagesy($im);
			}
			if ($neww > $dims[0]) {
				$neww=$dims[0];
				$newh=$neww/imagesx($im) * imagesy($im);
			}
			if ( $thumb_generator == "gd2" ) {
  				$im2 = imagecreatetruecolor($neww,$newh);
  				imagecopyresampled($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
			} elseif ( $thumb_generator == "gd" )	{		
    			$im2 = imagecreate($neww,$newh);
    			imagecopyresized($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
			}
			if ($type=="jpg") {
				imageinterlace($im2,1);
				imagejpeg($im2,$destpic,$quality);
			} elseif ($type=="png") {
				imagepng($im2,$destpic);
			} elseif ($type=="gif") {
				imagegif($im2,$destpic);
			}	
			ImageDestroy($im);
			ImageDestroy($im2);
			$ret_val = 1;
		} else {
			$ret_val = 0;
		}
	}
	return($ret_val);
}
//------------------------------------------------------------------------------
function chkgd2() { 
   static $gd_version_number = null; 
   if ($gd_version_number === null) { 
       ob_start(); 
       phpinfo(8); 
       $module_info = ob_get_contents(); 
       ob_end_clean(); 
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", 
               $module_info,$matches)) { 
           $gd_version_number = $matches[1]; 
       } else { 
           $gd_version_number = 0; 
       } 
   } 
   
	if ($gd_version_number >= 2) { 
   	return "gd2"; 
	} else {
   	return "gd";
	} 
}
//------------------------------------------------------------------------------
function dir_slash($dirname=""){
	if ( $dirname == "" ){
		return "";
	}
	if (strlen(substr(strrchr($dirname, "/"), 1))>0){
		$dirname = $dirname . "/";
	}
	return $dirname;

}
//------------------------------------------------------------------------------
class filebrowse{
	var $filter = array();
	var $files = array();
	var $types = array(
		"bmp"  =>   array("images/mime/bmp.gif","bmp",array('image/bmp')),
		"dir"  =>   array("images/mime/folder.gif","dir",array()),
		"exe"  =>   array("images/mime/binary.gif","bin",array()),
		"doc"  =>   array("images/mime/doc.gif","doc",array()),
		"docx" =>   array("images/mime/doc.gif","doc",array()),
		"pdf"  =>   array("images/mime/pdf.gif","pdf",array()),  
		"ppt"  =>   array("images/mime/ppt.gif","ppt",array()),  
		"pptx" =>   array("images/mime/ppt.gif","ppt",array()),  
		"gif"  =>   array("images/mime/gif.gif","gif",array()),  
		"html" =>   array("images/mime/html.gif","html",array()), 
		"htm"  =>   array("images/mime/html.gif","htm",array()), 
		"png"  =>   array("images/mime/img.gif","png",array()),  
		"jpg"  =>   array("images/mime/jpg.gif","jpg",array()),  
		"jpeg"  =>  array("images/mime/jpg.gif","jpeg",array()),  
		"qt"   =>   array("images/mime/movie.gif","qt",array()),
		"mov"  =>   array("images/mime/movie.gif","mov",array()),
		"mpg"  =>   array("images/mime/movie.gif","mp3",array()),
		"mp4"  =>   array("images/mime/movie.gif","mp4",array()),
		"rm"   =>   array("images/mime/movie.gif","rm",array()),
		"mp3"  =>   array("images/mime/sound.gif","mp3",array()),
		"swf"  =>   array("images/mime/swf.gif","swf",array()),  
		"txt"  =>   array("images/mime/txt.gif","txt",array()),  
		"wmv"  =>   array("images/mime/wmv.gif","wmv",array()),  
		"xls"  =>   array("images/mime/xls.gif","xls",array()),
		"xlsx" =>   array("images/mime/xls.gif","xls",array()),
	);
	//----------------------------------
	function filebrowse($type){
		if ($type=="image"){
				$this->filter = array("jpg","png","gif","jpeg","bmp");
		} elseif ($type=="media"){
				$this->filter = array("swf","mov","wmv","mp4","mp3","qt");
		} elseif ($type=="file"){
				$this->filter = array("jpg","png","gif","bmp","html","htm","pdf","doc","docx","xls","xlsx","ppt","pptx");
		} else {
				$this->filter = array();
		}
	}
	//----------------------------------
	function file_property($file){
		$partes_ruta = pathinfo($file);
		foreach($this->types as $k => $v){
			if (trim(strtolower($partes_ruta['extension'])) == trim($k)){
				$property[iname] = strtolower(trim($partes_ruta['basename']));
				$property[name] = trim($partes_ruta['basename']);
				$property[icon] = $v[0];
				$property[type] = $v[1];
				$property[size] = round(filesize($file)/1024,2)."&nbsp;KB";
				$property[mtime] = date ("d/m/Y H:i", filemtime($file));
				break;
			}
		}
		return $property;
	}
	//----------------------------------
	function listfiles($passdir,$dirname){
		$arradir = array();
		$arrafiles = array();
		if ($handle = opendir($passdir)) {
			while (false !== ($nombre_archivo = readdir($handle))) {
					if (is_dir($passdir.$nombre_archivo)){
						if ($nombre_archivo != "." && $nombre_archivo != "..") {
							$arradir[] = array (
								"iname" => strtolower(trim($nombre_archivo)),
								"name" => trim($nombre_archivo),
								"type" => "",
								"icon" => "images/mime/folder.gif",
								"size" => "",
								"mtime" => "",
							);
						} else {
							if ($passdir != $dirname && $nombre_archivo == ".."){
								$arradir[] = array (
									"iname" => strtolower(trim($nombre_archivo)),
									"name" => trim($nombre_archivo),
									"type" => "",
									"icon" => "images/mime/folder.gif",
									"size" => "",
									"mtime" => "",
								);
							}
						}
					} else {
						$partes_ruta = pathinfo($passdir.$nombre_archivo);
						if (in_array(strtolower($partes_ruta['extension']), $this->filter, true)){ 
							$arrafiles[] = $this->file_property($passdir.$nombre_archivo);
						}
					}
		 	}
			closedir($handle); 
		}
	
		sort($arradir);
		sort($arrafiles);
		$result = array_merge($arradir, $arrafiles);
		$this->files = $result;
		return;
	}
	//----------------------------------
}
//-------------------------------------------------------------------------------
?>