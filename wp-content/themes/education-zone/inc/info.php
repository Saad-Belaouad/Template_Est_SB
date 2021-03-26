<?php
/**
 * Education Zone Theme Info
 *
 * @package education_zone
 */
if ( ! function_exists( 'education_zone_customizer_theme_info' ) ) :

function education_zone_customizer_theme_info( $wp_customize ) {
	
    $wp_customize->add_section( 'theme_info' , array(
		'title'       => __( 'Video Tutorial' , 'education-zone' ),
		'priority'    => 6,
		));

	$wp_customize->add_setting('theme_info_theme',array(
		'default' => '',
		'sanitize_callback' => 'wp_kses_post',
		));
    
    $theme_info = '';
	$theme_info .= '<span class="sticky_title">' . __( 'Check out step-by-step video tutorial to create your website like the demo of Education Zone WordPress theme.', 'education-zone' ) . '</span>';
   
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Video Tutorial', 'education-zone' ) . ': </label><a href="' . esc_url( 'https://docs.rarathemes.com/docs/education-zone/' ) . '" target="_blank">' . __( 'Click here', 'education-zone' ) . '</a></span><br />';
	$theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Theme Demo', 'education-zone' ) . ': </label><a href="' . esc_url( 'https://rarathemes.com/previews/?theme=education-zone' ) . '" target="_blank">' . __( 'Click here', 'education-zone' ) . '</a></span><br />';

	$theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Support Ticket', 'education-zone' ) . ': </label><a href="' . esc_url( 'https://rarathemes.com/support-ticket/' ) . '" target="_blank">' . __( 'Click here', 'education-zone' ) . '</a></span><br />';

	$wp_customize->add_control( new Education_Zone_Theme_Info( $wp_customize ,'theme_info_theme',array(
		'label' => __( 'About Education Zone' , 'education-zone' ),
		'section' => 'theme_info',
		'description' => $theme_info
		)));

	$wp_customize->add_setting('theme_info_more_theme',array(
		'default' => '',
		'sanitize_callback' => 'wp_kses_post',
		));

}
endif;
add_action( 'customize_register', 'education_zone_customizer_theme_info' );

if( class_exists( 'WP_Customize_control' ) ){

	class Education_Zone_Theme_Info extends WP_Customize_Control
	{
    	/**
       	* Render the content on the theme customizer page
       	*/
       	public function render_content()
       	{ ?>
       		<label>
       			<span class="customize-text_editor_desc">
       				<?php echo wp_kses_post( $this->description ); ?>
       			</span>
       		</label>
       		<?php
       	}
    }//editor close
    
}//class close


if( class_exists( 'WP_Customize_Section' ) ) :
/**
 * Adding Go to Pro Section in Customizer
 * https://github.com/justintadlock/trt-customizer-pro
 */
class Education_zone_Customize_Section_Pro extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'pro-section';

	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_text = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_url = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['pro_text'] = $this->pro_text;
		$json['pro_url']  = esc_url( $this->pro_url );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<h3 class="accordion-section-title">
				{{ data.title }}
				<# if ( data.pro_text && data.pro_url ) { #>
					<a href="{{ data.pro_url }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}
endif;

add_action( 'customize_register', 'education_zone_sections_pro' );
function education_zone_sections_pro( $manager ) {
	// Register custom section types.
	$manager->register_section_type( 'Education_Zone_Customize_Section_Pro' );

	// Register sections.
	$manager->add_section(
		new Education_Zone_Customize_Section_Pro(
			$manager,
			'education_zone_view_pro',
			array(
				'title'    => esc_html__( 'Pro Available', 'education-zone' ),
                'priority' => 5, 
				'pro_text' => esc_html__( 'VIEW PRO THEME', 'education-zone' ),
				'pro_url'  => 'https://rarathemes.com/wordpress-themes/education-zone-pro/'
			)
		)
	);
}

