( function( api ) {

    // Extends our custom "example-1" section.
    api.sectionConstructor['pro-section'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );

jQuery(document).ready(function($) {
    
    // Scroll to Home section starts
    $('body').on('click', '#sub-accordion-panel-education_zone_home_page_settings .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        scrollToSection( section_id );
    });

    function scrollToSection( section_id ){
        var preview_section_id = "slider";

        var $contents = jQuery('#customize-preview iframe').contents();

        switch ( section_id ) {

            case 'accordion-section-education_zone_banner_settings':
            preview_section_id = "slider";
            break;

            case 'accordion-section-education_zone_information_settings':
            preview_section_id = "info";
            break;

            case 'accordion-section-education_zone_welcome_section_settings':
            preview_section_id = "welcome";
            break;

            case 'accordion-section-education_zone_featured_courses_section_settings':
            preview_section_id = "courses";
            break;

            case 'accordion-section-education_zone_extra_info_section_settings':
            preview_section_id = "extra_info";
            break;

            case 'accordion-section-education_zone_choose_us_section_settings':
            preview_section_id = "choose";
            break;

            case 'accordion-section-education_zone_testimonials_section_settings':
            preview_section_id = "testimonials";
            break;

            case 'accordion-section-education_zone_blog_section_settings':
            preview_section_id = "blog";
            break;

            case 'accordion-section-education_zone_gallery_section_settings':
            preview_section_id = "gallery";
            break;

            case 'accordion-section-education_zone_search_section_settings':
            preview_section_id = "search";
            break;
        
        }

        if( $contents.find('#'+preview_section_id).length > 0 && $contents.find('.home').length > 0 ){
            $contents.find("html, body").animate({
            scrollTop: $contents.find( "#" + preview_section_id ).offset().top
            }, 1000);
        }
	}

});