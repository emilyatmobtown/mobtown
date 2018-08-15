<?php
/** 	
 *	Summary. Theme functions for mobtown child theme
 *	
 * 	Description. Contains foundational, output, and filtering functions for mobtown child theme.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
	
require_once( __DIR__ . '/inc/theme-functions.php');
require_once( __DIR__ . '/inc/extend-vc.php');
require_once( __DIR__ . '/inc/sidebars.php');
require_once( __DIR__ . '/inc/shortcodes.php');
require_once( __DIR__ . '/inc/record.php');
require_once( __DIR__ . '/inc/project-type.php');
require_once( __DIR__ . '/inc/genre.php');
require_once( __DIR__ . '/widgets/playlist-widget.php');
require_once( __DIR__ . '/widgets/thumbnail-widget.php');
require_once( __DIR__ . '/widgets/record-download-widget.php');
require_once( __DIR__ . '/widgets/record-list-widget.php');
require_once( __DIR__ . '/widgets/share-widget.php');

//  Enqueue Elated child theme stylesheet
function elated_child_enqueue_style() {
	wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
	wp_enqueue_style( 'childstyle' );
}
add_action( 'wp_enqueue_scripts', 'elated_child_enqueue_style', 11);

// Change page title on single record pages
if ( ! function_exists( 'mob_filter_single_record_title' ) ) {

	function mob_filter_single_record_title( $title, $id ) {
		if( get_post_type( $id ) === 'record' && !is_admin() ) {
			
			$artist_name = get_field( 'artist_name' );
			if( $artist_name === '' ) {
				return $title;
			}
			
			if( has_term('microshows', 'project_type' ) ) {
				$record_name = 'Microshow';
			} elseif( has_term('bsides', 'project_type' ) ) {
				$record_name = 'The BSide Session';
			} else {
				$record_name = get_field( 'record_name' );
			}
			
			if( $record_name === '' ) {
				return $artist_name;
			}
			
			$title = $artist_name . ' <span class="mob-record-name">' . $record_name . '</span>';
			return $title;
		
		} else {
			return $title;
		}
	}
	
	add_filter( 'the_title', 'mob_filter_single_record_title', 10, 2 );
}

// Set featured image as header image on Records and Blog posts
if ( ! function_exists( 'mob_set_header_image' ) ) {

	function mob_set_header_image() {
		global $post;
		
		if( is_singular( array( 'record' ) ) ) {
			update_post_meta( $post->ID, 'eltd_title-image', get_the_post_thumbnail_url( $post->ID, 'mob-blog-header-image' ) );
			update_post_meta( $post->ID, 'eltd_fixed-title-image', 'yes' );
			update_post_meta( $post->ID, 'eltd_responsive-title-image', 'no' );
		}

		if( is_singular( array( 'post' ) ) ) {
			update_post_meta( $post->ID, 'eltd_title-image', '' );
			update_post_meta( $post->ID, 'eltd_fixed-title-image', 'yes' );
			update_post_meta( $post->ID, 'eltd_responsive-title-image', 'no' );
		}
	}
	
	add_action( 'get_header', 'mob_set_header_image' );
}

// Get content of single Record page
if( ! function_exists( 'mob_show_record_content' ) ) {
	
	function mob_show_record_content( $content ) {
		
		if( is_singular( 'record' ) && has_term( array( 'microshows', 'bsides' ), 'project_type' ) ) {
			$youtube_id = get_field( 'youtube_id' );
			$photo_gallery = get_field( 'photo_gallery' );
			$artist_name = get_field( 'artist_name' );
			$session_date = get_field( 'session_date' );
			
			// Show video
			if( $youtube_id ) {
				$paragraphAfter[1] = '<div class="mob-video-container"><div class="mob-video">';
				$paragraphAfter[1] .= '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="788.54" height="443" type="text/html" allow="autoplay; encrypted-media" allowfullscreen src="https://www.youtube.com/embed/' . esc_html( $youtube_id ) . '?showinfo=0&rel=0"></iframe>';
				$paragraphAfter[1] .= '</div>';
				
				if( $artist_name ) {
					$session_type = '';
					
					if( has_term( 'microshows', 'project_type' ) ) {
						$session_type = 'Microshow';
					} elseif ( has_term( 'bsides', 'project_type' ) ) {
						$session_type = 'BSide';
					}
			
					$paragraphAfter[1] .= '<p class="mob-video-caption">' . esc_html( $artist_name ) . ' joins us in the studio';
					
					if( $session_type ) {
						$paragraphAfter[1] .= ' for a ' . $session_type;
					}
					
					if( $session_date ) {
						$paragraphAfter[1] .= ' (' . esc_html( $session_date ) . ')';
					}
					
					$paragraphAfter[1] .= '.</p>';
				}
				
				$paragraphAfter[1] .= '</div>';
			}
			
			// Show photo slider
			if( $photo_gallery ) { 
				$paragraphAfter[2] = '<div class="mob-record-details-container mob-record-gallery-container">';
				$paragraphAfter[2] .= '<div class="mob-flexslider flexslider">';
				$paragraphAfter[2] .= '<ul class="slides">';
				
				foreach( $photo_gallery as $image ) {
					$paragraphAfter[2] .= '<li><a class="foobox" rel="gallery" href="' . esc_url( $image['sizes']['large'] ) . '"><img src="' . esc_url( $image['sizes']['mob-record-slider-image'] ) . '" alt="' . esc_html( $image['alt'] ) . '" /></a></li>';
				}
				
				$paragraphAfter[2] .= '</ul>';
				$paragraphAfter[2] .= '</div></div>';
			} 
			
			if( $youtube_id || $photo_gallery ) {
				
				$content_array = explode( '</p>', $content );
				$newcontent = '';
				$count = count( $content_array );
				for ( $i = 0; $i < $count; $i++ ) {
				    if ( array_key_exists( $i, $paragraphAfter ) ) {
				        $newcontent .= $paragraphAfter[$i];
				    }
				    $newcontent .= $content_array[$i] . '</p>';
				}
				$content = $newcontent;
			}
		}
		
		if( is_singular( 'record' ) ) {
			$production_credits = get_field( 'production_credits' );
			$artist_credits = get_field( 'artist_credits' );
			$session_date = get_field( 'session_date' );
			
			// Show credits
			if( $production_credits || $artist_credits ) {										
				$content .= '<div class="mob-record-details-container mob-record-credits-container">';
				$content .= '<h2 class="mob-record-credits-title">Credits</h2>';
										
				// Show session date
				if( $session_date ) {
					$content .= '<div class="mob-record-credit-group">';
					$content .= '<p>Recorded on ' . esc_html( $session_date ) . ' at Mobtown Studios</p>';
					$content .= '</div>';
				}

				if( have_rows( 'artist_credits' ) ) { 
					
					$content .= '<div class="mob-record-credit-group">';
					
						while ( have_rows( 'artist_credits' ) ) {
							the_row(); 
							
							$roles = get_sub_field( 'role' );
							$name = get_sub_field( 'name' ); 
							$preposition = 'by';
							
							// Check for empty rows
							if( !empty( $roles ) && !empty( $name ) ) {
							
								$has_commas = strstr( $name, ',' );
								if( $has_commas ) {
									$has_and = strstr( $name, ' and ');
									
									if( $has_and == false ) {
										$name = substr_replace( $name, ' and', strrpos( $name, ','), 1 );
									}
								} 
								
								$roles_string = implode( ', ', $roles );
								if( strstr( $roles_string, ',' ) != false ) {
									$roles_string = substr_replace( $roles_string, ' and', strrpos( $roles_string, ',' ), 1 );
								}
								
								$roles_string = strtolower( $roles_string );
								$roles_string = ucfirst( $roles_string );
																					
								$content .= '<p>' . esc_attr( $roles_string ) . ' ' . esc_attr( $preposition ) . ' ' . esc_attr( $name ) . '</p>';
							}
						} 
					
					$content .= '</div>';

				} 
										
				if( have_rows( 'production_credits' ) ) { 
					$contributors = get_field( 'contributor_credits', 'options' );
					foreach( $contributors as $i => $contributor ) {
						$contributors[$i]['added'] = false;
					}
					
					$content .= '<div class="mob-record-credit-group">';
					
						while ( have_rows( 'production_credits' ) ) {
							the_row(); 
							
							$roles = get_sub_field( 'role' );
							$name = get_sub_field( 'name' ); 
							$preposition = 'by';
							
							// Check for empty rows
							if( !empty( $roles ) && !empty( $name ) ) {
								$name = str_replace( ' and ', ', ', $name );
								$name_strings = explode( ', ', $name ); 
								$length = count( $name_strings );
								$name = '';
								$added_url = '';
								
								// Add URLs for production contributors
								foreach( $name_strings as $i => $name_string ) {
									foreach( $contributors as $j => $contributor ) {
										if( ( $contributor['name'] === $name_string ) && ( $contributors[$j]['added'] != true ) ) {
											$name_string = '<a href="' . esc_url( $contributor['url'] ) . '" title="Check out ' . esc_attr( $contributor['name'] ) . '\'s work" target="_blank">' . esc_attr( $contributor['name'] ) . '</a>';
											
											// Link each person only once
											$contributors[$j]['added'] = true;
										}
									}
									
									$name .= $name_string;
									if( $i < $length - 1 ) {
										$name .= ', ';
									}
								}
								
								// Replace last comma with 'and'
								$has_commas = strstr( $name, ',' );
								if( $has_commas ) {
									$has_and = strstr( $name, ' and ');
									
									if( $has_and == false ) {
										$name = substr_replace( $name, ' and', strrpos( $name, ','), 1 );
									}
								} 
								
								$roles_string = implode( ', ', $roles );
								if( strstr( $roles_string, 'Recorded' ) != false ) {
									$preposition = 'at';
								} elseif( strstr( $roles_string, 'Thanks' ) != false ) {
									$preposition = 'to';
								}
								
								if( strstr( $roles_string, ',' ) != false ) {
									$roles_string = substr_replace( $roles_string, ' and', strrpos( $roles_string, ',' ), 1 );
								}
								
								$roles_string = strtolower( $roles_string );
								$roles_string = ucfirst( $roles_string );
								
								$content .= '<p>' . esc_attr( $roles_string ) . ' ' . esc_attr( $preposition ) . ' ' . $name . '</p>';
							}
						} 
						
					$content .= '</div>';
					
				} 

				$content .= '</div>';
			
			} 
			
			// Show related records
			$content .= '<div class="mob-record-details-container mob-record-related-container">';
			$content .= '<h2 class="mob-record-related-title">You Might Also Like</h2>';
			$content .= do_shortcode( '[pt_view id="44ae6613y1" tag="GET_CURRENT"]' );
			$content .= '</div>';
		}
		
		echo $content;
	}
}

// Add class to Content View button
if( ! function_exists( 'mob_add_class_to_cv_button' ) ) {
	
	function mob_add_class_to_cv_button( $class, $fargs ) {
		$class .= ' ' . 'qbutton';
		return $class;
	}
	
	add_filter( 'pt_cv_field_content_readmore_class', 'mob_add_class_to_cv_button', 100, 2 );
}

if( ! function_exists( 'mob_get_content_view_post' ) ) {

	function mob_get_content_view_post( $args, $fields_html, $post ) {
		global $wpdb;

		if( 'post' === get_post_type( $post->ID ) ) {
			$args = '';
			$args = $fields_html[ 'thumbnail' ] . $fields_html[ 'title' ] . $fields_html[ 'content' ] . mob_filter_cv_terms( $fields_html[ 'meta-fields' ], $post );
		}

		elseif( 'record' === get_post_type( $post->ID ) ) {
			$args = '';
			$args = mob_get_record( $post->ID );
		}
		
		else {
			$args = '';
			$title_image = get_post_meta( $post->ID, 'eltd_title-image', true );
			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $title_image ) );
			$image_id = $attachment[0];
			$args = wp_get_attachment_image( $image_id, 'mob-record-cover-thumb' ) . $fields_html[ 'title' ] . $fields_html[ 'content' ];
		}
		
/*
		$fields_html[ 'thumbnail' ]
		$fields_html[ 'title' ]
		$fields_html[ 'content' ]
		$fields_html[ 'meta-fields' ]
		$fields_html[ 'custom-fields' ]
*/
	
		return $args;
	}
	
	add_filter( 'pt_cv_view_type_custom_output', 'mob_get_content_view_post', 100, 3 );

}

// Show term without hyperlink in Content View
if( ! function_exists( 'mob_remove_cv_taxonomy_link' ) ) {

	function mob_remove_cv_taxonomy_link( $args, $term ) {
		$args = $term->name;
		return $args;
	}

	add_filter( 'pt_cv_post_term_html', 'mob_remove_cv_taxonomy_link', 100, 2 );

}

// Remove link from post taxonomy on individual card in grid
if( ! function_exists( 'mob_remove_cv_taxonomy_link' ) ) {
	
	function mob_edit_content_view_post( $html, $post ) {
		
		if( 'post' === get_post_type( $post->ID ) ) {
			
			return '<p>' . $html . '</p>';
			
		}
		
		return $html;
	}

	add_filter( 'pt_cv_item_content', 'mob_edit_content_view_post', 10, 2 );
	
}

// Modify taxonomy terms on individual cards in grid
if( ! function_exists( 'mob_filter_cv_terms' ) ) {
	
	function mob_filter_cv_terms( $terms, $post ) {
		
		if( 'post' === get_post_type( $post->ID ) ) {
		
			$new_terms = '';
			
			$needle = array( 'Making a record, ', ', Making a record' );
			$replace = '';
			$new_terms = str_ireplace( $needle, $replace, $terms );
			
			$needle = ', ';
			$replace = ' / ';
			$new_terms = str_replace( $needle, $replace, $new_terms);
			
			$terms = $new_terms;
		}
		
		return $terms;
	}
}

// Change loader in Content View
if( ! function_exists( 'mob_cv_loading_image_url' ) ) {
	
	function mob_cv_loading_image_url( $args ) {
		$args = get_stylesheet_directory_uri() . '/img/mob-spinner.svg';
		return $args;
	}
	
	add_filter( 'pt_cv_loading_image_url', 'mob_cv_loading_image_url', 100, 1 );
}

// Overwrite Side Area link 
function eltd_get_side_menu_icon_html() {
	global $eltdIconCollections, $eltd_options;

	$icon_html = '';
	$title = get_field( 'side_area_link_name', 'options' );

	if(isset($eltd_options['side_area_button_icon_pack']) && $eltd_options['side_area_button_icon_pack'] !== '') {
		$icon_pack = $eltd_options['side_area_button_icon_pack'];
		if ($icon_pack !== '') {
			$icon_collection_obj = $eltdIconCollections->getIconCollection($icon_pack);
			$icon_field_name = 'side_area_icon_'. $icon_collection_obj->param;

			if(isset($eltd_options[$icon_field_name]) && $eltd_options[$icon_field_name] !== ''){
				$icon_single = $eltd_options[$icon_field_name];

				if (method_exists($icon_collection_obj, 'render')) {
					$icon_html = $icon_collection_obj->render($icon_single);
					
					if( !empty( $title ) ) {
						$icon_html .= '<span class="mob-side-area-link-text">' . esc_attr( $title ) . '</span>';
					}
				}
			}
		}
	}

	return $icon_html;
}

// Empty function to override parent theme's enqueuing of Google fonts
function eltd_google_fonts_styles() {}
add_action('wp_enqueue_scripts', 'eltd_google_fonts_styles');