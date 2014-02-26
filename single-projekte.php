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

				
				<?php // ################################ BEGIN SYNDIKAT CUSTOM STUFF ########################?>
				
                <div class="post-entry">             
                
				<div class="projektBlock alignleft">
				<div class="vcard kontakt">
					<h1 class="fn org"><?php the_title()?></h1>
					<?php if (get_field('gmbh-name')) echo '<span class="nickname">'.get_field('gmbh-name').'</span><br>'; ?>
 					<div class="adr">
 					<?php if (get_field('adresse')) echo '<span class="street-address">'.get_field('adresse').'</span>,<br>'; ?>
 					<?php if (get_field('plz')) echo '<span class="postal-code">'.prettified_field('plz').'</span>'; ?>
 					<?php if (get_field('ort')) echo '<span class="locality">'.get_field('ort').'</span><br>'; ?>
 				    </div>				
 					<?php if (get_field('telefon')) echo '<span class="tel">'.get_field('telefon').'</span><br>'; ?>	     
					<?php if (get_field('email')) 
						echo '<a class="email" href="mailto:'.antispambot(get_field('email')).'">'.antispambot(get_field('email')).'</a><br>'; ?>
 					<?php if (get_field('homepage')) 
						echo '<a class="url" href="'.urlify(get_field('homepage')).'">'.prettify_url(get_field('homepage')).'</a><br>'; ?>

 				</div>
                <?php echo get_the_post_thumbnail(get_the_ID(), 'projekt-view'); ?>
                      
			
				<?php 
								
				$data_group_id = 808;
				$data_fields = apply_filters('acf/field_group/get_fields', array(), $data_group_id);
				
				projekt_data_table($data_fields, "Projektdaten / Zahlen");
				
				?>
			

				</div>
	
				<?php // ################################ END SYNDIKAT CUSTOM STUFF ########################?>
			
				<?php the_content(); ?>
			</div>
			<!-- end of .post-entry -->
             
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
      
</div><!-- end of #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
