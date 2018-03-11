<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * 
 * @package Adeline
 * @since Adeline 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="site-footer" role="contentinfo">

			<?php get_sidebar('footer'); ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'adeline' ); ?>">
					<?php
						add_filter( 'walker_nav_menu_start_el', 'adeline_social_menu_item_output', 10, 4 );
						wp_nav_menu( array(
							'theme_location' => 'social',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => adeline_svg_icon('icon_replace') . '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						) );
						remove_filter( 'walker_nav_menu_start_el', 'adeline_social_menu_item_output' );
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>
			<?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
				<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'adeline' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'footer-menu',
							'menu_class'     => 'footer-menu',
						 ) );
					?>
				</nav><!-- .main-navigation -->
			<?php endif; ?>
			<div class="site-info">
				<?php
					/**
					 * Fires before the adeline footer text for footer customization.
					 *
					 * @since Adeline 1.0
					 */
					do_action( 'adeline_credits' );
					adeline_footer_credit(true);
				?>
			</div><!-- .site-info -->
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
