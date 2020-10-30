<?php
/**
*/
class upload{
    var $error_msg = '';
    /**
     * Constructor
     *
     * @param string $input_field_name form field name of uploaded file
     */
    function upload($input_field_name){
        $this->input_field_name = $input_field_name;
    }

    /**
     * Set the maximum file size
     *
     * @param array $accepted_mime_types Accepted MIME-types
     */
    function set_accepted_mime_types($accepted_mime_types){
        $this->accepted_mime_types = $accepted_mime_types;
    }

    /**
     * Set the maximum file size
     *
     * @param int $max_size Maximum file size in bytes
     */
    function set_max_file_size($max_size){
        $this->max_file_size = $max_size;
    }

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
            $accepted = '';
        }
        echo "<SCRIPT>\n";
        echo "function uploading(){\n";
        echo "   document.all['FM1'].style.visibility='hidden';\n";
        echo "   document.all['FM2'].style.visibility='visible';\n";
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
        echo "<DIV ID='FM2' ALIGN='CENTER' STYLE='position:absolute;left:120 px;top:60 px ;visibility:hidden;' >\n";
        echo "<P ALIGN='CENTER'><STRONG STYLE='color:red'>Espere por favor...</STRONG></P>\n";
        echo "</DIV>\n";

        if (isset($this->accepted_mime_types)){
          //  echo "This form only accepts the following MIME-types: " . implode(', ', $this->accepted_mime_types) . "<br>";
        }
    }

    /**
     * Make some security-checks (e.g. file-size, MIME-type,...)
     */
  function security_check(){
        if (is_uploaded_file($_FILES[$this->input_field_name][tmp_name])){
            $this->file = $_FILES[$this->input_field_name];
        }else{
            $this->error_msg = "El archivo que desea ingresar no existe!";
            return false;
        }

        if(isset($this->max_file_size) && ($this->file["size"] > $this->max_file_size)){
            $this->error_msg .= "Tamaño maximo superado . El tamaño del archivo no debe ser mayor que " . $this->max_file_size . " bytes . (= " . round($this->max_file_size / 1024, 2) . "KB)";
            return false;
        }
		  if($this->max_image_width != 0 && $this->max_image_height != 0){
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
    /**
     * Moves the uploaded file
     *
     * @param string $destination_folder
     * @param boolean $overwrite
     */
    function move($destination_folder, $overwrite = false, $newfilename=""){
        if ($this->security_check() == false){
            return false;
        }
        $filename = $this->file['tmp_name'];
		  if ( $newfilename == "" ){
				$destination = $destination_folder . $this->file['name'];
			} else {
				$destination = $destination_folder . $newfilename;
			}
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
?>