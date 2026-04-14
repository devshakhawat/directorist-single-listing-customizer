<?php // phpcs:ignore
namespace SKTPREVIEW;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Handles plugin shortcode.
 *
 * @since 1.0.0
 */
class Template_Locator {

    public function __construct() {
        add_filter( 'directorist_template_file_path', [ $this, 'override_directorist_template' ], 999 );
    }

    public function override_directorist_template( $template ) {

        pretty_log( $template, 'ggg' );

        return $template;
    } 

}