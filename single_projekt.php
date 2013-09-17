<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Single Posts Template
 *
 *
 * @file           single.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/single.php
 * @link           http://codex.wordpress.org/Theme_Development#Single_Post_.28single.php.29
 * @since          available since Release 1.0
 */

get_header(); ?>

<div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">
        
	<?php get_template_part( 'loop-header' ); ?>
        
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>       
				<?php responsive_entry_top(); ?>

                <?php get_template_part( 'post-meta' ); ?>

                <div class="post-entry">

                
                
				<?php // ################################ BEGIN SYNDIKAT CUSTOM STUFF ########################?>
				<?php 
				$groupId = 807;
				$fields = apply_filters('acf/field_group/get_fields', array(), $groupId);
				?>
	
	
				<?php if( $fields ): ?>
				<div class="alignleft"
					style="width: 220px; height: 160px; background-color: #dbe6f4; padding: 10px;"
					align="left">
					<strong>Adressdaten / Kontakt:</strong>
					<?php foreach( $fields as $field ): ?>
					<?php
					$value = get_field($field['name']);
	
	
					?>
					<?php if( $value ): ?>
					<?php echo $field['label']; ?>
					:
					<?php echo $value; ?>
					<?php endif; ?>
	
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
	
	
				<?php 
				$groupId = 808;
				$fields = apply_filters('acf/field_group/get_fields', array(), $groupId);
				?>
	
	
				<?php if( $fields ): ?>
				<div class="alignleft"
					style="width: 220px; height: 160px; background-color: #dbe6f4; padding: 10px;"
					align="left">
					<strong>Projektdaten / Zahlen:</strong>
					<?php foreach( $fields as $field ): ?>
					<?php
					$value = get_field($field['name']);
	
	
					?>
					<?php if( $value ): ?>
					<?php echo $field['label']; ?>
					:
					<?php echo $value; ?>
					<?php endif; ?>
	
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
	
	
	
				<div style="clear: both;"></div>
	
				<?php // ################################ END SYNDIKAT CUSTOM STUFF ########################?>

			
			
				<?php the_content(__('Read more &#8250;', 'responsive')); ?>

				<?php if ( get_the_author_meta('description') != '' ) : ?>

				<div id="author-meta">
					<?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); }?>
					<div class="about-author">
						<?php _e('About','responsive'); ?>
						<?php the_author_posts_link(); ?>
					</div>
					<p>
						<?php the_author_meta('description') ?>
					</p>
				</div>
				<!-- end of #author-meta -->

				<?php endif; // no description, no author's meta ?>

				<?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
			</div>
			<!-- end of .post-entry -->
                
                <div class="navigation">
			        <div class="previous"><?php previous_post_link( '&#8249; %link' ); ?></div>
                    <div class="next"><?php next_post_link( '%link &#8250;' ); ?></div>
		        </div><!-- end of .navigation -->
                
                <?php get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
			</div><!-- end of #post-<?php the_ID(); ?> -->       
			<?php responsive_entry_after(); ?>            
            
			<?php responsive_comments_before(); ?>
			<?php comments_template( '', true ); ?>
			<?php responsive_comments_after(); ?>
            
        <?php 
		endwhile; 

		get_template_part( 'loop-nav' ); 

	else : 

		get_template_part( 'loop-no-posts' ); 

	endif; 
	?>  
      
</div><!-- end of #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
