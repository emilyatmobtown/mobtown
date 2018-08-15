<?php
/** 	
 *	Summary. Genre Taxonomy.
 *	
 * 	Description. Registers and defines Genre taxonomy.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
	
if ( ! function_exists( 'mob_register_genre_taxonomy' ) ) {

	function mob_register_genre_taxonomy() {
	
		$labels = array(
			'name' => __( 'Genres', '' ),
			'singular_name' => __( 'Genre', '' ),
		);
	
		$args = array(
			'label' => __( 'Genres', '' ),
			'labels' => $labels,
			'public' => true,
			'hierarchical' => true,
			'label' => 'Genres',
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'genre', 'with_front' => true,  'hierarchical' => true, ),
			'show_admin_column' => true,
			'show_in_rest' => true,
			'rest_base' => '',
			'show_in_quick_edit' => true,
		);
		register_taxonomy( 'genre', array( 'record' ), $args );
	}
	
	add_action( 'init', 'mob_register_genre_taxonomy' );

}

