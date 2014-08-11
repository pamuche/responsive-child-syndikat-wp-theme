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

$description_of_type = __('Es gibt verschieden Gründe, warum Projekte nicht realisiert werden und sich Initiativen '
		.'wieder auflösen. Beim Eilhardshof war es eine Insolvenz. Andere Gruppen haben sich aufgelöst, weil sie '
		.'ihr Wunschobjekt nicht kaufen konnten, z B. weil sie überboten wurden. Die Auflistung ist unvollständig, '
		.'da wir erst 2014 damit begonnen haben.');

include 'projekte-initiativen-template.php'
?>