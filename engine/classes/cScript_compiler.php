<?php
	/**
	 * Script Compiler Class
	 *
	 * @used in  				Classes that provides website view for use default operational 
	 * 							proccesses
	 * @created  				20:25 | 29-12-2013
	 * @database table name   	-
	 */

	/*
	|--------------------------------------------------------------------------
	| Allows automation of create, search, delete, update, view processess
	|--------------------------------------------------------------------------
	|
	| Interfaces with the cForms - Form Generator Class
	|
	*/
	
	class cScript_compiler{
		public $class_settings = array();
		
		private $temp_directory_js = "tmp/js-bundle/";
		private $temp_directory_css = "tmp/css-bundle/";
		
		function script_compiler(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'compile_scripts':
				$returned_value = $this->_compile_scripts();
			break;
			case 'get_html_data':
				$returned_value = $this->_html_markup();
			break;
			}
			
			return $returned_value;
		}
		
		private function _compile_scripts(){
			//CHECK FOR EXISTING FILE
			$js_file = $this->_compile_js();
			
			$css_file = $this->_compile_css();
			
			$html_markup = $this->_html_markup();
			
			return array(
				'js_file' => $js_file,
				'css_file' => $css_file,
				'html_markup' => $html_markup,
			);
		}
		
		private function _compile_css(){
			//CHECK FOR EXISTING FILE
			$css_file_content = "";
			
			$filename = "";
			
			if( isset( $this->class_settings[ 'stylesheet' ] ) && $this->class_settings[ 'stylesheet' ] && isset( $this->class_settings[ 'css' ] ) && is_array( $this->class_settings[ 'css' ] ) ){
				
				$filename = $this->class_settings[ 'calling_page' ] . $this->temp_directory_css . $this->class_settings[ 'stylesheet' ] . '.css';
				$add_file = false;
				
				//if( ! file_exists( $filename ) || $this->class_settings[ 'project_data' ][ 'development_mode' ] ){
					//GET LIST OF FILE NAMES & PAGE NAME
					foreach( $this->class_settings[ 'css' ] as $file_link ){
						if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
							//READ CONTENTS OF EACH FILE
							//$css_file_content .= file_get_contents( $this->class_settings[ 'calling_page' ] . $file_link );
                            $css_file_content .= '<link type="text/css" href="' . $this->class_settings[ 'calling_page' ] . $file_link . '" rel="stylesheet" />';
						}
					}
					
                    return $css_file_content;
					if( $css_file_content ){
						//CREATE NEW FILE FOR PAGE
						//file_put_contents( $filename , $css_file_content );
						
						$add_file = true;
					}
                    /*
				}else{
					$add_file = true;
				}*/
				
				if( $add_file ){
					if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                        $css_file_content = '<link type="text/css" href="' . str_replace($this->class_settings[ 'calling_page' ], $this->class_settings[ 'calling_page_display' ], $filename ). '" rel="stylesheet" />';
                    }else{
                        $css_file_content = '<link type="text/css" href="' . $filename . '" rel="stylesheet" />';
                    }
				}
			}
			
			return $css_file_content;
		}
		
		private function _compile_js(){
			//CHECK FOR EXISTING FILE
			$js_file_content = "";
			$js_libraries = "";
			
			$filename = "";
			
            if( isset( $this->class_settings[ 'js_lib' ] ) && is_array( $this->class_settings[ 'js_lib' ] ) ){
                foreach( $this->class_settings[ 'js_lib' ] as $file_link ){
                    
                    if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
                        //READ CONTENTS OF EACH FILE
                        if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                            $js_libraries .= '<script type="text/javascript" src="' . $this->class_settings[ 'calling_page_display' ] . $file_link . '"></script>';
                        }else{
                            $js_libraries .= '<script type="text/javascript" src="' . $this->class_settings[ 'calling_page' ] . $file_link . '"></script>';
                        }
                    }
                }
            }
				
			if( isset( $this->class_settings[ 'script_name' ] ) && $this->class_settings[ 'script_name' ] && isset( $this->class_settings[ 'js' ] ) && is_array( $this->class_settings[ 'js' ] ) ){
				
				$filename = $this->class_settings[ 'calling_page' ] . $this->temp_directory_js . $this->class_settings[ 'script_name' ] . '.js';
				$add_file = false;
				
				if( ! file_exists( $filename ) || $this->class_settings[ 'project_data' ][ 'development_mode' ] ){
					//GET LIST OF FILE NAMES & PAGE NAME
					foreach( $this->class_settings[ 'js' ] as $file_link ){
						if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
							//READ CONTENTS OF EACH FILE
							$js_file_content .= file_get_contents( $this->class_settings[ 'calling_page' ] . $file_link );
						}
					}
					
					if( $js_file_content ){
						//ADD TO JQUERY DOCUMENT READY STRUCTURE
						$js_file_content = "(function(jQuery) {	$(document).ready(function(){ " . $js_file_content . "}); })(jQuery);";
						
						//CREATE NEW FILE FOR PAGE
						file_put_contents( $filename , $js_file_content );
						
						$add_file = true;
					}
				}else{
					$add_file = true;
				}
				
				if( $add_file ){
					if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                        $js_libraries .= '<script type="text/javascript" src="' . str_replace($this->class_settings[ 'calling_page' ], $this->class_settings[ 'calling_page_display' ], $filename ). '"></script>';
                    }else{
                        $js_libraries .= '<script type="text/javascript" src="' . $filename . '"></script>';
                    }
				}
			}
			
			return $js_libraries;
		}
		
		private function _html_markup(){
			$html_file_content = "";
			
			$user_info = array();
			if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
				$display_pagepointer = $this->class_settings[ 'calling_page_display' ];
				$pr = get_project_data();
				$site_url = $pr['domain_name'];
                unset($pr);
			}
			if( isset( $this->class_settings[ 'user_id' ] ) ){
				$user_info[ 'user_id' ] = $this->class_settings[ 'user_id' ];
			}
			if( isset( $this->class_settings[ 'user_email' ] ) ){
				$user_info[ 'user_email' ] = $this->class_settings[ 'user_email' ];
			}
			if( isset( $this->class_settings[ 'user_full_name' ] ) ){
				$user_info[ 'user_full_name' ] = $this->class_settings[ 'user_full_name' ];
				
				$user_info[ 'user_initials' ] = substr( $this->class_settings[ 'user_full_name' ] , 0 , 1 ) . '. ' . $this->class_settings[ 'user_lname' ];
			}
			
			if( isset( $this->class_settings[ 'data' ] ) && is_array( $this->class_settings[ 'data' ] ) ){
				$data = $this->class_settings[ 'data' ];
			}
			
			if( isset( $this->class_settings[ 'html' ] ) && is_array( $this->class_settings[ 'html' ] ) ){
				//GET LIST OF FILE NAMES & PAGE NAME
				foreach( $this->class_settings[ 'html' ] as $file_link ){
					if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
						//READ CONTENTS OF EACH FILE
						ob_start();
						include( $this->class_settings[ 'calling_page' ] . $file_link );
						$html_file_content .= ob_get_contents();
						ob_end_clean();
					
						//$html_file_content .= file_get_contents( $this->class_settings[ 'calling_page' ] . $file_link );
					}
				}
			}
			
			return $html_file_content;
		}
		
	}
?>