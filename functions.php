<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 *
 * Syndikat spezifische Funktionen
 *
 */

// Wir benutzen Beitragsbilder für Projekte
add_theme_support( 'post-thumbnails' );

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




?>