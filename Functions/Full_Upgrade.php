<?php 
function EWD_FEUP_Upgrade_To_Full() {
	global $ewd_otp_message, $Full_Version;
	
	$Key = $_POST['Key'];
	$opts = array('http'=>array('method'=>"GET"));
	$context = stream_context_create($opts);
	$Response = unserialize(file_get_contents("http://www.etoilewebdesign.com/UPCP-Key-Check/EWD_FEUP_KeyCheck.php?Key=" . $Key . "&Site=" . get_bloginfo('wpurl'), false, $context));
	//echo "http://www.etoilewebdesign.com/UPCP-Key-Check/EWD_OTP_KeyCheck.php?Key=" . $Key . "&Site=" . get_bloginfo('wpurl');
	//$Response = file_get_contents("http://www.etoilewebdesign.com/UPCP-Key-Check/KeyCheck.php?Key=" . $Key);
	
	if ($Response['Message_Type'] == "Error") {
		  $ewd_otp_message = array("Message_Type" => "Error", "Message" => $Response['Message']);
	}
	else {
			$ewd_otp_message = array("Message_Type" => "Update", "Message" => $Response['Message']);
			update_option("EWD_OTP_Full_Version", "Yes");
			$EWD_OTP_Full_Version = get_option("EWD_OTP_Full_Version");
	}
}

 ?>
