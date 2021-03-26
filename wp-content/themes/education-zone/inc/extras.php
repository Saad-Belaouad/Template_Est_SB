<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Education_Zone
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
if ( ! function_exists( 'education_zone_body_classes' ) ) :

function education_zone_body_classes( $classes ) {
	global $post;

    $page_class  = education_zone_sidebar_layout_class();
    $ed_banner   = get_theme_mod( 'education_zone_ed_slider_section' );

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    if( ! $ed_banner ){
        $classes[] = 'no-banner';
    }
	
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    if( !is_active_sidebar( 'right-sidebar' ) || is_page_template( 'template-home.php' ) || $page_class == 'no-sidebar' ){
        $classes[] = 'full-width';
    }

    if( education_zone_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || 'product' === get_post_type() ) && ! is_active_sidebar( 'shop-sidebar' ) ){
        $classes[] = 'full-width';
    }
 
	return $classes;
}
endif;
add_filter( 'body_class', 'education_zone_body_classes' );

if( ! function_exists( 'education_zone_header' ) ):
/**
 * Page Header 
*/
function education_zone_header(){
    if( ! ( is_front_page() || is_page_template('template-home.php') ) ){
    ?>
    <div class="page-header">
		<div class="container">
	        
            <?php
            /** For Woocommerce */
            if( education_zone_is_woocommerce_activated() && ( is_product_category() || is_product_tag() || is_shop() ) ){
                if( is_shop() ){
                    if( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                        return;
                    }

                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                    if( ! $_name ){
                        $product_post_type = get_post_type_object( 'product' );
                        $_name = $product_post_type->labels->singular_name; ?>
                        <h1 class="page-title"><?php echo esc_html( $_name ); ?></h1>
                    <?php 
                    } 

                }elseif( is_product_category() || is_product_tag() ){
                    $current_term = $GLOBALS['wp_query']->get_queried_object(); ?>
                    <h1 class="page-title"><?php echo esc_html( $current_term->name ); ?> </h1>
                <?php 
                } 
            }else{
                if( is_archive() ){ ?> 
                    <h1 class="page-title"> <?php the_archive_title(); ?> </h1>
                <?php 
                }
            } 
            
            if( is_search() ){ 
                global $wp_query;    
                ?>
                <h1 class="page-title"><?php printf( esc_html__( '%1$s Result for "%2$s"', 'education-zone' ), number_format_i18n( $wp_query->found_posts ), get_search_query() ); ?></h1>        		
                <?php                
            }
            
            if( is_home() ){ ?>
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            <?php 
            }
            
            if( is_page() ){
                the_title( '<h1 class="page-title">', '</h1>' );
            }
            
            if( is_404() ){ ?>
                <h1 class="page-title"><?php echo esc_html__( '404 Error - Page not Found', 'education-zone' ); ?></h1>
            <?php                
            }
       	
           do_action( 'education_zone_breadcrumbs' ); ?>
        
		</div>
	</div>
<?php
    }
}
endif;
add_action( 'education_zone_page_header', 'education_zone_header' );
        
if( !function_exists( 'education_zone_breadcrumbs_cb' ) ):
/**
 * Breadcrumb
*/
function education_zone_breadcrumbs_cb() {    
    global $post;
    
    $post_page   = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front  = get_option( 'show_on_front' ); //What to show on the front page
    $showCurrent = get_theme_mod( 'education_zone_ed_current', '1' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $delimiter   = get_theme_mod( 'education_zone_breadcrumb_separator', __( '>', 'education-zone' ) ); // delimiter between crumbs
    $home        = get_theme_mod( 'education_zone_breadcrumb_home_text', __( 'Home', 'education-zone' ) ); // text for the 'Home' link
    $before      = '<span class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
      
    $depth = 1;    
    if( get_theme_mod( 'education_zone_ed_breadcrumb' ) ){
        echo '<div id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList"><span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( home_url() ) . '" class="home_crumb"><span itemprop="name">' . esc_html( $home ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
            if( is_home() && ! is_front_page() ){            
                $depth = 2;
                if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( single_post_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;          
            }elseif( is_category() ){            
                $depth = 2;
                $thisCat = get_category( get_query_var( 'cat' ), false );
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $post_page ) ) . '"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                    $depth ++;  
                }

                if ( $thisCat->parent != 0 ) {
                    $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                    $parent_categories = explode( ',', $parent_categories );

                    foreach ( $parent_categories as $parent_term ) {
                        $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                        if( is_object( $parent_obj ) ){
                            $term_url    = get_term_link( $parent_obj->term_id );
                            $term_name   = $parent_obj->name;
                            echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth ++;
                        }
                    }
                }

                if( $showCurrent ) echo $before . '<span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;

            }elseif( is_tag() ){            
                $queried_object = get_queried_object();
                $depth = 2;

                if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( single_tag_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;    
            }elseif( is_author() ){            
                $depth = 2;
                global $author;
                $userdata = get_userdata( $author );
                if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( $userdata->display_name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;  
            }elseif( is_day() ){            
                $depth = 2;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'education-zone' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'education-zone' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'education-zone' ) ), get_the_time( __( 'm', 'education-zone' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'education-zone' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;
                if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'd', 'education-zone' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                 
            }elseif( is_month() ){            
                $depth = 2;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'education-zone' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'education-zone' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth++;
                if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'F', 'education-zone' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
            }elseif( is_year() ){            
                $depth = 2;
                if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'education-zone' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
            }elseif( is_single() && !is_attachment() ) {
                //For Woocommerce single product            
                if( education_zone_is_woocommerce_activated() && 'product' === get_post_type() ){ 
                    if ( wc_get_page_id( 'shop' ) ) { 
                        //Displaying Shop link in woocommerce archive page
                        $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                        if ( ! $_name ) {
                            $product_post_type = get_post_type_object( 'product' );
                            $_name = $product_post_type->labels->singular_name;
                        }
                        echo ' <a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name) . '</span></a> ' . '<span class="separator">' . $delimiter . '</span>';
                    }
                
                    if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                        $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );

                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );    
                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                                $depth++;
                            }
                        }
                        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                    }
                
                    if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                                   
                }else{ 
                    //For Post                
                    $cat_object       = get_the_category();
                    $potential_parent = 0;
                    $depth            = 2;
                    
                    if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                        $p = get_post( $post_page );
                        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';  
                        $depth++;
                    }
                    
                    if( is_array( $cat_object ) ){ //Getting category hierarchy if any
            
                        //Now try to find the deepest term of those that we know of
                        $use_term = key( $cat_object );
                        foreach( $cat_object as $key => $object ){
                            //Can't use the next($cat_object) trick since order is unknown
                            if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                                $use_term = $key;
                                $potential_parent = $object->term_id;
                            }
                        }
                        
                        $cat = $cat_object[$use_term];
                  
                        $cats = get_category_parents( $cat, false, ',' );
                        $cats = explode( ',', $cats );

                        foreach ( $cats as $cat ) {
                            $cat_obj = get_term_by( 'name', $cat, 'category' );
                            if( is_object( $cat_obj ) ){
                                $term_url    = get_term_link( $cat_obj->term_id );
                                $term_name   = $cat_obj->name;
                                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                                $depth ++;
                            }
                        }
                    }
        
                    if ( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                                 
                }        
            }elseif( is_page() ){            
                $depth = 2;
                if( $post->post_parent ){            
                    global $post;
                    $depth = 2;
                    $parent_id  = $post->post_parent;
                    $breadcrumbs = array();
                    
                    while( $parent_id ){
                        $current_page  = get_post( $parent_id );
                        $breadcrumbs[] = $current_page->ID;
                        $parent_id     = $current_page->post_parent;
                    }
                    $breadcrumbs = array_reverse( $breadcrumbs );
                    for ( $i = 0; $i < count( $breadcrumbs); $i++ ){
                        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $breadcrumbs[$i] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[$i] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>';
                        if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                        $depth++;
                    }

                    if ( $showCurrent ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" /></span>'. $after;      
                }else{
                    if ( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
                }
            }elseif( is_search() ){            
                $depth = 2;
                if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html__( 'Search Results for "', 'education-zone' ) . esc_html( get_search_query() ) . esc_html__( '"', 'education-zone' ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
            }elseif( education_zone_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ 
                //For Woocommerce archive page        
                $depth = 2;
                if ( wc_get_page_id( 'shop' ) ) { 
                    //Displaying Shop link in woocommerce archive page
                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                    if ( ! $_name ) {
                        $product_post_type = get_post_type_object( 'product' );
                        $_name = $product_post_type->labels->singular_name;
                    }
                    echo ' <a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name) . '</span></a> ' . '<span class="separator">' . $delimiter . '</span>';
                }
                $current_term = $GLOBALS['wp_query']->get_queried_object();
                if( is_product_category() ){
                    $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth ++;
                        }
                    }
                }           
                if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( $current_term->name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;           
            }elseif( education_zone_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
                $depth = 2;
                if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                    return;
                }
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
        
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( $_name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;                    
            }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {            
                $depth = 2;
                $post_type = get_post_type_object(get_post_type());
                if( get_query_var('paged') ){
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />';
                    echo ' <span class="separator">' . $delimiter . '</span></span> ' . $before . sprintf( __('Page %s', 'education-zone'), get_query_var('paged') ) . $after;
                }elseif( is_archive() ){
                    echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                }else{
                    echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                }              
            }elseif( is_attachment() ){            
                $depth  = 2;
                $parent = get_post( $post->post_parent );
                $cat    = get_the_category( $parent->ID );
                if( $cat ){
                    $cat = $cat[0];
                    echo get_category_parents( $cat, TRUE, ' <span class="separator">' . $delimiter . '</span> ');
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $parent ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $parent->post_title ) . '<span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . ' <span class="separator">' . $delimiter . '</span></span>';
                }
                if( $showCurrent ) echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;   
            }elseif ( is_404() ){
                if( $showCurrent ) echo $before . esc_html__( '404 Error - Page not Found', 'education-zone' ) . $after;
            }
            if( get_query_var('paged') ) echo __( ' (Page', 'education-zone' ) . ' ' . get_query_var('paged') . __( ')', 'education-zone' );        
            echo '</div>';
    }
} // end education_zone_breadcrumbs()
add_action( 'education_zone_breadcrumbs', 'education_zone_breadcrumbs_cb' );

endif;

/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
 
 function education_zone_theme_comment($comment, $args, $depth){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body" itemscope itemtype="https://schema.org/UserComments">
	<?php endif; ?>
	
    <footer class="comment-meta">
    
        <div class="comment-author vcard">
    	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    	<?php printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person">%s</b>', 'education-zone' ), get_comment_author_link() ); ?>
    	</div>
    	<?php if ( $comment->comment_approved == '0' ) : ?>
    		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'education-zone' ); ?></em>
    		<br />
    	<?php endif; ?>
    
    	<div class="comment-metadata commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_date(); ?>">
    		<?php
    			
    			echo esc_html( get_comment_date() ); ?></time></a><?php edit_comment_link( __( '(Edit)', 'education-zone' ), '  ', '' );
    		?>
    	</div>
    </footer>
    
    <div class="comment-content"><?php comment_text(); ?></div>

	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; 
}

if( ! function_exists( 'education_zone_social_cb' ) ):
/**
 * Callback for Social Links
*/
function education_zone_social_cb(){
    
    $facebook  = get_theme_mod( 'education_zone_facebook' );
    $twitter   = get_theme_mod( 'education_zone_twitter' );
    $pinterest = get_theme_mod( 'education_zone_pinterest' );
    $linkedin  = get_theme_mod( 'education_zone_linkedin' );
    $gplus     = get_theme_mod( 'education_zone_gplus' );
    $instagram = get_theme_mod( 'education_zone_instagram' );
    $youtube   = get_theme_mod( 'education_zone_youtube' );
    $ok        = get_theme_mod( 'education_zone_ok' );
    $vk        = get_theme_mod( 'education_zone_vk' );
    $xing      = get_theme_mod( 'education_zone_xing' );
    
    if( $facebook || $twitter || $pinterest || $linkedin || $gplus || $instagram || $youtube || $ok || $vk || $xing ){
    
    ?>
	<ul class="social-networks">
		<?php if( $facebook ){ ?>
        <li><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" title="<?php esc_attr_e( 'Facebook', 'education-zone' );?>"><i class="fa fa-facebook-square"></i></a></li>
		<?php } if( $twitter ){ ?>
        <li><a href="<?php echo esc_url( $twitter ); ?>" target="_blank" title="<?php esc_attr_e( 'Twitter', 'education-zone' );?>"><i class="fa fa-twitter-square"></i></a></li>
        <?php } if( $pinterest ){ ?>
        <li><a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" title="<?php esc_attr_e( 'Pinterest', 'education-zone' );?>"><i class="fa fa-pinterest-square"></i></a></li>
		<?php } if( $linkedin ){ ?>
        <li><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" title="<?php esc_attr_e( 'LinkedIn', 'education-zone' );?>"><i class="fa fa-linkedin-square"></i></a></li>
        <?php } if( $gplus ){ ?>
        <li><a href="<?php echo esc_url( $gplus ); ?>" target="_blank" title="<?php esc_attr_e( 'Google Plus', 'education-zone' );?>"><i class="fa fa-google-plus-square"></i></a></li>
        <?php } if( $instagram ){ ?>
        <li><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" title="<?php esc_attr_e( 'Instagram', 'education-zone' );?>"><i class="fa fa-instagram"></i></a></li>
		<?php } if( $youtube ){ ?>
        <li><a href="<?php echo esc_url( $youtube ); ?>" target="_blank" title="<?php esc_attr_e( 'YouTube', 'education-zone' );?>"><i class="fa fa-youtube-square"></i></a></li>
        <?php } if( $ok ){ ?>
        <li><a href="<?php echo esc_url( $ok ); ?>" target="_blank" title="<?php esc_attr_e( 'OK', 'education-zone' );?>"><i class="fa fa-odnoklassniki"></i></a></li>
        <?php } if( $vk ){ ?>
        <li><a href="<?php echo esc_url( $vk ); ?>" target="_blank" title="<?php esc_attr_e( 'VK', 'education-zone' );?>"><i class="fa fa-vk"></i></a></li>
        <?php } if( $xing ){ ?>
        <li><a href="<?php echo esc_url( $xing ); ?>" target="_blank" title="<?php esc_attr_e( 'Xing', 'education-zone' );?>"><i class="fa fa-xing"></i></a></li>
        <?php } ?>
	</ul>
    <?php
    }    
}
endif;
add_action( 'education_zone_social', 'education_zone_social_cb' );

if( ! function_exists( 'education_zone_mobile_header' ) ):
/**
 * Callback for Mobile Header
*/   
function education_zone_mobile_header(){
    $phone      = get_theme_mod( 'education_zone_phone' );
    $email      = get_theme_mod( 'education_zone_email' );
    ?>
    <div class="mobile-header">
            <div class="container">
                <div class="menu-opener">
                    <span></span>
                    <span></span>
                    <span></span>
                </div> <!-- menu-opener ends -->

                <div class="site-branding">
                    <?php 
                        if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                            echo '<div class="img-logo">';
                            the_custom_logo();
                            echo '</div><!-- .img-logo -->';
                        } 
                    ?>
                    <div class="text-logo">
                    <?php
                        $site_title =  get_bloginfo( 'name', 'display' );
                        $description = get_bloginfo( 'description', 'display' );

                        if( $site_title ) : ?>
                            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>
                        <?php
                        endif;
                    
                       if ( $description ) : ?>
                           <p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
                        <?php
                       endif; 
                    ?>
                    </div>
                </div> <!-- site-branding ends -->
            </div> <!-- container ends -->

            <div class="mobile-menu">
                <?php get_search_form(); ?>

                <nav class="main-navigation" role="navigation">
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'mobile-primary-menu' ) ); ?>
                </nav><!-- #site-navigation -->
                <?php 
                    if( has_nav_menu( 'secondary' ) ){ ?>
                        <nav class="secondary-nav" role="navigation"> 
                            <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'mobile-secondary-menu', 'fallback_cb' => false ) ); ?>
                        </nav><!-- #site-navigation -->
                    <?php 
                    }

                    if( $email || $phone ){ ?>
                       <div class="contact-info">
                        <?php 
                            if( $phone ) echo '<a href="' . esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) ) . '" class="tel-link">'. esc_html( $phone ) .'</a>';
                            if( $email ) echo '<a href="' . esc_url( 'mailto:'. sanitize_email( $email ) ) .'" class="email-link">'. esc_html( $email ) .'</a>';
                        ?>
                        </div> <!-- contact-info ends -->
                    <?php 
                    }
                ?>
            </div>
        </div> <!-- mobile-header ends -->
    <?php
}
endif;

if( ! function_exists( 'education_zone_site_header' ) ):
/**
 * Callback for Site Header
*/   
function education_zone_site_header(){
    $phone      = get_theme_mod( 'education_zone_phone' );
    $email      = get_theme_mod( 'education_zone_email' );
    $menu_label = get_theme_mod('education_zone_top_menu_label', __('Quick Links','education-zone') );
    ?>
    <header id="masthead" class="site-header" role="banner" itemscope itemtype="https://schema.org/WPHeader">
            <?php 
            if( $phone || $email || has_nav_menu( 'secondary' ) ) { ?>
                <div class="header-top">
                  <div class="container">
                    <?php 
                    if( $email || $phone ){ ?>
                        <div class="top-links">
                            <?php 
                            if( $email ){ ?>
                                <span><i class="fa fa-envelope-o"></i><a href="<?php echo esc_url( 'mailto:'. sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a>
                                </span>
                            <?php 
                            } 
                            if( $phone ){ ?>
                                <span><i class="fa fa-phone"></i><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </span>
                            <?php 
                            } ?>
                        </div>
                    <?php 
                    }
                    if( has_nav_menu( 'secondary' ) ){ ?>
                        <nav id="secondary-navigation" class="secondary-nav" role="navigation">     
                            <a href="javascript:void(0);"><?php echo esc_html( $menu_label ); ?></a>                
                            <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'secondary-menu', 'fallback_cb' => false ) ); ?>
                        </nav><!-- #site-navigation -->
                    <?php 
                    } ?>
                </div>
            </div>
            <?php 
            } ?>
        
            <div class="header-m">
                <div class="container">
                    <div class="site-branding" itemscope itemtype="https://schema.org/Organization">
                        <?php 
                            if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                                the_custom_logo();
                            } 
                        ?>
                        <?php if ( is_front_page() ) : ?>
                            <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php else : ?>
                            <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                        <?php endif;
                           $description = get_bloginfo( 'description', 'display' );
                           if ( $description || is_customize_preview() ) : ?>
                               <p class="site-description" itemprop="description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
                        <?php
                           endif; 
                        ?>                    
                   </div><!-- .site-branding -->
                   
                    <div class="form-section">
                        <div class="example">                       
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
           <div class="header-bottom">
                <div class="container">
                    <nav id="site-navigation" class="main-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">                        
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
                    </nav><!-- #site-navigation -->
                </div>
            </div>

        </header><!-- #masthead -->
    <?php
}
endif;
/**
 * Get home page sections 
*/ 
function education_zone_get_sections(){

    $sections = array(
            'slider-section' => array(
               'id' => 'slider',
               'class' => 'banner'
                ),
            'info-section' => array(
               'id' => 'info',
               'class' => 'information'
                ),
             'welcome-section' => array(
              'id' => 'welcome',
              'class' => 'welcome-note'
              ),
             'courses-section' => array(
              'id' => 'courses',
              'class' => 'featured-courses'
              ),
             'extra-info-section' => array(
              'id' => 'extra_info',
              'class' => 'theme'
              ),
            'choose-section' => array(
              'id' => 'choose',
              'class' => 'choose-us'
              ),
            'testimonial-section' => array(
              'id' => 'testimonials',
              'class' => 'student-stories'
              ),
            'blog-section' => array(
              'id' => 'blog',
              'class' => 'latest-events'
              ),
            'gallery-section'=> array(
              'id' => 'gallery',
              'class' => 'photo-gallery'
              ),
            'search-section' => array(
              'id' => 'search',
              'class' => 'search-section'
              ),
      );
    $enabled_section = array();
    foreach ( $sections as $section ) {
        if ( get_theme_mod( 'education_zone_ed_' . $section['id'] . '_section' ) == 1 ){
            $enabled_section[] = array(
                'id'    => $section['id'],
                'class' => $section['class']
            );
        }
    }
    return $enabled_section;
}

/**
 * Return sidebar layouts for pages
*/
function education_zone_sidebar_layout_class(){
    global $post;
    if( is_page() ){
        $sidebar = get_post_meta( $post->ID, 'education_zone_sidebar_layout', true );
        if( $sidebar ){
            return $sidebar;    
        }else{
            return 'right-sidebar';
        }
    }
}

if ( ! function_exists( 'education_zone_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function education_zone_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}
add_filter( 'excerpt_more', 'education_zone_excerpt_more' );
endif;

if ( ! function_exists( 'education_zone_excerpt_length' ) && ! is_admin() ) :
/**
 * Changes the default 55 character in excerpt 
*/
function education_zone_excerpt_length( $length ) {
	return is_admin() ? $length : 20;
}
add_filter( 'excerpt_length', 'education_zone_excerpt_length', 999 );
endif;

if( ! function_exists( 'education_zone_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function education_zone_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'education-zone' ) : __( 'Name', 'education-zone' ) );
    $email    = ( $req ? __( 'Email*', 'education-zone' ) : __( 'Email', 'education-zone' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'education-zone' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'education-zone' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'education-zone' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'education-zone' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'education_zone_change_comment_form_default_fields' );

if( ! function_exists( 'education_zone_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function education_zone_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'education-zone' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'education-zone' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'education_zone_change_comment_form_defaults' );

if ( ! function_exists( 'education_zone_get_section_header' ) ) :
/**
 * Returns Section header
*/
function education_zone_get_section_header( $section_title ){
    
    $header_query = new WP_Query( array( 'page_id' => $section_title ) );
    
    if( $section_title && $header_query->have_posts() ){ 
        echo '<div class="header-part">';
        while( $header_query->have_posts() ){ 
            $header_query->the_post();
            the_title( '<h2 class="section-title">', '</h2>' );
            the_content();
        }
        echo '</div>';
        wp_reset_postdata();
    }   
}
endif;

if( ! function_exists( 'education_zone_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function education_zone_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'education-zone-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = education_zone_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( education_zone_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "https://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => get_permalink( $post->ID )
            ),
            "headline"  => get_the_title( $post->ID ),
            "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
            "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => education_zone_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">' , PHP_EOL;
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
        } else {
            echo wp_json_encode( $args ) , PHP_EOL;
        }
        echo '</script>' , PHP_EOL;
    }
}
endif;
add_action( 'wp_head', 'education_zone_single_post_schema' );

if( ! function_exists( 'education_zone_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function education_zone_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'education_zone_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function education_zone_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if ( ! function_exists( 'education_zone_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function education_zone_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = education_zone_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

if( ! function_exists( 'education_zone_fonts_url' ) ) :
/**
 * Register custom fonts.
 */
function education_zone_fonts_url() {
    $fonts_url = '';

    /*
    * translators: If there are characters in your language that are not supported
    * by Roboto, translate this to 'off'. Do not translate into your own language.
    */
    $roboto = _x( 'on', 'Roboto font: on or off', 'education-zone' );
    
    /*
    * translators: If there are characters in your language that are not supported
    * by Lato, translate this to 'off'. Do not translate into your own language.
    */
    $lato = _x( 'on', 'Lato font: on or off', 'education-zone' );

    if ( 'off' !== $roboto || 'off' !== $lato ) {
        $font_families = array();

        if( 'off' !== $roboto ){
            $font_families[] = 'Roboto:400,700';
        }

        if( 'off' !== $lato ){
            $font_families[] = 'Lato:400,700,900';
        }

        $query_args = array(
            'family'  => urlencode( implode( '|', $font_families ) ),
            'display' => urlencode( 'fallback' ),
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url( $fonts_url );
}
endif;