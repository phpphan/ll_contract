<?php
namespace LicenseLounge\Contract;
require __DIR__ . "/../../../../wp-load.php";

class WP {
    public function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . 'contracts';

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            content text,
            data longtext NOT NULL,
            delivered_on datetime,
            email_address varchar(256),
            download_id int,
            payment_id int,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function edd_complete_download_purchase($download_id, $payment_id, $download_type) {
        \LicenseLounge\Contract\Model\Contract::save( array(
                "download_id" => $download_id,
                "payment_id" => $payment_id
            )
        );
    }
	
}

add_action( 'edd_complete_download_purchase', array('\LicenseLounge\Contract\WP', 'edd_complete_download_purchase'), 1, 3 );