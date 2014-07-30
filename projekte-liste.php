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

include 'projekte-initiativen-template.php'
?>