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
								 $user_message['Message'] = Confirm_Login();
								 break;
				}
		}
}

function Confirm_Login() {
		global $wpdb, $feup_success;
		global $ewd_feup_user_table_name;
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_Password FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Username']));
		if (sha1(md5($_POST['User_Password'].$Salt)) == $User->User_Password) {
			  CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
				$feup_success = true;
				return "Login succesful";
		}
		return "Login failed";
}

function FEUPRedirect($redirect_page) {
		header("location:" . $redirect_page);
}
?>
