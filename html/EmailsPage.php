<?php 
		$Admin_Email = get_option("EWD_FEUP_Admin_Email");
		$Email_Subject = get_option("EWD_FEUP_Email_Subject");
		$Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
		$SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
		$SMTP_Username = get_option("EWD_FEUP_SMTP_Username");
		$Message_Body = get_option("EWD_FEUP_Message_Body");
		$Email_Field = get_option("EWD_FEUP_Email_Field");
		
		$key = 'EWD_FEUP';
		$Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Email Settings</h2>

<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Emails&Action=EWD_FEUP_UpdateEmailSettings">
<table class="form-table">
<th scope="row">Email Field Name</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Field Name</span></legend>
	<label title='Email Field Name'><input type='text' name='email_field' value='<?php echo $Email_Field; ?>' /> </label><br />
	<p>The name of the field that should be used to send the e-mail to from your registration form.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">"Send-From" Email Address</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Address</span></legend>
	<label title='Email Address'><input type='text' name='admin_email' value='<?php echo $Admin_Email; ?>' /> </label><br />
	<p>The email address that sign-up messages should be sent from.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Message Body</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Message Body</span></legend>
	<label title='Message Body'></label><textarea class='ewd-feup-textarea' name='message_body'> <?php echo $Message_Body; ?></textarea><br />
	<p>What should be in the messages sent to users? You can put [username], [password], or [join-date] to include the Username, Password or sign-up datetime for the user.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Email Subject</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Subject</span></legend>
	<label title='Email Subject'><input type='text' name='email_subject' value='<?php echo $Email_Subject; ?>' /> </label><br />
	<p>The subject of the sign-up e-mail message.</p>
	</fieldset>
</td>
</tr>
<h3>SMTP Mail Settings</h3>
<tr>
<th scope="row">SMTP Mail Server Address</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>SMTP Mail Server Address</span></legend>
	<label title='Mail Server'><input type='text' name='smtp_mail_server' value='<?php echo $SMTP_Mail_Server; ?>' /> </label><br />
	<p>The server that should be connected to for SMTP e-mail, if you'd like to use SMTP to send your e-mails.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">SMTP Mail Username</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>SMTP Mail Username</span></legend>
	<label title='Email Username'><input type='text' name='smtp_username' value='<?php echo isset($SMTP_Username) ? $SMTP_Username : "" ?>' /> </label><br />
	<p>The username to connect to SMTP server, if you'd like to use SMTP to send your e-mails and it's different from the admin e-mail address.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">SMTP Mail Password</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>SMTP Mail Password</span></legend>
	<label title='Email Password'><input type='password' name='admin_password' value='<?php echo $Admin_Password; ?>' /> </label><br />
	<p>The password for your email account, if you'd like to use SMTP to send your e-mails.</p>
	</fieldset>
</td>
</tr>
</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>