<?php 
/**
 * 
 * Search Section
*/

$content = get_theme_mod( 'education_zone_search_section_content', __( 'Can we help you find what you are looking for?', 'education-zone' ) ); 

?>  

<div class="container">
	<div class="row">
	<?php if( $content ){  ?>
		<div class="col-1">
			<?php echo wpautop( wp_kses_post( $content ) ); ?>
		</div>
	<?php } ?>
		<div class="col-1">
			<?php get_search_form(); ?>
		</div>
	</div>
</div>