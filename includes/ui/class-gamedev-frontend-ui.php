<?php
/**
 * Class Game_Frontend_Ui - class-gamedev-frontend-ui.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Game_Frontend_Ui', false ) ) :
	/**
	 * Game_Frontend_Ui class. Here we'll output the game world...
	 */
	class Game_Frontend_Ui {

		/**
		 * Function that outputs the front-end game.
		 */
		public function gamedev_quick_ui() {

			// This echo is for inserting the Phaser.js canvas.
			echo '<span id="jre-gamedev-for-game-insertion"></span>';
			wp_enqueue_script( 'phaser_lib' );
			wp_enqueue_script( 'gamedev_frontendjs' );

		}
	}
endif;
