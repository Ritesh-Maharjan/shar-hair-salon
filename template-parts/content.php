<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BDW_Massage
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if (is_singular()):
			the_title('<h1 class="entrytitle-">', '</h1>');
		endif;
		?>
	</header><!-- .entry-header -->

	<!-- blog image thumbnail -->
	<?php if ( is_home() && has_post_thumbnail() ) : ?>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>" aria-hidden="true">
            <?php the_post_thumbnail( 'thumbnail', array('class' => 'card-shadow') ); ?>
        </a>
		<?php if (is_home()): ?>
			<h2 class="entry-title"><?php the_title(); ?></h2>
		<?php endif; ?>
    </div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		if (is_single()) {
			the_content();
		} else {
			the_excerpt();
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'shar-hair-salon'),
				'after' => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<!-- <footer class="entry-footer"> -->

</article><!-- #post-<?php the_ID(); ?> -->
