<?php
/**
 * Template Name: Home Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Education Zone
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-thumbnail">
					<?php the_post_thumbnail(); ?>
				</div>
				<?php 
				while ( have_posts() ) : the_post(); ?> 
			        <div class="entry-content-page" itemprop="text">
			            <?php the_content(); ?> 
			        </div><!-- .entry-content-page -->
			    <?php endwhile; ?>
			</article><!-- #post-## -->
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php
get_footer(); 