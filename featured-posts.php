<?php
/**
 * The template for displaying the featured posts
 *
 * @package WordPress
 * @subpackage Adeline
 * @since Adeline 1.0
 */

?>
<?php 
$featured = new WP_Query(
	array(
		'tag_slug__in'        => get_theme_mod('featured_posts_tags', 'featured'),
		'posts_per_page'      => 4,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true
	)
);

if ( $featured->have_posts() ) :
	$featured->the_post();
	?>
	<section id="featured-posts" class="site-featured-posts <?php echo esc_attr( get_theme_mod( 'featured_posts_type', 'carousel' ) ); ?>" >
		<div class="featured-background">
			<?php the_post_thumbnail( 'full', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
		</div>
		<div class="featured-wrapper">
			<div class="featured-post featured-big-post">
					<div class="entry-meta">
						<?php adeline_entry_meta(); ?>
					</div><!-- .entry-meta -->

					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
			<?php
			/* If still have another posts display as lists */ 
			if ( $featured->have_posts() ) : ?>
				<h2 class="more-featured-title">
					<?php _e( 'More Featured Posts', 'adeline' ); ?>
				</h2>
				<div class="more-featured-posts">
				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
					<div class="featured-post featured-small-post">
							<div class="entry-meta">
								<?php //adeline_entry_meta(); ?>
							</div><!-- .entry-meta -->

							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</div>
				<?php endwhile; ?>
				</div>
			<?php	
			endif;
			wp_reset_postdata();
			?>
		</div>
	</section>

<?php
endif; 
?>