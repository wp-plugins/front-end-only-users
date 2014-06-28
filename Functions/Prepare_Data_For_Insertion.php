<?php
/* Prepare the data to add or edit a single product */
function Add_Edit_User() {
		global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
		
		$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
		$Fields = $wpdb->get_results($Sql);
		
		$date = date("Y-m-d H:i:s");
		
		$UserCookie = CheckLoginCookie();

		$User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
		$User_ID = $User->User_ID;
		if ($User_ID == "" and is_admin()) {$User_ID = $_POST['User_ID'];}
		
		if (isset($_POST['Username'])) {$User_Fields['Username'] = $_POST['Username'];}
		if (isset($_POST['User_Password'])) {$User_Fields['User_Password'] = sha1(md5($_POST['User_Password'].$Salt));}
		if ($_POST['Admin_Approved'] == "Yes") {$User_Fields['User_Admin_Approved'] = "Yes";}
		
		if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {$user_update['Message'] = __("The passwords you entered did not match.", "EWD_FEUP"); return $user_update;}
		
		if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
			  $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $_POST['Username']));
				if ($wpdb->num_rows > 0) {$user_update['Message'] = __("There is already a user with that Username, please select a different one.", "EWD_FEUP"); return $user_update;}
		}
				
		foreach ($Fields as $Field) {
				$Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
				$Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
				$Field_Name = str_replace(" ", "_", $Field->Field_Name);
				if ($Field->Field_Type == "file") {
					  $File_Upload_Return = Handle_File_Upload($Field_Name);
						if ($File_Upload_Return['Success'] == "No") {return $File_Upload_Return['Data'];}
						elseif ($File_Upload_Return['Success'] == "N/A") {unset($Additional_Fields_Array[$Field->Field_Name]);}
						else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];}
				}
				elseif (is_array($_POST[$Field_Name])) {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = implode(",", $_POST[$Field_Name]);}
				else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $_POST[$Field_Name];}
		}

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
				if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
						if ($User->User_ID != "") {$user_update = __("There is already an account with that Username. Please select a different one.", "EWD_FEUP"); return $user_update;}
						if (!isset($User_Fields['User_Admin_Approved'])) {$User_Fields['User_Admin_Approved'] = "No";}
						$User_Fields['User_Date_Created'] = $date;
						$user_update = Add_EWD_FEUP_User($User_Fields);
						$User_ID = $wpdb->insert_id;
						foreach ($Additional_Fields_Array as $Field) {
								$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);
						}
						if ($_POST['ewd-feup-action'] == "register") {
							  CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
								$user_update = __("Your account has been succesfully created.", "EWD_FEUP");
								$feup_success = true;
								if ($Sign_Up_Email == "Yes") {EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array);}
						}
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
				else {
						echo "Edit_User<br>";
						if (isset($User_Fields)) {$user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);}
						foreach ($Additional_Fields_Array as $Field) {
								$CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'", $Field['Field_ID'], $User_ID));
								if ($CurrentField->User_Field_ID != "") {$user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value']);}
								else {$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);}
						}
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				$feup_success = true;
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array) {
		$Admin_Email = get_option("EWD_FEUP_Admin_Email");
		$Email_Subject = get_option("EWD_FEUP_Email_Subject");
		$Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
		$SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
		$Message_Body = get_option("EWD_FEUP_Message_Body");
		$Email_Field = get_option("EWD_FEUP_Email_Field");
		
		$key = 'EWD_FEUP';
		$Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		
		$Message_Body = str_replace("[username]", $User_Fields['Username'], $Message_Body);
		$Message_Body = str_replace("[password]", $User_Fields['User_Password'], $Message_Body);
		$Message_Body = str_replace("[join-date]", $User_Fields['User_Date_Created'], $Message_Body);
		
		$Email_Field = str_replace(" ", "_", $Email_Field);
		$User_Email = $Additional_Fields_Array[$Email_Field]['Field_Value'];
		
		if ($SMTP_Mail_Server != "") {
				require_once(EWD_FEUP_CD_PLUGIN_PATH . '/PHPMailer/class.phpmailer.php');
				$mail = new PHPMailer(true);
				try {
  					$mail->CharSet = 'UTF-8';
						$mail->IsSMTP();
  					$mail->Host = $SMTP_Mail_Server;
  					$mail->SMTPAuth = true;
  					$mail->Username = $Admin_Email;
  					$mail->Password = $Admin_Password;
  					$mail->WordWrap = 0;
  					$mail->AddCustomHeader('X-Mailer: EWD_FEUP v1.0');
  					$mail->SetFrom($Admin_Email);
  					$mail->AddAddress($User_Email);
  					$mail->Subject = $Email_Subject;
						$mail->Body = $Message_Body;
						$mail->isHTML(true);
  					//$mail->AltBody = $Text;
  			
						if (!$mail->Send()) {
								//echo "Email not sent.<br>";
    						//echo $mail->ErrorInfo;
  					} else {
    		  			//echo "Email sent.<br>";
  					}
				} catch (phpmailerException $e) {
    	 			//echo "FAIL:\n";
    				//echo $e->errorMessage(); // from PHPMailer
				} catch (Exception $e) {
    				//echo "FAIL:\n";
    				//echo $e->getMessage(); // from anything else!
				}		
		}
		else {
				$headers = 'From: ' . $Admin_Email . "\r\n" .
    						 	 'Reply-To: ' . $Admin_Email . "\r\n" .
    							 'X-Mailer: PHP/' . phpversion();
				$Mail_Success = mail($Order_Email, "Order Update", $Message_Body, $headers);
		}
}

function Handle_File_Upload($Field_Name) {
		
		/* Test if there is an error with the uploaded file and return that error if there is */
		if (!empty($_FILES[$Field_Name]['error']))
		{
				switch($_FILES[$Field_Name]['error'])
				{

				case '1':
						$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'EWD_FEUP');
						break;
				case '2':
						$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'EWD_FEUP');
						break;
				case '3':
						$error = __('The uploaded file was only partially uploaded', 'EWD_FEUP');
						break;
				case '4':
						$error = __('No file was uploaded.', 'EWD_FEUP');
						break;

				case '6':
						$error = __('Missing a temporary folder', 'EWD_FEUP');
						break;
				case '7':
						$error = __('Failed to write file to disk', 'EWD_FEUP');
						break;
				case '8':
						$error = __('File upload stopped by extension', 'EWD_FEUP');
						break;
				case '999':
						default:
						$error = __('No error code avaiable', 'EWD_FEUP');
				}
		}
		/* Make sure that the file exists */ 	 	
		elseif (empty($_FILES[$Field_Name]['tmp_name']) || $_FILES[$Field_Name]['tmp_name'] == 'none') {
				$error = __('No file was uploaded here..', 'EWD_FEUP');
		}
		/* Move the file and store the URL to pass it onwards*/ 	 	
		else {				 
				 	  $msg .= $_FILES[$Field_Name]['name'];
						//for security reason, we force to remove all uploaded file
						$target_path = ABSPATH . 'wp-content/uploads/ewd-feup-user-uploads/';
						
						//create the uploads directory if it doesn't exist
						if (!file_exists($target_path)) {
							  mkdir($target_path, 0777, true);
						}

						$Random = RandomString();
						$target_path = $target_path . $Random . basename( $_FILES[$Field_Name]['name']); 

						if (!move_uploaded_file($_FILES[$Field_Name]['tmp_name'], $target_path)) {
						//if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
				 			  $error .= "There was an error uploading the file, please try again!";
						}
						else {
				 				$User_Upload_File_Name = $Random . basename( $_FILES[$Field_Name]['name']);
						}	
		}
		
		/* Return the file name, or the error that was generated. */
		if (isset($error) and $error == __('No file was uploaded.', 'EWD_FEUP')) {
			  $Return['Success'] = "N/A";
				$Return['Data'] = __('No file was uploaded.', 'EWD_FEUP');
		}
		elseif (!isset($error)) {
				$Return['Success'] = "Yes";
				$Return['Data'] = $User_Upload_File_Name;
		}
		else {
				$Return['Success'] = "No";
				$Return['Data'] = $error;
		}
		return $Return;
}

function RandomString($CharLength = 10)
{
    $characters = ’0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ’;
    $randstring = '';
    for ($i = 0; $i < $CharLength; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function Mass_Delete_EWD_FEUP_Users() {
		$Users = $_POST['Users_Bulk'];
		
		if (is_array($Users)) {
				foreach ($Users as $User) {
						if ($User != "") {
								Delete_EWD_FEUP_User($User);
						}
				}
		}
		
		$update = __("Users have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_Edit_Field() {
		global $wpdb, $ewd_feup_fields_table_name;
		
		$Field_ID = stripslashes_deep($_POST['Field_ID']);
		$Field_Name = stripslashes_deep($_POST['Field_Name']);
		$Field_Type = stripslashes_deep($_POST['Field_Type']);
		$Field_Description = stripslashes_deep($_POST['Field_Description']);
		$Field_Options = stripslashes_deep($_POST['Field_Options']);
		$Field_Show_In_Admin = stripslashes_deep($_POST['Field_Show_In_Admin']);
		$Field_Show_In_Front_End = stripslashes_deep($_POST['Field_Show_In_Front_End']);
		$Field_Required = stripslashes_deep($_POST['Field_Required']);
		
		$Field_Date_Created = date("Y-m-d H:i:s");

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
				if ($_POST['action'] == "Add_Field") {
					  $user_update = Add_EWD_FEUP_Field($Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created);
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
				else {
						$user_update = Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required);
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_FEUP_Fields() {
		$Fields = $_POST['Fields_Bulk'];
		
		if (is_array($Fields)) {
				foreach ($Fields as $Field) {
						if ($Field != "") {
								Delete_EWD_FEUP_Field($Field);
						}
				}
		}
		
		$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_Edit_Level() {
		
		$Level_ID = $_POST['Level_ID'];
		$Level_Name = $_POST['Level_Name'];
		$Level_Privilege = $_POST['Level_Privilege'];
		
		$Level_Date_Created = date("Y-m-d H:i:s");

		if (!isset($error)) {
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
				if ($_POST['action'] == "Add_Level") {
					  $user_update = Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created);
				}
				/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
				else {
						$user_update = Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created);
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		/* Return any error that might have occurred */
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_FEUP_Levels() {
		$Levels = $_POST['Levels_Bulk'];
		
		if (is_array($Levels)) {
				foreach ($Levels as $Level) {
						if ($Level != "") {
								Delete_EWD_FEUP_Level($Level);
						}
				}
		}
		
		$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

?>
