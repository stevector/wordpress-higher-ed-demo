<?php
/**
 * Customize Hero options base class
 *
 * @package Customize_Hero
 * @since   0.1.0
 */

/**
 * Class CustomizeHero_Options
 */
class CustomizeHero_Options {

	/**
	 * Holds the singleton instance of this class
	 *
	 * @since 0.1.0
	 * @var CustomizeHero_Options|Bool
	 */
	public static $instance = false;

	/**
	 * Singleton
	 *
	 * @since 0.1.0
	 * @static
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new CustomizeHero_Options();
		}

		return self::$instance;
	}

	/**
	 * Constructor.  Initializes WordPress hooks
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );
	}

	/**
	 * Register the customize hero options admin menu link
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Customize Hero', 'customize-hero' ),
			__( 'Customize Hero', 'customize-hero' ),
			'edit_theme_options',
			/*
			 * Auto focus the customize hero options panel.
			 *
			 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#focusing
			 */
			'customize.php?autofocus[section]=customize_hero&return=' . rawurlencode( get_admin_url() )
		);
	}

	/**
	 * Adds option sections for customize hero options in customizer
	 *
	 * @param object $wp_customize an instance of the WP_Customize_Manager class.
	 */
	public function customizer_settings( $wp_customize ) {
		/*
		 * Add customize hero options section to customizer.
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#sections
		 */
		$wp_customize->add_section(
			// Use a unique, descriptive section slug to avoid conflicts.
			'customize_hero',
			array(
				'title'           => __( 'Customize Hero Options', 'customize-hero' ),
				'description'     => __( 'Customize Hero Options', 'customize-hero' ),
				// Priority 125 is just above twentyseventeen theme options.
				'priority'        => 125,
				// Null adds the section to the top level. Can also be a custom panel.
				'panel'           => null,

				/*
				 * Add an active callback so the customize hero options are only visible on the front page, where the banner is
				 * this way the user won't get confused by seeing options that don't apply to the page they are viewing.
				 *
				 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#contextual-controls-sections-and-panels
				 * @link https://core.trac.wordpress.org/browser/tags/4.6/src/wp-includes/class-wp-customize-section.php#L133
				 */
				'active_callback' => array( $this, 'active_callback' ),
			)
		);

		/*
		 * Add individual settings to the customize hero options section.
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#settings
		 */
		$wp_customize->add_setting(
			'customize_hero_background_color', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => CustomizeHero::$defaults['customize_hero_background_color']['value'],

				/**
				 * Sanitization callbacks are important!
				 * Don't add settings with out sanitizing them.
				 */
				'sanitize_callback' => 'sanitize_hex_color',
				/**
				 * The postMessage transport disables full page reload
				 * but we must manually add JavaScript to perform the update.
				 *
				 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#using-postmessage-for-improved-setting-previewing
				 */
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'customize_hero_title_text', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => get_bloginfo( 'name' ),
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
				/**
				 * You can have validation callbacks too.
				 * Make items required or validate a certain format.
				 */
				'validate_callback' => array( 'CustomizeHero_Options', 'customize_hero_validate_title_text' ),
			)
		);

		$wp_customize->add_setting(
			'customize_hero_title_size', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => CustomizeHero::$defaults['customize_hero_title_size']['value'],
				'sanitize_callback' => array( 'CustomizeHero', 'sanitize_font_size' ),
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'customize_hero_title_color', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => CustomizeHero::$defaults['customize_hero_title_color']['value'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'customize_hero_description_text', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'customize_hero_description_color', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				'default'           => CustomizeHero::$defaults['customize_hero_description_color']['value'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		/*
		 * Add a Customizer control for each setting.
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#controls
		 */
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'customize_hero_background_color',
				array(
					'priority'    => 10,
					'section'     => 'customize_hero',
					'label'       => __( 'Background Color', 'customize-hero' ),
					'description' => __( '', 'customize-hero' ),
					'default'     => CustomizeHero::$defaults['customize_hero_background_color']['value'],
				)
			)
		);

		$wp_customize->add_control(
			'customize_hero_title_text', array(
				'type'        => 'text',
				'priority'    => 20,
				'section'     => 'customize_hero',
				'label'       => __( 'Title Text', 'customize-hero' ),
				'description' => __( '', 'customize-hero' ),
				'input_attrs' => array(
					'placeholder' => get_bloginfo( 'name' ),
				),
			)
		);

		$wp_customize->add_control(
			'customize_hero_title_size', array(
				'type'        => 'range',
				'priority'    => 30,
				'section'     => 'customize_hero',
				'label'       => __( 'Title Font Size', 'customize-hero' ),
				'description' => __( '', 'customize-hero' ),
				// You can add HTML5 attributes too.
				'input_attrs' => array(
					'min'  => CustomizeHero::$defaults['customize_hero_title_size']['min'],
					'max'  => CustomizeHero::$defaults['customize_hero_title_size']['max'],
					'step' => 2,
				),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'customize_hero_title_color',
				array(
					'priority'    => 40,
					'section'     => 'customize_hero',
					'label'       => __( 'Title Text Color', 'customize-hero' ),
					'description' => __( '', 'customize-hero' ),
				)
			)
		);

		$wp_customize->add_control(
			'customize_hero_description_text', array(
				'type'        => 'text',
				'priority'    => 50,
				'section'     => 'customize_hero',
				'label'       => __( 'Description Text', 'customize-hero' ),
				'description' => __( '', 'customize-hero' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'customize_hero_description_color',
				array(
					'priority'    => 60,
					'section'     => 'customize_hero',
					'label'       => __( 'Description Text Color', 'customize-hero' ),
					'description' => __( '', 'customize-hero' ),
				)
			)
		);

		/**
		 * Add a field to the Customizer for the banner background image.
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#core-custom-controls
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#selective-refresh-fast-accurate-updates
		 */

		$wp_customize->add_setting(
			'customize_hero_background_image', array(
				'capability'        => 'manage_options',
				'type'              => 'theme_mod',
				// We need postMessage to avoid full page reload.
				'transport'         => 'postMessage',
				/**
				 * Sanitization callbacks can handle validity as well.
				 * Make items required or validate a certain format.
				 *
				 * @link https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#setting-validation
				 */
				'sanitize_callback' => array( 'CustomizeHero_Options', 'customize_hero_sanitize_background_image' ),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'customize_hero_background_image',
				array(
					'label'     => __( 'Banner Background Image. Should be 1920px x 1200px', 'customize-hero' ),
					'section'   => 'customize_hero',
					'mime_type' => 'image',
					'width'     => 1920,
					'height'    => 1200,
				)
			)
		);

		/**
		 * Add a selective refresh partial for the banner
		 * when the background image changes
		 */
		$wp_customize->selective_refresh->add_partial(
			'customize_hero_background_image', array(
				'selector'            => '#customize-hero',
				'render_callback'     => function () {
					$customize_hero = CustomizeHero::init();
					echo $customize_hero->hero_html();
				},
				'container_inclusive' => true,
			)
		);

	}

	/**
	 * Decide whether or not to show the single hero options.
	 *
	 * @return bool
	 */
	public function active_callback() {
		// Filterable so others can decide when to show the hero options.
		$show_options = apply_filters( 'customize_hero_active_callback', true );

		return ( false !== $show_options && 0 !== $show_options );
	}

	/**
	 * Validate the banner title text
	 *
	 * @param WP_Error $validity validity object.
	 * @param mixed    $value    user inputted value.
	 *
	 * @return WP_Error $validity
	 */
	public function customize_hero_validate_title_text( $validity, $value ) {
		if ( '' === $value || empty( $value ) ) {
			$validity->add( 'required', __( 'A site title is required.', 'customize-hero' ) );
		} elseif ( 1 === preg_match( '/[wW]ordpress/', $value ) ) {
			$validity->add( 'WordPress', __( 'Capital P dangit!', 'customize-hero' ) );
		}

		return $validity;
	}

	/**
	 * Validate the banner background image
	 *
	 * @param mixed $value user inputted value.
	 *
	 * @return false|int|WP_Error returns false when no image is selected, image attachment ID on success, WP_Error on failure
	 */
	public function customize_hero_sanitize_background_image( $value ) {
		if ( empty( $value ) ) {
			return false;
		}

		$value = intval( $value );
		if ( $value <= 0 ) {
			return new WP_Error( 'invalid_post_id', __( 'Invalid post ID', 'customize-hero' ) );
		}

		$image_atts = wp_get_attachment_metadata( $value );
		if ( false === $image_atts ) {
			return new WP_Error( 'invalid_attachment', __( 'Invalid attachment', 'customize-hero' ) );
		}

		if ( $image_atts['width'] < 1920 ) {
			return new WP_Error( 'image_size', __( 'The banner image should be at least 1920px wide', 'customize-hero' ) );
		}

		return $value;
	}
}
