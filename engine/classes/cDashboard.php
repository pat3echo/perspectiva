<?php
	/**
	 * dashboard Class
	 *
	 * @used in  				dashboard Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	dashboard
	 */

	/*
	|--------------------------------------------------------------------------
	| dashboard Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cDashboard{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'dashboard';
		
		private $table_fields = array();
		
        private $page_title = 'Zidoff.com - Global Online Shopping for Electronics, Fashion, Computers, Books & much more | http://www.zidoff.com';
        
		private $page_keywords = 'Zidoff, eCommerce, Zidoff.com, Zidoff eCommerce Limited, Shop from global merchants, global merchants, jumia, ecommerce, konga, africa, nigeria, ebay, amazon, sell, online store, own a shop, free store, international shipping, international, delivery, shipping, free shipping, start selling, sell, increase sales, become a seller, merchant, aliexpress, alibaba, buy, shop';
        
		private $page_description = 'The Zidoff global ecommerce platform operates a B2C (Business to consumer) model that provides secured and reliable online trade and marketing service for importers and exporters. Users can purchase from a wide variety of products located and marketed from over 251 countries.';
        
		function __construct(){
			
		}
	
		function dashboard(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cProduct_features.php';
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
			case 'setup_dashboard':
				$returned_value = $this->_setup_dashboard();
			break;
			case 'get_dashboard_notice_row':
				$returned_value = $this->_get_dashboard_notice_row();
			break;
			case 'get_dashboard_notification_task_row':
				$returned_value = $this->_get_dashboard_notification_task_row();
			break;
			case 'get_read_notification':
			case 'get_all_notification':
			case 'get_unread_notification':
				$returned_value = $this->_get_dashboard_notification();
			break;
			case 'get_pending_task':
			case 'get_completed_task':
			case 'get_all_task':
				$returned_value = $this->_get_dashboard_task();
			break;
			case 'update_user_profile':
				$returned_value = $this->_update_user_profile();
			break;
			case 'populate_dashboard':
				$returned_value = $this->_populate_dashboard();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_general_settings(){
			$general_settings = new cGeneral_settings();
			$general_settings->class_settings = $this->class_settings;
			$general_settings->class_settings[ 'action_to_perform' ] = 'get_general_settings';
			return $general_settings->general_settings();
		}
		
		private function _get_dashboard_notification(){
			$notifications = new cNotifications();
			$notifications->class_settings = $this->class_settings;
			$notification = $notifications->notifications();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'notification' => $notification,
			);
			
			if( empty( $notification ) ){
				$returning[ 'html' ] = 'No Notifications';
			}else{
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notification-cell-item.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html' ] = $script_compiler->script_compiler();
			}
			$returning[ 'status' ] = 'got-dashboard-notification';
			
			return $returning;
		}
		
		private function _get_dashboard_task(){
			$tasks = new cTasks();
			$tasks->class_settings = $this->class_settings;
			$task = $tasks->tasks();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'pending_task' => $task,
			);
			
			if( empty( $task ) ){
				$returning[ 'html' ] = 'No Task';
			}else{
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-task-cell-item.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html' ] = $script_compiler->script_compiler();
			}
			$returning[ 'status' ] = 'got-dashboard-task';
			
			return $returning;
		}
		
		private function _get_dashboard_notification_task_row(){
			$tasks = new cTasks();
			$tasks->class_settings = $this->class_settings;
			$tasks->class_settings[ 'action_to_perform' ] = 'get_pending_task';
			$pending_task = $tasks->tasks();
			
			$notifications = new cNotifications();
			$notifications->class_settings = $this->class_settings;
			$notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification';
			$unread_notification = $notifications->notifications();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array( 
				'pending_task' => $pending_task,
				'notification' => $unread_notification,
			);
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notification-task-row.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] = $script_compiler->script_compiler();
			
			return $returning;
		}
		
		private function _get_dashboard_notice_row(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
                'notice' => $this->_notice_row_data(),
            );
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notice-row.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] = $script_compiler->script_compiler();
			
			return $returning;
		}
		
		private function _setup_dashboard(){
			
            //check for logged in user
            if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id'] ) ){
                header('Location: ?page=login');
            }
            
            $user_details = get_site_user_details( array( 'id' => $this->class_settings['user_id'] ) );
            if( empty( $user_details ) ){
                header('Location: ?page=login');
            }
            
			if( isset( $this->class_settings[ 'bundle-name' ] ) ){
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'bundle-name' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'bundle-name' ];
			}else{
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'action_to_perform' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'action_to_perform' ];
			}
			
			//$this->class_settings[ 'js_lib' ] = array( 'js/jquery-1.7.1.min.js', 'js/fileuploader.js' ,'js/tiny_mce/jquery.tinymce.min.js' , 'js/tiny_mce/tinymce.min.js' , 'js/ui/jquery-ui-1.10.3.custom.min.js' , 'bootstrap-2.3.2/docs/assets/js/html5shiv.js' , 'js/amplify.min.js', 'site-admin-assets/js/bootstrap.js' , 'site-admin-assets/js/plugins/metisMenu/metisMenu.min.js' , 'site-admin-assets/js/plugins/morris/raphael.min.js' , 'site-admin-assets/js/sb-admin-2.js', 'js/jquery.dataTables.js' );
		
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
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/html-head-tag.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_head_tag' ] = $script_compiler->script_compiler();
			
            $product = new cProduct();
            $product->class_settings = $this->class_settings;
            $product->class_settings[ 'action_to_perform' ] = 'get_product_categories';
            
            $product->class_settings['ignore_selection'] = 1;
            
			$script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
				'menu' => get_website_menu_items( array( 'top_menu_bar_right' , 'top_menu_bar_left' ) ),
				'dashboard_menu' => $this->get_dashboard_menu(),
				'categories' => $product->product(),
                'terminating_categories' => get_array_of_all_terminating_categories(),
                'countries' => get_countries_data(),
			);
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/header-dashboard.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_header' ] = $script_compiler->script_compiler();
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function get_dashboard_menu(){
			return array(
				1 => array(
					'text' => 'Dashboard',
					'title' => 'Dashboard',
					'link' => '?page=user-dashboard',
					'icon-class' => 'fa fa-dashboard fa-fw',
				),
                
				2 => array(
					'text' => 'Notifications',
					'title' => 'View all Notifications',
					'link' => '?action=notifications&todo=site_notifications_manager',
					'icon-class' => 'fa fa-bell fa-fw',
				),
				3 => array(
					'text' => 'Tasks',
					'title' => 'View all Tasks',
					'link' => '?action=tasks&todo=site_tasks_manager',
					'icon-class' => 'fa fa-tasks fa-fw',
				),
                
				4 => array(
					'text' => 'Payment History',
					'title' => 'View / Manage Payment Status',
					'link' => '?action=order&todo=site_order_manager',
					'icon-class' => 'fa fa-bar-chart-o fa-fw',
				),
				5 => array(
					'text' => 'Bids History',
					'title' => 'View all your Bids',
					'link' => '?action=bids&todo=site_bids_manager',
					'icon-class' => 'fa fa-bar-chart-o fa-fw',
				),
				6 => array(
					'text' => 'Selling <span class="fa arrow"></span>',
					'title' => 'Sell',
					'link' => '#',
					'icon-class' => 'fa fa-upload fa-fw',
					'children' => array(
						1 => array(
							'text' => 'Inventory Manager',
							'title' => 'Add / Manage products for sale',
							'link' => '?action=product&todo=site_inventory_manager',
						),
						2 => array(
							'text' => 'Auctioned Items',
							'title' => 'View items placed on auction',
							'link' => '?action=auctioned_product&todo=site_auctioned_items_manager',
						),
						3 => array(
							'text' => 'Advertised Items',
							'title' => 'View items being advertised',
							'link' => '?action=advertised_product&todo=site_advertised_items_manager',
						),
						4 => array(
							'text' => 'Sold Items',
							'title' => 'View items that were purchased',
							'link' => '?action=order&todo=site_sales_stats_manager',
						),
						7 => array(
							'text' => 'Store Manager',
							'title' => 'Configure & Manage your store',
							'link' => '?action=store&todo=site_store_manager',
						),
						9 => array(
							'text' => 'My Store Banners',
							'title' => 'Upload your customized store banners',
							'link' => '?action=store_banners&todo=custom_store_banners_manager',
						),
						10 => array(
							'text' => 'Shipping Options Manager',
							'title' => 'Configure & Manage your shipping options',
							'link' => '?action=custom_shipping_options&todo=site_custom_shipping_options_manager',
						),
						11 => array(
							'text' => 'Coupons Manager',
							'title' => 'Create & Manage your sales coupons',
							'link' => '?action=coupons&todo=coupons_manager',
						),
						12 => array(
							'text' => 'Pay On Delivery Manager',
							'title' => 'Create & Manage locations where you offer the pay on delivery service',
							'link' => '?action=pay_on_delivery&todo=pay_on_delivery_manager',
						),
					),
				),
				7 => array(
					'text' => 'My Finance <span class="fa arrow"></span>',
					'title' => 'View all my Financial Information',
					'link' => '#',
					'icon-class' => 'fa fa-upload fa-fw',
					'children' => array(
						1 => array(
							'text' => 'Bank Account Manager',
							'title' => 'Verify / Activate Bank Accounts',
							'link' => '?action=merchant_accounts&todo=bank_account_manager',
						),
						2 => array(
							'text' => 'Request A Payout',
							'title' => 'Request A Payout',
							'link' => '?action=payout_requests&todo=payout_requests_manager',
						),
					),
				),
				20 => array(
					'text' => 'My Profile',
					'title' => 'Manage My Profile',
					'link' => '?action=site_users&todo=display_user_details',
					'icon-class' => 'fa fa-user fa-fw',
				),
			);
		}
		
		private function get_js_lib(){
			$js_lib[] = 'js/jquery-1.7.1.min.js';
			$js_lib[] = 'js/fileuploader.js';
			$js_lib[] = 'js/tiny_mce/jquery.tinymce.min.js';
			$js_lib[] = 'js/tiny_mce/tinymce.min.js';
			$js_lib[] = 'site-admin-assets/js/bootstrap.js';
			/*
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-collapse.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-transition.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-alert.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-modal.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-dropdown.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-scrollspy.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-tab.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-tooltip.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-popover.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-button.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-typeahead.js';
			*/
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/html5shiv.js';
			$js_lib[] = 'js/amplify.min.js';
			$js_lib[] = 'site-admin-assets/js/plugins/metisMenu/metisMenu.min.js';
			$js_lib[] = 'site-admin-assets/js/plugins/morris/raphael.min.js';
			$js_lib[] = 'site-admin-assets/js/sb-admin-2.js';
            
			if( isset( $this->class_settings[ 'js_lib' ] ) && is_array($this->class_settings[ 'js_lib' ]) ){
				foreach( $this->class_settings[ 'js_lib' ] as $val ){
					$js_lib[] = $val;
				}
			}
			
            $js_lib[] = 'js/chosen.jquery.js';
            $js_lib[] = 'css/docsupport/prism.js';
            
			return $js_lib;
		}
		
		private function get_js(){
			$js[] = 'my_js/ajax-requests.js';
			$js[] = 'my_js/form-handler.js';
			$js[] = 'my_js/navigate.js';
			
			if( isset( $this->class_settings[ 'js' ] ) && is_array($this->class_settings[ 'js' ]) ){
				foreach( $this->class_settings[ 'js' ] as $val ){
					$js[] = $val;
				}
			}
			$js[] = 'my_js/custom/dashboard.js';
            $js[] = 'my_js/custom/select.js';
            
			return $js;
		}
		
		private function get_css(){
			$css[] = 'site-admin-assets/css/bootstrap.css';
			$css[] = 'css/form.css';
			$css[] = 'site-admin-assets/css/plugins/metisMenu/metisMenu.min.css';
			$css[] = 'site-admin-assets/css/plugins/timeline.css';
			$css[] = 'site-admin-assets/css/sb-admin-2.css';
			$css[] = 'site-admin-assets/css/plugins/dataTables.bootstrap.css';
			$css[] = 'site-admin-assets/css/plugins/morris.css';
			$css[] = 'site-admin-assets/font-awesome-4.1.0/css/font-awesome.css';
			$css[] = 'css/template-1/dashboard_welcome_message.css';
			$css[] = 'bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css';
			$css[] = 'css/chosen.css';
            
			if( isset( $this->class_settings[ 'css' ] ) && is_array($this->class_settings[ 'css' ]) ){
				foreach( $this->class_settings[ 'css' ] as $val ){
					$css[] = $val;
				}
			}
			
			return $css;
		}
        
        private function _notice_row_data(){
            //check for verified bank account
            $merchants_accounts = get_merchant_accounts_details( array( 'id' => $this->class_settings['user_id'] ) );
            
            $bank_account_verified_status = array(
                'title' => 'Verify Bank Account',
                'link_caption' => 'Pending',
                'link_title' => 'Click here to verify your bank account',
                'link' => '?action=merchant_accounts&todo=bank_account_manager',
                'icon' => 'none',
                'status' => 'pending',
                'status_icon' => 'fa-times-circle',
                'theme' => 'panel-default',
            );
            
            $listed_product_status = array(
                'title' => 'List an Item for Sale',
                'link_caption' => 'Pending',
                'link_title' => 'Click here to list an item for sale',
                'link' => '?action=product&todo=site_inventory_manager',
                'icon' => 'none',
                'status' => 'pending',
                'status_icon' => 'fa-times-circle',
                'theme' => 'panel-default',
            );
                    
			if( isset( $merchants_accounts['active'] ) && strtolower( $merchants_accounts['active'] ) != 'no' ){
				$bank_account_verified_status = array(
                    'title' => 'Verify Bank Account',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-yellow',
                );
                
                //check for listed products
                $product = new cProduct();
                $product->class_settings = $this->class_settings;
                $product->class_settings[ 'action_to_perform' ] = 'get_merchant_products_count';
                $items_count = $product->product();
                
                if( $items_count ){
                    $listed_product_status = array(
                        'title' => 'List an Item for Sale',
                        'link_caption' => 'Completed',
                        'link_title' => '',
                        'link' => '#',
                        'icon' => 'none',
                        'status' => 'done',
                        'status_icon' => 'fa-check-circle',
                        'theme' => 'panel-red',
                    );
                    
                    $notifications = new cNotifications();
                    $notifications->class_settings = $this->class_settings;
                    $notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification_count';
                    $notification = $notifications->notifications();
                    
                    $tasks = new cTasks();
                    $tasks->class_settings = $this->class_settings;
                    $tasks->class_settings[ 'action_to_perform' ] = 'get_pending_task_count';
                    $task = $tasks->tasks();
                    
                    $product->class_settings[ 'action_to_perform' ] = 'get_merchant_sales_count';
                    $sales_count = $product->product();
                    
                    return array(
                        1 => array(
                            'title' => 'Pending Task',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $task,
                            'status_icon' => 'fa-tasks',
                            'theme' => 'panel-primary',
                        ),
                        2 => array(
                            'title' => 'Notifications',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $notification,
                            'status_icon' => 'fa-bell',
                            'theme' => 'panel-green',
                        ),
                        3 => array(
                            'title' => 'Sales',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $sales_count,
                            'status_icon' => 'fa-shopping-cart',
                            'theme' => 'panel-yellow',
                        ),
                        4 => array(
                            'title' => 'Inventory',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => doubleval($items_count),
                            'status_icon' => 'fa-support',
                            'theme' => 'panel-red',
                        ),
                    );            
                }
			}
            
            return array(
                1 => array(
                    'title' => 'Review Our Policies',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-primary',
                ),
                2 => array(
                    'title' => 'Create an Account',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-green',
                ),
                3 => $bank_account_verified_status,
                4 => $listed_product_status,
            );
        }
        
        private function _populate_dashboard(){
            $return = array();
            $return['status'] = 'displayed-dashboard';
            $return['html'] = '';
            return $return;
            
            $script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
            
            $site_users = new cSite_users();
            $site_users->class_settings = $this->class_settings;
            $site_users->class_settings[ 'action_to_perform' ] = 'get_newly_registered_users';
            
            $support_enquiry = new cSupport_enquiry();
            $support_enquiry->class_settings = $this->class_settings;
            $support_enquiry->class_settings[ 'action_to_perform' ] = 'get_recent_support_enquiry';
            
            $order = new cOrder();
            $order->class_settings = $this->class_settings;
            $order->class_settings[ 'action_to_perform' ] = 'get_recent_order';
            
            $product = new cProduct();
            $product->class_settings = $this->class_settings;
            $product->class_settings[ 'action_to_perform' ] = 'get_newly_listed_products';
            $nl = $product->product();
            
            $product->class_settings[ 'action_to_perform' ] = 'get_expiring_products';
            $el = $product->product();
            
            $product->class_settings[ 'action_to_perform' ] = 'get_inactive_products';
            $il = $product->product();
            
            $script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
                'notice' => array( 
                    'newly_registered_users' => array( 
                        'title' => 'Newly Registered Users', 
                        'data' => $site_users->site_users(), 
                        'field' => 'email', 
                        'table' => 'site_users',
                        'id_field' => 'id',
                        'date' => 'creation_date' 
                    ),
                    'recent_enquiries' => array( 
                        'title' => 'Recently Opened Tickets', 
                        'data' => $support_enquiry->support_enquiry(),
                        'field2' => 'ask_us_anything', 
                        'field' => 'email_address', 
                        'date' => 'creation_date' 
                    ),
                    'recent_transactions' => array( 
                        'title' => 'Recent Transactions', 
                        'data' => $order->order(),
                        'field2' => 'description', 
                        'field' => 'total',
                        
                        'table' => 'order',
                        'id_field' => 'id',
                        
                        'date' => 'creation_date' 
                    ),
                    'new_listings' => array( 
                        'title' => 'Newly Listed Items', 
                        'data' => $nl,
                        'table' => 'product',
                        'id_field' => 'serial_num',
                        //'field2' => 'category_text', 
                        'field' => 'product_name', 
                        'date' => 'listing_date' 
                    ),
                    'expiring_listings' => array( 
                        'title' => 'Items Expiring Soon', 
                        'data' => $el,
                        'table' => 'product',
                        'id_field' => 'serial_num',
                        //'field2' => 'category_text', 
                        'field' => 'product_name', 
                        'date' => 'expiry_date_timestamp' 
                    ),
                    'inactive_listings' => array( 
                        'title' => 'Inactive Items', 
                        'data' => $il,
                        'table' => 'product',
                        'id_field' => 'serial_num',
                        //'field2' => 'category_text', 
                        'field' => 'product_name', 
                        'date' => 'modification_date' 
                    ),
                ),
			);
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/admin-dashboard.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$return['html'] = $script_compiler->script_compiler();
            
            $return['status'] = 'displayed-dashboard';
            
            return $return;
        }
    }
?>