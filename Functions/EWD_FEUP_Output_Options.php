<?php
/* Creates the admin page, and fills it in based on whether the user is looking at
*  the overview page or an individual item is being edited */
function EWD_FEUP_Output_Options() {
		global $wpdb, $error, $Full_Version, $feup_message;;
		global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
		
		if (isset($_GET['DisplayPage'])) {
			  $Display_Page = $_GET['DisplayPage'];
		}
		else {
			$Display_Page = null;
		}

		if (!isset($_GET['Action'])) {
			$_GET['Action'] = null;
		}
		
		include( plugin_dir_path( __FILE__ ) . '../html/AdminHeader.php');
		if ($_GET['Action'] == "EWD_FEUP_User_Details")  {include( plugin_dir_path( __FILE__ ) . '../html/UserDetails.php');}
		elseif ($_GET['Action'] == "EWD_FEUP_Field_Details")  {include( plugin_dir_path( __FILE__ ) . '../html/FieldDetails.php');}
		else {include( plugin_dir_path( __FILE__ ) . '../html/MainScreen.php');}
		include( plugin_dir_path( __FILE__ ) . '../html/AdminFooter.php');
}
?>