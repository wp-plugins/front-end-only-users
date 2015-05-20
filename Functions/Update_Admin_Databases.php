<?php
/* The file contains all of the functions which make changes to the FEUP tables */

/* Adds a single new user to the FEUP database */
function Add_EWD_FEUP_User($User_Data_Array) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	$wpdb->insert($ewd_feup_user_table_name, $User_Data_Array);
	$update = __("User has been successfully created.", 'EWD_FEUP');
	return $update;
}

/* Edits a single user with a given ID in the FEUP database */
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

/* Deletes a single user with a given ID in the FEUP database */
function Delete_EWD_FEUP_User($User_ID) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_user_fields_table_name;
	
	$wpdb->delete(
		$ewd_feup_user_table_name,
		array('User_ID' => $User_ID)
	);
	$wpdb->delete(
		$ewd_feup_user_fields_table_name,
		array('User_ID' => $User_ID)
	);

	$update = __("User has been successfully deleted.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_FEUP_Users_From_Spreadsheet($Excel_File_Name) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_user_fields_table_name;
	global $ewd_feup_levels_table_name;
	global $ewd_feup_fields_table_name;
	global $EWD_FEUP_Full_Version;

	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");

	if (!wp_verify_nonce($_POST['_wpnonce'])) {return __("There has been a validation error.", 'EWD_FEUP');}
		
	$Excel_URL = '../wp-content/plugins/front-end-only-users/user-sheets/' . $Excel_File_Name;
		
	// Uses the PHPExcel class to simplify the file parsing process
	include_once('../wp-content/plugins/front-end-only-users/PHPExcel/Classes/PHPExcel.php');
		
	// Build the workbook object out of the uploaded spredsheet
	$inputFileType = PHPExcel_IOFactory::identify($Excel_URL);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objWorkBook = $objReader->load($Excel_URL);
		
	// Create a worksheet object out of the product sheet in the workbook
	$sheet = $objWorkBook->getActiveSheet();
		
	//List of fields that can be accepted via upload
	$Allowed_Fields = array ("Username" => "Username", "Password" => "User_Password", "Level" => "Level_Name", "Email Confirmed" => "User_Email_Confirmed", "Admin Approved" => "User_Admin_Approved");
	$Custom_Fields_From_DB = $wpdb->get_results("SELECT Field_ID, Field_Name, Field_Options, Field_Type FROM $ewd_feup_fields_table_name");
	if (is_array($Custom_Fields_From_DB)) {
		foreach ($Custom_Fields_From_DB as $Custom_Field_From_DB) {
			$Allowable_Custom_Fields[$Custom_Field_From_DB->Field_Name] = $Custom_Field_From_DB->Field_Name;
			$Field_IDs[$Custom_Field_From_DB->Field_Name] = $Custom_Field_From_DB->Field_ID;
		}
	}
		
	// Get column names
	$highestColumn = $sheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);	
	for ($column = 0; $column < $highestColumnIndex; $column++) {
		$Titles[$column] = trim($sheet->getCellByColumnAndRow($column, 1)->getValue());
	}

	// Make sure all columns are acceptable based on the acceptable fields above
	foreach ($Titles as $key => $Title) {
		if ($Title != "" and !array_key_exists($Title, $Allowed_Fields) and !array_key_exists($Title, $Allowable_Custom_Fields)) {
			$Error = __("You have a column which is not recognized: ", 'EWD_FEUP') . $Title . __(". <br>Please make sure that the column names match the user field labels exactly.", 'EWD_FEUP');
			$user_update = array("Message_Type" => "Error", "Message" => $Error);
			return $user_update;
		}
		if ($Title == "") {
			$Error = __("You have a blank column that has been edited.<br>Please delete that column and re-upload your spreadsheet.", 'EWD_FEUP');
			$user_update = array("Message_Type" => "Error", "Message" => $Error);
			return $user_update;
		}
		if (is_array($Allowable_Custom_Fields)) {
			if (array_key_exists($Title, $Allowable_Custom_Fields)) {
				$Custom_Fields[$key] = $Title;
				unset($Titles[$key]);
			}
		}
	}
	if (!is_array($Custom_Fields)) {$Custom_Fields = array();}
		
	// Put the spreadsheet data into a multi-dimensional array to facilitate processing
	$highestRow = $sheet->getHighestRow();
	for ($row = 2; $row <= $highestRow; $row++) {
		for ($column = 0; $column < $highestColumnIndex; $column++) {
			$Data[$row][$column] = $sheet->getCellByColumnAndRow($column, $row)->getValue();
		}
	}

	// Create an array of the levels currently in the FEUP database, 
	// with Level_Name as the key and Level_ID as the value
	$Levels_From_DB = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name");
	foreach ($Levels_From_DB as $Level) {
		$Levels[$Level->Level_Name] = $Level->Level_ID;
	}

	// Creates an array of the field names which are going to be inserted into the database
	// and then turns that array into a string so that it can be used in the query
	for ($column = 0; $column < $highestColumnIndex; $column++) {
		if ($Allowed_Fields[$Titles[$column]] != "Level_Name" and !array_key_exists($column, $Custom_Fields)) {$Fields[] = $Allowed_Fields[$Titles[$column]];}
		if ($Allowed_Fields[$Titles[$column]] == "Level_Name") {$Level_Column = $column; $Fields[] = "Level_ID";}
		if ($Allowed_Fields[$Titles[$column]] == "User_Password") {$Password_Column = $column;}
	}
	$FieldsString = implode(",", $Fields);
		
	$ShowStatus = "Show";
	$Today = date("Y-m-d H:i:s"); 
	$wpdb->show_errors();
	// Create the query to insert the users one at a time into the database and then run it
	foreach ($Data as $User) {
				
		// Create an array of the values that are being inserted for each user
		foreach ($User as $Col_Index => $Value) {
			if ((!isset($Password_Column) or $Password_Column != $Col_Index) and (!isset($Level_Column) or $Level_Column != $Col_Index) and !array_key_exists($Col_Index, $Custom_Fields)) {$Values[] = esc_sql($Value);}
			if (isset($Level_Column) and $Level_Column == $Col_Index) {
				$Values[] = $Levels[$Value];
			}
			if (isset($Password_Column) and $Password_Column == $Col_Index) {
				if($Use_Crypt == "Yes") {
					$Values[] = Generate_Password($Value);
				} else {
					$Values[] = sha1(md5($Value.$Salt));
				}
			}
			if (array_key_exists($Col_Index, $Custom_Fields)) {
				$Custom_Fields_To_Insert[$Custom_Fields[$Col_Index]] = $Value;
			}
		}
				
		$ValuesString = implode("','", $Values);
		$wpdb->query(
			$wpdb->prepare("INSERT INTO $ewd_feup_user_table_name (" . $FieldsString . ", User_Date_Created) VALUES ('" . $ValuesString . "','%s')", $Today)
		);

		$User_ID = $wpdb->insert_id;
				
		if (is_array($Custom_Fields_To_Insert)) {
			foreach ($Custom_Fields_To_Insert as $Field => $Value) {
				$Trimmed_Field = trim($Field);
				$Field_ID = $Field_IDs[$Trimmed_Field];
				$wpdb->query($wpdb->prepare("INSERT INTO $ewd_feup_user_fields_table_name (Field_ID, User_ID, Field_Name, Field_Value, User_Field_Date_Created) VALUES (%d, %d, %s, %s, %s)", $Field_ID, $User_ID, $Trimmed_Field, $Value, $Today));
			}
		}

		unset($Values);
		unset($User_ID);
		unset($ValuesString);
		unset($Custom_Fields_To_Insert);
	}
	
	$message = __("Users added successfully.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $message);
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
	$Use_SMTP = $_POST['use_smtp'];
	$Port = $_POST['port'];
	$SMTP_Username = $_POST['smtp_username'];
	$Admin_Password = $_POST['admin_password'];
	$Email_Field = $_POST['email_field'];
	
	$Admin_Email = stripslashes_deep($Admin_Email);
	$Message_Body = stripslashes_deep($Message_Body);
	$Email_Subject = stripslashes_deep($Email_Subject);
	$SMTP_Mail_Server = stripslashes_deep($SMTP_Mail_Server);
	$Use_SMTP = stripslashes_deep($Use_SMTP);
	$Port = stripslashes_deep($Port);
	$SMTP_Username = stripslashes_deep($SMTP_Username);
	$Admin_Password = stripslashes_deep($Admin_Password);
	$Email_Field = stripslashes_deep($Email_Field);
	
	$key = 'EWD_FEUP';
	if (function_exists('mcrypt_decrypt')) {$Encrypted_Admin_Password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $Admin_Password, MCRYPT_MODE_CBC, md5(md5($key))));}
	else {$Encrypted_Admin_Password = $Admin_Password;}
	
	update_option('EWD_FEUP_Admin_Email', $Admin_Email);
	update_option('EWD_FEUP_Message_Body', $Message_Body);
	update_option('EWD_FEUP_Email_Subject', $Email_Subject);
	update_option('EWD_FEUP_SMTP_Mail_Server', $SMTP_Mail_Server);
	update_option('EWD_FEUP_Use_SMTP', $Use_SMTP);
	update_option('EWD_FEUP_Port', $Port);
	update_option('EWD_FEUP_SMTP_Username', $SMTP_Username);
	update_option('EWD_FEUP_Admin_Password', $Encrypted_Admin_Password);
	update_option('EWD_FEUP_Email_Field', $Email_Field);
	
	$update = __("Email options have been succesfully updated.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}
?>