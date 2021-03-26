<?php
/**
 * Education Zone functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Education_Zone
 */

$education_zone_theme_data = wp_get_theme();
if( ! defined( 'EDUCATION_ZONE_THEME_VERSION' ) ) define( 'EDUCATION_ZONE_THEME_VERSION', $education_zone_theme_data->get( 'Version' ) );
if( ! defined( 'EDUCATION_ZONE_THEME_NAME' ) ) define( 'EDUCATION_ZONE_THEME_NAME', $education_zone_theme_data->get( 'Name' ) );
if( ! defined( 'EDUCATION_ZONE_THEME_TEXTDOMAIN' ) ) define( 'EDUCATION_ZONE_THEME_TEXTDOMAIN', $education_zone_theme_data->get( 'TextDomain' ) );

if ( ! function_exists( 'education_zone_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function education_zone_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Education Zone, use a find and replace
	 * to change 'education-zone' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'education-zone', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary', 'education-zone' ),
		'secondary' => esc_html__( 'Secondary', 'education-zone' ),		
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
	) );

	add_theme_support( 'post-formats', array( 'image', 'link', 'aside', 'status' ) );



	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'education_zone_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
    
    /* Custom Logo */
    add_theme_support( 'custom-logo', array(    	
    	'header-text' => array( 'site-title', 'site-description' ),
    ) );
    
    //Custom Image Sizes
    add_image_size( 'education-zone-banner', 1920, 692, true);
    add_image_size( 'education-zone-image-full', 1140, 458, true);
    add_image_size( 'education-zone-image', 750, 458, true);
    add_image_size( 'education-zone-recent-post', 70, 70, true);
    add_image_size( 'education-zone-search-result', 246, 246, true);
    add_image_size( 'education-zone-featured-course', 276, 276, true);
    add_image_size( 'education-zone-testimonial', 125, 125, true);
    add_image_size( 'education-zone-blog-full', 848, 480, true);
    add_image_size( 'education-zone-schema', 600, 60, true);
    
}
endif;
add_action( 'after_setup_theme', 'education_zone_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function education_zone_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'education_zone_content_width', 750 );
}
add_action( 'after_setup_theme', 'education_zone_content_width', 0 );

/**
* Adjust content_width value according to template.
*
* @return void
*/
function education_zone_template_redirect_content_width() {

	// Full Width in the absence of sidebar.
	if( is_page() ){
	   $sidebar_layout = education_zone_sidebar_layout_class();
       if( ( $sidebar_layout == 'no-sidebar' ) || ! ( is_active_sidebar( 'right-sidebar' ) ) ) $GLOBALS['content_width'] = 1140;
        
	}elseif ( ! ( is_active_sidebar( 'right-sidebar' ) ) ) {
		$GLOBALS['content_width'] = 1140;
	}

}
add_action( 'template_redirect', 'education_zone_template_redirect_content_width' );

/**
 * Query WooCommerce activation
 */
function education_zone_is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? true : false;
}
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function education_zone_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'education-zone' ),
		'id'            => 'right-sidebar',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    register_sidebar( array(
		'name'          => esc_html__( 'Footer One', 'education-zone' ),
		'id'            => 'footer-one',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    register_sidebar( array(
		'name'          => esc_html__( 'Footer Two', 'education-zone' ),
		'id'            => 'footer-two',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
		
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Three', 'education-zone' ),
		'id'            => 'footer-three',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
}
add_action( 'widgets_init', 'education_zone_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function education_zone_scripts() {
	
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css' );
    wp_enqueue_style( 'owl-theme-default', get_template_directory_uri(). '/css' . $build . '/owl.theme.default' . $suffix . '.css' );
    wp_enqueue_style( 'education-zone-google-fonts', education_zone_fonts_url() );
    wp_enqueue_style( 'education-zone-style', get_stylesheet_uri(), array(), EDUCATION_ZONE_THEME_VERSION );
    
    if( education_zone_is_woocommerce_activated() )
    wp_enqueue_style( 'education-zone-woocommerce-style', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', EDUCATION_ZONE_THEME_VERSION );
  
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array('jquery'), '2.2.1', true );
    wp_enqueue_script( 'owlcarousel2-a11ylayer', get_template_directory_uri() . '/js' . $build . '/owlcarousel2-a11ylayer' . $suffix . '.js', array('owl-carousel'), '0.2.1', true );
	wp_enqueue_script( 'waypoint', get_template_directory_uri() . '/js' . $build . '/waypoint' . $suffix . '.js', array('jquery'), '2.0.3', true );
	wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/js' . $build . '/jquery.counterup' . $suffix . '.js', array('jquery', 'waypoint'), '1.0', true );
	wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
	
	wp_register_script( 'education-zone-custom', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array('jquery'), EDUCATION_ZONE_THEME_VERSION, true );
    
	$custom_array = array('rtl' => is_rtl(), );
	
	wp_localize_script( 'education-zone-custom', 'education_zone_data', $custom_array );
	wp_enqueue_script( 'education-zone-custom' );
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'education_zone_scripts' );

if( ! function_exists( 'education_zone_customize_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function education_zone_customize_scripts() {
	wp_enqueue_style( 'education-zone-customize-style',get_template_directory_uri().'/inc/css/customize.css', '', EDUCATION_ZONE_THEME_VERSION );
	wp_enqueue_script( 'education-zone-customize-js', get_template_directory_uri().'/inc/js/customize.js', array( 'jquery' ), EDUCATION_ZONE_THEME_VERSION, true );
}
endif;
add_action( 'customize_controls_enqueue_scripts', 'education_zone_customize_scripts' );

if( ! function_exists( 'education_zone_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function education_zone_admin_scripts(){
    wp_enqueue_style( 'education-zone-admin', get_template_directory_uri() . '/inc/css/admin.css', '', EDUCATION_ZONE_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'education_zone_admin_scripts' );

if( ! function_exists( 'education_zone_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function education_zone_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'education_zone_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'education-zone' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'education-zone' ), esc_html( $name ) ); ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=education-zone-getting-started' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to the getting started.', 'education-zone' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?education_zone_admin_notice=1"><?php esc_html_e( 'Dismiss', 'education-zone' ); ?></a></strong></p>
                </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'education_zone_admin_notice' );

if( ! function_exists( 'education_zone_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function education_zone_update_admin_notice(){
    if ( isset( $_GET['education_zone_admin_notice'] ) && $_GET['education_zone_admin_notice'] = '1' ) {
        update_option( 'education_zone_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'education_zone_update_admin_notice' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Custom functions for meta box.
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Recent Post Widget.
*/ 
require get_template_directory() . '/inc/widget-recent-post.php';

/**
 * Popular Post Widget.
*/ 
require get_template_directory() . '/inc/widget-popular-post.php';

/**
 * Social Links Widget.
*/ 
require get_template_directory() . '/inc/widget-social-links.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/getting-started/getting-started.php';

/**
 * Info Section
 */
require get_template_directory() . '/inc/info.php';

/**
 * WooCommerce Related funcitons
*/
if( education_zone_is_woocommerce_activated() )
require get_template_directory() . '/inc/woocommerce-functions.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';