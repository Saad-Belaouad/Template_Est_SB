<?php 
/**
 * 
 * Testimonial Section
*/

$section_title   = get_theme_mod( 'education_zone_testimonials_section_title' );
$testimonial_cat = get_theme_mod( 'education_zone_testimonial_category' ); ?>
 
<div class="image-wrapper">
	<div class="container">
		
        <?php education_zone_get_section_header( $section_title );
        
        
		if( $testimonial_cat ){
            $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'post',
                        'tax_query' => array(
    										array(
    										'taxonomy' => 'post_format',
    										'field'    => 'slug',
    										'terms'    => array( 'post-format-gallery' ),
    										'operator' => 'NOT IN',
    										)),
                        'cat' => $testimonial_cat,
                    );		  
		  
            $qry = new WP_Query( $args );

            if( $qry->have_posts() ){ ?>
                    <ul class="testimonial-slide owl-carousel">
                    <?php 
                        while( $qry->have_posts() ){ $qry->the_post(); ?>
                        <li>
                            <blockquote>
                            <?php the_content(); ?>
                                <cite>
                                    <div class="text">
                                        <?php if(has_post_thumbnail()) the_post_thumbnail( 'education-zone-testimonial' ); ?>
                                        <span><?php the_title(); ?></span>
                                    </div>
                                </cite>
                            </blockquote>
                        </li>
                        <?php 
                        }
                        wp_reset_postdata();
                    ?>
                    </ul>
            <?php 
            } 
        } 
        ?>        
    </div>
</div>
