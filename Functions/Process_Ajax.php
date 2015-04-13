<?php
function Field_Save_Order() {
	global $wpdb;
	global $ewd_feup_fields_table_name;
	
	foreach ($_POST['list-item'] as $Key=>$ID) {
		$Result = $wpdb->query("UPDATE $ewd_feup_fields_table_name SET Field_Order='" . $Key . "' WHERE Field_ID=" . $ID);
	}
		
}
add_action('wp_ajax_ewd_feup_update_field_order', 'Field_Save_Order');


// Updates the order of privilege levels after a user has dragged and dropped them
function Level_Save_Order() {
	global $wpdb;
	global $ewd_feup_levels_table_name;
	
	foreach ($_POST['list-item'] as $Key=>$ID) {
		$Result = $wpdb->query("UPDATE $ewd_feup_levels_table_name SET Level_Privilege='" . $Key . "' WHERE Level_ID=" . $ID);
	}
		
}
add_action('wp_ajax_ewd_feup_update_levels_order', 'Level_Save_Order');


?>
