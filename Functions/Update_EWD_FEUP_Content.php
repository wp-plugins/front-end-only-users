<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */

function Update_EWD_FEUP_Content() {
global $message;
if (isset($_GET['Action'])) {
				switch ($_GET['Action']) {
    				case "EWD_FEUP_AddUser":
						case "EWD_FEUP_EditUser":
        				$message = Add_Edit_User();
								break;
						case "EWD_FEUP_DeleteUser":
								$message = Delete_EWD_FEUP_User($_GET['User_ID']);
								break;
						case "EWD_FEUP_MassDeleteUsers":
								$message = Mass_Delete_EWD_FEUP_Users();
								break;
						case "EWD_FEUP_AddField":
						case "EWD_FEUP_EditField":
								$message = Add_Edit_Field();
								break;
						case "EWD_FEUP_DeleteField":
								$message = Delete_EWD_FEUP_Field($_GET['Field_ID']);
								break;
						case "EWD_FEUP_MassDeleteFields":
								$message = Mass_Delete_EWD_FEUP_Fields();
								break;
						case "EWD_FEUP_AddLevel":
						case "EWD_FEUP_EditLevel":
								$message = Add_Edit_Level();
								break;
						case "EWD_FEUP_DeleteLevel":
								$message = Delete_EWD_FEUP_Level($_GET['Level_ID']);
								break;
						case "EWD_FEUP_MassDeleteLevels":
								$message = Mass_Delete_EWD_FEUP_Levels();
								break;
						case "EWD_FEUP_UpdateOptions":
								$message = Update_EWD_FEUP_Options();
								break;
						case "EWD_FEUP_UpdateEmailSettings":
        				$message = Update_EWD_FEUP_Email_Settings();
								break;
						default:
								$message = __("The form has not worked correctly. Please contact the plugin developer.", 'EWD_FEUP');
								break;
				}
		}
}

?>