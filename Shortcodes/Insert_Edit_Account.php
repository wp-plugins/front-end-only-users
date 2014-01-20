<?php
function Insert_Edit_Account_Form($atts) {
		$ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
		$ReturnString .= "<form action='" . $redirect_page . "' method='post' id='ewd-feup-edit-profile-form'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-profile'>";
		$ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Username', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' value='" . $User->Username . "'>";
		$ReturnString .= "<div id='ewd-feup-register-password-div' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' value=''>";
		$ReturnString .= "<div id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'>" . __('Repeat Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' value=''>";
}
add_shortcode("account-details", "Insert_Edit_Account_Form");
?>
