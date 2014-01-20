<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Logout($atts) {
		// Include the required global variables, and create a few new ones
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$CookieName = urlencode("EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt))); 
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'redirect_page' => '#',
																'submit_text' => 'Logout'),
																$atts
														)
												);
		
		setcookie($CookieName, "", time()-3600, "/");
	
}
add_shortcode("logout", "Insert_Logout");
