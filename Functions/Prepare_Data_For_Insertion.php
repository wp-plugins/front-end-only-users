<?php
/* Prepare the data to add or edit a single product */
function Add_Edit_User() {
		global $wpdb, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		
		$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
		$Fields = $wpdb->get_results($Sql);
		
		$date = date("Y-m-d H:i:s");
		
		$UserCookie = CheckLoginCookie();
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
		$User_ID = $User->User_ID;
		$User_Fields = array( 'Username' => $_POST['Username'],
								 	 				'User_Password' => sha1(md5($_POST['User_Password'].$Salt)),
													'User_Date_Created' => $date);
		
		if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {$user_update = __("The passwords you entered did not match.", "EWD_FEUP"); return $user_update;}
				
		foreach ($Fields as $Field) {
				$Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
				$Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
				$Field_Name = str_replace(" ", "_", $Field->Field_Name);
				if (is_array($_POST[$Field_Name])) {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = implode(",", $_POST[$Field_Name]);}
				else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $_POST[$Field_Name];}
		}

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
				if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
					  if ($User->User_ID != "") {$user_update = __("There is already an account with that Username. Please select a different one.", "EWD_FEUP"); return $user_update;}
						$user_update = Add_EWD_FEUP_User($User_Fields);
						$User_ID = $wpdb->insert_id;
						foreach ($Additional_Fields_Array as $Field) {
								$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);
						}
						if ($_POST['ewd-feup-action'] == "register") {
							  CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
								$user_update = __("Your account has been succesfully created.", "EWD_FEUP");
						}
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
				else {
						if (isset($_POST['Username'])) {$user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);}
						foreach ($Additional_Fields_Array as $Field) {
								$CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'", $Field['Field_ID'], $User_ID));
								if ($CurrentField->User_Field_ID != "") {$user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value']);}
								else {$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);}
						}
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_FEUP_Users() {
		$Users = $_POST['Users_Bulk'];
		
		if (is_array($Users)) {
				foreach ($Users as $User) {
						if ($User != "") {
								Delete_EWD_FEUP_User($User);
						}
				}
		}
		
		$update = __("Users have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_Edit_Field() {
		global $wpdb, $ewd_feup_fields_table_name;
		
		$Field_ID = $_POST['Field_ID'];
		$Field_Name = $_POST['Field_Name'];
		$Field_Type = $_POST['Field_Type'];
		$Field_Description = $_POST['Field_Description'];
		$Field_Options = $_POST['Field_Options'];
		$Field_Show_In_Admin = $_POST['Field_Show_In_Admin'];
		$Field_Show_In_Front_End = $_POST['Field_Show_In_Front_End'];
		$Field_Required = $_POST['Field_Required'];
		
		$Field_Date_Created = date("Y-m-d H:i:s");

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
				if ($_POST['action'] == "Add_Field") {
					  $user_update = Add_EWD_FEUP_Field($Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created);
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
				else {
						$user_update = Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required);
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_FEUP_Fields() {
		$Fields = $_POST['Fields_Bulk'];
		
		if (is_array($Fields)) {
				foreach ($Fields as $Field) {
						if ($Field != "") {
								Delete_EWD_FEUP_Field($Field);
						}
				}
		}
		
		$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_Edit_Level() {
		
		$Level_ID = $_POST['Level_ID'];
		$Level_Name = $_POST['Level_Name'];
		$Level_Privilege = $_POST['Level_Privilege'];
		
		$Level_Date_Created = date("Y-m-d H:i:s");

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
				if ($_POST['action'] == "Add_Level") {
					  $user_update = Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created);
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
				else {
						$user_update = Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created);
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_FEUP_Levels() {
		$Levels = $_POST['Levels_Bulk'];
		
		if (is_array($Levels)) {
				foreach ($Levels as $Level) {
						if ($Level != "") {
								Delete_EWD_FEUP_Level($Level);
						}
				}
		}
		
		$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Confirm_Login() {
		global $wpdb;
		global $ewd_feup_user_table_name;
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_Password FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Username']));
		if (sha1(md5($_POST['User_Password'].$Salt)) == $User->User_Password) {
			  CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
				return "Login succesful";
		}
		return "Login failed";
}
?>
