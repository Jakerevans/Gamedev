<?php
/**
 * Class _General_Functions - class--general-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '_General_Functions', false ) ) :
	/**
	 * _General_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class _General_Functions {

		/**
		 * Function that outputs the front-end game.
		 */
		public function gamedev_shortcode_function( $atts ) {

			extract(
				shortcode_atts(
					array(
						'table'  => null,
						'action' => null,
					),
				$atts )
			);

			ob_start();
			include_once GAMEDEV_ROOT_INCLUDES_UI . 'class-gamedev-frontend-ui.php';
			$front_end_ui = new Game_Frontend_Ui();
			$front_end_ui->gamedev_quick_ui();
			return ob_get_clean();
		}

		/** Function that loads up the menu page entry for this Extension.
		 *
		 *  @param array $submenu_array - The array that contains submenu entries to add to.
		 */
		public function gamedev__submenu( $submenu_array ) {
			$extra_submenu = array(
				'',
			);

			// Combine the two arrays.
			$submenu_array = array_merge( $submenu_array, $extra_submenu );
			return $submenu_array;
		}

		/**
		 *  Here we take the Constant defined in gamedev.php that holds the values that all our nonces will be created from, we create the actual nonces using wp_create_nonce, and the we define our new, final nonces Constant, called WPBOOKLIST_FINAL_NONCES_ARRAY.
		 */
		public function gamedev__create_nonces() {

			$temp_array = array();
			foreach ( json_decode( _NONCES_ARRAY ) as $key => $noncetext ) {
				$nonce              = wp_create_nonce( $noncetext );
				$temp_array[ $key ] = $nonce;
			}

			// Defining our final nonce array.
			define( '_FINAL_NONCES_ARRAY', wp_json_encode( $temp_array ) );

		}

		/**
		 *  Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'gamedev_jre_user_options' table of the core plugin.
		 */
		public function gamedev__record_extension_version() {

			/*
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'gamedev_jre_user_options' );

			// Check to see if Extension is already registered.
			if ( false !== strpos( $existing_string->extensionversions, '' ) ) {
				$split_string = explode( '', $existing_string->extensionversions );
				$first_part   = $split_string[0];
				$last_part    = substr( $split_string[1], 5 );
				$new_string   = $first_part . '' . _VERSION_NUM . $last_part;
			} else {
				$new_string = $existing_string->extensionversions . '' . _VERSION_NUM;
			}

			$data         = array(
				'extensionversions' => $new_string,
			);
			$format       = array( '%s' );
			$where        = array( 'ID' => 1 );
			$where_format = array( '%d' );
			$wpdb->update( $wpdb->prefix . 'gamedev_jre_user_options', $data, $where, $format, $where_format );
			*/

		}

		/**
		 *  Function to run the compatability code in the Compat class for upgrades/updates, if stored version number doesn't match the defined global in gamedev-.php
		 */
		public function gamedev__update_upgrade_function() {

			/* Get current version #.
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'gamedev_jre_user_options' );

			// Check to see if Extension is already registered and matches this version.
			if ( false !== strpos( $existing_string->extensionversions, '' ) ) {
				$split_string = explode( '', $existing_string->extensionversions );
				$version      = substr( $split_string[1], 0, 5 );

				// If version number does not match the current version number found in gamedev.php, call the Compat class and run upgrade functions.
				if ( _VERSION_NUM !== $version ) {
					require_once _CLASS_COMPAT_DIR . 'class--compat-functions.php';
					$compat_class = new _Compat_Functions();
				}
			}
			*/
		}

		/**
		 * Adding the admin js file
		 */
		public function gamedev__admin_js() {

			wp_register_script( 'gamedev_adminjs', GAMEDEV_JS_URL . 'gamedev_admin.min.js', array( 'jquery' ), WPBOOKLIST_VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the gamedev-admin-js.js JavaScript File.
			require_once _CLASS_TRANSLATIONS_DIR . 'class-gamedev-translations.php';
			$trans = new GameDev__Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( _FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_ADMIN_URL']                   = GAMEDEV_ROOT_IMG_ADMIN_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_URL']                         = GAMEDEV_ROOT_IMG_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_GAMEASSETS_URL']              = GAMEDEV_ROOT_IMG_GAMEASSETS_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL'] = GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_SPRITESHEETS_URL']            = GAMEDEV_ROOT_IMG_SPRITESHEETS_URL;
			$final_array_of_php_values['FOR_TAB_HIGHLIGHT']                            = admin_url() . 'admin.php';
			$final_array_of_php_values['SAVED_ATTACHEMENT_ID']                         = get_option( 'media_selector_attachment_id', 0 );

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'gamedev__adminjs', 'gamedevPhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'gamedev__adminjs' );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the frontend js file
		 */
		public function gamedev__frontend_js() {

			wp_register_script( 'phaser_lib', GAMEDEV_JS_URL . 'phaser.js', array( 'jquery' ), WPBOOKLIST_VERSION_NUM, true );

			wp_register_script( 'gamedev_frontendjs', GAMEDEV_JS_URL . 'gamedev_frontend.min.js', array( 'jquery' ), _VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the gamedev-admin-js.js JavaScript File.
			require_once _CLASS_TRANSLATIONS_DIR . 'class-gamedev-translations.php';
			$trans = new GameDev__Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( _FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_ADMIN_URL']                   = GAMEDEV_ROOT_IMG_ADMIN_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_URL']                         = GAMEDEV_ROOT_IMG_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_GAMEASSETS_URL']              = GAMEDEV_ROOT_IMG_GAMEASSETS_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL'] = GAMEDEV_ROOT_IMG_GAMEASSETS_ENVIRONMENTS_URL;
			$final_array_of_php_values['GAMEDEV_ROOT_IMG_SPRITESHEETS_URL']            = GAMEDEV_ROOT_IMG_SPRITESHEETS_URL;

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'gamedev_frontendjs', 'gamedevPhpVariables', $final_array_of_php_values );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the admin css file
		 */
		public function gamedev__admin_style() {

			wp_register_style( 'gamedev__adminui', GAMEDEV_CSS_URL . 'gamedev-main-admin.css', null, _VERSION_NUM );
			wp_enqueue_style( 'gamedev__adminui' );

		}

		/**
		 * Adding the frontend css file
		 */
		public function gamedev__frontend_style() {

			wp_register_style( 'gamedev__frontendui', GAMEDEV_CSS_URL . 'gamedev-main-frontend.css', null, _VERSION_NUM );
			wp_enqueue_style( 'gamedev__frontendui' );

		}

		/**
		 *  Function to add table names to the global $wpdb.
		 */
		public function gamedev__register_table_name() {
			global $wpdb;
			//$wpdb->gamedev_jre_saved_book_log = "{$wpdb->prefix}gamedev_jre_saved_book_log";
		}

		/**
		 *  Function that calls the Style and Scripts needed for displaying of admin pointer messages.
		 */
		public function gamedev__admin_pointers_javascript() {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
		}

		/**
		 *  Runs once upon plugin activation and creates the table that holds info on GameDev Pages & Posts.
		 */
		public function gamedev__create_tables() {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			global $charset_collate;

			/*
			// Call this manually as we may have missed the init hook.
			$this->gamedev__register_table_name();

			$sql_create_table1 = "CREATE TABLE {$wpdb->gamedev_}
			(
				ID bigint(190) auto_increment,
				getstories bigint(255),
				createpost bigint(255),
				createpage bigint(255),
				storypersist bigint(255),
				deletedefault bigint(255),
				notifydismiss bigint(255) NOT NULL DEFAULT 1,
				newnotify bigint(255) NOT NULL DEFAULT 1,
				notifymessage MEDIUMTEXT,
				storytimestylepak varchar(255) NOT NULL DEFAULT 'default',
				PRIMARY KEY  (ID),
				KEY getstories (getstories)
			) $charset_collate; ";
			dbDelta( $sql_create_table1 );
			*/
		}

	}
endif;
