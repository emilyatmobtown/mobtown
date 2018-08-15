<?php 
/** 	
 *	Summary. Widget to output sharing links. 
 *	
 * 	Description. Extends WP_Widget to create a widget that outputs sharing links with content-based text.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
 
// Creating the widget 
class mob_share_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'mob_share_widget', 
		 
		// Widget name will appear in UI
		__('Mobtown Sharing Widget', ''), 
		 
		// Widget description
		array( 'description' => __( 'Displays social sharing links', '' ), ) );
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = 'Share';
		
		if( 'record' === get_post_type() ) {
			$title = 'Share this record';
		}
		
		if( has_term( array( 'microshows', 'bsides' ), 'project_type' ) ) {
			$title = 'Share this session';
		}
		
		echo $args['before_widget'];
		echo '<div class="mob-share-links-container">';
		echo '<h2 class="mob-widget-title mob-share-title">' . $title . '</h2>';
		echo do_shortcode('[mob_social_share_list]'); 
		echo '</div>';
		echo $args['after_widget'];
	}
	         	
} // Class mob_share_widget ends here