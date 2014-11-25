<?php

function Process_EWD_FEUP_Front_End_Forms() {
	global $user_message;
		
	if (isset($_POST['ewd-feup-action'])) {
		switch ($_POST['ewd-feup-action']) {
			case "register":
			case "edit-profile":
			case "edit-account":
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
				$Date = date("Y-m-d H:i:s");   
				$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Last_Login='" . $Date . "' WHERE Username ='%s'", $_POST['Username']));
				$feup_success = true;
				return __("Login successful", 'EWD_FEUP');
			}
			return __("Login failed - you need to confirm your e-mail before you can log in", 'EWD_FEUP');
		}
		return __("Login failed - an administrator needs to approve your registration before you can log in", 'EWD_FEUP');
	}
	return __("Login failed - incorrect username or password", 'EWD_FEUP');
}

function Forgot_Password() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Email_Field = str_replace(" ", "_", $Email_Field);
		
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Username']));
		
		
	if( !empty( $User ) )
	{
		//update users password
		$password = wp_generate_password();
		$hashedPassword = sha1( md5( $password.$Salt ) );
		
		$wpdb -> update( $ewd_feup_user_table_name, array(
				'User_Password' => $hashedPassword,
			),
			array(
				'Username' => $_POST['Username'],
			),
			array(
				'%s'
			)
		);
		
		$User_Email = $wpdb -> get_row( $wpdb -> prepare( "SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID = '%d' AND Field_Name = '%s' ", $User->User_ID, $Email_Field ) );
		$User_Email = $User_Email->Field_Value;
		
		//send email to user with account credentials
		$subject = __("Password changed");

		$headers = 'From: ' . $Admin_Email . "\r\n" .
    		'Reply-To: ' . $Admin_Email . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		
		$message = __("Hello,")."<br /><br />";
		$message .= __("Your new credentials are:")."<br /><br />";
		$message .= __("Username:")." ".$User->Username."<br />";
		$message .= __("Password:")." ".$password;
		
		wp_mail( $User_Email, $subject, $message, $headers);
		
		$feup_success = true;
		
		//return success message
		return __("Your new credentials have been sent to the email address specified.", 'EWD_FEUP');
	}
	else
	{
		//return failed message
		return __("The email address does not exist!", 'EWD_FEUP');
	}
}


function FEUPRedirect($redirect_page) {
	header("location:" . $redirect_page);
}

function ConfirmUserEmail() {
	global $wpdb, $ewd_feup_user_table_name;

	$User_ID = $_GET['UserID'];
	$Email_Address = $_GET['ConfirmEmail'];
	$Confirmation_Code = $_GET['ConfirmationCode'];

	$Retrieved_User_ID = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d AND User_Confirmation_Code=%s", $User_ID, $ConfirmationCode));
	if (isset($Retrieved_User_ID->User_ID)) {
		$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Email_Confirmed='Yes' WHERE User_ID=%d", $Retrieved_User_ID->User_ID));
		$ConfirmationSuccess = "Yes";
	}
	else {
		$ConfirmationSuccess = "No";
	}

	return $ConfirmationSuccess;
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
