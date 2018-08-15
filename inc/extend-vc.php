<?php
/** 	
 *	Summary. Functions to create WPBakery elements from theme components.
 *	
 * 	Description. Adds mobtown theme components, including audio listings and pricing tables, to WPBakery (Visual Composer).
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
	
// Integrate Mobtown Record List into VC
if ( ! function_exists( 'mob_record_list_vc_integrate' ) ) {

	function mob_record_list_vc_integrate() {
		vc_map( array(
			'name' => __( 'Mobtown Record List', '' ),
			'base' => 'mob_record_list',
			'class' => 'mob-record-list-holder',
			'icon' => 'icon-wpb-blog-slider extended-custom-icon',
			'allowed_container_element' => 'vc_row',
			'category' => __( 'Content', ''),
			'params' => array(
				array(
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Project Type',
					'param_name' => 'project_type',
					'value' => array(
						'' => '',
						'Studio Projects' => 'studio-projects',
						'Microshows' => 'microshows',
						'BSides' => 'bsides'
					),
					'description' => 'Types of projects to include (use slugs)'
				),
				array(
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Number of Posts',
					'param_name' => 'number_of_posts',
					'description' => 'Enter -1 for all',
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Number of Columns',
					'param_name' => 'number_of_columns',
					'value' => array(
						'' => '',
						'One' => '1',
						'Two' => '2',
						'Three' => '3',
						'Four' => '4'
					),
					'save_always' => true,
					'description' => 'Defaults to 3',
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Size of Cover Art',
					'param_name' => 'image_size',
					'value' => array(
						'' => '',
						'Small' => 'mob-small-record-cover-thumb',
						'Medium' => 'mob-record-cover-thumb',
					),
					'save_always' => true,
					'description' => 'Defaults to medium',
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Filtering',
					'param_name' => 'show_filtering',
					'value' => array(
						'' => '',
						'Yes' => true,
						'No' => false,
					),
					'save_always' => true,
					'description' => 'Defaults to no',
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Show More',
					'param_name' => 'show_more',
					'value' => array(
						'' => '',
						'Yes' => true,
						'No' => false,
					),
					'save_always' => true,
					'description' => 'Defaults to no',
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Order By',
					'param_name' => 'order_by',
					'value' => array(
						'' => '',
						'Title' => 'title',
						'Date' => 'date'
					),
					'description' => 'Defaults to random'
				),
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Order',
					'param_name' => 'order',
					'value' => array(
						'' => '',
						'ASC' => 'ASC',
						'DESC' => 'DESC',
					),
					'description' => ''
				),
			)
		));
	}
	
	add_action( 'vc_before_init', 'mob_record_list_vc_integrate' );
	
}

// Integrate Mobtown Featured Records into VC
if ( ! function_exists( 'mob_featured_records_vc_integrate' ) ) {

	function mob_featured_records_vc_integrate() {
		vc_map( array(
			'name' => __( 'Mobtown Featured Records', '' ),
			'base' => 'mob_featured_records',
			'class' => 'mob-featured-records-holder',
			'icon' => 'icon-wpb-blog-slider extended-custom-icon',
			'allowed_container_element' => 'vc_row',
			'category' => __( 'Content', ''),
			'params' => array(
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Show More Link',
					'param_name' => 'show_more_link',
					'value' => array(
						'' => '',
						'Yes' => true,
						'No' => false,
					),
					'save_always' => true,
					'description' => 'Defaults to no',
				),
			)
		));
	}
	
	add_action( 'vc_before_init', 'mob_featured_records_vc_integrate' );
	
}

// Integrate Mobtown Pricing List Table into VC
if ( ! function_exists( 'mob_pricing_table_vc_integrate' ) ) {

	function mob_pricing_table_vc_integrate() {
		vc_map( array(
			'name' => __( 'Mobtown Pricing Table', '' ),
			'base' => 'mob_pricing_table',
			'class' => 'mob-pricing-table-holder',
			'icon' => 'icon-wpb-blog-slider extended-custom-icon',
			'allowed_container_element' => 'vc_row',
			'category' => __( 'Content', ''),
			'params' => array(
				array(
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Service Type',
					'param_name' => 'service_type',
					'value' => array(
						'' => '',
						'Mixing' => 'mixing',
						'Mastering' => 'mastering',
					),
					'description' => 'Type of services to include'
				),
			)
		));
	}
	
	add_action( 'vc_before_init', 'mob_pricing_table_vc_integrate' );
	
}