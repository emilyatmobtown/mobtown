<?php
/** 	
 *	Summary. Shortcodes for mobtown theme components.
 *	
 * 	Description. Contains shortcode functions for audio listings and displays, sharing links, and pricing tables.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
 
	
// Show Record List
if ( !function_exists( 'mob_record_list' ) ) {
	
	function mob_record_list( $atts, $content = null ) {

        $args = array(
            'project_type' 		=> 'studio-projects',
            'number_of_posts' 	=> 48,
            'number_of_columns'	=> 3,
            'image_size'		=> 'mob-record-cover-thumb',
            'show_featured'		=> false,
            'show_filtering'	=> false,
            'order_by' 			=> 'rand',
            'order' 			=> '',
		);

        extract( shortcode_atts( $args, $atts ) );
        
        $project_type_strings = explode( ", ", $project_type );

        $posts_number = $number_of_posts;
        if ( $number_of_posts === '' ) {
            $posts_number = $number_of_columns;
        }
        
		$q = new WP_Query( array(
			'orderby' 			=> $order_by,
			'order' 			=> $order,
			'posts_per_page' 	=> $posts_number,
			'post_type' 		=> 'record',
			'paged' 			=> $paged,
			'page' 				=> $paged,
			'tax_query' 		=> array(
				array(
					'taxonomy' => 'project_type',
					'field'    => 'slug',
					'terms'    => $project_type_strings,
				),
			),
		));
		
		$classes = '';
        $html = '';
        
		$html = mob_get_record_list( $q, $html, $number_of_columns, $image_size, $classes, $show_filtering );

	    wp_reset_postdata();

        return $html;
	}
	
	add_shortcode( 'mob_record_list', 'mob_record_list' );
}


// Show Featured Records
if ( !function_exists( 'mob_featured_records' ) ) {
	
	function mob_featured_records( $atts, $content = null ) {
        $args = array(
            'show_more_link'	=> false,
		);

        extract( shortcode_atts( $args, $atts ) );

		$ids = get_field( 'featured_records', 'option', false );
		$posts_per_page = 6;
		
		$q = new WP_Query(array(
			'post_type'      	=> 'record',
			'posts_per_page'	=> $posts_per_page,
			'post__in'			=> $ids,
			'post_status'		=> 'published',
			'orderby'        	=> 'post__in',
		));
		
		$number_of_columns = 3;
		$image_size = 'mob-record-cover-thumb';
		$classes = 'mob-featured-record-list ';
		$show_filtering = false;
		
		$html = '';
		
		$html = mob_get_record_list( $q, $html, $number_of_columns, $image_size, $classes, $show_filtering, $show_more_link );
				
		wp_reset_postdata();
		
		return $html;
	}
	
	add_shortcode( 'mob_featured_records', 'mob_featured_records' );
}


// Get Record List
if ( !function_exists( 'mob_get_record_list' ) ) {
	
	function mob_get_record_list( $q, $html='', $columns, $image_size, $holder_classes = '', $show_filtering = false, $show_more_link = false ) {
		global $post;
		
        if( $q->have_posts() ) {
	        
	        if( $show_filtering ) {
		        $holder_classes .= 'filtered ';
		        $id = 'mob-infinite-content';
	        }
	        	        
	        $html .= '<div class="projects_holder_outer v' . esc_attr( $columns ) . ' portfolio_with_space portfolio_with_hover_text mob-record-list-holder-outer ' . esc_attr( $holder_classes ) . '">';
	        
	        if( $show_filtering ) {
		        $html .= '<div class="controls">';		        
                $html .= '<div class="filter_outer filter_portfolio center_align">';
                $html .= '<div class="filter_holder"><a class="qbutton mob-filter-button">Filter Records</a><ul>';
                $html .= '<li class="filter" data-filter="all"><span>All</span></li>';

				$genres = get_terms( array( 'taxonomy' => 'genre' ) );
		        if( $genres ) {
			        foreach( $genres as $genre ) {
				        $html .= '<li class="filter" data-filter=".portfolio_category_' . $genre->term_id . '"><span>' . $genre->name . '</span></li>';
			        }
		        }
		        
		        $html .= '</ul></div>';
                $html .= '</div></div>';
                $html .= '<div id="' . $id . '" class="targets">';
	        }

	        $html .= '<div class="portfolio_main_holder projects_holder clearfix v' . esc_attr( $columns ) . ' hover_text mob-record-list-holder">';
	
	        while ( $q->have_posts() ) { 
		        $q->the_post();
	        	$html .= mob_get_record( $q->post->ID, $image_size );
	        }
	
	        $i = 1;
	        while ( $i <= $columns ) {
	            $i++;
	            if ( $columns != 1 ) {
	                $html .= "<div class='filler'></div>\n";
	            }
	        }
	
	        $html .= '</div>'; // End 'portfolio_main_holder'
	        
	        if( $show_filtering ) {
	        	$html .= '</div>'; // End 'targets'
	        }
	        
	        if ( $show_more_link ) {
		        $url = get_permalink( get_page_by_path( 'records' ) );
		        
	            $html .= '<div class="mob-record-list-more-link"><a id="mob-load-more-records-button-from-' . $post->post_name . '" " href="' . $url . '" class="qbutton">See More</a></div>';
	        }
                
	        $html .= '</div>'; // End 'projects_holder_outer'
	        
	        return $html;
	        
	    }
	}
}

// Get Record (short-form)
if ( !function_exists( 'mob_get_record' ) ) {
	
	function mob_get_record( $id, $image_size = 'mob-record-cover' ) {
		global $post;
		
		if( empty( $id ) ) {
			$id = $post->ID;
		}
		
		$html = '';
		
		if( 'record' === get_post_type( $id ) ) {
	    	$artistname = get_field( 'artist_name', $id );
	    	$recordname = get_field( 'record_name', $id );
	    	$playlist = get_field( 'playlist', $id );
	    	$track = $playlist[0]['url'];
	    	$mime = $playlist[0]['mime_type'];
	    	$errormessage = 'Your browser does not support the audio player.';
	    	$article_id = $artistname . ' ' . $recordname;
	    	$article_id = str_replace( ' ', '-', $article_id );
	    	$article_id = strtolower( $article_id );

	    	if( $artistname === '' ) {
	        	$artistname = get_the_title( $id );
	    	}
	    	
			$html .= '<article id="' . $article_id . '" class="mob-record mix mix_all ';
		
        	$terms = wp_get_post_terms( $id, 'genre' );
			foreach ( $terms as $term ) {
                $html .= 'portfolio_category_' . $term->term_id . ' ';
            }

            $html .= 'mix_filtered ';
	        
	        $html .= '">';
	        
			$html .= '<div class="item_holder prominent_plain_hover">';
			
			$html .= '<div class="text_holder">';
	        $html .= '<div class="text_holder_outer">';
	        $html .= '<div class="text_holder_inner">';
	        
	        // Adds overlay text
	        $html .= '<h3 class="portfolio_title mob-artist-name"><a href="' . esc_url( get_permalink() ) . '">' . esc_attr( $artistname ) . '</a></h3>';
	        $html .= '<span class="separator mob-record-name-separator animate" style="height: 1px"></span>';
	        $html .= '<span class="project_category mob-record-name">' . esc_attr( $recordname ) . '</span>';
	        
	        $html .= '</div>'; // End 'text_holder_inner'
	        $html .= '</div>'; // End 'text_holder_outer'
	        $html .= '</div>'; // End 'text_holder'
	        
	        // Links the entire box
	        $html .= '<a id="mob-record-button-' . $article_id . '" class="portfolio_link_class" href="' . esc_url( get_permalink( $id ) ) . '"></a>';
	        
	        // Adds shaded overlay on hover
	        $html .= '<div class="portfolio_shader mob-cover-shader"></div>';
	        
	        $html .= '<div class="image_holder mob-cover-holder">';
	        $html .= '<span class="image mob-cover-image">';
	        $html .= get_the_post_thumbnail( $id, $image_size );
	        $html .= '</span>';
	        $html .= '</div>'; // End 'image_holder'
	         
			if( $track ) {
				$html .= '<div class="audio_image mob-audio-holder">';
				$html .= '<audio class="blog_audio mob-audio-player" controls="controls" preload="none">';
				$html .= '<source src="' . esc_url( $track ) . '" type="' . esc_attr( $mime ) . '">';
				$html .= esc_attr( $errormessage );
				$html .= '</audio></div>'; // End 'audio_image'
			}
			
	        $html .= '</div>'; // End 'item_holder'
	        $html .= "</article>";
		}
		
		return $html;
	}
}

// Show Social Share for Records
if (!function_exists('mob_social_share_list')) {

    function mob_social_share_list($atts, $content = null) {
        global $eltd_options;
        if (isset($eltd_options['twitter_via']) && !empty($eltd_options['twitter_via'])) {
            $twitter_via = " via " . esc_attr($eltd_options['twitter_via']) . " ";
        } else {
            $twitter_via = "";
        }
        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        $html = "";
        if (isset($eltd_options['enable_social_share']) && $eltd_options['enable_social_share'] == "yes") {
            $post_type = get_post_type();
            
            $share_icons_type = '';
            if(isset($eltd_options['social_share_list_icons_type']) && $eltd_options['social_share_list_icons_type'] !== '') {
                $share_icons_type = $eltd_options['social_share_list_icons_type'];
            }

            if ($share_icons_type == 'circle') {
                $share_icons_type_array = array(
                    'facebook' => 'social_facebook_circle',
                    'twitter' => 'social_twitter_circle',
                    'google_plus' => 'social_googleplus_circle',
                    'linkedin' => 'social_linkedin_circle',
                    'tumblr' => 'social_tumblr_circle',
                    'pinterest' => 'social_pinterest_circle',
                    'vk' => 'fa fa-vk'
                );
            } else {
                $share_icons_type_array = array(
                    'facebook' => 'social_facebook',
                    'twitter' => 'social_twitter',
                    'google_plus' => 'social_googleplus',
                    'linkedin' => 'social_linkedin',
                    'tumblr' => 'social_tumblr',
                    'pinterest' => 'social_pinterest',
                    'vk' => 'fa fa-vk'
                );
            }

            if ( $post_type == 'record' ) {
                $html .= '<div class="social_share_list_holder">';
                $html .= '<ul>';

                if (isset($eltd_options['enable_facebook_share']) && $eltd_options['enable_facebook_share'] == "yes") {
                    $html .= '<li class="facebook_share">';
                    if(wp_is_mobile()) {
                        $html .= '<a id="mob-fb-share-button" title="'.__("Share on Facebook","eltd").'" href="javascript:void(0)" onclick="window.open(\'http://m.facebook.com/sharer.php?u=' . urlencode(get_permalink());
                    }
                    else {
                        $html .= '<a id="mob-fb-share-button" title="'.__("Share on Facebook","eltd").'" href="javascript:void(0)" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . urlencode(eltd_addslashes(get_the_title())) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;p[images][0]=';
                        if(function_exists('the_post_thumbnail')) {
                            $html .=  wp_get_attachment_url(get_post_thumbnail_id());
                        }
                    }

                    $html .= '&amp;p[summary]=' . urlencode(eltd_addslashes(strip_tags(get_the_excerpt())));
                    $html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
                    if (!empty($eltd_options['facebook_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options["facebook_icon"]) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['facebook']).'"></i>';
                    }
                    $html .= "</a>";
                    $html .= "</li>";
                }

                if ($eltd_options['enable_twitter_share'] == "yes") {
                    $html .= '<li class="twitter_share">';
                    if(wp_is_mobile()) {
                        $html .= '<a id="mob-tw-share-button" href="#" title="'.__("Share on Twitter", 'eltd').'" onclick="popUp=window.open(\'http://twitter.com/intent/tweet?text=' . urlencode(eltd_the_excerpt_max_charlength(mb_strlen(get_permalink())) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                    }
                    else {
                        $html .= '<a id="mob-tw-share-button" href="#" title="'.__("Share on Twitter", 'eltd').'" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(eltd_the_excerpt_max_charlength(mb_strlen(get_permalink())) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                    }
                    if (!empty($eltd_options['twitter_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options["twitter_icon"]) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['twitter']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }
                if ($eltd_options['enable_google_plus'] == "yes") {
                    $html .= '<li  class="google_share">';
                    $html .= '<a id="mob-gp-share-button" href="#" title="' . __("Share on Google+", "eltd") . '" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    if (!empty($eltd_options['google_plus_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options['google_plus_icon']) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['google_plus']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }
                if (isset($eltd_options['enable_linkedin']) && $eltd_options['enable_linkedin'] == "yes") {
                    $html .= '<li  class="linkedin_share">';
                    $html .= '<a href="#" class="' . __("Share on LinkedIn", "eltd") . '" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()) . '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    if (!empty($eltd_options['linkedin_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options['linkedin_icon']) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['linkedin']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }
                if (isset($eltd_options['enable_tumblr']) && $eltd_options['enable_tumblr'] == "yes") {
                    $html .= '<li  class="tumblr_share">';
                    $html .= '<a href="#" title="' . __("Share on Tumblr", "eltd") . '" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()) . '&amp;name=' . urlencode(get_the_title()) . '&amp;description=' . urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    if (!empty($eltd_options['tumblr_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options['tumblr_icon']) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['tumblr']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }
                if (isset($eltd_options['enable_pinterest']) && $eltd_options['enable_pinterest'] == "yes") {
                    $html .= '<li  class="pinterest_share">';
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                    $html .= '<a id="mob-pi-share-button" href="#" title="' . __("Share on Pinterest", "eltd") . '" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()) . '&amp;description=' . eltd_addslashes(get_the_title()) . '&amp;media=' . urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    if (!empty($eltd_options['pinterest_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options['pinterest_icon']) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['pinterest']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }
                if (isset($eltd_options['enable_vk']) && $eltd_options['enable_vk'] == "yes") {
                    $html .= '<li  class="vk_share">';
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                    $html .= '<a href="#" title="' . __("Share on VK", "eltd") . '" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()) . '&amp;title=' . urlencode(get_the_title()) . '&amp;description=' . urlencode(get_the_excerpt()) . '&amp;image=' . urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                    if (!empty($eltd_options['vk_icon'])) {
                        $html .= '<img src="' . esc_url($eltd_options['vk_icon']) . '" alt="" />';
                    } else {
                        $html .= '<i class="'.esc_attr($share_icons_type_array['vk']).'"></i>';
                    }

                    $html .= "</a>";
                    $html .= "</li>";
                }

                $html .= '</ul>'; //close ul
                $html .= '</div>'; //close div.social_share_list_holder
            }
        }
        return $html;
    }

    add_shortcode('mob_social_share_list', 'mob_social_share_list');
}


// Show Pricing Table
if ( !function_exists( 'mob_pricing_table' ) ) {
	
	function mob_pricing_table( $atts, $content = null ) {

        $args = array(
            'service_type' 		=> 'mixing',
		);

        extract( shortcode_atts( $args, $atts ) );
        
		$q = new WP_Query( array(
			'post_type'     => 'product',
			'post_status'   => array( 'private', 'publish' ),
			'numberposts'   => -1,
			'orderby'       => 'menu_order',
			'order'         => 'asc',
			'product_cat'   => $service_type,
		));
		
		$html = "";
		
		if( $q->have_posts() ) {
			
			$q->the_post();

			$product_name = get_the_title( $q->post->ID );
			
			$html .= '<div class="cd-pricing-container">';
			$html .= '<nav class="cd-filter">';
			$html .= '<ul>';
									
			$args = array(
				'post_type'     => 'product_variation',
				'post_status'   => array( 'private', 'publish' ),
				'numberposts'   => -1,
				'orderby'       => 'menu_order',
				'order'         => 'asc',
				'post_parent'   => $q->post->ID,
			);
			
			$variations = get_posts( $args );
			
			$i = 0;
			
			foreach ( $variations as $variation ) {

				$variation_ID = $variation->ID;
				$product_variation = new WC_Product_Variation( $variation_ID );
				$variation_name = $product_variation->get_name();
				
				$delimiter = $product_name . ' - ';
				$exploded_string = explode( $delimiter, $variation_name );
				$variation_name = $exploded_string[1];
				
				$value = 'panel-' . $variation_name;
				$value = sanitize_html_class( $value );
				$link_class = '';
				$link_class = $i ? '' : 'selected';
				
				if( $i === 0 ) {
					// selected option on mobile
					$html .= '<li class="placeholder">';
					$html .= '<a data-type="' . $value . '" href="#0">' . $variation_name . '</a>'; 				
					$html .= '</li>';
				}

				$html .= '<li>';
				$html .= '<a class="' . $link_class . '" data-type="' . $value . '" href="#0">' . $variation_name . '</a>';
				$html .= '</li>';
				
				$i++;
			}
			
			$q->rewind_posts();

			$html .='</ul></nav>'; // End 'cd-filter'
		
			$html .= '<ul class="cd-pricing-list">';
			
			$j = 0;

			while( $q->have_posts() ) : $q->the_post();
			
				$product_name = get_the_title( $q->post->ID );
				$price_class = '';
				$price_class = $j === 1 ? 'cd-popular' : '';
				
				$html .= '<li class="' . $price_class . '">';
				$html .= '<ul class="cd-pricing-wrapper">';
				
				$args = array(
					'post_type'     => 'product_variation',
					'post_status'   => array( 'private', 'publish' ),
					'numberposts'   => -1,
					'orderby'       => 'menu_order',
					'order'         => 'asc',
					'post_parent'   => $q->post->ID,
				);
				
				$variations = get_posts( $args );
				
				$k = 0;
				
				foreach ( $variations as $variation ) {
					
					$variation_ID = $variation->ID;
					$product_variation = new WC_Product_Variation( $variation_ID );
					$variation_price = $product_variation->get_price_html();
					$variation_name = $product_variation->get_name();
					
					$delimiter = $product_name . ' - ';
					$exploded_string = explode( $delimiter, $variation_name );
					$variation_name = $exploded_string[1];

					$datatype = 'panel-' . $variation_name;
					$datatype = sanitize_html_class( $datatype );
					$panel_class = '';
					$panel_class = $k ? 'is-hidden' : 'is-visible';
					
					$html .= '
					<li data-type="' . $datatype . '" class="' . $panel_class . '">
						<header class="cd-pricing-header">
							<h2>' . $product_name . '</h2>

							<div class="cd-price">
								<span class="cd-currency">$</span>
								<span class="cd-value">' . $variation_price . '</span>
								<span class="cd-duration">mo</span>
							</div>
						</header> 

						<div class="cd-pricing-body">
							<ul class="cd-pricing-features">
								<li><em>256MB</em> Memory</li>
								<li><em>1</em> User</li>
								<li><em>1</em> Website</li>
								<li><em>1</em> Domain</li>
								<li><em>Unlimited</em> Bandwidth</li>
								<li><em>24/7</em> Support</li>
							</ul>
						</div> 
						<footer class="cd-pricing-footer">
							<a class="cd-select" href="http://codyhouse.co/?p=429">Select</a>
						</footer> <!-- .cd-pricing-footer -->
					</li>';
					
					$k++;
				}
				
				$html .= '</ul>'; // End 'cd-pricing-wrapper'
				$html .= '</li>';
				
				$j++;
				
			endwhile;
			
			$html .= '</ul>'; // End 'cd-pricing-list'
			$html .= '</div>'; // End 'cd-pricing-container'
		}

        return $html;
	}
	
	add_shortcode( 'mob_pricing_table', 'mob_pricing_table' );
	
}
