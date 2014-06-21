<?php 
		$Login_Time = get_option("EWD_FEUP_Login_Time");
		$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
		$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
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
<!--<tr>
<th scope="row">Email Confirmation</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Confirmation</span></legend>
	<label title='Yes'><input type='radio' name='email_confirmation' value='Yes' <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='email_confirmation' value='No' <?php if($Email_Confirmation == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Make users confirm their e-mail address before they can log in.</p>
	</fieldset>
</td>
</tr>-->
<tr>
<th scope="row">Custom CSS</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
	<label title='Custom CSS'><textarea name='custom_css'><?php echo $Custom_CSS ?></textarea></label><br />
	<p>Custom CSS that should be included on any page that uses one of the FEUP shortcodes.</p>
	</fieldset>
</td>
</tr>
</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>