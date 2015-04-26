<?php
	/**
	 * website Class
	 *
	 * @used in  				website Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	website
	 */

	/*
	|--------------------------------------------------------------------------
	| website Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cWebsite{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'website';
        
		private $page_title = 'Perspectiva';
        
		private $page_keywords = 'Perspectiva';
        
		private $page_description = 'Perspectiva';
		
		private $table_fields = array();
		
		function __construct(){
			
		}
	
		function website(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'setup_website':
				$returned_value = $this->_setup_website();
			break;
			case 'setup_store':
				$returned_value = $this->_setup_store();
			break;
			case 'cart':
			case 'homepage':
			case 'all_products':
			case 'product':
			case 'category_products':
			case 'terms_of_service':
			case 'newsletter':
			default:
				$returned_value = $this->_homepage();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_general_settings(){
			return get_from_cached( array( 'cache_key' => 'general_settings' ) );
		}
		
		private function _homepage(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$filename = str_replace( '_', '-', $this->class_settings['action_to_perform'] );
			
            $do_not_use_cache = true;
            $cache_key = 'website-cache-1-'.$filename;
            $country_id = '1';
            if( defined( 'SELECTED_COUNTRY_ID' ) ){
                $country_id = SELECTED_COUNTRY_ID;
            }
            $csettings = array(
                'cache_key' => $cache_key.'-'.$country_id,
                'cache_time' => 'load-time',
            );
            //clear_cache_for_special_values( $csettings );
            //$cached_data = get_cache_for_special_values( $csettings );
            if( isset( $cached_data ) && is_array( $cached_data ) && ! empty( $cached_data ) ){
                $script_compiler->class_settings[ 'data' ] = $cached_data;
                $do_not_use_cache = false;
            }
            /*  
            if( isset( $_GET['s'] ) && isset( $_GET['categories'] ) && $_GET['categories'] && $this->class_settings['action_to_perform'] == 'all_products' )
                $do_not_use_cache = true;
            */
            
			switch ( $this->class_settings['action_to_perform'] ){
            case 'homepage':
                $this->class_settings[ 'js' ][] = 'my_js/custom/homepage.js';
                
                $visit_schedule = new cVisit_schedule();
                $visit_schedule->class_settings = $this->class_settings;
                $visit_schedule->class_settings[ 'action_to_perform' ] = 'get_first_step_visit_schedule_form';
                $form = $visit_schedule->visit_schedule();
                
                $script_compiler->class_settings[ 'data' ]['visit_schedule_form'] = $form['html'];
                //$this->class_settings[ 'css' ][] = 'css/template-1/discount-patch.css';
                
                $script_compiler->class_settings[ 'data' ]['pagepointer'] = $this->class_settings['calling_page'];
            break;
			case 'faq':
				$do_not_use_cache = false;
                
                $this->class_settings[ 'js' ][] = 'my_js/custom/' . $filename . '.js';
                
                $support_enquiry = new cSupport_enquiry();
                $support_enquiry->class_settings = $this->class_settings;
                $support_enquiry->class_settings[ 'action_to_perform' ] = 'get_faq_list';
                
				$script_compiler->class_settings[ 'data' ][ 'faq_list' ] = $support_enquiry->support_enquiry();
				$script_compiler->class_settings[ 'data' ][ 'faq_categories' ] = enquiry_category_frontend();
                
                $script_compiler->class_settings[ 'data' ]['pagepointer'] = $this->class_settings['calling_page'];
                
                $this->page_title = $this->class_settings[ 'project_data' ]['project_title'] .' - ' . ucwords( str_replace('_',' ', $this->class_settings['action_to_perform']) );
			break;
			case 'support':
				$do_not_use_cache = false;
                
                $this->class_settings[ 'js' ][] = 'my_js/custom/' . $filename . '.js';
                
                $support_enquiry = new cSupport_enquiry();
                $support_enquiry->class_settings = $this->class_settings;
                $support_enquiry->class_settings[ 'action_to_perform' ] = 'get_support_ticket_form';
                
				$script_compiler->class_settings[ 'data' ] = $support_enquiry->support_enquiry();
				
                $script_compiler->class_settings[ 'data' ][ 'appsettings' ] = get_appsettings();
                
				$script_compiler->class_settings[ 'data' ][ 'faq_categories' ] = enquiry_category_frontend();
                
                $script_compiler->class_settings[ 'data' ]['pagepointer'] = $this->class_settings['calling_page'];
                
                $this->page_title = $this->class_settings[ 'project_data' ]['project_title'] .' - ' . ucwords( str_replace('_',' ', $this->class_settings['action_to_perform']) );
			break;
			default:
				$do_not_use_cache = false;
                
                //check for page width settings
				$script_compiler->class_settings[ 'data' ][ 'content' ] = get_website_page_content( array( 'id' => $this->class_settings['action_to_perform'] ) );
				$script_compiler->class_settings[ 'data' ][ 'database_table' ] = 'website_page_content';
				$script_compiler->class_settings[ 'data' ][ 'sidebars' ] = get_website_sidebars( array( 'id' => $this->class_settings['action_to_perform'] ) );
                
                $this->page_title = $this->class_settings[ 'project_data' ]['project_title'] .' - ' . ucwords( str_replace('_',' ', $this->class_settings['action_to_perform']) );
			break;
			}
			
            if( $do_not_use_cache ){
                //set cache
                $csettings[ 'cache_values' ] = $script_compiler->class_settings[ 'data' ];
                set_cache_for_special_values( $csettings );
            }
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename.'.php' );
			
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$additional_html = $script_compiler->script_compiler();
			
			if( ! $additional_html ){
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/error-404.php' );
				$additional_html = $script_compiler->script_compiler();
			}
            
			$returning = $this->_setup_website();
			$returning['html'] = $additional_html;
			
			return $returning;
		}
		
		private function _setup_website(){
			
			if( isset( $this->class_settings[ 'bundle-name' ] ) ){
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'bundle-name' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'bundle-name' ];
			}else{
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'action_to_perform' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'action_to_perform' ];
			}
			
			$this->class_settings[ 'js_lib' ] = $this->get_js_lib();
			$this->class_settings[ 'js' ] = $this->get_js();
			$this->class_settings[ 'css' ] = $this->get_css();
			
			$this->class_settings[ 'html' ] = array( 'html-files/static-markup.php' );
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
            
			$script_compiler->class_settings[ 'action_to_perform' ] = 'compile_scripts';
			
			$script_data = $script_compiler->script_compiler();
			
			if( isset( $script_data[ 'js_file' ] ) ){
				$returning[ 'javascript' ] = $script_data[ 'js_file' ];
			}
			
			if( isset( $script_data[ 'css_file' ] ) ){
				$returning[ 'stylesheet' ] = $script_data[ 'css_file' ];
			}
			
			if( isset( $script_data[ 'html_markup' ] ) ){
				$returning[ 'html_markup' ] = $script_data[ 'html_markup' ];
			}
			
			$script_compiler->class_settings[ 'data' ]['title'] = $this->page_title;
			$script_compiler->class_settings[ 'data' ]['keywords'] = $this->page_keywords;
			$script_compiler->class_settings[ 'data' ]['description'] = $this->page_description;
			$script_compiler->class_settings[ 'data' ]['pagepointer'] = $this->class_settings['calling_page'];
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/html-head-tag.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_head_tag' ] = $script_compiler->script_compiler();
			
			$script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
				'menu' => get_website_menu_items( array( 'top_menu_bar_right' , 'top_menu_bar_left' ) ),
                'title_heading' => $this->page_title,
			);
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/header-website.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_header' ] = $script_compiler->script_compiler();
			
			$script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
				'menu' => get_website_menu_items( array( 'footer_menu_item_1' , 'footer_menu_item_2' , 'footer_menu_item_3' , 'footer_menu_item_4' , 'footer_menu_item_5', 'bottom_menu_bar_left' ) ),
			);
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/footer-website.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_footer' ] = $script_compiler->script_compiler();
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function get_js_lib(){
            
			$js_lib[] = 'frontend-assets/plugins/jquery-1.10.2.min.js';
            $js_lib[] = 'js/fileuploader.js';
			$js_lib[] = 'frontend-assets/plugins/jquery-migrate-1.2.1.min.js';
			$js_lib[] = 'frontend-assets/plugins/bootstrap/js/bootstrap.min.js';
			$js_lib[] = 'frontend-assets/plugins/hover-dropdown.js';
			$js_lib[] = 'frontend-assets/plugins/back-to-top.js';
			$js_lib[] = 'frontend-assets/plugins/fancybox/source/jquery.fancybox.pack.js';
			$js_lib[] = 'frontend-assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js';
			$js_lib[] = 'frontend-assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js';
			$js_lib[] = 'frontend-assets/plugins/bxslider/jquery.bxslider.min.js';
			$js_lib[] = 'frontend-assets/scripts/app.js';
			$js_lib[] = 'frontend-assets/scripts/index.js';
            
			$js_lib[] = 'js/ajaxfileupload.js';
			
			$js_lib[] = 'js/amplify.min.js';
			
			if( isset( $this->class_settings[ 'js_lib' ] ) && is_array($this->class_settings[ 'js_lib' ]) ){
				foreach( $this->class_settings[ 'js_lib' ] as $val ){
					$js_lib[] = $val;
				}
			}
			
			return $js_lib;
		}
		
		private function get_js(){
			
            $js[] = 'my_js/ajax-requests.js';
			$js[] = 'my_js/form-handler.js';
			$js[] = 'my_js/navigate.js';
            /*
			$js[] = 'my_js/custom/website.js';
			*/
			if( isset( $this->class_settings[ 'js' ] ) && is_array($this->class_settings[ 'js' ]) ){
				foreach( $this->class_settings[ 'js' ] as $val ){
					$js[] = $val;
				}
			}
			
            //$js[] = 'my_js/custom/select.js';
            
			return $js;
		}
		
		private function get_css(){
			$css[] = 'frontend-assets/plugins/font-awesome/css/font-awesome.min.css';
			$css[] = 'frontend-assets/plugins/bootstrap/css/bootstrap.min.css';
			$css[] = 'frontend-assets/plugins/fancybox/source/jquery.fancybox.css';
			$css[] = 'frontend-assets/plugins/revolution_slider/css/rs-style.css';
			$css[] = 'frontend-assets/plugins/revolution_slider/rs-plugin/css/settings.css';
			$css[] = 'frontend-assets/plugins/bxslider/jquery.bxslider.css';
            
			$css[] = 'frontend-assets/css/style-metronic.css';
			$css[] = 'frontend-assets/css/style.css';
			$css[] = 'frontend-assets/css/themes/blue.css';
			$css[] = 'frontend-assets/css/style-responsive.css';
			$css[] = 'frontend-assets/css/custom.css';
            
			if( isset( $this->class_settings[ 'css' ] ) && is_array($this->class_settings[ 'css' ]) ){
				foreach( $this->class_settings[ 'css' ] as $val ){
					$css[] = $val;
				}
			}
			
			return $css;
		}
		
	}
?>