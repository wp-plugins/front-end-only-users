<?php
if (!class_exists('FEUP_User')){
    class FEUP_User {
    		private $Username;
				private $User_ID;
				
				function __construct() {
						$CheckCookie = CheckLoginCookie();
						if ($CheckCookie['Username'] != "") {
							  global $wpdb, $ewd_feup_user_table_name;
								
								$this->Username = $CheckCookie['Username'];
								$this->User_ID = $wpdb->get_var("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='" . $this->Username ."'");
						}
    		}
				
				function Get_User_ID() {
						return $this->User_ID;
				}
				
				function Get_Username() {
						return $this->Get_Username;
				}
				
				function Get_Field_Value($Field) {
						global $wpdb, $ewd_feup_user_fields_table_name;
						$Value = $wpdb->get_var("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name='" . $Field ."'");
				}

    		function Is_Logged_In() {
						if ($this->Username == "") {return false;}
						else {return true;}
    		}
		}
}
	
?>