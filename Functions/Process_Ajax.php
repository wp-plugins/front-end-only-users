<?php

// Updates the order of privilege levels after a user has dragged and dropped them
function Level_Save_Order() {
		global $wpdb;
		global $ewd_feup_levels_table_name;
		
		foreach ($_POST['list-item'] as $Key=>$ID) {
				$Result = $wpdb->query("UPDATE $ewd_feup_levels_table_name SET Level_Privilege='" . $Key . "' WHERE Level_ID=" . $ID);
		}
		
}
add_action('wp_ajax_levels_update_order', 'Level_Save_Order');


?>
