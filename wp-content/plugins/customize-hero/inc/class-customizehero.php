<?php
/**
 * Customize Hero base class
 *
 * @package Customize_Hero
 * @since   0.1.0
 */

/**
 * Class CustomizeHero
 */
class CustomizeHero {

	/**
	 * Holds the singleton instance of this class
	 *
	 * @since 0.1.0
	 * @var CustomizeHero|Bool
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
			self::$instance = new CustomizeHero();
		}

		return self::$instance;
	}

	/**
	 * Holds the default values for settings.
	 *
	 * @since 0.1.0
	 * @var array
	 * @static
	 */
	public static $defaults = array(
		'customize_hero_background_color'  => array(
			'value' => '#074c01',
		),
		'customize_hero_background_image'  => array(
			'value' => false,
		),
		'customize_hero_title_size'        => array(
			'value' => 48,
			'min'   => 48,
			'max'   => 80,
		),
		'customize_hero_title_color'       => array(
			'value' => '#FFFFFF',
		),
		'customize_hero_description_color' => array(
			'value' => '#FFFFFF',
		),
		'customize_hero_title_text'        => array(
			'value' => '',
		),
		'customize_hero_description_text'  => array(
			'value' => '',
		),
	);

	/**
	 * Constructor.  Initializes WordPress hooks and sets defaults.
	 *
	 * @since 0.1.0
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts_styles_customizer_control' ) );

		// Default title text and description to bloginfo.
		self::$defaults['customize_hero_title_text']['value']       = get_bloginfo( 'name' );
		self::$defaults['customize_hero_description_text']['value'] = get_bloginfo( 'description' );
	}

	/**
	 * Sanitize title size
	 *
	 * @param string $input user input.
	 * @param object $wp_customize_setting the current WP_Customize_Setting instance.
	 *
	 * @since 0.1.0
	 *
	 * @return int|WP_Error Integer if valid or WP_Error if invalid.
	 */
	public function sanitize_font_size( $input, $wp_customize_setting ) {
		// Stash the unique id.
		$key = $wp_customize_setting->id;

		$input = intval( $input, 10 );

		// Bail if the option isn't in the defaults array.
		if ( false === array_key_exists( $key, self::$defaults ) ) {
			return new WP_Error( $key, __( 'Setting does not have a default defined', 'customize-hero' ) );
		} elseif ( ! isset( self::$defaults[ $key ]['min'] ) ) {
			return new WP_Error( $key, __( 'Setting does not have min defined', 'customize-hero' ) );
		} elseif ( ! isset( self::$defaults[ $key ]['max'] ) ) {
			return new WP_Error( $key, __( 'Setting does not have max defined', 'customize-hero' ) );
		}

		$valid_sizes = array();

		$start = intval( self::$defaults[ $key ]['min'], 10 );

		$end = intval( self::$defaults[ $key ]['max'], 10 );

		// Sanitize against min and max values.
		for ( $i = $start; $i <= $end; $i = $i + 2 ) {
			$valid_sizes[] = $i;
		}

		// If there isn't a valid size return WP_Error to trigger a validation error and block the save.
		return ( in_array( $input, $valid_sizes, true ) ) ? $input : new WP_Error( $key, __( 'Invalid font size; Must be within the min/max', 'customize-hero' ) );
	}

	/**
	 * Escape font size by returning an integer value.
	 *
	 * @param string|int $size incoming font size.
	 *
	 * @since 0.1.0
	 * @return int sanitized font size.
	 */
	private function escape_font_size( $size ) {
		return intval( $size, 10 );
	}

	/**
	 * Output dynamic CSS for customize hero
	 */
	public function hero_inline_css() {
		$background_color    = get_theme_mod( 'customize_hero_background_color', self::$defaults['customize_hero_background_color']['value'] );
		$background_image_id = get_theme_mod( 'customize_hero_background_image', self::$defaults['customize_hero_background_image']['value'] );
		if ( ! empty( $background_image_id ) && false !== $background_image_id ) {
			$background_image = wp_get_attachment_image_src( $background_image_id, 'full' );
			$background_image = $background_image[0];
		} else {
			$background_image = false;
		}
		$title_color       = get_theme_mod( 'customize_hero_title_color', self::$defaults['customize_hero_title_color']['value'] );
		$description_color = get_theme_mod( 'customize_hero_description_color', self::$defaults['customize_hero_description_color']['value'] );
        $title_size        = get_theme_mod( 'customize_hero_title_size', self::$defaults['customize_hero_title_size']['value'] );
        
        $output = array (
            'main' => 'background-color: ' . sanitize_hex_color( $background_color ) . ';',
            'title' => 'font-size: ' . $this->escape_font_size( $title_size ) . 'px; color:' . sanitize_hex_color( $title_color ) . ';',
            'description' => 'color:' . sanitize_hex_color( $description_color ) . ';',
        );

        if ( false !== $background_image ){
            $output['main'] = 'background-size: cover; background: transparent url(' . esc_url( $background_image ) . ') 50% 0 no-repeat fixed;';
        }

        return $output;
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts_styles() {

		wp_enqueue_style( 'customize-hero', CUSTOMIZE_HERO_URL . '/assets/css/customize-hero.css' );

		/*
		 * The JS file enqueue'd below is necessary for postMessage to work properly.
		 * See assets/js/customize-preview.js.
		 *
		 * if_customize_preview is checked so we don't enqueue this script outside of the customizer where it isn't needed.
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#using-postmessage-for-improved-setting-previewing
		 */

		if ( is_customize_preview() ) {
			wp_enqueue_script(
				'customize-hero-preview-script', CUSTOMIZE_HERO_URL . '/assets/js/customizer-preview.js', array(
					'jquery',
					'customize-preview',
				), false, true
			);
		}

	}

	/**
	 * Enqueue scripts and styles for the customizer control.
	 */
	public function enqueue_scripts_styles_customizer_control() {

		/**
		 * The JS file enqueue'd below is used for client side settings validation.
		 * See the notes in assets/js/customize-control.js for next steps.
		 */
		wp_enqueue_script(
			'customize-hero-control-script', CUSTOMIZE_HERO_URL . '/assets/js/customizer-control.js', array(
				'customize-controls',
			), false, true
		);

		$customize_hero_control_js = array(
			'l10n' => array(
				'capitalP' => __( 'Capital P dangit!', 'customize-hero' ),
			),
		);

		wp_localize_script( 'customize-hero-control-script', 'customizeHero', $customize_hero_control_js );

	}

	/**
	 * Return the hero HTML.
	 *
	 * @return string
	 */
	public function hero_html() {
        $hero_css = $this->hero_inline_css(); 
		ob_start();
		?>
		<section id='customize-hero' style='<?php echo $hero_css['main']; ?>'>
			<div class='wrap-center'>
				<div class='wrap-inner'>

					<?php
					/**
					 * Allow other to add custom HTML before the title/description
					 */
					do_action( 'customize_hero_html_before' );
					?>

					<div id='customize-hero-title' style='<?php echo $hero_css['title']; ?>'>
						<?php
						$hero_title = get_theme_mod( 'customize_hero_title_text', '' );
						// Use the default if there isn't a value set.
						if ( empty( $hero_title ) ) {
							$hero_title = self::$defaults['customize_hero_title_text']['value'];
						}
						// Apply filters to allow others to customize the value.
						$hero_title = apply_filters( 'customize_hero_title_text', $hero_title );
						echo esc_html( $hero_title );
						?>
					</div>

					<div id='customize-hero-description' style='<?php echo $hero_css['description']; ?>'>
						<?php
						$hero_description = get_theme_mod( 'customize_hero_description_text', '' );
						// Use the default if there isn't a value set.
						if ( empty( $hero_description ) ) {
							$hero_description = self::$defaults['customize_hero_description_text']['value'];
						}
						// Apply filters to allow others to customize the value.
						$hero_description = apply_filters( 'customize_hero_description_text', $hero_description );
						echo esc_html( $hero_description );
						?>
					</div>

					<?php
					/**
					 * Allow other to add custom HTML after the title/description
					 */
					do_action( 'customize_hero_html_after' );
					?>

				</div>
			</div>
		</section>
		<?php
		$hero_html = ob_get_clean();

		return apply_filters( 'customize_hero_html', $hero_html );
	}
}
