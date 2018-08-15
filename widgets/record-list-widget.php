<?php 
/** 	
 *	Summary. Widget to output discography. 
 *	
 * 	Description. Extends WP_Widget to create a widget that outputs a discography listing based on admin user's parameters.
 *
 *	@package mobtown
 *	@since 1.0.0 
 */	
 
// Creating the widget 
class mob_record_list_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'mob_record_list_widget', 
		 
		// Widget name will appear in UI
		__('Mobtown Record List Widget', ''), 
		 
		// Widget description
		array( 'description' => __( 'Displays a record list', '' ), ) );
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		
		extract( $args );
		 
        $number_of_posts = ( ! empty( $instance['number_of_posts'] ) ) ? absint( $instance['number_of_posts'] ) : 6;
        $number_of_columns = ( ! empty( $instance['number_of_columns'] ) ) ? absint( $instance['number_of_columns'] ) : 2;
        $image_size = ( ! empty( $instance['image_size'] ) ) ? $instance['image_size'] : '';
        $order_by = isset( $instance['order_by'] ) ? $instance['order_by'] : '';
        $order = isset( $instance['order'] ) ? $instance['order'] : '';
        $term_id = isset( $instance['term_id'] ) ? $instance['term_id'] : '';
        $term = get_term( $term_id );
        $name = $term->name;
	
		echo $args['before_widget'];

		echo do_shortcode( '[mob_record_list project_type="' . $name . ' " number_of_posts="' . $number_of_posts . '" number_of_columns="' . $number_of_columns . '" order_by="' . $order_by . '" order="' . $order . '" image_size="' . $image_size . '"]' );
	
		echo $args['after_widget'];
	}
	
	// Widget back-end
	public function form( $instance ) {
		$defaults = array(
			'number_of_posts'    	=> 6,
			'number_of_columns'     => 2,
			'image_size'			=> '',
			'term_id' 				=> 1,
			'order_by' 				=> '',
			'order'   				=> '',
		);
		
		extract( wp_parse_args( ( array ) $instance, $defaults ) );
        
		?>
		
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php _e( 'Number of records:' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $number_of_posts ); ?>" size="3" />
        </p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number_of_columns' ) ); ?>"><?php _e( 'Number of columns:' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'number_of_columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_columns' ) ); ?>" type="text" value="<?php echo esc_attr( $number_of_columns ); ?>" size="3" />
        </p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php _e( 'Image Size:' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>" type="text" value="<?php echo esc_attr( $image_size ); ?>" size="20" />
        </p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id('term_id') ); ?>"><?php _e( 'Project Type:' )?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('term_id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('term_id') ); ?>">
	            <option value="" <?php selected( '', $term_id ) ?>></option>
                <?php 
                $this->terms = get_terms( array( 'taxonomy' => 'project_type' ) );
                foreach ( $this->terms as $term ) {
                    echo '<option value="' . esc_attr( $term->term_id ) . '" id="' . esc_attr( $term->name ) . '" ' . selected( $term->term_id, $term_id, false ) . '>' . esc_attr( $term->name ) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php 
	        $options = array(
				''		=> __( '', '' ),
				'rand'	=> __( 'Random', '' ),
				'date'	=> __( 'Date', '' ),
				'title'	=> __( 'Title', '' ),
			)?>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>"><?php _e( 'Order By:' )?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>" name="<?php echo esc_attr( $this->get_field_name('order_by') ); ?>">
	            <?php 
		            foreach ( $options as $key => $name ) {
						echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $order_by, $key, false ) . '>'. $name . '</option>';
					} ?>
            </select>
        </p>
        
        <?php 
	        $options = array(
				''		=> __( '', '' ),
				'ASC'	=> __( 'ASC', '' ),
				'DESC'	=> __( 'DESC', '' ),
			)?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id('order') ); ?>"><?php _e( 'Order:' )?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('order') ); ?>" name="<?php echo esc_attr( $this->get_field_name('order') ); ?>">
	            <?php 
		            foreach ( $options as $key => $name ) {
						echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $order, $key, false ) . '>'. $name . '</option>';
					} ?>
            </select>
        </p>
        
        <?php 
	}
	
	// Update widget
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['number_of_posts'] = isset( $new_instance['number_of_posts'] ) ? (int) $new_instance['number_of_posts'] : '';
		$instance['number_of_columns'] = isset( $new_instance['number_of_columns'] ) ? (int) $new_instance['number_of_columns'] : '';
        $instance['image_size'] = isset( $new_instance['image_size'] ) ? wp_strip_all_tags( $new_instance['image_size'] ) : '';
		$instance['term_id'] = isset( $new_instance['term_id'] ) ? (int) $new_instance['term_id'] : '';
        $instance['order_by'] = isset( $new_instance['order_by'] ) ? wp_strip_all_tags( $new_instance['order_by'] ) : '';
        $instance['order'] = isset( $new_instance['order'] ) ? wp_strip_all_tags( $new_instance['order'] ) : '';
        return $instance;
	}
	
} // Class mob_record_list_widget ends here