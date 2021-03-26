<?php
/**
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Business One Page
 */

$enabled_sections = education_zone_get_sections();   
        
if ( 'posts' == get_option( 'show_on_front' ) ){
    include( get_home_template() );
}elseif( $enabled_sections ){ 
    get_header();
    foreach( $enabled_sections as $section ){ ?>    
        <section class="<?php echo esc_attr( $section['class'] ); ?>" id="<?php echo esc_attr( $section['id'] ); ?>">
            <?php get_template_part( 'sections/section', $section['id'] ); ?>
        </section>
        <?php
    }
    get_footer();
}else{
    include( get_page_template() );
}