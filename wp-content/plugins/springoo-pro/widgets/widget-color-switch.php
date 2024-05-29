<?php

class Posterlaab_Color_Attribute_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'color_attribute_widget',
			__( 'Color Attribute Filter', 'springoo-pro' ),
			array( 'description' => __( 'A widget to filter products by color attribute', 'springoo-pro' ), )
		);
	}

	public function widget( $args, $instance ) {
        if ( ! taxonomy_exists( 'pa_color' ) ) {
            return;
        }
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo '<ul class="color-attribute-filter">';
		$terms = get_terms( array(
			'taxonomy' => 'pa_color',
			'hide_empty' => true,
		) );
		foreach ( $terms as $term ) {
			$slug = $term->slug;
			$name = $term->name;
			$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
			$color_filter_url = add_query_arg( 'filter_color', $slug, $shop_page_url );
            $color = get_term_meta( $term->term_id, 'color', true );
            $count = $term->count;
			echo '<li><a href="' . esc_url( $color_filter_url ) . '" ><span class="color-attribute-box" style="background-color: ' . $color . '; border-color: ' . $color . '"></span><span class="color-attribute-filter-name">'. $name .'</span><span class="color-attribute-filter-count">('. $count .')</span></a></li>';
		}
		echo '</ul>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'springoo-pro' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
