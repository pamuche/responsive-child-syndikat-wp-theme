<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


wp_deregister_script( 'jquery' );
wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, false );

wp_enqueue_style( 'jvectormap-style', get_stylesheet_directory_uri() . '/js/jquery-jvectormap.css', array(), false, 'all' );
wp_enqueue_script( 'jvectormap-script', get_stylesheet_directory_uri() . '/js/jquery-jvectormap.min.js', array( 'jquery' ), false, true );
wp_enqueue_script( 'jvectormap-map', get_stylesheet_directory_uri() . '/js/jquery-jvectormap-de-merc-en.js', array( 'jquery' ), false, true );
wp_enqueue_script( 'jvectormap-map_config', get_stylesheet_directory_uri() . '/js/map_config.js', array( 'jquery' ), false, true );



get_header(); ?>

<!-- div id="content-archive" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>" -->
<div id="content-archive" class="grid col-540">

	<?php if (have_posts()) : ?>
        
        <?php get_template_part( 'loop-header' ); ?>
           
           
        <?php // ################################ BEGIN SYNDIKAT CUSTOM STUFF ########################?>
			
        <?php 
        $is_projekt_page = $is_projekt_page ? true : false;
        
        $query_args = array(
        		'post_type' => 'projekte',
        		'nopaging' => true,
        		'meta_query' => array(
        				//Gescheiterte Projekte sollen hier erstmal nicht angezeigt werden
        				array('key' => 'ist_gescheitert', 'value' => '0', 'compare' => '=='),
        				array('key' => 'ist_projektinititative', 
        						'value' => ($is_projekt_page ? '0' : '1'), 'compare' => '=='),
        				'relation' => 'AND'
        				),
        		'meta_key'		=> 'plz',
        		'orderby'		=> 'meta_value_num',
        		'order'			=> 'ASC'
        );
        
		// Documentation: http://codex.wordpress.org/Custom_Queries
        if( isset( $wp_query->query_vars['ort'] )) {
        	$query_args['meta_query'][] = array('key' => 'ort', 'value' => $wp_query->query_vars['ort']);
        };
        if( isset( $wp_query->query_vars['land'] )) {
        	$query_args['meta_query'][] = array('key' => 'land', 'value' => $wp_query->query_vars['land']);
        };

        
        
        $loop_projekte = new WP_Query( $query_args );
        $syndikats_projekte = array();
        
        ?>   
                   
        <h1>
        <?php 
        $anzahl_projekte = $loop_projekte->post_count;
        echo anzahl_projekte_ueberschrift($anzahl_projekte, $is_projekt_page);
            
        ?>
        </h1>
        <?php if( isset( $wp_query->query_vars['ort'] ) || isset( $wp_query->query_vars['land'] ) ) : ?>
        <?php $link_to_projekte = get_page_link(); ?>
        <p>
        	<a href="<?php echo $link_to_projekte; ?>">Zurück zu der Gesamtliste</a>
        </p>
		<?php endif; ?>

		
        <?php while ( $loop_projekte->have_posts() ) : $loop_projekte->the_post(); ?>
        

  
			<?php responsive_entry_before(); ?>
			<div id="projekt-<?php the_ID(); ?>" <?php post_class(); ?>>       
				<?php responsive_entry_top(); ?>

                <?php get_template_part( 'content' ); ?>
                
                <div class="projekt-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('projekt-liste', array('class' => 'alignleft')); ?>
                        </a>
                    <?php endif; ?>
                    <div class='projektName'>
                      <p>
                      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                       <?php the_title ()?>
                      </a>
                      
                      <?php if(get_field('plz') && get_field('ort') && get_field('land')) { 
                      	echo 'in '.prettified_field('plz').' '.get_field('ort') . ', '.get_field('land').'.'; } ?>
                      </p>
                    </div>
                    <div class='projektDaten'>
						<p>
	                      <?php //output_fields_as_sentence(array('beschluss', 'kauf', 'grundstuck', 'gewerbeflache', 'wohnflache', 'personen', 'kosten', 'miete'))?>
	                      <?php echo projekt_description() ?>
	                    </p>
                    </div>
                    <?php //the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .projekt-entry -->
                
                <?php // get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
				
				<?php
					//Kartendaten bereitstellen
// 					array_push($syndikats-orte, array('Leipzig', '51.3288', '12.371'));
					$ort = get_field('ort');
					$land = get_field('land');
					$gps = explode(',', get_field('gps'));
					$lng = (float) $gps[0];
					$lat = (float) $gps[1];
					if ( is_string($ort) && is_string($land) && ($ort !== '') && is_float($lat) && is_float($lng) ) {
						$syndikats_projekte[] = array('ort' => $ort, 'land' => $land, 'lat' => $lat, 'lng' => $lng);
					}
				?>
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

					<?php $orte = count_projekte_per_place($syndikats_projekte) ?>
					<?php $laender = count_projekte_per_place($syndikats_projekte, 'land') ?>
					<div id="projekte_filter_links">
						<?php if( isset( $wp_query->query_vars['ort'] ) || isset( $wp_query->query_vars['land'] ) ) : ?>
				        <p>
				        	<a href="<?php echo $link_to_projekte ?>">Zurück zu der Gesamtliste</a>
				        </p>
						<?php else : ?>
							<ul>
								<?php foreach( $laender as $land => $daten ) : ?>
								<li><a href="<?php echo get_post_type_archive_link( 'Projekte' ).'?land='.$land;?>"> <?php echo projekte_in_sentence($land, $daten['count']);?>
								</a>
								</li>
								<?php endforeach; ?>
							</ul>
							<ul>
								<?php foreach( $orte as $ort => $daten ) : ?>
								<li><a href="<?php echo get_post_type_archive_link( 'Projekte' ).'?ort='.$ort;?>"> <?php echo projekte_in_sentence($ort, $daten['count']);?>
								</a>
								</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
				
				
					</div>
				</div>
        <?php 
			$map_markers = map_markers_for($syndikats_projekte); 
			wp_localize_script( 'jvectormap-map_config', 'syndikats_orte', $map_markers );
        ?>
<?php get_footer(); ?>
