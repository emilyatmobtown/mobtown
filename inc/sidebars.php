<?php 
/** 	
 *	Summary. Adds cusotm sidebars.
 *	
 * 	Description. Registers and defines theme sidebars, including Single Record Sidebar.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	

if ( ! function_exists( 'mob_custom_sidebars' ) ) {

	// Register Sidebars
	function mob_custom_sidebars() {
	
		$args = array(
			'id'            => 'mob_single_record_sidebar',
			'class'         => 'mob-sidebar',
			'name'          => __( 'Single Record Sidebar', '' ),
			'description'   => __( 'Sidebar displayed on single record posts.', '' ),
			'before_title'  => '<h3 class="widgettitle mob-sidebar-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );
	
	}
	
	add_action( 'widgets_init', 'mob_custom_sidebars' );
}