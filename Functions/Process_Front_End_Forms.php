<?php

function Process_EWD_FEUP_Front_End_Forms() {
		global $user_message;
		
		if (isset($_POST['ewd-feup-action'])) {
			  switch ($_POST['ewd-feup-action']) {
						case "register":
						case "edit-profile":
								 $user_message = Add_Edit_User();
								 break;
						case "login":
								 $user_message['Message'] = Confirm_Login();
								 break;
				}
		}
}

function Confirm_Login() {
		global $wpdb, $feup_success;
		global $ewd_feup_user_table_name;
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
		$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
		
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_Password, User_Email_Confirmed, User_Admin_Approved FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Username']));
		
		if (sha1(md5($_POST['User_Password'].$Salt)) == $User->User_Password) {
			  if ($Admin_Approval != "Yes" or $User->User_Admin_Approved == "Yes") {
					  if ($Email_Confirmation != "Yes" or $User->User_Email_Confirmed == "Yes") {
			  			  CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
								$feup_success = true;
								return __("Login succesful", 'EWD_FEUP');
						}
						return __("Login failed - you need to confirm your e-mail before you can log in", 'EWD_FEUP');
				}
				return __("Login failed - an administrator needs to approve your registration before you can log in", 'EWD_FEUP');
		}
		return __("Login failed - incorrect username or password", 'EWD_FEUP');
}

function FEUPRedirect($redirect_page) {
		header("location:" . $redirect_page);
}

function Get_User_Search_Results() {
		global $wpdb, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
		
		foreach ($_POST as $field => $value) {
				if (substr($field, 0, 7) == "search_") {
						$DataSet['Criteria'] .= str_replace("_", " ", substr($field, 7));
						$DataSet['Value'] = "%" . $value . "%";
						$Criterion[] = $DataSet;
						unset($DataSet);
				}
		}
		
		if (!is_array($Criterion)) {return array();}
		
		$list = array();
		foreach ($Criterion as $DataSet) {
				unset($IDs);
				$IDs = array();
				$UserList = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value LIKE '%s'", $DataSet['Criteria'], $DataSet['Value']));
				foreach ($UserList as $User) {
						$IDs[] = $User->User_ID;
				}
				$list[] = $IDs;
		}
		
		if (sizeOf($list) < 2) {
			  $UserIDs = $IDs;
		} else {
				$UserIDs = call_user_func_array('array_intersect',$list);
		}

		foreach ($UserIDs as $UserID) {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $UserID));
				$UserInformation['Username'] = $User->Username;
				$Users[] = $UserInformation;
				unset($UserInformation);
		}
		
		return $Users;
}
?>
