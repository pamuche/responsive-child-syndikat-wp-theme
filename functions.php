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

// Hide Email from Spam Bots using a short code
function HideMail($atts , $content = null ){
	if ( ! is_email ($content) )
		return;

	return '<a href="mailto:'.antispambot($content).'">'.antispambot($content).'</a>';
}
add_shortcode( 'email','HideMail');


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
		$value_present = get_field($field['name']);
		if( $value_present ):
			$label = $field['label'];
			$formatted_value = prettified_field($field['name']);
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
		$date = strtotime($value);
		if ($date) {
			return date($date_output_format, $date);
		}
	}
	
	if (in_array( $name, array('kosten', 'solibeitrag') ) ) {
		$formatted_number = number_format( $value , 0 , $dec_point, $thousands_sep);
		return "$formatted_number €";
	}
	
	if ( $name == 'plz' ) {
		return str_pad($value, 5 ,'0', STR_PAD_LEFT);
	}
	
	if ( $name == 'miete' ){
		$total_sqm = floatval(get_field('wohnflache')) + floatval(get_field('gewerbeflache'));
		$euro_per_sqm_per_month = $value / $total_sqm / 12;
		$formatted_number = number_format( $euro_per_sqm_per_month , 2 , $dec_point, $thousands_sep);
		return "$formatted_number €/m²";
	}
	
	
	return $value;
// 	http://www.advancedcustomfields.com/resources/field-types/date-picker/
}


function anzahl_projekte_ueberschrift($count, $projekte_liste_typ, $ort_oder_land) {
	if( $projekte_liste_typ == 'projekte' ) {
		$ueberschrift = ($count == 1) ? "Ein Syndikatsprojekt" : "$count Syndikatsprojekte";
	}
	elseif( $projekte_liste_typ == 'initiativen' ) {
		$ueberschrift = ($count == 1) ? "Eine Syndikatsinitiative" : "$count Syndikatsinitiativen";
	}
	elseif( $projekte_liste_typ == 'gescheiterten' ) {
		$ueberschrift = ($count == 1) ? "Ein nicht realisiertes Syndikatsprojekt" : "$count nicht realisierte Syndikatsprojekte";
	}
	else {
		$ueberschrift = "$count";
	}
	 
	if( $ort_oder_land ) {
		$ueberschrift = $ueberschrift.' in '.$ort_oder_land;
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

function projekt_or_initiative_description($projekte_liste_typ) {
	if( $projekte_liste_typ == 'projekte' ) {
		return projekt_description();
	}
	elseif( $projekte_liste_typ == 'initiativen' ) {
		return initiative_description();
	}
	elseif( $projekte_liste_typ == 'gescheiterten' ) {
		return gescheitert_description();
	}
}

function projekt_description(){

	$entprivatisiert = prettified_field('grundung_mit_syndikat_oder_anteilsabtretung_an_syndikat');

	$wohn = prettified_field('wohnflache');
	$personen = prettified_field('personen');
	$gewerbe = prettified_field('gewerbeflache');
	
	$description = "Seit dem {$entprivatisiert} durch das Syndikat entprivatisiert. {$wohn} Wohnraum 
	für {$personen} Personen";
	
	if ($gewerbe != '0 m²') {
		$description = $description." und {$gewerbe} Fläche für Projekte oder Gewerbe";
	}
	
	$description = $description.'.';
	
	return $description;
	
	
}

function initiative_description(){
    $date = strtotime(get_field('beschluss'));
	if ($date) {
		return "Auf der Mitgliederversammlung im ".formatDateStringGermanMonth($date)." als Initiative aufgenommen.";
	}
	
	return "Als Initiative aufgenommen.";
}

function gescheitert_description(){
	$description = "";
	$date = strtotime(get_field('beschluss'));
	if ($date) {
		$description = $description."Die Beteiligung wurde auf der Mitgliederversammlung im ";
		$description = $description.formatDateStringGermanMonth($date)."  beschlossen. ";
	}

	$description = $description."Das Projekt wurde leider nicht realisiert.";
	return $description;
}

function formatDateStringGermanMonth($date) {
	//initalise String:
	//Gettting the months set up...
	$monate = array(1=>"Januar",
			2=>"Februar",
			3=>"M&auml;rz",
			4=>"April",
			5=>"Mai",
			6=>"Juni",
			7=>"Juli",
			8=>"August",
			9=>"September",
			10=>"Oktober",
			11=>"November",
			12=>"Dezember");
	 
	//Getting our Month
	$monat_number = date('n', $date);

	return $monate[$monat_number]." ".date('Y', $date);
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

// ****************************************************************
// Projekt-eigene Widgets für Karte und Bildergalerie konstruieren

/**
 * widgets registrieren
 */
function widget_sidebar_init() {
	wp_register_sidebar_widget('Projektkarte', 'Projektkarte', 'widget_sidebar_karte', array('description' => 'Karte einfügen'));
	wp_register_sidebar_widget('Projektbilder', 'Projektbilder', 'widget_sidebar_galerie', array('description' => 'Bildergalerie einfügen'));
}

/**
 *
 * @param type $args Der html-text eines widgets in kompakter Form.
 * Mit extract in die Bestandteile auspacken
 */
function widget_sidebar_karte($args) {
	extract($args);
	echo $before_widget;
	$id = get_the_ID();
	$post_type = get_post_type($id);
	// nur auf projektseiten
	if( 'projekte' == $post_type) {
		// Anker setzen
		echo '<div id="karte"></div>';
		$meta = get_metadata('post', get_the_ID(), 'gps');
		$latlon = explode(',', $meta[0]);
		echo '<script type="text/javascript">jQuery(document).ready(function($){
		drawmap(' . $latlon[1] . ',' . $latlon[0] . ');
	});</script>';
	}
	echo $after_widget;
}

function widget_sidebar_galerie($args) {
	extract($args);
	echo $before_widget;
	$id = get_the_ID();
	$post_type = get_post_type($id);
	// nur bei projektseiten
	if ('projekte' == $post_type) {
		$posts = get_children($id);
		// sollte eigentlich immer projektbild geben aber ...
		if (!is_bool($posts)) {
			$galerie = '<div id="galerie">';
			$link_start = '<a class="galerie" href="';
			$link_middle = '" rel="colorbox"> ';
			$link_end = '</a>';
			$box_start = '<div class="bildbox"';
			$box_end = '</div>';
			$i = 0;     // bildzähler
			//echo '<h3>Bildergalerie</h3>';
			foreach ($posts as $post) :
			// können auch Videos eingebaut werden?
			if ($post->post_type == 'attachment' && preg_match("/image/", $post->post_mime_type)) :
			// url besorgen
			$url = $post->guid;
			// nur Bilder aus eigenem Web-Space
			$url = preg_replace('|http://[^/]+|', '', $url);
			$directory = preg_replace('|(.*/).*|', '$1', $url);
			// nur erstes Bild als Vorschau
			if ($i):
			$hidden = 'style="display: none"> ';
			$bild = '';
			else :
			$meta = get_post_meta($post->ID, '_wp_attachment_metadata');
			if (key_exists('medium', $meta[0]['sizes'])) :
			$thumb = $directory . $meta[0]['sizes']['medium']['file'];
			else :
			$thumb = $url;
			endif;
			$hidden = '>';
			$bild = '<img title="zur Bildergalerie" src="' . $thumb . '"></img>';
			endif;
			$image = $link_start . $url . $link_middle . $bild . $link_end;
			$galerie = $galerie . $box_start . $hidden . $image . $box_end;
			$i++;
			endif;
			endforeach;
			$galerie .= '</div>';
			echo $galerie;
			echo '<script type="text/javascript">jQuery(document).ready(function(){
			jQuery("a.galerie").colorbox({rel:"colorbox",width:"600",height:"500"});
		});</script>';
		}
	}
	echo $after_widget;
}

add_action('widgets_init', 'widget_sidebar_init');
// **************  Ende widgets

// Shortcodes
// Die Anzahl der Projekte  überall dort anzeigen, wo   [anzahl_projekte]   steht z.B. Standortkarte
function anzahl_projekte(){
	global $wpdb;
	$query = "select count(ID) from wp_posts
	join wp_postmeta on wp_posts.ID=wp_postmeta.post_id
	join wp_postmeta meta on wp_posts.ID=meta.post_id
	join wp_term_relationships on wp_posts.ID=wp_term_relationships.object_id
	where post_type='projekte'
	and post_status='publish'
	and wp_postmeta.meta_key='ist_projektinititative' and wp_postmeta.meta_value=0
	and meta.meta_key='ist_gescheitert' and meta.meta_value=0
	and wp_term_relationships.term_taxonomy_id=28"; // Annahme dass deutsch die Nr. 28 hat, sonst ändern
	return $wpdb->get_var($query);
}
add_shortcode("anzahl_projekte", "anzahl_projekte");

// dito mit den Inis [anzahl_inis]
function anzahl_inis(){
	global $wpdb;
	$query = "select count(ID) from wp_posts
	join wp_postmeta on wp_posts.ID=wp_postmeta.post_id
	join wp_postmeta meta on wp_posts.ID=meta.post_id
	join wp_term_relationships on wp_posts.ID=wp_term_relationships.object_id
	where post_type='projekte'
	and post_status='publish'
	and wp_postmeta.meta_key='ist_projektinititative' and wp_postmeta.meta_value=1
	and meta.meta_key='ist_gescheitert' and meta.meta_value=0
	and wp_term_relationships.term_taxonomy_id=28"; // siehe oben
	return $wpdb->get_var($query);
}
add_shortcode("anzahl_inis", "anzahl_inis");

?>