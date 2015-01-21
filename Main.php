<?php
/*
Plugin Name: Front-End Users Plugin
Plugin URI: http://www.etoilewebdesign.com/front-end-users-plugin/
Description: A plugin that creates a separate set of users that are front-end only users, who do not appear in the default WordPress users area, and allows content to be tailored based on user profiles
Author: Tim Ruse
Author URI: http://www.EtoileWebDesign.com/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: EWD_FEUP
Version: 1.26
*/

global $EWD_FEUP_db_version;
global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
global $wpdb;
global $feup_message;
global $user_message;
global $feup_success;
global $Full_Version;
$ewd_feup_user_table_name = $wpdb->prefix . "EWD_FEUP_Users";
$ewd_feup_user_fields_table_name = $wpdb->prefix . "EWD_FEUP_User_Fields";
$ewd_feup_fields_table_name = $wpdb->prefix . "EWD_FEUP_Fields";
$ewd_feup_levels_table_name = $wpdb->prefix . "EWD_FEUP_Levels";
$EWD_FEUP_db_version = "1.26";

define( 'EWD_FEUP_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWD_FEUP_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

 /* error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//define('WP_DEBUG', true);
$wpdb->show_errors(); */

/* When plugin is activated */
register_activation_hook(__FILE__,'Install_EWD_FEUP');
register_activation_hook(__FILE__,'Initial_EWD_FEUP_Data');
//register_activation_hook(__FILE__,'Initial_EWD_FEUP_Options');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'Remove_EWD_FEUP' );

/* Creates the admin menu for the contests plugin */
if ( is_admin() ){
	add_action('admin_menu', 'EWD_FEUP_Plugin_Menu');
	add_action('admin_head', 'EWD_FEUP_Admin_Options');
	add_action('admin_init', 'Add_EWD_FEUP_Scripts');
	add_action('widgets_init', 'Update_EWD_FEUP_Content');
	add_action('admin_notices', 'EWD_FEUP_Error_Notices');
}

function Remove_EWD_FEUP() {
  	/* Deletes the database field */
	delete_option('EWD_FEUP_db_version');
}

// Process the forms posted by users from the front-end of the plugin
if (isset($_POST['ewd-feup-action'])) {
	add_action('init', 'Process_EWD_FEUP_Front_End_Forms');
}

/* Admin Page setup */
function EWD_FEUP_Plugin_Menu() {
	add_menu_page('Front End User Plugin', 'Front-End Users', 'administrator', 'EWD-FEUP-options', 'EWD_FEUP_Output_Options',null , '50.6');
}

/* Add localization support */
function EWD_FEUP_localization_setup() {
	load_plugin_textdomain('EWD_FEUP', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_FEUP_localization_setup');

// Add settings link on plugin page
function EWD_FEUP_plugin_settings_link($links) { 
	$settings_link = '<a href="admin.php?page=EWD-FEUP-options">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'EWD_FEUP_plugin_settings_link' );

/* Put in the pretty permalinks filter */
//add_filter( 'query_vars', 'add_query_vars_filter' );

function Add_EWD_FEUP_Scripts() {
	if (isset($_GET['page']) && $_GET['page'] == 'EWD-FEUP-options') {
		$url_one = plugins_url("front-end-only-users/js/Admin.js");
		$url_two = plugins_url("front-end-only-users/js/sorttable.js");
		$url_three = plugins_url("front-end-only-users/js/jquery.confirm.min.js");
		$url_four = plugins_url("front-end-only-users/js/bootstrap.min.js");
		wp_enqueue_script('PageSwitch', $url_one, array('jquery'));
		wp_enqueue_script('sorttable', $url_two, array('jquery'));
		wp_enqueue_script('confirmation', $url_three, array('jquery'));
		wp_enqueue_script('bootstrap', $url_four, array('jquery'));
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('update-privilege-level-order', plugin_dir_url(__FILE__) . '/js/update-privilege-level-order.js');
	}
}

add_action( 'wp_enqueue_scripts', 'EWD_FEUP_Add_Stylesheet' );
function EWD_FEUP_Add_Stylesheet() {
    wp_register_style( 'ewd-feup-style', plugins_url('css/feu-styles.css', __FILE__) );
	wp_register_style( 'yahoo-pure-css', plugins_url('css/pure.css', __FILE__) );
    wp_enqueue_style( 'ewd-feup-style' );
	wp_enqueue_style( 'yahoo-pure-css' );
}

/*add_action( 'wp_enqueue_scripts', 'Add_EWD_FEUP_FrontEnd_Scripts' );
function Add_EWD_FEUP_FrontEnd_Scripts() {
	wp_enqueue_script(
		'upcpjquery',
		plugins_url( '/js/upcp-jquery-functions.js' , __FILE__ ),
		array( 'jquery' )
	);
}*/

function EWD_FEUP_Admin_Options() {
	$url = plugins_url("front-end-only-users/css/Admin.css");
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

add_action('activated_plugin','save_feup_error');
function save_feup_error(){
	update_option('plugin_error',  ob_get_contents());
	file_put_contents("Error.txt", ob_get_contents());
}

$Full_Version = get_option("EWD_FEUP_Full_Version");

/*if (isset($_POST['Upgrade_To_Full'])) {
	  add_action('admin_init', 'Upgrade_To_Full');
}*/

include "Functions/CheckLoginCookie.php";
include "Functions/CreateLoginCookie.php";
include "Functions/Determine_Redirect_Page.php";
include "Functions/Error_Notices.php";
include "Functions/EWD_FEUP_Export_To_Excel.php";
include "Functions/EWD_FEUP_Output_Options.php";
include "Functions/Initial_Data.php";
include "Functions/Install_EWD_FEUP.php";
include "Functions/Output_Buffering.php";
include "Functions/Prepare_Data_For_Insertion.php";
include "Functions/Process_Ajax.php";
include "Functions/Process_Front_End_Forms.php";
include "Functions/Public_Functions.php";
include "Functions/Update_Admin_Databases.php";
include "Functions/Update_EWD_FEUP_Content.php";
include "Functions/Update_EWD_FEUP_Tables.php";

include "Shortcodes/Insert_Confirm_Forgot_Password.php";
include "Shortcodes/Insert_Edit_Account.php";
include "Shortcodes/Insert_Edit_Profile.php";
include "Shortcodes/Insert_Forgot_Password.php";
include "Shortcodes/Insert_Login_Form.php";
include "Shortcodes/Insert_Logout.php";
include "Shortcodes/Insert_Register_Form.php";
include "Shortcodes/Insert_Reset_Password.php";
include "Shortcodes/Insert_User_Data.php";
include "Shortcodes/Insert_User_List.php";
include "Shortcodes/Insert_User_Search.php";
include "Shortcodes/Privilege_Level.php";

// Updates the UPCP database when required
if (get_option('EWD_FEUP_DB_Version') != $EWD_FEUP_db_version) {
	Update_EWD_FEUP_Tables();
}

/*if (get_option("EWD_FEUP_Update_RR_Rules") == "Yes") {
	  add_filter( 'query_vars', 'add_query_vars_filter' );
		add_filter('init', 'EWD_FEUP_Rewrite_Rules');
		update_option("EWD_FEUP_Update_RR_Rules", "No");
}*/
?>