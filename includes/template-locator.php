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
        add_filter( 'directorist_template_file_path', [ $this, 'override_directorist_template' ], 999, 3 );
    }

    public function override_directorist_template( $template, $template_name, $args ) {

        $overrides = [
            'single-contents' => 'directory-single-listing-page.php',
        ];

        if ( isset( $overrides[ $template_name ] ) ) {
            $plugin_template = SKTPR_PLUGIN_DIR . 'templates/' . $overrides[ $template_name ];

            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }

}