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

        $query_args = array(
        		'post_type' => 'projekte',
        		'nopaging' => true,
        		'meta_query' => array()
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
                      <p>
                      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                       <?php the_title ()?>
                      </a>
                      
                      <?php if(get_field('plz') && get_field('ort') && get_field('land')) { 
                      	echo 'in '.get_field('plz').' '.get_field('ort') . ', '.get_field('land').'.'; } ?>
                      </p>
                    </div>
                    <div class='projektDaten'>
                    <?php if(get_field('ist_projektinititative')) : ?>
                    	<p>Leider noch ohne Haus.</p>
                    <?php else : ?>
	                    <p>
	                      <?php output_fields_as_sentence(array('kauf', 'grundstuck', 'gewerbeflache', 
	                      					'wohnflache', 'personen', 'kosten', 'miete'))?>
	                    </p>
                    <?php endif; ?>
                    </div>
                    <?php //the_excerpt(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php // get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
				
				<?php
					//Kartendaten bereitstellen
// 					array_push($syndikats-orte, array('Leipzig', '51.3288', '12.371'));
					$ort = get_field('ort');
					$gps = explode(',', get_field('gps'));
					$lng = (float) $gps[0];
					$lat = (float) $gps[1];
					if ( is_string($ort) && ($ort !== '') && is_float($lat) && is_float($lng) ) :
						//$syndikats_orte[] = array('name' => 'Leipzig', 'latLng' => array(51.3288, 12.371));
						$syndikats_projekte[] = array('ort' => $ort, 'lat' => $lat, 'lng' => $lng);
					endif;
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
        </div>
        <?php 
      //Projekte per Ort zählen
      $syndikats_orte = array();
      foreach ( $syndikats_projekte as $projekt) {
      	$ort = $projekt['ort'];
      	$ort_already_exists = isset( $syndikats_orte[$ort] );
      	if ( $ort_already_exists  ) {
      		$syndikats_orte[$ort]['count'] += 1;
      		$syndikats_orte[$ort]['lat'] += $projekt['lat'];
      		$syndikats_orte[$ort]['lng'] += $projekt['lng'];
      	} else {
      		$syndikats_orte[$ort] = array('count' => 1, 'lat' => $projekt['lat'], 'lng' => $projekt['lng']);
      	}
      }
      
      $map_marker = array();
      foreach ( $syndikats_orte as $ort => $daten ) {
      	$anzahl_projekte_im_ort = $daten['count'];
      	$lat_average = $daten['lat'] / $anzahl_projekte_im_ort;
      	$lng_average = $daten['lng'] / $anzahl_projekte_im_ort;
      	$projekte_in = ($anzahl_projekte_im_ort == 1) ? ' Projekt in ' : ' Projekte in ';
      	$name = $daten['count'].$projekte_in.$ort;
      	$map_marker[] = array( 'name' => $name, 
      			'latLng' => array($lat_average, $lng_average), 
      			'count' => $anzahl_projekte_im_ort,
      			'r' => $anzahl_projekte_im_ort);
      }
        
      //Marker auf Karte hinzufügen
//       $syndikats_projekte[] = array('ort' => $ort, 'latLng' => array($lat, $lng));
        wp_localize_script( 'jvectormap-map_config', 'syndikats_orte', $map_marker );
        
      //javascript:
//         map = $('#map').vectorMap('get', 'mapObject');
//         markers als js variable
//         map.addMarkers(markers)
        
        ?>
<?php get_footer(); ?>
