<?php
/**
 * Community Commons NYSCAA
 *
 * @package   Community_Commons_NYSCAA
 * @author    Yan Barnett
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2016 Community Commons
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `admin/class-nyscaa-admin.php`
 *
 *
 * @package Community_Commons_NYSCAA
 * @author  Yan Barnett
 */
class CC_NYSCAA {

	/**
	 *
	 * The current version of the plugin.
	 *
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $version = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cc-nyscaa';

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->version = nyscaa_get_plugin_version();

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

		// Save some specific reports to the docs library
		// add_action( 'wp_ajax_save-nyscaa-report-as-doc', array( $this, 'ajax_save_report_as_doc' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return   string Plugin slug.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_scripts() {
		if ( nyscaa_is_nyscaa_group() ) {
			// Styles
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );

			// IE specific
			// global $wp_styles;
			// wp_enqueue_style( $this->plugin_slug . '-ie-plugin-styles', plugins_url( 'css/public-ie.css', __FILE__ ), array(), $this->version );
			// $wp_styles->add_data( $this->plugin_slug . '-ie-plugin-styles', 'conditional', 'lte IE 9' );

			// Scripts
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.min.js', __FILE__ ), array( 'jquery' ), $this->version );
		}
	}

	/**
	 * Create a report-style doc via AJAX from the report card tab.
	 *
	 * @since 1.0.0
	 */
	public function ajax_save_report_as_doc() {
		// Do our dependencies exist?
		if ( ! class_exists( 'CC_MRAD' ) || ! function_exists( 'bp_docs_get_post_type_name' ) ) {
			wp_send_json_error( 'Software dependencies are not met.' );
		}

		// Is the nonce any good?
		check_ajax_referer( 'save-leader-report-' . bp_loggedin_user_id() );

		// Can the user do this?
		if ( ! current_user_can( 'bp_docs_associate_with_group', sa_get_group_id() ) ) {
			wp_send_json_error( 'User is not allowed to take this action.' );
		}

		// Sanitize incoming POST data.
		$county = $state = $geoid = null;
		if ( ! empty( $_POST['county'] ) ) {
			$county = sanitize_text_field( $_POST['county'] );
		}
		if ( ! empty( $_POST['state'] ) ) {
			$state = sanitize_text_field( $_POST['state'] );
		}
		if ( ! empty( $_POST['geoid'] ) ) {
			// These are county level and take the form 05000US05013
			if ( preg_match( "/^05000US([0-9]{5})$/i", $_POST['geoid'] ) ) {
				$geoid = $_POST['geoid'];
			}
		}
		if ( empty( $geoid ) ) {
			wp_send_json_error( 'A location must be specified and be of the correct format.' );
		}

		// First, check to see if this user has already "saved" this exact report.
		$dupe_check_args = array(
			'post_type'  => bp_docs_get_post_type_name(),
			'author'     => bp_loggedin_user_id(),
			'meta_key'   => 'geoid',
			'meta_value' => $geoid,
			'fields'     => 'ids',
		);
		$dupes = new WP_Query( $dupe_check_args );
		if ( $dupes->posts ) {
			// Return the post ID. Should this be a success or a failure?
			wp_send_json_success( current( $dupes->posts ) );
		}

		if ( $county && $state ) {
			$title = "Salud Report Card for {$county}, {$state}";
		} else {
			$title = 'Salud Report Card';
		}

		$content = '<a href="/groups/salud-america/report-card/?geoid=' . $geoid . '" title="Link to report" class="button report-link"><span class="icon reportx24"></span>View Report Card</a>';

		$args = array(
			'title'			=> $title,
			'content' 		=> $content,
			'author_id'     => bp_loggedin_user_id(),
			'group_id'		=> sa_get_group_id(),
			'is_auto'		=> 0,
			'settings'		=> array(   'read' => 'group-members',
										'edit' => 'creator',
										'read_comments' => 'group-members',
										'post_comments' => 'group-members',
										'view_history' => 'creator'
									),
			'parent_id'		=> 0,
		);

		$instance = new CC_MRAD_BP_Doc_Save;
		$post_id = $instance->save( $args );

		if ( $post_id ) {
			$mrad_class = new CC_MRAD;
			wp_set_object_terms( $post_id, 'report', $mrad_class->get_taxonomy_name() );
			// Set the GeoID as post_meta so we can check for uniqueness in the future.
			add_post_meta( $post_id, 'geoid', $geoid );
		}

		wp_send_json_success( $post_id );
	}

}
$cc_nyscaa = new CC_NYSCAA();
