<?php

defined( 'ABSPATH' ) or die( 'Keep Silent' );

define( 'SPRINGOO_PRO_WIDGET_ATTR_DIR', dirname( __FILE__ ) );
define( 'SPRINGOO_PRO_WIDGET_ATTR_RELATIVE_PATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'SPRINGOO_PRO_WIDGET_ATTR_URL', plugins_url( basename( dirname( __FILE__ ) ), dirname( __FILE__ ) ) );


class Springoo_Widget_Attrs {

	public function __construct( $sidebars = array(), $fields = array() ) {

		add_action( 'init', array( $this, 'setup' ) );
	}

	public function setup() {

		add_action( 'in_widget_form', array( $this, 'form' ), 10, 3 );
		add_filter( 'widget_update_callback', array( $this, 'save_update' ), 10, 2 );
		add_filter( 'dynamic_sidebar_params', array( $this, 'display' ) );
	}


	public function form( $widget, $return, $instance ) {

		$grid  = isset( $instance['springoo_bs_grid_class'] ) ? $instance['springoo_bs_grid_class'] : '';
		$clear = isset( $instance['springoo_bs_grid_clear'] ) ? $instance['springoo_bs_grid_clear'] : '';

		ob_start();
		?>
		<p>
			<label for="<?php echo $widget->get_field_id( 'springoo_bs_grid_class' ) ?>"><?php _e( 'Bootstrap Grid Class:', 'springoo-pro' ) ?>
				<input class="widefat" value="<?php echo $grid ?>" id="<?php echo $widget->get_field_id( 'springoo_bs_grid_class' ) ?>" name="<?php echo $widget->get_field_name( 'springoo_bs_grid_class' ) ?>">
				<br/>
				<small><?php _e( 'Twitter Bootstrap grid class name. like: col-md-3, col-sm-4 etc. You can add multiple class name separate by space.', 'springoo-pro' ) ?></small>
			</label>
		</p>


		<p>
			<input value="1" id="<?php echo $widget->get_field_id( 'springoo_bs_grid_clear' ) ?>" name="<?php echo $widget->get_field_name( 'springoo_bs_grid_clear' ) ?>" type="checkbox" <?php checked( '1', $clear ) ?>>
			<label for="<?php echo $widget->get_field_id( 'springoo_bs_grid_clear' ) ?>"><?php _e( 'Clear grid column', 'springoo-pro' ) ?></label>
			<br/>
			<small><?php _e( 'This will add a clearfix class to clearfix bootstrap grid column.', 'springoo-pro' ) ?></small>
		</p>

		<?php

		do_action( 'springoo-pro-widget-attr-form', $widget, $return, $instance );

		echo ob_get_clean();
	}


	public function save_update( $instance, $new_instance ) {
		$instance['springoo_bs_grid_class'] = $new_instance['springoo_bs_grid_class'];
		$instance['springoo_bs_grid_clear'] = $new_instance['springoo_bs_grid_clear'];

		return apply_filters( 'springoo-pro-widget-attr-save', $instance, $new_instance );
	}


	public function display( $params ) {

		global $wp_registered_widgets;

		$sidebar_id   = $params[0]['id'];
		$sidebar_name = $params[0]['name'];

		$widget_id        = $params[0]['widget_id'];
		$widget_obj       = $wp_registered_widgets[ $widget_id ];
		$widget_opt       = get_option( $widget_obj['callback'][0]->option_name );
		$widget_num       = $widget_obj['params'][0]['number'];
		$grid_class       = isset( $widget_opt[ $widget_num ]['springoo_bs_grid_class'] ) ? $widget_opt[ $widget_num ]['springoo_bs_grid_class'] : '';
		$grid_clear_class = isset( $widget_opt[ $widget_num ]['springoo_bs_grid_clear'] ) ? $widget_opt[ $widget_num ]['springoo_bs_grid_clear'] : '';


		if ( preg_match( '/class="/', $params[0]['before_widget'] ) ) {

			$default_grid_classes = apply_filters( 'pxlr_widget_grid_class_to_remove', array() );

			if ( $grid_class ) {
				$params[0]['before_widget'] = str_ireplace( $default_grid_classes, '', $params[0]['before_widget'] );
			}
			$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$grid_class} ", $params[0]['before_widget'], 1 );
		} else {
			$params[0]['before_widget'] = preg_replace( '/(\<[a-zA-Z]+)(.*?)(\>)/', "$1 $2 class=\"{$grid_class}\" $3", $params[0]['before_widget'], 1 );
		}

		if ( ! empty( $grid_clear_class ) ) {
			$params[0]['after_widget'] = $params[0]['after_widget'] . '<div class="break" style="width: 100%"></div>';
		}

		return apply_filters( 'springoo-pro-widget-attr-display-params', $params );
	}
}

$Springoo_Widget_Attrs = new Springoo_Widget_Attrs();
