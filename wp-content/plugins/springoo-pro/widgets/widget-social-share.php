<?php
/**
 * Widget: Widget Social Share
 */


class Springoo_Widgets_social_share extends WP_Widget {


    function __construct()
    {
        parent::__construct('springoo_widget_social_share', __('springoo Social Share', 'cworld'), array(
            'description' => __('Display social share with icons', 'cworld'),
        ));
    }

    public function widget($args, $instance)
    {

        $title          = apply_filters('widget_title', $instance['title']);

        $ss_facebook    = isset($instance['ss_facebook']) ? esc_url( $instance['ss_facebook'] ) : '';
        $ss_gplus       = isset($instance['ss_gplus']) ? esc_url( $instance['ss_gplus'] ) : '';
        $ss_instagram   = isset($instance['ss_instagram']) ? esc_url( $instance['ss_instagram'] ) : '';
        $ss_linkedin    = isset($instance['ss_linkedin']) ? esc_url( $instance['ss_linkedin'] ) : '';
        $ss_twitter     = isset($instance['ss_twitter']) ? esc_url( $instance['ss_twitter'] ) : '';

        echo $args['before_widget'];

        if( ! empty( $title) ) echo $args['before_title'] . $title . $args['after_title'];

        echo '<ul>';

        if( ! empty( $ss_facebook ) ) printf( '<li><a href="%s"><i class="ti-facebook"></i></a></li>', $ss_facebook);
        if( ! empty( $ss_gplus ) ) printf( '<li><a href="%s"><i class="ti-google"></i></a></li>', $ss_gplus);
        if( ! empty( $ss_instagram ) ) printf( '<li><a href="%s"><i class="ti-instagram"></i></a></li>', $ss_instagram);
        if( ! empty( $ss_linkedin ) ) printf( '<li><a href="%s"><i class="ti-linkedin"></i></a></li>', $ss_linkedin);
        if( ! empty( $ss_twitter ) ) printf( '<li><a href="%s"><i class="ti-twitter"></i></a></li>', $ss_twitter);

        echo '</ul>';


        echo $args['after_widget'];
    }

    public function form($instance)
    {

        $title          = isset($instance['title']) ? $instance['title'] : __('Social icons', 'cworld');
        $ss_facebook    = isset($instance['ss_facebook']) ? $instance['ss_facebook'] : '';
        $ss_gplus       = isset($instance['ss_gplus']) ? $instance['ss_gplus'] : '';
        $ss_instagram   = isset($instance['ss_instagram']) ? $instance['ss_instagram'] : '';
        $ss_linkedin    = isset($instance['ss_linkedin']) ? $instance['ss_linkedin'] : '';
        $ss_twitter     = isset($instance['ss_twitter']) ? $instance['ss_twitter'] : '';

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ss_facebook'); ?>">
                <?php _e('Facebook URL', 'cworld'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('ss_facebook'); ?>"
                   name="<?php echo $this->get_field_name('ss_facebook'); ?>" type="text"
                   value="<?php echo esc_attr($ss_facebook); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ss_gplus'); ?>">
                <?php _e('Google Plus URL', 'cworld'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('ss_gplus'); ?>"
                   name="<?php echo $this->get_field_name('ss_gplus'); ?>" type="text"
                   value="<?php echo esc_attr($ss_gplus); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ss_instagram'); ?>">
                <?php _e('Instagram URL', 'cworld'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('ss_instagram'); ?>"
                   name="<?php echo $this->get_field_name('ss_instagram'); ?>" type="text"
                   value="<?php echo esc_attr($ss_instagram); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ss_linkedin'); ?>">
                <?php _e('LinkedIn URL', 'cworld'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('ss_linkedin'); ?>"
                   name="<?php echo $this->get_field_name('ss_linkedin'); ?>" type="text"
                   value="<?php echo esc_attr($ss_linkedin); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ss_twitter'); ?>">
                <?php _e('Twitter URL', 'cworld'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('ss_twitter'); ?>"
                   name="<?php echo $this->get_field_name('ss_twitter'); ?>" type="text"
                   value="<?php echo esc_attr($ss_twitter); ?>"/>
        </p>

        <?php


    }

    public function update($new_instance, $old_instance)
    {
        $instance   = array();

        $instance['title']          = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['ss_facebook']    = (!empty($new_instance['ss_facebook'])) ? strip_tags($new_instance['ss_facebook']) : '';
        $instance['ss_gplus']       = (!empty($new_instance['ss_gplus'])) ? strip_tags($new_instance['ss_gplus']) : '';
        $instance['ss_instagram']   = (!empty($new_instance['ss_instagram'])) ? strip_tags($new_instance['ss_instagram']) : '';
        $instance['ss_linkedin']    = (!empty($new_instance['ss_linkedin'])) ? strip_tags($new_instance['ss_linkedin']) : '';
        $instance['ss_twitter']     = (!empty($new_instance['ss_twitter'])) ? strip_tags($new_instance['ss_twitter']) : '';

        return $instance;
    }
}
