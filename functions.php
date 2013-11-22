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
	add_image_size( 'projekt-liste', 80, 80, true );
	add_image_size( 'projekt-view', 200, 9999);
}


// Tabellen auf der Projektseite
function projekt_data_table($fields_to_show, $table_head) {
	echo "<table class='daten'>";
	
// 	if( $table_head ) :
// 		echo "<thead>
// 				<tr>
// 					<th colspan='2'>$table_head</th>
// 				</tr>
// 			</thead>";
// 	endif;

	foreach( $fields_to_show as $field ) :
		$formatted_value = prettified_field($field['name']);
		if( $formatted_value ):
			$label = $field['label'];
			
			echo "<tr><td align='right'>$label:</td><td>$formatted_value</td></tr>";
			
		endif;
	endforeach;

	echo "</table>";
}



function prettified_field($name) {
	
	$date_output_format = 'd.m.Y';
	$dec_point = ',';
	$thousands_sep = '.';
	
	$value = get_field($name);
	
	if ($value == '') { return $value; }
	
	if (in_array( $name, array('grundstuck', 'wohnflache', 'gewerbeflache') ) ) {
		$formatted_number = number_format( $value , 0 , $dec_point, $thousands_sep);
		return "$formatted_number m²";
	}
	
	if (in_array( $name, array('gmbh-grundung_ohne_syndikat', 'beschluss', 'grundung_mit_syndikat_oder_anteilsabtretung_an_syndikat', 'kauf') ) ) {
		$date = DateTime::createFromFormat('Ymd', $value);
		if ($date) {
			return $date->format($date_output_format);
		}
	}
	
	if (in_array( $name, array('kosten', 'miete', 'solibeitrag') ) ) {
		$formatted_number = number_format( $value , 2 , $dec_point, $thousands_sep);
		return "$formatted_number €";
	}
	
	if ( $name == 'plz' ) {
		return str_pad($value, 5 ,'0', STR_PAD_LEFT);
	}
	
	
	return $value;
// 	http://www.advancedcustomfields.com/resources/field-types/date-picker/
}


function anzahl_projekte_ueberschrift($count, $is_projekt_page) {
	if( $is_projekt_page ) {
		$ueberschrift = ($count == 1) ? "Ein Syndikatsprojekt" : "$count Syndikatsprojekte";
	}
	else {
		$ueberschrift = ($count == 1) ? "Eine Syndikatsinitiative" : "$count Syndikatsinitiativen";
	}
	 
	if( isset( $wp_query->query_vars['ort'] )) {
		$ueberschrift = $ueberschrift.' in '.$wp_query->query_vars['ort'];
	}
	elseif( isset( $wp_query->query_vars['land'] )) {
		$ueberschrift.' in '.$wp_query->query_vars['land'];
	}
	
	return $ueberschrift;
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

function projekt_description(){

	$entprivatisiert = prettified_field('grundung_mit_syndikat_oder_anteilsabtretung_an_syndikat');

	$wohn = prettified_field('wohnflache');
	$personen = prettified_field('personen');
	$gewerbe = prettified_field('gewerbeflache');
	
	return "Seit dem {$entprivatisiert} durch das Syndikat entprivatisiert. {$wohn} Wohnraum 
	für {$personen} Personen und {$gewerbe} Fläche für Projekte oder Gewerbe.";
	
	
}

function initiative_description(){
	
	"Bei der Mitgliederversammlung im Januar 2013 als Initiative aufgenommen.";
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


// Prefix with protokoll if not existent
function urlify($string) {
	if (preg_match('/^http[s]?:\/\//', $string)) {
		return $string;
	} 
	else {
		return 'http://'.$string;
	}
}

// Remove prefix from url to make it look nicer
function prettify_url($url) {
	if (preg_match('/^http[s]?:\/\/(.*)/', $url, $match)) {
		return $match[1];
	}
	else {
		return $url;
	}
	
}




?>