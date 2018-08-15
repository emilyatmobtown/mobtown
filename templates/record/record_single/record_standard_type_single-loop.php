<?php 
	
if( is_singular( 'record' ) ) { ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post_content_holder">								
        <div class="post_text">
            <div class="post_text_inner">	
				<?php $content = apply_filters( 'the_content', get_the_content() ); ?>
				<?php mob_show_record_content( $content ); ?>
            </div>
        </div>
	</div>
</article>

<?php 

} else {
	
	echo mob_get_record( get_the_ID() );

} 
?>
