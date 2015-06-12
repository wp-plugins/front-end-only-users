<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function User_Search($atts, $content = null) {
	// Include the required global variables, and create a few new ones
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
	
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	
	$UserCookie = CheckLoginCookie();
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				 	'login_page' => '',
					'submit_text' => 'Search Users',
					'display_field' => 'Username',
					'search_fields' => 'Username',
					'login_necessary' => 'Yes',
					'search_logic' => 'AND',
					'user_profile_page' => ''),
					$atts
			)
	);
	
	$search_logic = strtoupper($search_logic);

	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
	
	if (!$UserCookie and $login_necessary == "Yes") {
		$ReturnString .= __("Please log in to access this content.", 'EWD_FEUP'); 
		if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
		return $ReturnString;
	}
	
	if ($_POST['ewd-feup-action'] == "user-search") {
		$Users = Get_User_Search_Results($search_logic, $display_field);
			
		$ReturnString .= "<div class='ewd-feup-user-list-result'>";
		if (is_array($Users)) {
			foreach ($Users as $User) {
				$ReturnString .= "<div class='ewd-feup-user'>";
				foreach ($User as $FieldName => $ReturnField) {
					if ($FieldName != "User_ID") {
						$ReturnString .= "<div class='ewd-feup-user-field'>";
						$ReturnString .= $FieldName . ": ";
						if ($user_profile_page != "") {$ReturnString .= "<a href='" . $user_profile_page . "?User_ID=" . $User['User_ID'] . "'>";}
						$ReturnString .= $ReturnField;
						if ($user_profile_page != "") {$ReturnString .= "</a>";}
						$ReturnString .= "</div>";
					}
				}
				$ReturnString .= "</div>";
			}
		}
		else {
			foreach ($_POST as $field => $value) {
				if (substr($field, 0, 7) == "search_") {
					$DataSet['Criteria'] .= str_replace("_", " ", substr($field, 7));
					$DataSet['Value'] = $value;
					$Criterion[] = $DataSet;
					unset($DataSet);
				}
			}
			if ($search_logic == "AND") {$Linkage_Word = __(" and ", "EWD_FEUP");}
			else {$Linkage_Word = __(" or ", "EWD_FEUP");}
			$ReturnString .= __("No users found where ", "EWD_FEUP");
			foreach ($Criterion as $DataSet) {
				$ReturnString .= $DataSet['Criteria'] . __(" contains ", "EWD_FEUP") . $DataSet['Value'] . $Linkage_Word;
			}
			$ReturnString = substr($ReturnString, 0, -5);
		}
		$ReturnString .= "</div>";
	}
	
	$search_fields_array = explode(",", $search_fields);
	$ReturnString .= "<div id='ewd-feup-login-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-login-form' class='pure-form pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='user-search'>";
	foreach ($search_fields_array as $field) {	
		$field_clean = trim(str_replace(" ", "_", $field));
		$field_clean = str_replace("'", "&#39", $field_clean);
		$ReturnString .= "<div class='pure-control-group'>";
		$ReturnString .= "<label for='" . $field . "' id='ewd-feup-" . $field_clean . "-div' class='ewd-feup-field-label'>" . $field . ": </label>";
		$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='search_" . $field_clean . "' placeholder='" .$field . "...'>";
		$ReturnString .= "</div>";
	}
	$ReturnString .= "<div class='pure-control-group'>";
	$ReturnString .= "<label for='Submit'></label><input type='submit' class='ewd-feup-submit pure-button pure-button-primary' name='Search_Submit' value='" . $submit_text . "'>";
	$ReturnString .= "</div>";
	$ReturnString .= "</form>";
	$ReturnString .= "</div>";
	
	return $ReturnString;
}
add_shortcode("user-search", "User_Search");

