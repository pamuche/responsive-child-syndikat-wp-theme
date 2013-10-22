<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 *
 * Syndikat spezifische Funktionen
 *
 */

// // Wir benutzen Projekte als custom-post-type um Projekte und Inis abzubilden
// add_action('init', 'create_projekte_custom_post_type');
// function create_projekte_custom_post_type() {
// 	register_post_type('projekte', 
// 			array(
// 			'labels' => array(
// 				'name' => __( 'Projekte' ),
// 				'singular_name' => __( 'Projekt' )
// 			),
// 			'public' => true,
// 			'show_ui' => true,
// 			'show_in_menu' => true,
// 			'capability_type' => 'post',
// 			'hierarchical' => false,
// 			'rewrite' => array('slug' => 'projekte', 'with_front' => '1'),
// 			'query_var' => true,
// 			'exclude_from_search' => false,
// 			'menu_position' => 20,
// 			'supports' => array('title','editor','revisions','thumbnail'),
// 			'labels' => array (
// 				'name' => 'Projekte',
// 				'singular_name' => 'Projekt')
// 	) );
// }

// Wir benutzen Beitragsbilder für Projekte und benötigen in der Listenansicht eine andere Größe
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'projekt-liste', 80, 80 );
}


// Tabellen auf der Projektseite
function projekt_data_table($fields_to_show, $table_head) {
	echo "<table class='projekt-data-table'>";
	
	if( $table_head ) :
		echo "<thead>
				<tr>
					<th colspan='2'>$table_head</th>
				</tr>
			</thead>";
	endif;

	foreach( $fields_to_show as $field ) :
		$field_value = get_field($field['name']);
		if( $field_value ):
			$label = $field['label'];
			
// 			TODO: Add case for email (antispambot wp function) and date fields
			echo "<tr><td>$label</td><td>$field_value</td></tr>";
			
		endif;
	endforeach;

	echo "</table>";
}

// Projektkurzbeschreibung auf der Projekteliste
function output_fields_as_sentence($fields_to_show) {
	foreach( $fields_to_show as $field_name ) {
		$field = get_field_object($field_name);
		$field_value = $field['value'];
		if( $field_value ){
			$label = $field['label'];
			
// 			TODO: Add case for email (antispambot wp function) and date fields
			echo "$label: $field_value, ";
			
		}
	}
}

function map_markers_for($syndikats_projekte) {

	$syndikats_orte = count_projekte_per_place($syndikats_projekte);
	
	$map_marker = array();
	foreach ( $syndikats_orte as $ort => $daten ) {
		$anzahl_projekte_im_ort = $daten['count'];
		$lat_average = $daten['lat'] / $anzahl_projekte_im_ort;
		$lng_average = $daten['lng'] / $anzahl_projekte_im_ort;
		
		$map_marker[] = array( 'name' => projekte_in_sentence($ort, $anzahl_projekte_im_ort),
				'latLng' => array($lat_average, $lng_average),
				'count' => $anzahl_projekte_im_ort,
				'r' => $anzahl_projekte_im_ort);
	}
	
	return $map_marker;
}

function projekte_in_sentence($place, $count) {
	$projekte_in = ($count == 1) ? ' Projekt in ' : ' Projekte in ';
	$sentence = $count.$projekte_in.$place.'.';
	return $sentence;
}

function count_projekte_per_place($syndikats_projekte, $place_type = 'ort') {
	$syndikats_orte = array();
	foreach ( $syndikats_projekte as $projekt) {
		$ort = $projekt[$place_type];
		$ort_already_exists = isset( $syndikats_orte[$ort] );
		if ( $ort_already_exists  ) {
			$syndikats_orte[$ort]['count'] += 1;
			$syndikats_orte[$ort]['lat'] += $projekt['lat'];
			$syndikats_orte[$ort]['lng'] += $projekt['lng'];
		} else {
			$syndikats_orte[$ort] = array('count' => 1, 'lat' => $projekt['lat'], 'lng' => $projekt['lng']);
		}
	}
	ksort($syndikats_orte);
	return $syndikats_orte;
}


// Query params um nur bestimmt Orte/Länder an zeigen zu können.

add_filter('query_vars', 'projekt_liste_queryvars' );

function projekt_liste_queryvars( $qvars )
{
  $qvars[] = 'ort';
  $qvars[] = 'land';
  return $qvars;
}






?>