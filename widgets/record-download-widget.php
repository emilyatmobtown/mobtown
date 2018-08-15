<?php 
/** 	
 *	Summary. Widget to output audio sharing and downloading links. 
 *	
 * 	Description. Extends WP_Widget to create a widget that outputs iTunes, Spotify, and Bandcamp links or download an existing audio file.
 *
 *	@package mobtownstudios
 *	@since 1.0.0 
 */	
 
// Creating the widget 
class mob_record_download_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'mob_record_download_widget', 
		 
		// Widget name will appear in UI
		__('Mobtown Record Download Widget', ''), 
		 
		// Widget description
		array( 'description' => __( 'Displays the links to download the record', '' ), ) );
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$links = get_field( 'download_links', $post->ID );
		$session_file = get_field( 'session_file', $post->ID );
		if( empty( $session_file ) ) {
			$session_file = get_field( 'session_url', $post->ID );
		}
				 
		if( $links ) {
			
			$itunes = $links['itunes_link'];
			$spotify = $links['spotify_link'];
			$bandcamp = $links['bandcamp_link'];
			
			if( $itunes || $spotify || $bandcamp ) {

				echo $args['before_widget'];
	
				echo '<div class="mob-record-download-links-container">';
				echo '<h2 class="mob-widget-title mob-record-download-title">Find this record</h2>';
				
				if( $itunes ) {
					echo '<a id="mob-itunes-find-button" class="mob-record-download-link itunes-link" title="Find this album on iTunes" href="' . esc_url( $itunes ) . '" target="_blank"><span class="mob-offscreen">iTunes</span></a>';
				}
	
				if( $bandcamp ) {
					echo '<a id="mob-bandcamp-find-button" class="mob-record-download-link bandcamp-link" title="Find this album on Bandcamp" href="' . esc_url( $bandcamp ) . '" target="_blank"><span class="mob-offscreen">Bandcamp</span></a>';
				}
	
				if( $spotify ) {
					echo '<a id="mob-spotify-find-button" class="mob-record-download-link spotify-link" title="Find this album on Spotify" href="' . esc_url( $spotify ) . '" target="_blank"><span class="mob-offscreen">Spotify</span></a>';
				}
				
				echo '</div>';
		
				echo $args['after_widget'];
				
			}
		}
		
		if( $session_file ) {

				echo $args['before_widget'];
	
				echo '<div class="mob-record-download-links-container">';
				echo '<h2 class="mob-widget-title mob-record-download-title">Download this session</h2>';
				echo '<a id="mob-session-download-button" class="mob-record-download-link zip-download-link" title="Download this session" href="' . esc_url( $session_file ) . '" target="_blank"><span class="mob-offscreen">Download</span></a>';
				echo '</div>';
				echo $args['after_widget'];
				
		}
	}
	         	
} // Class mob_record_download_widget ends here