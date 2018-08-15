<?php
/** 	
 *	Summary. Project Type Taxonomy.
 *	
 * 	Description. Registers and defines Project Type taxonomy.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
	
if ( ! function_exists( 'mob_register_project_type_taxonomy' ) ) {

	function mob_register_project_type_taxonomy() {
	
		$labels = array(
			'name' => __( 'Project Types', '' ),
			'singular_name' => __( 'Project Type', '' ),
		);
	
		$args = array(
			'label' => __( 'Project Types', '' ),
			'labels' => $labels,
			'public' => true,
			'hierarchical' => true,
			'label' => 'Project Types',
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'project_type', 'with_front' => true,  'hierarchical' => true, ),
			'show_admin_column' => true,
			'show_in_rest' => true,
			'rest_base' => '',
			'show_in_quick_edit' => true,
		);
		register_taxonomy( 'project_type', array( 'record' ), $args );
	}
	
	add_action( 'init', 'mob_register_project_type_taxonomy' );

}

