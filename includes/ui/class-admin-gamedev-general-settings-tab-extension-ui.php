<?php
/**
 * GameDev  Tab
 *
 * @author   Jake Evans
 * @category Extension Ui
 * @package  Includes/UI
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GameDev_', false ) ) :
	/**
	 * GameDev_Admin_Menu Class.
	 */
	class GameDev_ {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			require_once CLASS_DIR . 'class-admin-ui-template.php';
			require_once _CLASS_DIR . 'class--form.php';

			// Get Translations.
			require_once _CLASS_TRANSLATIONS_DIR . 'class-gamedev-translations.php';
			$this->trans = new GameDev__Translations();
			$this->trans->trans_strings();

			// Instantiate the class.
			$this->template = new GameDev_Admin_UI_Template();
			$this->form     = new GameDev__Form();
			$this->output_open_admin_container();
			$this->output_tab_content();
			$this->output_close_admin_container();
			$this->output_admin_template_advert();
		}

		/**
		 * Opens the admin container for the tab
		 */
		private function output_open_admin_container(){
			$title = ' General Settings';
			$icon_url = _ROOT_IMG_URL.'book.svg';
			echo $this->template->output_open_admin_container($title, $icon_url);
		}

		/**
		 * Outputs actual tab contents
		 */
		private function output_tab_content(){
			echo $this->form->output__form();
		}

		/**
		 * Closes admin container
		 */
		private function output_close_admin_container(){
			echo $this->template->output_close_admin_container();
		}

		/**
		 * Outputs advertisment area
		 */
		private function output_admin_template_advert(){
			echo $this->template->output_template_advert();
		}


	}
endif;

// Instantiate the class
$cm = new GameDev_;
