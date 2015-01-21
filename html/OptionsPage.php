<?php 
	$Login_Time = get_option("EWD_FEUP_Login_Time");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Settings</h2>

<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Options&Action=EWD_FEUP_UpdateOptions">
<table class="form-table">
<tr>
<th scope="row">Login Time</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Login Time</span></legend>
	<label title='Login Time'><input type='text' name='login_time' value='<?php echo $Login_Time; ?>' /><span> Minutes</span></label><br />
	<p>For reference: 1440 minutes in a day, 10080 minutes in a week, 43200 minutes in a 30-day month, 525600 minutes in a year</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Admin Approval of Users</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Admin Approval of Users</span></legend>
	<label title='Yes'><input type='radio' name='admin_approval' value='Yes' <?php if($Admin_Approval == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='admin_approval' value='No' <?php if($Admin_Approval == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Require users to be approved by an administrator in the WordPress back-end before they can log in.</p>
	</fieldset>
</td>
</tr>
<th scope="row">Default User Level</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Default User Level</span></legend>
	<label title='Default User Level'><select name='default_user_level'>
		<option value='0'>None (0)</option>
		<?php foreach ($Levels as $Level) {
				echo "<option value='" . $Level->Level_ID . "' ";
				if ($Default_User_Level == $Level->Level_ID) {echo "selected=selected";}
				echo ">" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
		}?> 
	</select>
	<p>Which level should users be set to when they register (created on the "Levels" tab)?</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Send Sign Up Emails</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Send Sign Up Emails</span></legend>
	<label title='Yes'><input type='radio' name='sign_up_email' value='Yes' <?php if($Sign_Up_Email == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='sign_up_email' value='No' <?php if($Sign_Up_Email == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Send e-mails to users after they successfully register.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Email Confirmation</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Confirmation</span></legend>
	<label title='Yes'><input type='radio' name='email_confirmation' value='Yes' <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='email_confirmation' value='No' <?php if($Email_Confirmation == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Make users confirm their e-mail address before they can log in.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Custom CSS</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
	<label title='Custom CSS'><textarea name='custom_css'><?php echo $Custom_CSS ?></textarea></label><br />
	<p>Custom CSS that should be included on any page that uses one of the FEUP shortcodes.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Use Crypt</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Use Crypt</span></legend>
	<label title='Yes'><input type='radio' name='use_crypt' value='Yes' <?php if($Use_Crypt == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='use_crypt' value='No' <?php if($Use_Crypt == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Should the plugin use crypt to encode user passwords? (Higher security)<br /><strong>Warning! All current user passwords will permanently stop working when switching between encoding methods!</strong></p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Username is Email</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Username is Email</span></legend>
	<label title='Yes'><input type='radio' name='username_is_email' value='Yes' <?php if($Username_Is_Email == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='username_is_email' value='No' <?php if($Username_Is_Email == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Should your users register using their e-mail addresses instead of by creating usernames?</p>
	</fieldset>
</td>
</tr>
</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>