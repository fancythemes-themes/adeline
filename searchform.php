<?php
/**
 * Template for displaying search forms in Adeline
 *
 * 
 * @package Adeline
 * @since Adeline 1.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'adeline' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here', 'placeholder', 'adeline' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit"><?php echo adeline_svg_icon('search'); ?><span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'adeline' ); ?></span></button>
</form>
