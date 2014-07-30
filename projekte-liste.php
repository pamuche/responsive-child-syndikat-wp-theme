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

$is_projekt_page = true;
$projekte_liste_typ = 'projekte';

$meta_query_for_projekte = array(
		array('key' => 'ist_gescheitert', 'value' => '0', 'compare' => '=='),
		array('key' => 'ist_projektinititative', 'value' => '0', 'compare' => '=='),
		'relation' => 'AND'
);

$description_of_type = __('Projekte zeichnen sich dadurch aus, dass sie alle entscheidenden Schritte, '
		.'also die Gründung einer Haus GmbH mit Syndikatsbeteiligung und den Kauf eines Hauses und/oder '
		.'eines Grundstücks bereits hinter sich gebracht haben.');

include 'projekte-initiativen-template.php'
?>