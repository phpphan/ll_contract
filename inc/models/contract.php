<?php
namespace LicenseLounge\Contract\Model;

class Contract {

    public static function save($data) {
        global $wpdb;

        return $wpdb->insert(
            $wpdb->prefix . "contracts",
            $data
        );
    }

    public static function find_one_undelivered() {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}contracts where delivered_on IS NULL LIMIT 1");
        if( !$row ) {
            return false;
        }
        $row->payment = get_post_meta($row->payment_id);
        $row->download = get_post_meta($row->download_id);
        $row->price_id = self::get_price_id($row);
        $row->soundkit = self::is_soundkit($row->download_id);

        return $row;
    }

    private static function get_price_id($row) {
        $payment_meta = unserialize( $row->payment["_edd_payment_meta"][0] );
        foreach($payment_meta["downloads"] as $download) {
            if( $download["id"] == $row->download_id ) {
                return $download["options"]["price_id"];
            }
        }        
    }

    private static function is_soundkit($id){
         $soundkit = get_post_meta($id,'soundkit');
        return $soundkit[0];
    }


    public static function update($id, $data) {
        global $wpdb;
        $wpdb->update($wpdb->prefix . "contracts", $data, array("id" => $id));
    }
}
