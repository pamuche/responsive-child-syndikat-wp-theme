<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Initiativen Liste Template
 * Cloned from responsive archive.php Template
 *
 *
 * Template Name: Initiativen Liste
 */

$projekte_liste_typ = 'initiativen';
$is_projekt_page = false;

$meta_query_for_projekte = array(
		array('key' => 'ist_gescheitert', 'value' => '0', 'compare' => '=='),
		array('key' => 'ist_projektinititative', 'value' => '1', 'compare' => '=='),
		'relation' => 'AND'
);

include 'projekte-initiativen-template.php'
?>