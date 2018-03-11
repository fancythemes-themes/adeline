<?php
/**
 * Widget API: Adeline_Widget_Recent_Posts class
 *
 * @package Adeline
 * @since 1.0.0
 */

/**
 * Core class used to implement a Recent Posts widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Adeline_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget-recent-posts',
			'description' => esc_html__( "Your site&#8217;s most recent Posts.", 'adeline'),
			'customize_selective_refresh' => true,
		 );
		parent::__construct('adeline-recent-posts', esc_html__('Adeline - Recent Posts', 'adeline'), $widget_ops);
		$this->alt_option_name = 'widget_recent_posts';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$sort = isset( $instance['sort'] ) ? $instance['sort'] : 'date';
		$category = isset( $instance['category'] ) ? $instance['category'] : '';
		$tags = !empty( $instance['tags'] ) ? explode(',', $instance['tags']) : false;
		$animation = ( isset($instance['animation']) && in_array( $instance['animation'], array( 'fade', 'horizontal', 'vertical') ) ) ? $instance['animation'] : 'horizontal';


		$query_args = array(
			'posts_per_page'		=> $number,
			'cat'					=> $category,
			'tag_slug__in'			=> $tags,
			'no_found_rows'			=> true,
			'post_status'			=> 'publish',
			'orderby'				=> $sort,
			'ignore_sticky_posts'	=> true
		);

		$presented = isset( $instance['presentation'] ) ? $instance['presentation'] : 'thumbnail';

		$slider_opt = json_encode ( array (
			'slideshow'			=> ( isset($instance['slideshow']) && $instance['slideshow'] == 'slideshow' ) ? true : false,
			'slideshow_time'	=> isset( $instance['slideshow_time'] ) ? $instance['slideshow_time'] * 1000 : 5000,
			'maxItems'			=> $presented == 'image-overlay' ? 3 : 4,
			'itemMargin'		=> $presented == 'image-overlay' ? 0 : 40,
			'animation'			=> ( $animation == 'fade' ) ? 'fade' : 'slide',
			'direction'			=> ( $animation !== 'fade') ? $animation : null,
		) );

		if ( isset($instance['is_slider'] ) && $instance['is_slider'] == 'slider' ) {
			$slider_class = ' posts-slider';
			$is_slider = true;
			$attr_slider_opt = 'data-slider-options="' . esc_attr($slider_opt) . '"';
			$title = empty( $title ) ? '&nbsp' : $title;
		} else {
			$slider_class = '';
			$is_slider = false;
			$attr_slider_opt = '';
		}

		$featured = new WP_Query( $query_args ); 

		if ( $featured->have_posts() ) {

		//if ( $presented == '')
			echo $args['before_widget'];
			if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
			$classes = $presented . '-view' . $slider_class . ' sort-' . $sort;
			echo '<div class="' . esc_attr($classes) . '" ';
			echo $attr_slider_opt;
			echo '>';
			if ( $is_slider ) echo '<div class="slides">';
			while ( $featured->have_posts() ) : 
				$featured->the_post();
				if ( $presented == 'small-thumbnail')
					get_template_part( 'template-parts/content-list-small', get_post_format() );
				else
					get_template_part( 'template-parts/content-list', get_post_format() );
			endwhile;
			wp_reset_postdata();
			if ( $is_slider ) echo '</div>';
			echo '</div>';
			echo $args['after_widget'];

		}

	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']			= sanitize_text_field( $new_instance['title'] );
		$instance['number']			= absint( $new_instance['number'] );
		$instance['sort']			= $new_instance['sort'];
		$instance['category']		= $new_instance['category'];
		$instance['tags']			= sanitize_text_field( $new_instance['tags'] );
		$instance['presentation']	= sanitize_text_field( $new_instance['presentation'] );
		$instance['slideshow']		= $new_instance['slideshow'];
		$instance['slideshow_time']	= absint( $new_instance['slideshow_time'] );
		$instance['animation']		= $new_instance['animation'];
		$instance['is_slider']		= $new_instance['is_slider'] == 'slider' ? 'slider' : false ;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date		= isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$sort			= isset( $instance['sort'] ) ? esc_attr( $instance['sort'] ) : 'date';
		$category		= isset( $instance['category'] ) ? $instance['category'] : '';
		$tags			= isset( $instance['tags'] ) ? esc_attr( $instance['tags'] ) : '';
		$presentation	= isset( $instance['presentation'] ) ? esc_attr( $instance['presentation'] ) : 'thumbnail';
		$slideshow		= isset( $instance['slideshow'] ) ? (bool) $instance['slideshow'] : false;
		$slideshow_time	= isset( $instance['slideshow_time'] ) ? absint( $instance['slideshow_time'] ) : 5;
		$animation		= isset( $instance['animation'] ) ? esc_attr( $instance['animation'] ) : 'fade';
		$is_slider		= isset( $instance['is_slider'] ) ? esc_attr( $instance['is_slider'] ) : false;

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'adeline' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category:', 'adeline' ); ?></label>
			<?php wp_dropdown_categories( 
					array (
						'show_option_all' => esc_html__('All Categories', 'adeline'),
						'name'            => $this->get_field_name( 'category' ),
						'id'              => $this->get_field_id( 'category' ),
						'selected'        => $category,
						'class'			  => 'widefat',
					) ); 
			?>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php esc_html_e( 'Tags:', 'adeline' ); ?></label>
		<input class="widefat" id="<?php $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" value="<?php echo $tags; ?>" />
		</p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts to show:', 'adeline' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php esc_html_e( 'Sort By:', 'adeline' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'sort' ); ?>" name="<?php echo $this->get_field_name( 'sort' ); ?>" >
				<option value="date" <?php selected( $sort, 'date' ); ?> > <?php esc_html_e('Date', 'adeline'); ?> </option>
				<option value="comment_count" <?php selected( $sort, 'comment_count' ); ?> > <?php esc_html_e('Comments Number', 'adeline'); ?> </option>
			</select>
		</p>
		<p>
			<span><?php esc_html_e('Presented as', 'adeline'); ?></span><br>
			<input type="radio" id="<?php echo $this->get_field_id( 'presentation' ) . '-1'; ?>" name="<?php echo $this->get_field_name( 'presentation' ); ?>" value="thumbnail" <?php checked( $presentation, 'thumbnail'); ?> />
			<label for="<?php echo $this->get_field_id( 'presentation' ) . '-1'; ?>"><?php esc_html_e( 'Large Thumbnail', 'adeline'); ?></label><br>
			
			<input type="radio" id="<?php echo $this->get_field_id( 'presentation' ) . '-2'; ?>" name="<?php echo $this->get_field_name( 'presentation' ); ?>" value="small-thumbnail" <?php checked( $presentation, 'small-thumbnail'); ?>/>
			<label for="<?php echo $this->get_field_id( 'presentation' ) . '-2'; ?>"><?php esc_html_e( 'Small thumbnail', 'adeline'); ?></label><br>
			
			<input type="radio" id="<?php echo $this->get_field_id( 'presentation' ) . '-3'; ?>" name="<?php echo $this->get_field_name( 'presentation' ); ?>" value="image-overlay" class="presentation-featured-opt" <?php checked( $presentation, 'image-overlay'); ?>/>
			<label for="<?php echo $this->get_field_id( 'presentation' ) . '-3'; ?>"><?php esc_html_e( 'Image Overlay', 'adeline'); ?></label><br>
			
			<input type="checkbox" id="<?php echo $this->get_field_id( 'is_slider' ); ?>" name="<?php echo $this->get_field_name( 'is_slider' ); ?>" value="slider" class="is-slider" <?php checked( $is_slider, 'slider'); ?>/>
			<label for="<?php echo $this->get_field_id( 'is_slider' ); ?>"><?php esc_html_e( 'Show as slider', 'adeline'); ?></label><br> 

			<span class="slider-options">
				<input type="checkbox" id="<?php echo $this->get_field_id( 'slideshow' ); ?>" name="<?php echo $this->get_field_name( 'slideshow' ); ?>" value="slideshow" <?php checked( $slideshow, 'slideshow'); ?> /><label for="<?php echo $this->get_field_id( 'slideshow' ); ?>"><?php esc_html_e('Slideshow', 'adeline'); ?></label><br>
				<label for="<?php echo $this->get_field_id( 'slideshow_time' ); ?>"><?php esc_html_e( 'Slideshow time:', 'adeline' ); ?></label><br>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'slideshow_time' ); ?>" name="<?php echo $this->get_field_name( 'slideshow_time' ); ?>" type="number" step="1" min="1" value="<?php echo $slideshow_time; ?>" size="3" /> 
				<?php esc_html_e('in seconds', 'adeline'); ?><br>
				<label for="<?php echo $this->get_field_id( 'animation' ); ?>"><?php esc_html_e( 'Slide Animation:', 'adeline' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'animation' ); ?>" name="<?php echo $this->get_field_name( 'animation' ); ?>" >
					<option value="fade" <?php selected($animation, 'fade'); ?> ><?php esc_html_e('Fade In/Out', 'adeline') ?></option>
					<option value="horizontal" <?php selected($animation, 'horizontal'); ?> ><?php esc_html_e('Slide Horizontal', 'adeline') ?></option>
					<option value="vertical" <?php selected($animation, 'vertical'); ?> ><?php esc_html_e('Slide Vertical', 'adeline') ?></option>
				</select>
			</span>
		</p>
<?php
	}
}

function adeline_widget_recent_posts() {
	return register_widget("Adeline_Widget_Recent_Posts"); 
}
add_action('widgets_init', 'adeline_widget_recent_posts');
