<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'pt_wc_BAPFG_Admin' ) ) {

	/**
	 * pt_wc_BAPFG_Admin class
	 *
	 */
	class pt_wc_BAPFG_Admin {

		/**
		 * @var         pt_wc_BAPFG_Admin $instance The one true pt_wc_BAPFG_Admin
		 * @since       0.2.0
		 */
		private static $instance;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       0.2.0
		 * @return      object $this->$instance The one true pt_wc_BAPFG_Admin
		 */
		public static function instance() {

			if ( ! $instance ) {

				$instance = new pt_wc_BAPFG_Admin();
				$instance->hooks();

			}


			return $instance;
		}


		private function hooks() {

			add_action( 'admin_notices',                    array( $this, 'admin_notice' ) );
			add_filter( 'bulk_actions-edit-product',        array( $this, 'bulk_actions' ) );
			add_filter( 'handle_bulk_actions-edit-product', array( $this, 'action_handler'), 10, 3 );

		}

		public function bulk_actions( $bulk_actions ) {

			$bulk_actions['pt_wc_bapfg_hide'] = esc_html__('Hide from Google feed', 'pt-wc-bulk-actions-for-product-feed-for-google' );
			$bulk_actions['pt_wc_bapfg_show'] = esc_html__('Show at Google feed', 'pt-wc-bulk-actions-for-product-feed-for-google' );

			return $bulk_actions;

		}

		public function action_handler( $redirect_to, $action, $post_ids ) {
	
			if ( 'pt_wc_bapfg_show' === $action ) {

				$this->_bulk_show( $post_ids ); 

			} elseif ( 'pt_wc_bapfg_hide' === $action ) {

				$this->_bulk_hide( $post_ids );

			} else {

				return $redirect_to;

			}

			$redirect_to = add_query_arg( array( $action => count( $post_ids ) ), $redirect_to );
			
			return $redirect_to;
		}

		/**
		 * Shows a notice in the admin when the bulk actions are completed.
		 */
		public function admin_notice() {

			$message = '';
		
			if ( isset( $_REQUEST['pt_wc_bapfg_show'] ) ) {

				$processed = intval( $_REQUEST['pt_wc_bapfg_show'] );

				if ( 1 == $processed ) {

					$message = esc_html__( 'One product enabled on Google feed.', 'pt-wc-bulk-actions-for-product-feed-for-google' );
		
				} else {

					$message = sprintf(

									esc_html__( '%d products enabled on Google feed.', 'pt-wc-bulk-actions-for-product-feed-for-google' ),
		
										number_format_i18n( $processed ) );
				}


			} elseif ( isset( $_REQUEST['pt_wc_bapfg_hide'] ) ) {

				$processed = intval( $_REQUEST['pt_wc_bapfg_hide'] );

				if ( 1 == $processed ) {

					$message = esc_html__( 'One product hidden from Google feed.', 'pt-wc-bulk-actions-for-product-feed-for-google' );
		
				} else {

					$message = sprintf(

									esc_html__( '%d products hidden from Google feed.', 'pt-wc-bulk-actions-for-product-feed-for-google' ),
		
										number_format_i18n( $processed ) );
				}
			}

			if ( ! empty( $message ) ) {

				printf(	'<div id="message" class="notice notice-success fade"><p>%s</p></div>', $message );

			}
		}

		private function _bulk_show( $posts = array() ) {

			foreach ( $posts as $key => $post_id ) {

				$status = get_post_meta( $post_id, '_woocommerce_gpf_data', true );

				if ( isset( $status['exclude_product'] ) && 'on' === $status['exclude_product'] ) {
					
					unset( $status['exclude_product'] );

					if ( empty( $status ) ) {

						delete_post_meta( $post_id, '_woocommerce_gpf_data' );

					} else {

						update_post_meta( $post_id, '_woocommerce_gpf_data', $status );	

					}
				}
			}
		}

		private function _bulk_hide( $posts = array() ) {

			foreach ( $posts as $key => $post_id ) {
			
				$status = get_post_meta( $post_id, '_woocommerce_gpf_data', true );

				if ( ! $status ) {

					$status = array( 'exclude_product' => 'on' );
					add_post_meta( $post_id, '_woocommerce_gpf_data', $status );


				} elseif ( ! isset( $status['exclude_product'] ) ) {
					
					$status['exclude_product'] = 'on';
					update_post_meta( $post_id, '_woocommerce_gpf_data', $status );

				}
			}
		}

	}
	
	$pt_wc_BAPFG_Admin = new pt_wc_BAPFG_Admin();
	$pt_wc_BAPFG_Admin->instance();

} // end class_exists check