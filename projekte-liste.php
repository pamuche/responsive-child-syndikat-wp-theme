<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Projekte Template
 * Cloned from responsive archive.php Template
 * 
 * 
 * Template Name: Projekte Liste
 */

get_header(); ?>

<div id="content-archive" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">

	<?php if (have_posts()) : ?>
        
        <?php get_template_part( 'loop-header' ); ?>
                    
        <?php while (have_posts()) : the_post(); ?>
        
        <H1>ARCHIVE PROJETKTE WSEITE</H1>
        ARCHIVE PROJETKTE WSEITE
			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>       
				<?php responsive_entry_top(); ?>

                <?php get_template_part( 'post-meta' ); ?>
                
                <div class="post-entry">
                ARCHIVE PROJETKTE WSEITE
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('thumbnail', array('class' => 'alignleft')); ?>
                        </a>
                    <?php endif; ?>
                    <?php the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
			</div><!-- end of #post-<?php the_ID(); ?> -->       
			<?php responsive_entry_after(); ?>
            
        <?php 
		endwhile; 

		get_template_part( 'loop-nav' ); 

	else : 

		get_template_part( 'loop-no-posts' ); 

	endif; 
	?>  
      
</div><!-- end of #content-archive -->
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
