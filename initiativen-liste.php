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

$description_of_type = __('Projektinitiativen haben einen Hausverein, der Mitglied im Verein Mietshäuser Syndikat ist. '
		.'Sie haben in den meisten Fällen ein konkretes Haus, welches sie erwerben wollen und die Syndikatsversammlung '
		.'hat den Beschluss gefasst, mit der jeweiligen Initiative eine Haus GmbH zu gründen. Oft fehlt noch der Kauf '
		.'und/oder die Beteiligung durch die Mietshäuser Syndikat GmbH. Erst wenn alles "geschafft" ist, wird eine '
		.'Initiative zum Projekt.');

include 'projekte-initiativen-template.php'
?>