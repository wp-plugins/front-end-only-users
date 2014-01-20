<?php
function CheckLoginCookie() {
global $wpdb, $ewd_feup_user_table_name;

$LoginTime = get_option("EWD_FEUP_Login_Time");
$Salt = get_option("EWD_FEUP_Hash_Salt");
$CookieName = "EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt)); 
$Cookie = $_COOKIE[$CookieName];

$Username = substr($Cookie, 0, strpos($Cookie, "%"));
$TimeStamp = substr($Cookie, strpos($Cookie, "%")+1, strrpos($Cookie, "%")-strpos($Cookie, "%")); 
$HashedPassword = sha1(substr($Cookie, strrpos($Cookie, "%")+1));

if (isset($_COOKIE[$CookieName]) and $TimeStamp < (time() + $LoginTime*60)) {
		$UserDB = $wpdb->get_row($wpdb->prepare("SELECT User_Password FROM $ewd_feup_user_table_name WHERE Username ='%s'", $Username));
		$HashedDBPassword = $UserDB->User_Password;
		
		if ($HashedDBPassword == $HashedPassword) {
			  $User = array('Username' => $Username, 'User_Password' => $UserDB->User_Password);
			  return $User;
		}
		else {
				return false;
		}
}

return false;
}
?>
