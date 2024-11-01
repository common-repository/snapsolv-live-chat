<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$chat_script =get_option('chat_script');
$chat_script_status = get_option('chat_script_status');
$html = '';
if($chat_script && $chat_script_status =='true') { 
	$html .='<div class="snap_solv_container">
		
		<h2>Thanks for choosing Snapsolv Chat. </h2>  <br><br><h3>Please check your email to activate Snapsolv account.</h3> <br> <br>
        <h3> Signin to <a href="https://signin.snapsolv.com" target="_blank">Snapsolv dashboard here</a>  to customize the chat widget to your branding needs and to customize your messaging defaults. <br>That’s it, you are all set to engage customers from Snapsolv or download our mobile apps to support on the go.</h3>

		
		<h3>To enable or disable chat go to <a href="'.admin_url( 'admin.php?page=wc-settings&tab=settings_tab_demo', '' ).'" class="">SnapSolv Webchat Settings</a></h3>
	</div>';
} else {

	$html .='<div class="snap_solv_container">
	<h3>Signup to add Snapsolv Chat</h3>
	<div class="snap_banner"><img src="'.plugins_url( 'woocommerce-snapsolv-webchat/assets/images/wp-widget-banner.jpg' ).'"></div>
	<p class="res_cur_msg error"></p>
						<div class="registration-form-snap">
							<form method="post" id="mainform" action="" enctype="multipart/form-data" novalidate="novalidate">
								<table class="form-table">
								
									<tbody>
										<tr valign="top">
											<th scope="row" class="titledesc">
												<label for="wc_settings_tab_demo_brand">Business/Brand Name </label>
											</th>
											<td class="forminp forminp-text">
												<input name="brand_name" id="wc_settings_tab_demo_brand" style="" value="" class="" placeholder="Enter your business name" type="text"> 			
											</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
												<label for="wc_settings_tab_demo_agent_firstname">Account Admin Firstname </label>
											</th>
											<td class="forminp forminp-text">
												<input name="agent_firstname" id="wc_settings_tab_demo_agent_firstname" style="" value="" class="" placeholder="Enter Admin firstname" type="text"> 						
											</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
												<label for="wc_settings_tab_demo_agent_lastname">Account Admin Lastname </label>
											</th>
											<td class="forminp forminp-text">
												<input name="agent_lastname" id="wc_settings_tab_demo_agent_lastname" style="" value="" class="" placeholder="Enter Admin lastname" type="text"> 						
											</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="agent_email">Email address </label>
											</th>
											<td class="forminp forminp-email">
											<input name="agent_email" id="wc_settings_tab_demo_agent_email" style="" value="" class="" placeholder="Enter Account Admin email" type="email"> <p class="nomatch_email error"></p>						</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="wc_settings_tab_demo_password">Account Password</label>
											</th>
											<td class="forminp forminp-text">
											<input name="password" id="wc_settings_tab_demo_password" style="" value="" class="" placeholder="Choose Password" type="password"> <p class="description_password error"></p>						</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="wc_settings_tab_demo_password_again">Confirm Account Password </label>
											</th>
											<td class="forminp forminp-text">
											<input name="password_again" id="wc_settings_tab_demo_password_again" style="" value="" class="" placeholder="Enter Password again" type="password"> <p class="description_password error"></p><p class="description nomatch_password error"></p>						</td>
										</tr>
										<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="wc_settings_tab_demo_agent_phone">Phone Number</label>
											</th>
											<td class="forminp forminp-text">
											<input name="agent_phone" id="wc_settings_tab_demo_agent_phone" style="" value="" class="" placeholder="Enter Account Admin phone" type="text"> 						</td>
										</tr>
										<tr>
												<th>
													
												</th>
												<td>
												   <input type="checkbox" name="term_condition_privacy_policy" id="term_condition_privacy_policy">
												   <label for="for_term_condition_privacy_policy">By submitting my information above, I agree to Snapsolv <a href="https://snapsolv.com/conditions" target="_blank">Terms</a> and <a href="https://snapsolv.com/privacy" target="_blank">Privacy Policies</a></label>
												   <p id="term_condition_privacy_policy-error1" class="error" for="term_condition_privacy_policy"></p>
												</td>
										</tr>
									
										
										
										<tr>
											<td>
											<div id="formLoad" class="formLoad" style="display:none;"><img src="'.plugins_url( 'woocommerce-snapsolv-webchat/assets/images/form-loader.gif' ).'" width="40px" height="40px"></div>
											
											<input name="action" id="" style="" value="send_data_third_part_with_json_format" class="" placeholder="" type="hidden">
										
										<input name="is_thirdparty_signup" id="is_thirdparty_signup" style="" value="true" class="is_thirdparty_signup"  type="hidden"> 
											<input name="save" class="button-primary woocommerce-save-button" value="Signup" type="submit">
											
											</td>
											  
										</tr>		
									    </tr>
										
									</tbody>
								</table>
								'.wp_nonce_field( "snapsolv_form_signup" ).'
							</form>
							<div class="ss_login">
							<p> Already have Snapsolv account? <a href="javascript:void(0)" class="click_for_login">Login here</a></p>
							</div>
						</div>
						<div class="login-form-snap">
							<form method="post" id="mainform1" action="" enctype="multipart/form-data" novalidate="novalidate">
								<table class="form-table">
									<tbody>
										<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="agent_email">Email address </label>
											</th>
											<td class="forminp forminp-email">
											<input name="username" id="wc_settings_tab_demo_agent_email" style="" value="" class="" placeholder="Enter Account Admin email" type="email"> 						            </td>
										</tr>
											<tr valign="top">
											<th scope="row" class="titledesc">
											<label for="wc_settings_tab_demo_password">Account Password </label>
											</th>
											<td class="forminp forminp-text">
											<input name="password" id="wc_settings_tab_demo_password" style="" value="" class="" placeholder="Choose Password" type="password"> 
											<p class=" description_password error"></p>						
											</td>
										</tr>
										
											
											<tr>
												<th>
													
												</th>
												<td>
												   <input type="checkbox" name="term_condition_privacy_policy" id="term_condition_privacy_policy">
												   <label for="for_term_condition_privacy_policy">By submitting my information above, I agree to Snapsolv <a href="https://snapsolv.com/conditions" target="_blank">Terms</a> and <a href="https://snapsolv.com/privacy" target="_blank">Privacy Policies</a></label>
												   <p id="term_condition_privacy_policy-error1" class="error" for="term_condition_privacy_policy"></p>
												</td>
											</tr>
											
										<tr><td>
											<p class="submit-login">
											<input name="action" id="" style="" value="send_data_third_part_with_json_format_for_login" class="" placeholder="" type="hidden">
											<div id="formLoad" class="formLoad" style="display:none;"><img src="'.plugins_url( 'woocommerce-snapsolv-webchat/assets/images/form-loader.gif' ).'" width="40px" height="40px"></div>
											<input name="login" class="button-primary woocommerce-login-button" value="Login" type="submit">
										</p>
											
											</td>
										</tr>
									
									</tbody>
								</table>
								'.wp_nonce_field( "snapsolv_form_signin" ).'
							</form>
							<div class="ss_login">
							<p>Not Registered yet ?<a href="javascript:void(0)" class="click_for_signup">SignUp</a></p>
							</div>
						</div></div>';
		}
	echo $html;					
?>					