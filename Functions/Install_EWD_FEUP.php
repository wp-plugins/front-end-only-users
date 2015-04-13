<?php
function Install_EWD_FEUP() {
	/* Add in the required globals to be able to create the tables */
  	global $wpdb;
   	global $EWD_FEUP_db_version;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
    
	/* Create the users table */  
   	$sql = "CREATE TABLE $ewd_feup_user_table_name (
  		User_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Username text  NULL,
		User_Password text   NULL,
		Level_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_Email_Confirmed text NULL,
		User_Confirmation_Code text NULL,
		User_Admin_Approved text NULL,
		User_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Last_Login datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Total_Logins mediumint(9) DEFAULT 0 NOT NULL,
		User_Password_Reset_Code text NULL,
		User_Password_Reset_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Sessioncheck varchar(255) DEFAULT NULL,
  		UNIQUE KEY id (User_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the fields table */
	$sql = "CREATE TABLE $ewd_feup_fields_table_name (
  		Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Field_Name text   NULL,
		Field_Description text   NULL,
		Field_Type text   NULL,
		Field_Options text   NULL,
		Field_Show_In_Admin text   NULL,
		Field_Show_In_Front_End   text NULL,
		Field_Required text   NULL,
		Field_Order mediumint(9) DEFAULT 0 NOT NULL,
		Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the user-fields table */
	$sql = "CREATE TABLE $ewd_feup_user_fields_table_name (
  		User_Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Field_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Field_Name text NULL,
		Field_Value text NULL,
		User_Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (User_Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the levels table */
	$sql = "CREATE TABLE $ewd_feup_levels_table_name (
  		Level_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Level_Name text   NULL,
		Level_Privilege text   NULL,
		Level_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Level_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
 
   	add_option("EWD_FEUP_db_version", $EWD_FEUP_db_version);
	add_option("EWD_FEUP_Admin_Approval", "No");
	add_option("EWD_FEUP_Login_Time", "1440");
	add_option("EWD_FEUP_Email_Confirmation", "No");
	add_option("EWD_FEUP_Full_Version", "No");
	if (get_option("EWD_FEUP_Use_Crypt") == "") {add_option("EWD_FEUP_Use_Crypt", "No");}
	if (get_option("EWD_FEUP_Username_Is_Email") == "") {add_option("EWD_FEUP_Username_Is_Email", "No");}
	if (get_option("EWD_FEUP_Use_SMTP") == "") {update_option("EWD_FEUP_Use_SMTP", "Yes");}
	if (get_option("EWD_FEUP_Port") == "") {update_option("EWD_FEUP_Port", "25");}
	add_option("EWD_FEUP_Custom_CSS", "");
}
?>
