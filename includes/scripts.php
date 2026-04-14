<?php // phpcs:ignore
namespace SKTPREVIEW;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Handles plugin scripts and styles.
 *
 * @since 1.0.0
 */
class Scripts {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The current admin page.
	 */
	public function admin_enqueue_scripts( $hook ) {

	}

	/**
	 * Enqueue public-facing scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function public_enqueue_scripts() {

		// Enqueue styles for Directorist single listing pages
		if ( is_singular( 'at_biz_dir' ) || isset( $_GET['atbdp_listing_slug'] ) ) {
			wp_enqueue_style(
				'sktpr-single-listing',
				SKTPR_PLUGIN_URI . 'assets/css/single-listing-page.css',
				array(),
				SKTPR_VERSION
			);
		}
	}

}
