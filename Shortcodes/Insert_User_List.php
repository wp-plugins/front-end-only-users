<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function User_List($atts, $content = null) {
		// Include the required global variables, and create a few new ones
		global $wpdb;
		global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		
		$UserCookie = CheckLoginCookie();
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'login_page' => '',
																'field_name' => '',
																'field_value' => ''),
																$atts
														)
												);
		
		if (!$UserCookie) {
			  $ReturnString .= __("Please log in to access this content.", 'EWD_FEUP'); 
				if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
				return $ReturnString;
		}
		
		if ($field_name == ""  or $field_value == "") {
			  $ReturnString .= __("Either field_name or field_value was left blank. Please make sure to include both attributes inside your shortcode.", 'EWD_FEUP'); 
				return $ReturnString;
		}
		
		$UserIDs = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value='%s'", $field_name, $field_value));
		foreach ($UserIDs as $UserID) {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $UserID->User_ID));
				$Usernames[] = $User->Username;
		}
		
		if (is_array($Usernames)) {
			  foreach ($Usernames as $Username) {
						$ReturnString .= "<div class='ewd-feup-user-list-result'>" . $Username . "</div>";
				}
		}
		
		return $ReturnString;
}
add_shortcode("user-list", "User_List");

