<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * 
 * @package Adeline
 * @since Adeline 1.0
 */

get_header(); ?>
	<?php //if ( get_theme_mod('featured_posts_on', false ) ) get_template_part('featured-posts'); ?>
	<?php if ( is_active_sidebar( 'header-widget-full-width' )  ) : ?>
		<div class="header-widget widget-area-full" >
			<?php dynamic_sidebar( 'header-widget-full-width' ); ?>
		</div><!-- .header-full .widget-area -->
	<?php endif; ?>

	<?php $list_class = get_theme_mod( 'blog_list_view', 'classic') . '-view'; ?>
	<div id="primary" class="content-area <?php echo esc_attr($list_class); ?>">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				if ( 'classic' == get_theme_mod( 'blog_list_view', 'classic') )
					get_template_part( 'template-parts/content', get_post_format() );
				else
					get_template_part( 'template-parts/content-list', get_post_format() );

			// End the loop.
			endwhile;

			if ( get_theme_mod( 'pagination_load_more', false ) ) {
				//if ( max_num_page() > 1 ) {
					echo '<div class="load-more">';
					next_posts_link( esc_html__( 'Load More', 'adeline' ) );
					echo '</div>';
				//}
			} else {
				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => esc_html__( 'Previous', 'adeline' ),
					'next_text'          => esc_html__( 'Next', 'adeline' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'adeline' ) . ' </span>',
				) );
			}

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
