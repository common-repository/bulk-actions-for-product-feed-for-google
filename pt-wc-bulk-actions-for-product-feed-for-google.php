<?php
/**
 * Plugin Name: Bulk actions for product feed for Google
 * Description: Add bulk action for hidding/showing products at feed sent to Google Merchant Center.
 *
 * Author: Plugin Territory
 * Author URI: https://pluginterritory.com
 *
 * Text Domain: pt-wc-bulk-actions-for-product-feed-for-google
 * Domain Path: /languages
 *
 * Version: 1.0
 *
 * Requires at least: 5.0
 * Tested up to: 5.4.1
 *
 * Requires PHP: 5.6
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.2
 *
 * Copyright: 2020 Plugin Territory
 * License: GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'pt_wc_BAPFG' ) ) {

	/**
	 * Main pt_wc_BAPFG class
	 *
	 * @since       0.1.0
	 */
	class pt_wc_BAPFG {

		/**
		 * @var         pt_wc_BAPFG $instance The one true pt_wc_BAPFG
		 * @since       0.1.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       0.1.0
		 * @return      self The one true pt_wc_BAPFG
		 */
		public static function instance() {
			
			if ( ! self::$instance ) {

				self::$instance = new pt_wc_BAPFG();

				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();

				// self::$instance->hooks();
			
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       0.1.0
		 * @return      void
		 */
		private function setup_constants() {
			
			// Plugin version
			define( 'pt_wc_BAPFG_VER', '1.0' );

			// Plugin path
			define( 'pt_wc_BAPFG_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'pt_wc_BAPFG_URL', plugin_dir_url( __FILE__ ) );
		
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       0.1.0
		 * @return      void
		 */
		private function includes() {

			if ( is_admin() ) {

				require_once( pt_wc_BAPFG_DIR . 'class-admin.php' );

			}
		}


		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       0.1.0
		 * @return      void
		 *
		 *
		 */
		private function hooks() {

		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       0.1.0
		 * @return      void
		 */
		public function load_textdomain() {

			load_plugin_textdomain( 'pt-wc-bulk-actions-for-product-feed-for-google' );
			
		}

	}
} // End if class_exists check


add_action( 'plugins_loaded', 'pt_wc_bapfg_load' );
/**
 * The main function responsible for returning the one true
 * instance to functions everywhere
 *
 * @since       0.1.0
 * @return      \pt_wc_BAPFG The one true pt_wc_BAPFG
 *
 */
function pt_wc_bapfg_load() {

	// Check if WooCommerce Product Feed is active

	if ( class_exists( 'WoocommerceGpfCommon', false ) ) {

		return pt_wc_BAPFG::instance();

	}

}


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       0.1.0
 * @return      void
 */
function pt_wc_bapfg_activation() {
	/* Activation functions here */
}
register_activation_hook( __FILE__, 'pt_wc_bapfg_activation' );