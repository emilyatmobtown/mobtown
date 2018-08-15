<?php
/** 	
 *	Summary. Theme starter class. 
 *	
 * 	Description. Handles theme image sizes, widget registration, script enqueueing, white labeling, menu registration, header and footer code insertion, and MIME types.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	

class MobThemeStarter {
	private $uri = '';
	private $directory = ''; 
	private $prefix = 'mob';
	private $name = 'Mobtown';
	
	private $images = array ( 
		array( 
			'name'		=>	'record-cover-thumb', 
			'width'		=>	400, 
			'height'	=>	400, 
			'crop'		=>	true 
		),	
		array( 
			'name'		=>	'small-record-cover-thumb', 
			'width'		=>	125, 
			'height'	=>	125, 
			'crop'		=>	true 
		),	
		array( 
			'name'		=>	'medium-record-cover-thumb', 
			'width'		=>	280, 
			'height'	=>	280, 
			'crop'		=>	true 
		),	
		array( 
			'name'		=>	'record-slider-image', 
			'width'		=>	800, 
			'height'	=>	9999, 
			'crop'		=>	false 
		),	
		array( 
			'name'		=>	'header-image', 
			'width'		=>	900, 
			'height'	=>	249, 
			'crop'		=>	true 
		),	
		array( 
			'name'		=>	'blog-header-image', 
			'width'		=>	1200, 
			'height'	=>	240, 
			'crop'		=>	true 
		),	
		array( 
			'name'		=>	'record-header-image', 
			'width'		=>	1200, 
			'height'	=>	240, 
			'crop'		=>	true 
		),	
	);
	
	private $widgets = array (
		array( 
			'name'		=> 'playlist_widget',
		),
		array( 
			'name'		=> 'thumbnail_widget',
		),
		array( 
			'name'		=> 'record_download_widget',
		),
		array( 
			'name'		=> 'record_list_widget',
		),
		array( 
			'name'		=> 'share_widget',
		),
	);
	
	
	public function __construct() {
		$this->set_variables();
		add_action( 'init', array( $this, 'add_options_pages' ) );
		add_action( 'init', array( $this, 'add_image_sizes' ) );
		add_action( 'init', array( $this, 'remove_image_sizes' ) );
		add_action( 'wp_head' , array( $this, 'add_head_scripts' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'replace_login_logo' ) );
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
		add_action( 'mob_after_body_open_tag', array( $this, 'add_after_body_open_tag' ) );
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		add_filter( 'image_size_names_choose', array( $this, 'set_image_sizes' ) );
		add_filter( 'upload_mimes', array( $this, 'add_mime_types' ) );
	}
	
	public function set_variables() {
		$this->uri = get_stylesheet_directory_uri();
		$this->directory = get_stylesheet_directory();
	}	
	
	public function enqueue_scripts() {
		if ( function_exists( 'wp_enqueue_script' ) ) {

			wp_enqueue_script( $this->prefix . '-utils', $this->uri . '/js/utils.js', array( 'jquery' ) );
			wp_enqueue_script( $this->prefix . '-mixitup', $this->uri . '/js/mixitup.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->prefix . '-flexslider', $this->uri . '/js/jquery.flexslider-min.js', array( 'jquery' ) );
		}
	}
	
	public function dequeue_scripts() {}

	public function replace_login_logo() { 
		?>
	    <style type='text/css'>
	        #login h1 a, .login h1 a {
		        background-image: url('<?php echo $this->uri ?>/img/<?php echo $this->prefix ?>-logo.svg');
				height:150px;
				width:320px;
				background-size: 320px 150px;
				background-repeat: no-repeat;
		        padding-bottom: 0;
	        }
	    </style> 
	    <?php 
	}

	public function add_body_classes( $classes ) {
		global $post;
	 
		if ( isset( $post ) ) :
			$classes[] = $post->post_type . '-' . $post->post_name;
		endif;
	     
	    $classes[] = $this->prefix;
	 
	    return $classes; 
	}
	
	public function add_after_body_open_tag() {
		?>
		
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5CWVCT"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->

		<?php 
	}
	
	public function add_head_scripts() {
    ?>
		<!-- Event tracking for Analytics/AdWords -->

		<script>
		  if (!Element.prototype.matches) {
		    Element.prototype.matches = 
		        Element.prototype.matchesSelector || 
		        Element.prototype.mozMatchesSelector ||
		        Element.prototype.msMatchesSelector || 
		        Element.prototype.oMatchesSelector || 
		        Element.prototype.webkitMatchesSelector ||
		        function(s) {
		            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
		                i = matches.length;
		            while (--i >= 0 && matches.item(i) !== this) {}
		            return i > -1;            
		        };
		  }
		</script>
		
		<script>
		document.addEventListener( 'wpcf7mailsent', function( event ) {
		    window.dataLayer.push({
		      "event" : "cf7submission",
		      "formId" : event.detail.contactFormId,
		      "response" : event.detail.inputs
		    });
		}); 
		</script>

		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-M5CWVCT');</script>
		<!-- End Google Tag Manager -->

    	<!-- Don't pull info from other directories -->
    	<meta name="robots" content="noodp,noydir" />
    	
    	<!-- Adds sites icon/favicon variations -->
    	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=yyaKqNzW8A">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=yyaKqNzW8A">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=yyaKqNzW8A">
		<link rel="manifest" href="/site.webmanifest?v=yyaKqNzW8A">
		<link rel="mask-icon" href="/safari-pinned-tab.svg?v=yyaKqNzW8A" color="#000000">
		<link rel="shortcut icon" href="/favicon.ico?v=yyaKqNzW8A">
		<meta name="msapplication-TileColor" content="#ffc40d">
		<meta name="theme-color" content="#000000">
		
		<!-- Google Search Console verification -->
		<meta name="google-site-verification" content="ExiAamnSUHjenQe5jyNZbyCUzl-XtrzafBEw3pPCXQE" /> <!-- HTTP -->
		<meta name="google-site-verification" content="ZdA1vuifZBXKDbLV6Bn4EwMUl1lWZnMaDCTnlcCtVA8" /> <!-- HTTPS -->
		
		<!-- Bing/Yahoo site verification -->
		<meta name="msvalidate.01" content="FB53D7A3D5F8668EBD90388283F079AD" />
		
		<!-- Google fonts -->
		<script>
		   WebFontConfig = {
		      google: { 
			      families: ['Raleway:300,400,400italic,500,600,700', 'Satisfy:400', 'Roboto:300,300italic,400,400italic,500,500italic,600,600italic,700,700italic']
			  }
		   };
		
		   (function(d) {
		      var wf = d.createElement('script'), s = d.scripts[0];
		      wf.src = '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
		      wf.async = true;
		      s.parentNode.insertBefore(wf, s);
		   })(document);
		</script>


    <?php
	}
	
	public function add_footer_scipts() {}
	
	public function add_options_pages() {
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page(array(
				'page_title' 	=> 'General Settings',
				'menu_title'	=> $this->name . ' Settings',
				'menu_slug' 	=> $this->prefix . 'general-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			));
		}
	}
	
	public function load_widgets() {
		if ( function_exists( 'register_widget' ) ) {
			foreach ( $this->widgets as $widget ) {
				register_widget( $this->prefix . '_' . $widget['name'] );				
			}
		}
	}

	public function add_image_sizes() {
		if ( function_exists( 'add_image_size' ) ) {
			foreach ( $this->images as $image ) {
				add_image_size( $this->prefix . '-' . $image['name'], $image['width'], $image['height'], $image['crop'] );				
			}
		}
	}
	
	public function remove_image_sizes() {
		if ( function_exists( 'remove_image_size' ) ) {
			remove_image_size( 'entry' );
			remove_image_size( 'entry-cropped' );
			remove_image_size( 'entry-fullwidth' );
			remove_image_size( 'entry-cropped-fullwidth' );
			remove_image_size( 'medium_large' );
			remove_image_size( 'portfolio-square' );
			remove_image_size( 'portfolio-landscape' );
			remove_image_size( 'portfolio-portrait' );
			remove_image_size( 'portfolio_masonry_with_space' );
			remove_image_size( 'portfolio_masonry_large' );
			remove_image_size( 'portfolio_masonry_tall' );
			remove_image_size( 'portfolio_masonry_wide' );
			remove_image_size( 'blog_image_format_link_quote' );
		}
	}

	public function set_image_sizes( $sizes ) {
		$addsizes = array(
					$this->prefix . '-attachment-img' => $this->name . ' Attachment Size',
		);
		
		$newsizes = array_merge( $sizes, $addsizes );
		return $newsizes;
	}

	public function add_mime_types($mimes){
		$addmimes = array (
			'svg' => 'image/svg+xml',
		);
		
		$newmimes = array_merge( $mimes, $addmimes );
		return $newmimes;
	}

	public function register_menus() {
	  register_nav_menu( 'top_menu', __( 'Top Menu', '__x__' ) );
	}

}

$theme_starter = new MobThemeStarter;

?>