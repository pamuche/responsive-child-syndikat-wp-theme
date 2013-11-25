<?php
/*
Plugin Name: Syndikat Projekte Export
Description: Alle Projekte und Initiativen als csv datei exportieren
Author: Michael-Zolle11
Version: 1.1
License: MIT

Simply copy this file into the plugins directory
*/


class CSVExport
{
	/**
	 * Constructor
	 */
	public function __construct()	{
		if(isset($_GET['download_projekte']))
		{
			$csv = $this->generate_csv();

			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"syndikatsprojekte_".date("Y-m-d").".csv\";" );
			header("Content-Transfer-Encoding: binary");

			echo $csv;
			exit;
		}

		// Add extra menu items for admins
		add_action('admin_menu', array($this, 'admin_menu'));

		// Create end-points
		add_filter('query_vars', array($this, 'query_vars'));
		add_action('parse_request', array($this, 'parse_request'));
	}

	/**
	 * Add extra menu items for admins
	 */
	public function admin_menu()
	{
		add_menu_page('Download Projekte', 'Download Projekte', 'manage_options', 'download_projekte', array($this, 'download_projekte'));
	}

	/**
	 * Allow for custom query variables
	 */
	public function query_vars($query_vars)
	{
		$query_vars[] = 'download_projekte';
		return $query_vars;
	}

	/**
	 * Parse the request
	 */
	public function parse_request(&$wp)
	{
		if(array_key_exists('download_projekte', $wp->query_vars))
		{
			$this->download_projekte();
			exit;
		}
	}

	/**
	 * Download report
	 */
	public function download_projekte()
	{
		echo '<div class="wrap">';
		echo '<div id="icon-tools" class="icon32"></div>';
		echo '<h2>Download Projekte</h2>';
		//$url = site_url();

		echo '<p><a href="/wp-admin/admin.php?page=download_projekte&download_projekte">Projekte und Initativen Tabelle herunterladen</a></p>';
		echo '<p>Die Datei muss mit den Einstellungen: Seperator = Semikolon, Zeichensatz = Unicode (UTF-8) in Calc importiert werden.</p>';
	}


	/**
	 * Converting data to CSV
	 * 
	 * Not easily possible to use AdvancedCustomFields because functions are not available yet.
	 * Would have to hook into admin_head or similar to use ACF
	 * Workaround: Use fixed list of field names
	 */
	public function generate_csv()
	{
		$separator = ';';
		$csv_output = '';
		
		$csv_output = $csv_output.'Name'.$separator;
		
		$field_names = array('ist_projektinititative', 'ist_gescheitert', 'plz', 'ort', 'land', 'projektgrundung', 'gmbh-grundung_ohne_syndikat',
				'beschluss', 'grundung_mit_syndikat_oder_anteilsabtretung_an_syndikat', 'kauf', 'erbbaurecht', 'grundstuck',
				'gewerbeflache', 'wohnflache', 'personen', 'kosten', 'miete', 'solibeitrag', 'gmbh-name', 'adresse', 'hausverein-name',
				'telefon', 'telefon_privat', 'name_kontaktperson', 'email', 'e-mail_privat', 'homepage', 'gps');
		
		//Headers
		foreach( $field_names as $field_name) {
			$csv_output = $csv_output.$field_name.$separator;
		}
		$csv_output .= "\n";
		
		$projekte = get_posts(array(
				'post_type' => 'projekte',
				'nopaging' => true,
				'meta_query' => array(),
				'meta_key'		=> 'plz',
				'orderby'		=> 'meta_value_num',
				'order'			=> 'ASC'
		));
		
		if($projekte) : foreach($projekte as $projekt) :
		    $projekt_id = $projekt->ID;

			$csv_output = $csv_output.get_the_title($projekt_id).$separator;	
// 		    $csv_output = $csv_output.get_post_field('post_content', $projekt->ID).$separator;

					
			foreach( $field_names as $field_name)
			{
				$value = get_post_meta($projekt_id, $field_name, true);
				$value = preg_replace("/$separator/", '_-_', $value); #make csv safe
				
				if (in_array( $field_name, array('gmbh-grundung_ohne_syndikat', 'beschluss', 'grundung_mit_syndikat_oder_anteilsabtretung_an_syndikat', 'kauf') ) ) {
					$date = strtotime($value);
					if ($date) {
						$value =  date('Y-m-d', $date);
					}
				}
				
				$csv_output = $csv_output.$value.$separator;
			}
			
			

			$csv_output .= "\n";
			
		endforeach;	endif;

		return $csv_output;
	}



}

// Instantiate a singleton of this plugin
$csvExport = new CSVExport();

?>
