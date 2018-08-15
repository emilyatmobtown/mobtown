<?php 
/** 	
 *	Summary. Widget to output audio playlist. 
 *	
 * 	Description. Extends WP_Widget to create a widget that outputs an audio playlist .
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
 
// Creating the widget 
class mob_playlist_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'mob_playlist_widget', 
		 
		// Widget name will appear in UI
		__('Mobtown Playlist Widget', ''), 
		 
		// Widget description
		array( 'description' => __( 'Displays the playlist of a record', '' ), ) );
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		 
		$playlist = get_field( 'playlist', $post->ID );
    	$artistname = get_field( 'artist_name', $id );
    	$recordname = get_field( 'record_name', $id );
    	$article_id = $artistname . ' ' . $recordname;
    	$article_id = str_replace( ' ', '-', $article_id );
    	$article_id = strtolower( $article_id );
		
		if( $playlist ) {
			echo $args['before_widget'];
			
			$playlist_array = array();
			foreach ( $playlist as $audio ){
				array_push ( $playlist_array, $audio["ID"] );
			}
			
			$audio_player = do_shortcode( '[playlist ids="' . implode( ',', $playlist_array ) . '" images="false" artists="false"]' );
			$audio_player = str_replace( 'amp;', '', $audio_player );
	
			echo '<div id="' . $article_id . '" class="mob-audio-container"' . $audio_player . '</div>';
	
			echo $args['after_widget'];
		}
	}
	         	
} // Class mob_playlist_widget ends here