<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Projekte Template
 * Cloned from responsive archive.php Template
 * 
 * 
 * Template Name: Gescheiterten Liste
 */

$projekte_liste_typ = 'gescheiterten';
$is_gescheiterten_page = true;

$meta_query_for_projekte = array(
		array('key' => 'ist_gescheitert', 'value' => '1', 'compare' => '==')
);

$description_of_type = __('');

include 'projekte-initiativen-template.php'
?>