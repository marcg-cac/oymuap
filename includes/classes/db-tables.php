<?php
namespace Oym\Uap\Includes;
use Oym\Uap\Includes;
use Oym\Uap\Public;

class DB_Tables {
    Public $data;
    public $table_name;
	Public $debug;
    function __construct() {
		$this->data =  new Includes\Data();	
		$this->table_name  = $this->data->current_wpdb->prefix . 'oymtt_entries';
		$this->create_entries_table();
    }

    public function create_application_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = '';

		if ( ! empty( $this->data->current_wpdb->charset ) ) {
			$charset_collate .= "DEFAULT CHARACTER SET ". $this->data->current_wpdb->charset;
		}
		if ( ! empty( $this->data->current_wpdb->collate ) ) {
			$charset_collate .= " COLLATE " . $this->data->current_wpdb->collate;
		}
		/*
		$sql = "CREATE TABLE `portal_oymuap_application` (
				`ID` bigint unsigned NOT NULL AUTO_INCREMENT,
				`application_guid` varchar(100) NOT NULL,
				`application_pdf_name` varchar(500) DEFAULT NULL,
				`household_id` bigint NOT NULL,
				`registered_user_id` bigint DEFAULT NULL,
				`application_date` datetime NOT NULL,
				`application_year_id` bigint NOT NULL DEFAULT '6',
				`current_app_user` bigint DEFAULT NULL,
				`hear_about_cac` varchar(100) DEFAULT NULL,
				`application_certification` varchar(500) DEFAULT NULL,
				`signature_certification` varchar(500) DEFAULT NULL,
				`typed_name` varchar(200) DEFAULT NULL,
				`signature_url` varchar(200) DEFAULT NULL,
				`signature` longblob,
				`status` varchar(50) DEFAULT NULL,
				PRIMARY KEY (`ID`,`application_guid`)
				) 
				) {$charset_collate};";

		dbDelta( $sql );
		*/
	}

	public function create_application_year_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = '';

		if ( ! empty( $this->data->current_wpdb->charset ) ) {
			$charset_collate .= "DEFAULT CHARACTER SET ". $this->data->current_wpdb->charset;
		}
		if ( ! empty( $this->data->current_wpdb->collate ) ) {
			$charset_collate .= " COLLATE " . $this->data->current_wpdb->collate;
		}
		/*
		$sql = "CREATE TABLE `portal_oymuap_application_year` (
				`id` bigint NOT NULL AUTO_INCREMENT,
				`name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`start_date` date DEFAULT NULL,
				`end_date` date DEFAULT NULL,
				`rollover_date` date DEFAULT NULL,
				`current_year` tinyint NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`) 
				) {$charset_collate};";

		dbDelta( $sql );
		*/
	}

}

