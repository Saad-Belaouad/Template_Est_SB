<?php
/**
 * Welcome Section
*/  

$section_title = get_theme_mod( 'education_zone_welcome_section_title' );
?>
    
<div class="container">
    <?php 
    education_zone_get_section_header( $section_title );  

    $first_stats_number  = get_theme_mod( 'education_zone_first_stats_number' );
    $first_stats_title   = get_theme_mod( 'education_zone_first_stats_title' );
    $second_stats_number = get_theme_mod( 'education_zone_second_stats_number' );
    $second_stats_title  = get_theme_mod( 'education_zone_second_stats_title' );
    $third_stats_number  = get_theme_mod( 'education_zone_third_stats_number' );
    $third_stats_title   = get_theme_mod( 'education_zone_third_stats_title' );
    $fourth_stats_number = get_theme_mod( 'education_zone_fourth_stats_number' );
    $fourth_stats_title  = get_theme_mod( 'education_zone_fourth_stats_title' );
 
    if( $first_stats_number || $first_stats_title || $second_stats_number || $second_stats_title || $third_stats_number || $third_stats_title || $fourth_stats_number || $fourth_stats_title ){ ?>
    
        <div class="row">
        
            <?php if( $first_stats_number && $first_stats_title ){ ?>
            	<div class="col">
            		<div class="text">
            			<h3 class="number"><?php echo esc_html( $first_stats_number ); ?></h3>
            			<span><?php echo esc_html( $first_stats_title ); ?></span>            
            		</div>
            	</div>
                
            <?php } if( $second_stats_number && $second_stats_title ){ ?>
            	<div class="col">
            		<div class="text">
            			<h3 class="number"><?php echo esc_html( $second_stats_number ); ?></h3>
            			<span><?php echo esc_html( $second_stats_title ); ?></span>            
            		</div>
            	</div>
                
            <?php } if( $third_stats_number && $third_stats_title ){ ?>
            	<div class="col">
            		<div class="text">
            			<h3 class="number"><?php echo esc_html( $third_stats_number ); ?></h3>
            			<span><?php echo esc_html( $third_stats_title ); ?></span>    
            		</div>
            	</div>
                
            <?php }  if( $fourth_stats_number && $fourth_stats_title ){ ?>
            	<div class="col">
            		<div class="text">
            			<h3 class="number"><?php echo esc_html( $fourth_stats_number ); ?></h3>
            			<span><?php echo esc_html( $fourth_stats_title ); ?></span>
            		</div>
            	</div>
                
            <?php } ?>
        
        </div>
    <?php } ?>
</div>