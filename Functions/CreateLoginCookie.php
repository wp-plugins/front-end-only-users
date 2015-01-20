<?php
function CreateLoginCookie($Username, $Password) {
$LoginTime = get_option("EWD_FEUP_Login_Time");
$Salt = get_option("EWD_FEUP_Hash_Salt");

$CookieName = urlencode("EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt))); 
$CookieValue = $Username . "%" . time() . "%" . md5($_SERVER['REMOTE_ADDR'].$Salt);
$ExpirySecond = time() + (1+$LoginTime)*60;

if (setcookie($CookieName, $CookieValue, $ExpirySecond, '/')) {return true;}
else {return false;}
}
?>
