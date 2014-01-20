<?php 
		$Login_Time = get_option("EWD_FEUP_Login_Time");
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Settings</h2>

<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Options&Action=UpdateOptions">
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
</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>