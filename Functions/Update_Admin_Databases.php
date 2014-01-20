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
		$update = __("Category has been successfully edited.", 'EWD_FEUP');
		return $update;
}

/* Deletes a single category with a given ID in the UPCP database */
function Delete_EWD_FEUP_User($User_ID) {
		global $wpdb;
		global $ewd_feup_user_table_name;
		
		$wpdb->delete(
						$ewd_feup_user_table_name,
						array('User_ID' => $User_ID)
					);

		$update = __("User has been successfully deleted.", 'EWD_FEUP');
		return $update;
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
		return $update;
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
		return $update;
}

function Update_EWD_FEUP_Options() {
		update_option('EWD_FEUP_Login_Time', $_POST['login_time']);
		
		$update = __("Options have been succesfully updated.", 'EWD_FEUP');
		return $update;
}
?>