<?php
/* The file contains all of the functions which make changes to the UPCP tables */

/* Adds a single new category to the UPCP database */
function Add_EWD_FEUP_User($User_Data_Array) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	$wpdb->insert($ewd_feup_user_table_name, $User_Data_Array);
	$update = __("User has been successfully created.", 'EWD_FEUP');
	return $update;
}

/* Edits a single category with a given ID in the UPCP database */
function Edit_EWD_FEUP_User($User_ID, $User_Data_Array) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	$wpdb->update(
		$ewd_feup_user_table_name,
		$User_Data_Array,
		array( 'User_ID' => $User_ID)
	);
	$update = __("User has been successfully edited.", 'EWD_FEUP');
	return $update;
}

/* Deletes a single category with a given ID in the UPCP database */
function Delete_EWD_FEUP_User($User_ID) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_fields_table_name;
	
	$wpdb->delete(
		$ewd_feup_user_table_name,
		array('User_ID' => $User_ID)
	);
	$wpdb->delete(
		$ewd_feup_fields_table_name,
		array('User_ID' => $User_ID)
	);

	$update = __("User has been successfully deleted.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_EWD_FEUP_Field($Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created) {
	global $wpdb;
	global $ewd_feup_fields_table_name;
		
	$wpdb->insert($ewd_feup_fields_table_name, 
		array( 'Field_Name' => $Field_Name,
			'Field_Type' => $Field_Type,
			'Field_Description' => $Field_Description,
			'Field_Options' => $Field_Options,
			'Field_Show_In_Admin' => $Field_Show_In_Admin,
			'Field_Show_In_Front_End' => $Field_Show_In_Front_End,
			'Field_Required' => $Field_Required,
			'Field_Date_Created' => $Field_Date_Created)
	);							
		
	$update = __("Field has been successfully created.", 'EWD_FEUP');
	return $update;
}

function Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required) {
	global $wpdb;
	global $ewd_feup_fields_table_name;
		
	$wpdb->update($ewd_feup_fields_table_name, 
		array( 'Field_Name' => $Field_Name,
			'Field_Type' => $Field_Type,
			'Field_Description' => $Field_Description,
			'Field_Options' => $Field_Options,
			'Field_Show_In_Admin' => $Field_Show_In_Admin,
			'Field_Show_In_Front_End' => $Field_Show_In_Front_End,
			'Field_Required' => $Field_Required),
		array( 'Field_ID' => $Field_ID)
	);
		
	$update = __("Field has been successfully edited.", 'EWD_FEUP');
	return $update;
}

function Delete_EWD_FEUP_Field($Field_ID) {
		global $wpdb;
		global $ewd_feup_fields_table_name;
		
		$wpdb->delete(
						$ewd_feup_fields_table_name,
						array('Field_ID' => $Field_ID)
					);
		
		$update = __("Field has been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_EWD_FEUP_User_Field($Field_ID, $User_ID, $Field_Name, $Field_Value, $date) {
		global $wpdb;
		global $ewd_feup_user_fields_table_name;
		
		$wpdb->insert($ewd_feup_user_fields_table_name, 
									array( 'Field_ID' => $Field_ID,
												 'User_ID' => $User_ID,
												 'Field_Name' => $Field_Name,
												 'Field_Value' => $Field_Value,
												 'User_Field_Date_Created' => $date)
									);
									
		$update = __("Field has been successfully created.", 'EWD_FEUP');
		return $update;
}

function Edit_EWD_FEUP_User_Field($Field_ID, $User_ID, $Field_Name, $Field_Value) {
		global $wpdb;
		global $ewd_feup_user_fields_table_name;
		
		$User_Field_ID = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID ='%d' AND User_ID='%d'", $Field_ID, $User_ID));
		
		$wpdb->update($ewd_feup_user_fields_table_name, 
									array( 'Field_Name' => $Field_Name,
												 'Field_Value' => $Field_Value),
									array( 'User_Field_ID' => $User_Field_ID->User_Field_ID)
									);
		
		$update = __("Field has been successfully edited.", 'EWD_FEUP');
		return $update;
}

function Delete_EWD_FEUP_User_Field($User_Field_ID) {
		global $wpdb;
		global $ewd_feup_user_fields_table_name;
		
		$wpdb->delete(
						$ewd_feup_user_fields_table_name,
						array('User_Field_ID' => $User_Field_ID)
					);
					
		$update = __("Field has been successfully deleted.", 'EWD_FEUP');
		return $update;
}

/*function GenerateUsersTableSql() {
		global $wpdb;
		global $ewd_feup_user_fields_table_name;
		
		$Sql = "CREATE TABLE $ewd_feup_user_table_name (
  	User_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	Username text DEFAULT '' NOT NULL,
		User_Password text  DEFAULT '' NOT NULL,
		User_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,";
  	
		$Fields_Sql = "SELECT * FROM $ewd_feup_user_fields_table_name ";
		$Fields = $wpdb->get_results($Fields_Sql);

		foreach ($Fields as $Field) {
				if ($Field['Field_Type'] == "text" or $Field['Field_Type'] == "select" or $Field['Field_Type'] == "radio" or $Field['Field_Type'] == "checkbox" or $Field['Field_Type'] == "textarea" or $Field['Field_Type'] == "countries") {$Type = "text";}
				elseif ($Field['Field_Type'] == "mediumint") {$Type = "mediumint(9)";}
				elseif ($Field['Field_Type'] == "datetime") {$Type = "datetime";}
				elseif ($Field['Field_Type'] == "text") {$Type = "date";}
				$Sql .= $Field['Field_Name'] . " " . $Type . ", ";
		}
		
		$Sql .= " UNIQUE KEY id (User_ID))";
		
		return $Sql;
}*/

function Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created) {
		global $wpdb;
		global $ewd_feup_levels_table_name;
		
		$wpdb->insert($ewd_feup_levels_table_name, 
									array( 'Level_Name' => $Level_Name,
												 'Level_Privilege' => $Level_Privilege,
												 'Level_Date_Created' => $Level_Date_Created)
									);
		
		$update = __("Level has been successfully created.", 'EWD_FEUP');
		return $update;
}

/* Edits a single category with a given ID in the UPCP database */
function Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created) {
		global $wpdb;
		global $ewd_feup_levels_table_name;
		
		$wpdb->insert($ewd_feup_levels_table_name, 
									array( 'Level_Name' => $Level_Name,
												 'Level_Privilege' => $Level_Privilege,
												 'Level_Date_Created' => $Level_Date_Created),
									array( 'Level_ID' => $Level_ID)
		);
		$update = __("Level has been successfully edited.", 'EWD_FEUP');
		return $update;
}

/* Deletes a single category with a given ID in the UPCP database */
function Delete_EWD_FEUP_Level($Level_ID) {
		global $wpdb;
		global $ewd_feup_levels_table_name;
		
		$wpdb->delete(
						$ewd_feup_levels_table_name,
						array('Level_ID' => $Level_ID)
					);

		$update = __("Level has been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Update_EWD_FEUP_Options() {
		update_option('EWD_FEUP_Login_Time', $_POST['login_time']);
		update_option("EWD_FEUP_Admin_Approval", $_POST['admin_approval']);
		update_option("EWD_Default_User_Level", $_POST['default_user_level']);
		update_option("EWD_FEUP_Sign_Up_Email", $_POST['sign_up_email']);
		update_option("EWD_FEUP_Email_Confirmation", $_POST['email_confirmation']);
		update_option("EWD_FEUP_Custom_CSS", $_POST['custom_css']);
		update_option("EWD_FEUP_Use_Crypt", $_POST['use_crypt']);
		update_option("EWD_FEUP_Username_Is_Email", $_POST['username_is_email']);
		
		$update = __("Options have been succesfully updated.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Update_EWD_FEUP_Email_Settings() {
		$Admin_Email = $_POST['admin_email'];
		$Message_Body = $_POST['message_body'];
		$Email_Subject = $_POST['email_subject'];
		$SMTP_Mail_Server = $_POST['smtp_mail_server'];
		$SMTP_Username = $_POST['smtp_username'];
		$Admin_Password = $_POST['admin_password'];
		$Email_Field = $_POST['email_field'];
		
		$Admin_Email = stripslashes_deep($Admin_Email);
		$Message_Body = stripslashes_deep($Message_Body);
		$Email_Subject = stripslashes_deep($Email_Subject);
		$SMTP_Mail_Server = stripslashes_deep($SMTP_Mail_Server);
		$SMTP_Username = stripslashes_deep($SMTP_Username);
		$Admin_Password = stripslashes_deep($Admin_Password);
		$Email_Field = stripslashes_deep($Email_Field);
		
		$key = 'EWD_FEUP';
		$Encrypted_Admin_Password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $Admin_Password, MCRYPT_MODE_CBC, md5(md5($key))));
		
		update_option('EWD_FEUP_Admin_Email', $Admin_Email);
		update_option('EWD_FEUP_Message_Body', $Message_Body);
		update_option('EWD_FEUP_Email_Subject', $Email_Subject);
		update_option('EWD_FEUP_SMTP_Mail_Server', $SMTP_Mail_Server);
		update_option('EWD_FEUP_SMTP_Username', $SMTP_Username);
		update_option('EWD_FEUP_Admin_Password', $Encrypted_Admin_Password);
		update_option('EWD_FEUP_Email_Field', $Email_Field);
		
		$update = __("Email options have been succesfully updated.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}
?>