<?php 
/** 	
 *	Summary. Widget to output featured image. 
 *	
 * 	Description. Extends WP_Widget to create a widget that outputs a featured image at 'mob-record-cover-thumb' image size with custom classes.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
 
// Creating the widget 
class mob_thumbnail_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'mob_thumbnail_widget', 
		 
		// Widget name will appear in UI
		__('Mobtown Thumbnail Widget', ''), 
		 
		// Widget description
		array( 'description' => __( 'Displays the featured image', '' ), ) );
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		 
		$image = get_the_post_thumbnail( $post->ID, 'mob-record-cover-thumb' );
		
		if( $image ) {
			echo $args['before_widget'];
	
			echo '<div class="mob-image-container">' . $image . '</div>';
	
			echo $args['after_widget'];
		}
	}
	         	
} // Class mob_thumbnail_widget ends here