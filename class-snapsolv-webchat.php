<?php
/**
 * Snapsolv Live Chat
 * @package SW
 * @author  sureshv@snapsolv.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Snapsolv_Webchat {
	
	/**
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';
	
	
    /**
     * The class and hooks required actions & filters.
     *
     */
    function __construct() 
	{
        add_filter( 'woocommerce_settings_tabs_array', array(&$this,'add_settings_tab'), 50);
        add_action( 'woocommerce_settings_tabs_settings_tab_demo',array(&$this,'settings_tab' ));
		add_action( 'admin_init', array( $this, 'start' ) );
		//add custom style and js
		add_action( 'admin_enqueue_scripts', array( &$this, 'custom_eneque_js_css') );
		//send_data_third_part_with_json_format
		add_action("wp_ajax_send_data_third_part_with_json_format", array(&$this, "send_data_third_part_with_json_format"));
		add_action("wp_ajax_nopriv_send_data_third_part_with_json_format", array(&$this, "send_data_third_part_with_json_format"));
		//send_data_third_part_with_json_format_for_login
		add_action("wp_ajax_send_data_third_part_with_json_format_for_login", array(&$this, "send_data_third_part_with_json_format_for_login"));
		add_action("wp_ajax_nopriv_send_data_third_part_with_json_format_for_login", array(&$this, "send_data_third_part_with_json_format_for_login"));
		//reset_snap_settings
		add_action("wp_ajax_reset_snap_settings", array(&$this, "reset_snap_settings"));
		add_action("wp_ajax_nopriv_reset_snap_settings", array(&$this, "reset_snap_settings"));
		add_action( 'wp_head', array(&$this, 'add_chat_in_footer_according_to_page_seting'));
		add_action('admin_menu', array(&$this, 'register_custom_submenu_page_for_woocommece_snapsolv'),999);

    }	
		
	public function woocommerce_is_active() 
	{
		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}
	
	public function start() {
		if ( ! $this->woocommerce_is_active() ) {
			deactivate_plugins( 'woocommerce-snapsolv-webchat/woocommerce-snapsolv-webchat.php' );
			unset( $_GET['activate'] ); // Input var okay.
			wp_die( 'This plugin requires woocommerce activate.  So please active woocommerce first.' );
		 }
	 }

     /**
	 *custom_eneque_js_css
	 */
	 public function custom_eneque_js_css(){
		    
			wp_enqueue_style('custom-style-css', plugin_dir_url( __FILE__ ) . 'assets/css/custom.css');
			wp_enqueue_script('custom-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js');
			wp_enqueue_script('jquery-validate-min', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.validate.min.js');
		   
		 }
		 
	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['settings_tab_demo'] = __( 'Snapsolv Live Chat Settings', 'woocommerce-settings-tab-demo' );
		return $settings_tabs;
	}
	
    /**
     * Add Seting HTML For Showing Chat Script
     */
    public  function settings_tab() 
	{
		
		if ( current_user_can( 'manage_woocommerce' ) ) {
			$this->add_setting_for_showing_chat_script();
			$html = '';
			$chat_script =get_option('chat_script');
			$chat_script_status = get_option('chat_script_status');
			if($chat_script && $chat_script_status =='true') { 
				$configuation_link = '';
			} else {
				$configuation_link = '<h3 class="description"><a href="'.admin_url( 'admin.php?page=custom_submenu_page_for_woocommece_snapsolv', '' ).'">Click To Signin or Signup if not registered yet</a></h3>';
			}
			$chat_on_off = get_option('chat_on_off');
			$show_chat_on_page = json_decode(get_option('show_chat_on_page'));
			//print_r($show_chat_on_page);
			$display_none_block ='';
			if($chat_on_off == 'on') {
				$on ='checked=checked';
				$off ='';
				$display_none_block = "display:table-row";
				if (isset($show_chat_on_page[0]) && !empty($show_chat_on_page[0])){ 
					$display_none_block_button = '';
				} else {
					$display_none_block_button = 'disabled="disabled"';
				}
			} else {
				$on ='';
				$off ='checked=checked';
				$display_none_block = "display:none";
				$display_none_block_button = '';
			}
			
			if(!$chat_script) { 
				$display_none_block_button = 'disabled="disabled"';
				$chat_script_status = 0;
			} else {
				$chat_script_status = 1;
			}
			   
			$html .='<div class="snap_solv_container_settings">
				<h2>To change your settings for Chat widget or Messaging defaults, login into your <a href="https://signin.snapsolv.com" target="_blank">Snapsolv account here</a> and go to Settings and choose Live Chat</h2>'.$configuation_link.'
				<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row" class="titledesc">
										<label for="wc_settings_tab_demo_agent_firstname">Enable Chat on Website? :</label>
									</th>
									<td class="forminp forminp-text"> 						
										<p>
											<label for="wc_settings_tab_demo_agent_firstname">Yes </label>
											<input type="radio" class="chat_on_off" name="chat_on_off" value="on" '.$on.'>
										</p>
										<p>
											<label for="wc_settings_tab_demo_agent_firstname">No </label>
											<input type="radio" class="chat_on_off" name="chat_on_off" value="off" '.$off.'>
										</p>
									</td>
								</tr>	
								<tr class="select-drop-pages" valign="top" style="'.$display_none_block.'">
									<th scope="row" class="titledesc">
										<label for="wc_settings_tab_demo_agent_firstname">Select pages for chat:</label>
									</th>
									<td class="forminp forminp-text"> 	'.$this->multiselection_for_pages().' 
										
									</td>
								</tr>
								<tr class="select-save-button">
								<td><input name="save" class="button-primary woocommerce-save-changes-button " value="Save changes" type="submit" '.$display_none_block_button.'></td>
								</tr>
								';	
			if($configuation_link =='') {
				$html .='<input name="reset"   value="Reset all" class="button-primary woocommerce-reset-button" placeholder="" type="button">';
			}else {
				$html .='<input name="reset"   value="Reset all" class="button-primary woocommerce-reset-button" placeholder="" type="button" disabled="disabled">';
				}
				$html .='<input name="action"   value="save_data_general_setting_for_snap" class="button-primary woocommerce-reset-button" placeholder="" type="hidden"> 
						<input value="'.$chat_script_status.'" id="chat-script-status" type="hidden" />
							</tbody>
						</table></div>';
			echo $html;
		} else {
			throw new Exception( __( 'You do not have permissions to access this page!', 'woocommerce' ) );
		}
    }
	
    /*
	*send_data_third_part_with_json_format
	* when signup
	* Return script
	*/
	public function send_data_third_part_with_json_format(){
		if ( current_user_can( 'manage_woocommerce' ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'snapsolv_form_signup' )) {
			if($_POST['action'] =='send_data_third_part_with_json_format') {
				
				$name = sanitize_text_field( $_POST["brand_name"] );
				$brand_name =  sanitize_text_field( $_POST['brand_name']);
				$agent_firstname =  sanitize_text_field( $_POST["agent_firstname"]);
				$agent_lastname = sanitize_text_field( $_POST["agent_lastname"]);
				$agent_email =  sanitize_email( $_POST["agent_email"]);
				$username =  sanitize_text_field( $_POST["agent_email"]);
				$password =  sanitize_text_field( $_POST["password"]);
				$agent_phone =  sanitize_text_field( $_POST["agent_phone"]);
				
				$body = array(
					"account_type"=>'prm',
					"name"=> $name,
					"brand_name"=> $brand_name,
					"agent_firstname"=> $agent_firstname,
					"agent_lastname"=> $agent_lastname,
					"agent_email"=> $agent_email,
					"username"=> $username,
					"password"=> $password,
					"agent_phone"=> $agent_phone,
					"is_thirdparty_signup"=>true,
					"third_party_source" => "WooCommerce",
				);
				
				$args = array(
					'body' => $body
				);

				
				  $url='https://api.snapsolv.com:3060/company/create?type=account';
				  $buffer = wp_remote_post( $url, $args );
				  if ( is_wp_error(  $buffer ) ) {
					   $error_message = $buffer->get_error_message();
					   $output['status'] .= "fail";
					   $output['message'] = "Something went wrong";
				  } else {
					  if (!isset($buffer['body'])){
						$output['nothing'] .="<p>Nothing returned from url.</p>";  
					  } else{
						  $buffer_data = json_decode($buffer['body'], true);
						  if($buffer_data['result'] =='Failure' || $buffer_data['message']){
							 update_option('chat_script_status','false', 'yes');
							 update_option('chat_script',$buffer_data['message'], 'yes'); 
							 $output['status'] .= "fail";
							 $output['message'] = $buffer_data['message'];
						  } else {
							 update_option('chat_script_status', 'true', 'yes');
							 update_option('chat_script',$buffer_data['widgetScript'], 'yes');
							 update_option('chat_script_data',$data_ser, 'yes');
							 $output['status'] .=  "success"; 
							}
					  }
				  }
			}
			$output = json_encode($output);
			echo $output; exit; 
		} else {
			throw new Exception( __( 'You do not have permissions to access this page!', 'woocommerce' ) );
		}
  }
	
	/*
	*send_data_third_part_with_json_format_for_login
	*
	*/	
	public function send_data_third_part_with_json_format_for_login(){
		
		if ( current_user_can( 'manage_woocommerce' ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'snapsolv_form_signin' )) {
			if($_POST['action'] =='send_data_third_part_with_json_format_for_login') {
				$username = sanitize_text_field( $_POST["username"]);
				$password = sanitize_text_field( $_POST["password"]);
				$body = array(
					"username"=> $username,
					"password"=> $password,
				);
				
				$args = array(
					'body' => $body
				);
				
				$url='https://api.snapsolv.com:3060/account/get_chat_script';
				$buffer = wp_remote_post( $url, $args );
				
				if ( is_wp_error(  $buffer ) ) {
				   $error_message = $buffer->get_error_message();
				   $output['status'] .= "fail";
				   $output['message'] = "Something went wrong";
				} else {
					if (!isset($buffer['body'])){
						$output['nothing'] .="Nothing returned from url.";
					} else {
						$buffer_data = json_decode($buffer['body'], true);
						if(($buffer_data['result'] == '' && $buffer_data['widgetScript'] =='') || $buffer_data['result'] == 'Failure'){
							 update_option('chat_script_status','false', 'yes');
							 update_option('chat_script',$buffer_data['message'], 'yes'); 
							 $output['status'] .= "fail";
							 $output['message'] = 'Unauthorized login details.';
						} else {
							 update_option('chat_script_status', 'true', 'yes');
							 update_option('chat_script',$buffer_data['widgetScript'], 'yes');
							 update_option('chat_script_data',$data_ser, 'yes');
							 $output['status'] .=  "success"; 
						}
					}
				}
			}
			$output = json_encode($output);
			echo $output; exit; 
		
		} else {
			throw new Exception( __( 'You do not have permissions to access this page!', 'woocommerce' ) );
		}
		
	}
	/*
	*reset_snap_settings
	*
	*/
	public function reset_snap_settings(){
		$output = array();
		if($_POST['action'] =='reset_snap_settings'){
			delete_option( 'chat_script' );
			delete_option( 'chat_script_status' );
			delete_option( 'chat_script_data' );
			delete_option( 'chat_on_off' );
			delete_option( 'show_chat_on_page' );
			$output['reset_data'] .= 'yes';
		 }
		 $output = json_encode($output);
		 echo $output; exit;    
		}
		
	 /*
	  * Multiselection dropdown for pages
	  */	
	  public function multiselection_for_pages(){
	
	      $string = '';
	
		  $post_types = array('page');
		  
		  $frontpage_id = ( get_option( 'page_on_front' ) ) ? get_option( 'page_on_front' ) : get_option( 'page_for_posts' );
		  
		  if($frontpage_id){
			  $frontPage = get_post($frontpage_id);
			  $frontPageName =  $frontPage->post_title;
		  } else {
			  $frontpage_id = '00001';
			  $frontPageName =  'Home page';
		  }
		  $chatOption = get_option('show_chat_on_page');
		  $show_chat_on_page = ($chatOption) ? (array)json_decode($chatOption) : array();
		  if ( $post_types ) { // If there are any custom public post types.
			$string .='<select name="show_chat_on_page[]" multiple="multiple" id="show_chat_on_page" class="show_chat_on_page">';
			$string .='<option value="">--------------Select option--------------</option>';
			
			if(in_array($frontpage_id, $show_chat_on_page)){
				$selected = "selected=selected";
			} else{
				$selected ="";
			}
			
			$string .='<option value="'.$frontpage_id.'" '.$selected.'>'.$frontPageName.'</option>';
			foreach ( $post_types  as $post_type ) {
				$args = array('post_type' => $post_type, 'post_status' => 'publish', 'post_per_pages' => -1);
				$posts  = get_posts($args);
				foreach($posts as $post){
					if($show_chat_on_page) {
						if(in_array($post->ID, $show_chat_on_page)){
							$selected = "selected=selected";
						} else{
							$selected ="";
						}
					}
					if( $frontpage_id != $post->ID){
						$string .='<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.'</option>';
					}
				}
			}
			$string .='</select>';		
		}
		return $string;
	}
	
	 /*
	  * Add update chat in db
	  */
	public function add_setting_for_showing_chat_script()
	{
		if(isset($_POST['action']) && $_POST['action'] =='save_data_general_setting_for_snap') {
			//print_r($_POST);
			//die;
			$chat_script = ( isset($_POST['chat_script'])) ? sanitize_text_field( $_POST['chat_script']) : '';
			$on_off_option = sanitize_text_field( $_POST['chat_on_off'] ); 

			if(isset($_POST['chat_on_off'])){
				$chat_on_off = sanitize_text_field($_POST['chat_on_off']);
				update_option('chat_on_off',  $chat_on_off);
				
			}	 
			if(isset($_POST['show_chat_on_page'])){
				$show_chat_on_page = isset( $_POST['show_chat_on_page'] ) ? (array) $_POST['show_chat_on_page'] : array();
				$show_chat_on_page = json_encode($show_chat_on_page);
				if(get_option('show_chat_on_page')) {
					update_option('show_chat_on_page',  $show_chat_on_page, 'yes');
				} else {
					update_option('show_chat_on_page',  $show_chat_on_page, 'yes'); 
				}
			}     			  
		}
	}
		  
	/*
	* Show chat in footer according to  page seting
	*/
	function add_chat_in_footer_according_to_page_seting() {
		
		$show_chat_on_page = json_decode(get_option('show_chat_on_page'));
		
		if(get_option('chat_on_off') =='on'){
			if (isset($show_chat_on_page[0]) && !empty($show_chat_on_page[0])){ 
				foreach ($show_chat_on_page as $key=>$value) {
					if($value){
						if(is_page($value)) {
							echo get_option('chat_script');
						}  elseif( $value == 0001 && is_front_page()){
							echo get_option('chat_script');
						}
					} 
				}
			}
		} 
	}
	/*
	* Register Custom menu for SnapSolv WooCommerce
	*
	*/
	public function register_custom_submenu_page_for_woocommece_snapsolv() {
		add_submenu_page( 'woocommerce', 'Snapsolv Live Chat', 'Snapsolv Live Chat', 'manage_options', 'custom_submenu_page_for_woocommece_snapsolv', array(&$this, 'custom_submenu_page_for_woocommece_snapsolv_callback') ); 
	}
	/*
	* Add content in SnapSolv custom menu page
	*
	*/
	public function custom_submenu_page_for_woocommece_snapsolv_callback() {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			include('assets/admin/snapsolv-signup-login.php');
		} else {
			throw new Exception( __( 'You do not have permissions to access this page!', 'woocommerce' ) );
		}
	}
}