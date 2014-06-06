<?php
if (!class_exists('FEUP_User')){
    class FEUP_User {
    		function __construct() {
    		}

    		function Is_Logged_In() {
      			$CheckCookie = CheckLoginCookie();
						if ($CheckCookie['Username'] == "") {return false;}
						else {return true;}
    		}
		}
}

/*if ( class_exists( 'Foo' ) ):
  $MyFoo = new Foo();*/

	
?>