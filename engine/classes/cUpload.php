<?php
	class cUpload{
		private $url;
		public $fixed_name;
		public $spec_dir;
		
		function geturl(){
			return $this->url;
		}
		
		function upload_($ufile,$uname,$usize,$utype,$dest){
			$err_msg;
			
			if($ufile == "none"){
				$err_msg = "Problem: No file uploaded";
				return $err_msg;
			}
			
			if($usize == 0){
				$err_msg = "Problem: Size of file is invalid";
				return $err_msg;
			}
			if($usize > 510000){
				$err_msg = "Problem: Size of file is too large";
				return $err_msg;
			}
			
			if(in_array($utype,array("image/jpeg","image/jpg","image/gif","image/png","image/tiff"))){
			}else{
				$err_msg = "Problem: Only Portable Documents(pdf) are acceptable";
				return $err_msg;
			}
			
			if(!is_uploaded_file($ufile)){
				$err_msg = "Problem: Possible file upload attack";
				return $err_msg;
			}
			//Comment the line below when loaded on a server
			$upload_dir= ''; $dest=$upload_dir.$this->spec_dir;
			
			$pre_name = $uname;
			$new_name = $this->generate_name($pre_name);
			
			if(!copy($ufile, $dest.$new_name)){
				$err_msg = "Problem: Could not move file";
				return $err_msg;
			}else{
				$err_msg = "Uploaded ".$new_name;
				$this->url = $new_name;
				return $err_msg;
			}
			return $err_msg;
		}
		
		private function generate_name($inc){
			$day =date_default_timezone_set("GMT");
			$month = getdate();
			$date = $month["month"] . $month["mday"] . $month["year"].$month["seconds"] . $month["minutes"] . $month["hours"].$month["yday"].$month[0] ;
			
			$pos = strrpos($inc,".");
			$le = strlen($inc);
			$num = $le - $pos;
			$ext = substr($inc,$pos,$num);
			
			$new_name = $date . $ext;
			//Uncomment for auto name
			//return $new_name;
			return $this->fixed_name;
		}
		
		function extension($inc){
			$pos = strrpos($inc,".");
			$le = strlen($inc);
			$num = $le - $pos;
			$ext = substr($inc,$pos,$num);
			return $ext;
		}
	}
?>
