<?php 
/** 	
 *	Summary. Record CPT.
 *	
 * 	Description. Registers and defines Record custom post type.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
	
if ( ! function_exists( 'mob_register_record_post_type' ) ) {
	
	function mob_register_record_post_type() {
	
		$labels = array(
			'name' 					=> __( 'Records', '' ),
			'singular_name' 		=> __( 'Record', '' ),
			'menu_name'             => __( 'Records', '' ),
			'name_admin_bar'        => __( 'Records', '' ),
			'archives'              => __( 'Record Archives', '' ),
			'parent_item_colon'     => __( 'Parent Record:', '' ),
			'all_items'             => __( 'All Records', '' ),
			'add_new_item'          => __( 'Add New Record', '' ),
			'add_new'               => __( 'Add New', '' ),
			'new_item'              => __( 'New Record', '' ),
			'edit_item'             => __( 'Edit Record', '' ),
			'update_item'           => __( 'Update Record', '' ),
			'view_item'             => __( 'View Record', '' ),
			'search_items'          => __( 'Search Records', '' ),
			'not_found'             => __( 'Not found', '' ),
			'not_found_in_trash'    => __( 'Not found in Trash', '' ),
			'featured_image'        => __( 'Cover Art', '' ),
			'set_featured_image'    => __( 'Set cover art', '' ),
			'remove_featured_image' => __( 'Remove cover art', '' ),
			'use_featured_image'    => __( 'Use as cover art', '' ),
			'insert_into_item'      => __( 'Insert into item', '' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', '' ),
			'items_list'            => __( 'Record list', '' ),
			'items_list_navigation' => __( 'Record list navigation', '' ),
			'filter_items_list'     => __( 'Filter items list', '' ),		
		);
	
		$args = array(
			'label' 				=> __( 'Records', '' ),
			'labels' 				=> $labels,
			'description' 			=> 'Custom Record post type for Mobtown Studios',
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'show_ui' 				=> true,
			'show_in_rest' 			=> true,
			'rest_base' 			=> '',
			'has_archive' 			=> 'record-archive',
			'taxonomies'            => array( 'project_type', 'genre', 'post_tag' ),
			'show_in_menu' 			=> true,
			'exclude_from_search' 	=> false,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'hierarchical' 			=> false,
			'rewrite' 				=> array( 'slug' => 'records', 'with_front' => true ),
			'query_var' 			=> true,
			'menu_position' 		=> 5,
			'menu_icon' 			=> 'dashicons-format-audio',
			'supports' 				=> array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'author', 'post-formats' ),
		);
	
		register_post_type( 'record', $args );
	}

	add_action( 'init', 'mob_register_record_post_type', 0 );
}

