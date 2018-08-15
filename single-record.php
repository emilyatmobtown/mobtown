<?php
/** 	
 *	Summary. Template for Single Record CPT
 *	
 * 	Description. Outputs layout and content for single record page
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	

$id = get_the_ID();

$default_array = array('default', '');

?>
<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
		<?php if(get_post_meta($id, "eltd_page_scroll_amount_for_sticky", true)) { ?>
			<script>
			var page_scroll_amount_for_sticky = <?php echo esc_attr(get_post_meta($id, "eltd_page_scroll_amount_for_sticky", true)); ?>;
			</script>
		<?php } ?>

		<?php get_template_part( 'title' ); ?>
		
		<div class="container"<?php eltd_inline_style($background_color); ?>>
			<div class="container_inner default_template_holder" <?php eltd_inline_style($content_style); ?>>
				<div class="two_columns_33_66 background_color_sidebar grid2 clearfix">
					<div class="column1">
						<div class="column_inner">
							<aside class="mob-sidebar mob-single-record-sidebar">
								<?php dynamic_sidebar( 'mob_single_record_sidebar' ); ?>
							</aside>
						</div>
					</div>
					<div class="column2 content_right_from_sidebar">
						<div class="column_inner">
							<div class="blog_holder blog_single blog_standard_type">
								<main class="mob-main mob-single-record-main">
									<?php get_template_part('templates/record/record_single', 'loop'); ?>
								</main>
							</div>
						</div>
					</div>
				</div>
				<div class="wpb_single_image wpb_content_element vc_align_center mob-separator mob-med-separator">
					<div class="vc_single_image-wrapper vc_box_border_grey">
						<img width="1" height="1" src="<?php echo get_stylesheet_directory_uri() ?>/img/icons/svg/icon-headphones-sm.svg" class="vc_single_image-img attachment-medium jetpack-lazy-image--handled" alt="" data-lazy-loaded="1" scale="0">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endwhile; ?>
<?php endif; ?>	


<?php get_footer(); ?>	