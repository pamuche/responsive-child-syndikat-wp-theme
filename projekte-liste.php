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


wp_deregister_script( 'jquery' );
wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, false );

wp_enqueue_style( 'jvectormap-style', get_stylesheet_directory_uri() . '/js/jquery-jvectormap.css', array(), '20120805', 'all' );
wp_enqueue_script( 'jvectormap-script', get_stylesheet_directory_uri() . '/js/jquery-jvectormap.min.js', array( 'jquery' ), '20120805', true );
wp_enqueue_script( 'jvectormap-map', get_stylesheet_directory_uri() . '/js/jquery-jvectormap-de-merc-en.js', array( 'jquery' ), '20120805', true );
wp_enqueue_script( 'jvectormap-map_code', get_stylesheet_directory_uri() . '/js/map_config.js', array( 'jquery' ), '20120805', true );



get_header(); ?>

<!-- div id="content-archive" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>" -->
<div id="content-archive" class="grid col-540">

	<?php if (have_posts()) : ?>
        
        <?php get_template_part( 'loop-header' ); ?>
           
           
        <?php // ################################ BEGIN SYNDIKAT CUSTOM STUFF ########################?>

        <?php 
        $args = array( 'post_type' => 'projekte' );
        $loop_projekte = new WP_Query( $args );
//         while ( $loop->have_posts() ) : $loop->the_post();
//         the_title();
//         echo '<div class="entry-content">';
//         the_content();
//         echo '</div>';
//         endwhile;
        
        ?>   
                   

                    
        <?php while ( $loop_projekte->have_posts() ) : $loop_projekte->the_post(); ?>
        

  
			<?php responsive_entry_before(); ?>
			<div id="projekt-<?php the_ID(); ?>" <?php post_class(); ?>>       
				<?php responsive_entry_top(); ?>

                <?php //get_template_part( 'post-meta' ); ?>
                
                <div class="projekt-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('projekt-liste', array('class' => 'alignleft')); ?>
                        </a>
                    <?php endif; ?>
                    <div class='projektName'>
                      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                       <?php the_title ()?>
                      </a>
                    </div>
                    <div class='projektDaten'>
                    <p>
                      <?php if(get_field('grundstucksgrosse')) { 
                      	echo get_field('grundstucksgrosse') . ' m² Grundstück, '; } ?>
                      	<?php if(get_field('kaufdatum')) { 
                      	echo 'gekauft am ' . get_field('kaufdatum') . '.'; } ?>
                    </p>
                    </div>
                    <?php //the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php // get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
			</div><!-- end of #projekt-<?php the_ID(); ?> -->       
			<?php responsive_entry_after(); ?>
            
        <?php 
		endwhile; 

		get_template_part( 'loop-nav' ); 

	else : 

		get_template_part( 'loop-no-posts' ); 

	endif; 
	?>  
      
</div><!-- end of #content-archive -->
        <div class='grid col-380 fit'>
                   <div id="map">

                   </div>
        </div>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
