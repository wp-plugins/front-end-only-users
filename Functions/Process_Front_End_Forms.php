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
								 $user_message = Confirm_Login();
								 break;
				}
		}
}
?>
