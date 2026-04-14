<?php // phpcs:ignore
namespace SKTPREVIEW;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @return void
 */
class Plugin {

	/**
	 * Define Instance.
	 *
	 * @var $instance.
	 */
	private static $instance;

	/**
	 * Returns an instance of the Plugin class.
	 *
	 * @return self
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public $scripts; // phpcs:ignore
	public $template_locator; // phpcs:ignore

	/**
	 * Constructor for the class.
	 */
	public function __construct() {

		$this->scripts         			= new Scripts();
		$this->template_locator         = new Template_Locator();
	}
}

/**
 * Returns an instance of the Plugin class.
 *
 * @return self
 */
function plugin() { // phpcs:ignore
	return Plugin::get_instance();
}

add_action(
	'plugins_loaded',
	function () {
		plugin();
	},
	0
);
