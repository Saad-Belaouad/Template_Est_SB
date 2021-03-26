<?php
/**
 * Education Zone woocommerce hooks and functions.
 *
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 *
 * @package education_zone
 */

/**
 * Woocommerce related hooks
*/
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content','woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content','woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar',10 );

add_action( 'woocommerce_before_main_content','education_zone_wc_wrapper',10 );
add_action( 'woocommerce_after_main_content','education_zone_wc_wrapper_end',10 );
add_action( 'after_setup_theme','education_zone_woocommerce_support' );
add_action( 'education_zone_wo_sidebar','education_zone_wc_sidebar_cb' );
add_action( 'widgets_init','education_zone_wc_widgets_init' );
add_filter( 'woocommerce_show_page_title','__return_false' );

/**
 * Declare Woocommerce Support
*/
function education_zone_woocommerce_support() {
    global $woocommerce;
    
    add_theme_support( 'woocommerce' );

    if( version_compare( $woocommerce->version, '3.0', ">=" ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}

/**
 * Woocommerce Sidebar
*/
function education_zone_wc_widgets_init(){
    register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'education-zone' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Sidebar displaying only in woocommerce pages.', 'education-zone' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );    
}

/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
*/
function education_zone_wc_wrapper(){    
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}

/**
 * After Content
 * Closes the wrapping divs
*/
function education_zone_wc_wrapper_end(){
    ?>
        </main>
    </div>
    <?php
    if( is_active_sidebar( 'shop-sidebar' ) ) do_action( 'education_zone_wo_sidebar' );
}

/**
 * Callback function for Shop sidebar
*/
function education_zone_wc_sidebar_cb(){
    if( is_active_sidebar( 'shop-sidebar' ) ){
        echo '<div class="sidebar"><aside id="secondary" class="widget-area" role="complementary">';
        dynamic_sidebar( 'shop-sidebar' );
        echo '</aside></div>'; 
    }
}