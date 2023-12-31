<?php

/**
 * Magic Elementor functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Magic Elementor
 */


if (!defined('MAGIC_ELEMENTOR_VERSION')) {
	$magic_elementor_theme = wp_get_theme();
	define('MAGIC_ELEMENTOR_VERSION', $magic_elementor_theme->get('Version'));
}

if (!function_exists('magic_elementor_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function magic_elementor_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Magic Elementor, use a find and replace
		 * to change 'magic-elementor' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('magic-elementor', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'main-menu' => esc_html__('Main Menu', 'magic-elementor'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'magic_elementor_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');
		// Add support for Block Styles.
		add_theme_support('wp-block-styles');

		// Add support for full and wide align images.
		add_theme_support('align-wide');
		add_theme_support("responsive-embeds");

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		add_editor_style(array(magic_elementor_fonts_url()));

		$magicelementor_install_date = get_option('magicelementor_install_date');
		if (empty($magicelementor_install_date)) {
			update_option('magicelementor_install_date', current_time('mysql'));
		}
	}
endif;
add_action('after_setup_theme', 'magic_elementor_setup');



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function magic_elementor_content_width()
{
	$GLOBALS['content_width'] = apply_filters('magic_elementor_content_width', 1170);
}
add_action('after_setup_theme', 'magic_elementor_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function magic_elementor_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'magic-elementor'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'magic-elementor'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'magic_elementor_widgets_init');

/**
 * Register custom fonts.
 */
function magic_elementor_fonts_url()
{
	$fonts_url = '';

	$font_families = array();

	$font_families[] = 'Poppins:400,400i,700,700i';
	$font_families[] = 'Roboto Condensed:400,400i,700,700i';

	$query_args = array(
		'family' => urlencode(implode('|', $font_families)),
		'subset' => urlencode('latin,latin-ext'),
	);

	$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');


	return esc_url_raw($fonts_url);
}


/**
 * Enqueue scripts and styles.
 */
function magic_elementor_scripts()
{
	wp_enqueue_style('magic-elementor-google-font', magic_elementor_fonts_url(), array(), null);
	wp_enqueue_style('magic-elementor-block-style', get_template_directory_uri() . '/assets/css/block.css', array(), MAGIC_ELEMENTOR_VERSION);
	wp_enqueue_style('magic-elementor-default-style', get_template_directory_uri() . '/assets/css/default-style.css', array(), MAGIC_ELEMENTOR_VERSION);
	wp_enqueue_style('magic-elementor-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), MAGIC_ELEMENTOR_VERSION);
	wp_enqueue_style('magic-elementor-style', get_stylesheet_uri(), array(), MAGIC_ELEMENTOR_VERSION);
	wp_enqueue_style('magic-elementor-responsive-style', get_template_directory_uri() . '/assets/css/responsive.css', array(), MAGIC_ELEMENTOR_VERSION);

	wp_enqueue_script('magic-elementor-mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array(), MAGIC_ELEMENTOR_VERSION, true);
	wp_enqueue_script('magic-elementor-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), MAGIC_ELEMENTOR_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'magic_elementor_scripts');

function magic_elementor_gb_block_style()
{

	wp_enqueue_style('magic-elementor-gb-block', get_theme_file_uri('/assets/css/admin-block.css'), false, '1.0', 'all');
	wp_enqueue_style('magic-elementor-admin-google-font', magic_elementor_fonts_url(), array(), null);
}
add_action('enqueue_block_assets', 'magic_elementor_gb_block_style');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';


/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Load all actions file
require get_template_directory() . '/actions/header-actions.php';

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */

add_filter('excerpt_more', '__return_empty_string', 999);
function newsx_paper_excerpt_length($length)
{
	return 30;
}
add_filter('excerpt_length', 'newsx_paper_excerpt_length', 999);



/**
 * Theme info
 */
require get_template_directory() . '/inc/theme-info.php';

/**
 * Add tem plugin activation
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/recomended-plugin.php';
