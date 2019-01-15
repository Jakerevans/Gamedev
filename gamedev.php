<?php
/**
 * GameDev  Extension
 *
 * @package     GameDev  Extension
 * @author      Jake Evans
 * @copyright   2018 Jake Evans
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: GameDev  Extension
 * Plugin URI: https://www.jakerevans.com
 * Description: A Plugin that houses a Game.
 * Version: 0.0.1
 * Author: Jake Evans
 * Text Domain: gamedev
 * Author URI: https://www.jakerevans.com
 */

/*
 * SETUP NOTES:
 *
 * Change all filename instances from  to desired plugin name
 *
 * Modify Plugin Name
 *
 * Modify Description
 *
 * Modify Version Number in Block comment and in Constant
 *
 * Find & Replace these 3 strings:
 * 
 * 
 * 
 *
 * Install Gulp & all Plugins listed in gulpfile.js
 *
 *
 *
 *
 *
 */




// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/* REQUIRE STATEMENTS */
	require_once 'includes/class-gamedev-general-functions.php';
	require_once 'includes/class-gamedev-ajax-functions.php';
/* END REQUIRE STATEMENTS */

/* CONSTANT DEFINITIONS */

	// Extension version number.
	define( '_VERSION_NUM', '6.0.5' );

	// Root plugin folder directory.
	define( 'GAMEDEV_ROOT_DIR', plugin_dir_path( __FILE__ ) );

	// Root WordPress Plugin Directory.
	define( 'GAMEDEV_ROOT_WP_PLUGINS_DIR', str_replace( '/gamedev', '', plugin_dir_path( __FILE__ ) ) );

	// Root plugin folder URL .
	define( 'GAMEDEV_ROOT_URL', plugins_url() . '/gamedev/' );

	// Root Includes Directory.
	define( '_INCLUDES_DIR', GAMEDEV_ROOT_DIR . 'includes/' );

	// Root Classes Directory.
	define( '_CLASS_DIR', GAMEDEV_ROOT_DIR . 'includes/classes/' );

	// Root REST Classes Directory.
	define( '_CLASS_REST_DIR', GAMEDEV_ROOT_DIR . 'includes/classes/rest/' );

	// Root Compatability Classes Directory.
	define( '_CLASS_COMPAT_DIR', GAMEDEV_ROOT_DIR . 'includes/classes/compat/' );

	// Root Translations Directory.
	define( '_CLASS_TRANSLATIONS_DIR', GAMEDEV_ROOT_DIR . 'includes/classes/translations/' );

	// Root Transients Directory.
	define( '_CLASS_TRANSIENTS_DIR', GAMEDEV_ROOT_DIR . 'includes/classes/transients/' );

	// Root Image URL.
	define( 'GAMEDEV_ROOT_IMG_URL', GAMEDEV_ROOT_URL . 'assets/img/' );

	// Root Image URL.
	define( 'GAMEDEV_ROOT_TILED_URL', GAMEDEV_ROOT_URL . 'assets/json/' );

	// Root Image Admin URL.
	define( 'GAMEDEV_ROOT_IMG_ADMIN_URL', GAMEDEV_ROOT_URL . 'assets/img/admin/' );

	// Root Image Game Assets URL - Images specificaly for the game.
	define( 'GAMEDEV_ROOT_IMG_GAMEASSETS_URL', GAMEDEV_ROOT_URL . 'assets/img/gameassets/' );

	// Root Image Game Assets URL - Images specificaly for the game.
	define( 'GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL', GAMEDEV_ROOT_URL . 'assets/img/gameassets/environments/' );

	// Root Image Spritesheets URL - all spritesheets for game.
	define( 'GAMEDEV_ROOT_IMG_SPRITESHEETS_URL', GAMEDEV_ROOT_URL . 'assets/img/gameassets/spritesheets/' );

	// Root CSS URL.
	define( 'GAMEDEV_CSS_URL', GAMEDEV_ROOT_URL . 'assets/css/' );

	// Root JS URL.
	define( 'GAMEDEV_JS_URL', GAMEDEV_ROOT_URL . 'assets/js/' );

	// Root UI directory.
	define( 'GAMEDEV_ROOT_INCLUDES_UI', GAMEDEV_ROOT_DIR . 'includes/ui/' );

	// Root UI Admin directory.
	define( 'GAMEDEV_ROOT_INCLUDES_UI_ADMIN_DIR', GAMEDEV_ROOT_DIR . 'includes/ui/admin/' );

	// Define the Uploads base directory.
	$uploads     = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	define( '_UPLOADS_BASE_DIR', $upload_path . '/' );

	// Define the Uploads base URL.
	$upload_url = $uploads['baseurl'];
	define( '_UPLOADS_BASE_URL', $upload_url . '/' );

	// Nonces array.
	define( '_NONCES_ARRAY',
		wp_json_encode(array(
			'adminnonce1' => 'gamedev__functionname_action_callback',
		))
	);

/* END OF CONSTANT DEFINITIONS */

/* MISC. INCLUSIONS & DEFINITIONS */

	// Loading textdomain.
	load_plugin_textdomain( 'gamedev', false, GAMEDEV_ROOT_DIR . 'languages' );

/* END MISC. INCLUSIONS & DEFINITIONS */

/* CLASS INSTANTIATIONS */

	// Call the class found in gamedev-functions.php.
	$gamedev_general_functions = new _General_Functions();

	// Call the class found in gamedev-functions.php.
	$_ajax_functions = new _Ajax_Functions();


/* END CLASS INSTANTIATIONS */


/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// Function that loads up the menu page entry for this Extension.
	add_filter( 'gamedev_add_sub_menu', array( $gamedev_general_functions, 'gamedev__submenu' ) );

	// Adding the function that will take our _NONCES_ARRAY Constant from above and create actual nonces to be passed to Javascript functions.
	add_action( 'init', array( $gamedev_general_functions, 'gamedev__create_nonces' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'plugins_loaded', array( $gamedev_general_functions, 'gamedev__update_upgrade_function' ) );

	// Adding the frontend js file.
	add_action( 'wp_enqueue_scripts', array( $gamedev_general_functions, 'gamedev__frontend_js' ) );

	// Adding the admin js file.
	add_action( 'admin_enqueue_scripts', array( $gamedev_general_functions, 'gamedev__admin_js' ) );

	

	// Adding the admin css file for this extension.
	add_action( 'admin_enqueue_scripts', array( $gamedev_general_functions, 'gamedev__admin_style' ) );

	// Adding the Front-End css file for this extension.
	add_action( 'wp_enqueue_scripts', array( $gamedev_general_functions, 'gamedev__frontend_style' ) );

	// Function to add table names to the global $wpdb.
	add_action( 'admin_footer', array( $gamedev_general_functions, 'gamedev__register_table_name' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'admin_footer', array( $gamedev_general_functions, 'gamedev__admin_pointers_javascript' ) );

	// Creates tables upon activation.
	register_activation_hook( __FILE__, array( $gamedev_general_functions, 'gamedev__create_tables' ) );

	// Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'gamedev_jre_user_options' table of the core plugin.
	register_activation_hook( __FILE__, array( $gamedev_general_functions, 'gamedev__record_extension_version' ) );

	// Adding the front-end shortcode.
	add_shortcode( 'gamedev_shortcode', array( $gamedev_general_functions, 'gamedev_shortcode_function' ) );



/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// For receiving user feedback upon deactivation & deletion.
	add_action( 'wp_ajax__exit_results_action', array( $_ajax_functions, '_exit_results_action_callback' ) );

/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */






















