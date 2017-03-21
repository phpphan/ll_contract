<?php
namespace LicenseLounge\Contract;

class Contract_Template {
    const TEMPLATE_TYPE_GOLD_ID = 0;
    const TEMPLATE_TYPE_GOLD_PATH = "contract-gold";

    const TEMPLATE_TYPE_PLATINUM_ID = 1;
    const TEMPLATE_TYPE_PLATINUM_PATH = "contract-platinum";

    const TEMPLATE_TYPE_SOUNDKIT_PATH = "sound-kit-license-agreement";

    var $content;
    var $processed_content;
    var $replacement_data;

    public function __construct( $price_id = TEMPLATE_TYPE_GOLD_ID,$soundkit=null) {
        if($soundkit){
            $this->content = get_page_by_path(self::TEMPLATE_TYPE_SOUNDKIT_PATH)->post_content;
        }
        else{
            if( $price_id == self::TEMPLATE_TYPE_PLATINUM_ID ) {
                $this->content = get_page_by_path(self::TEMPLATE_TYPE_PLATINUM_PATH)->post_content;
            }
            else{
                $this->content = get_page_by_path(self::TEMPLATE_TYPE_GOLD_PATH)->post_content;
            }
        }
        
      
      
    }

    public function process_content($contract) {
        $payment_meta = get_post_meta($contract->payment_id);
        $payment_meta = unserialize($payment_meta["_edd_payment_meta"][0]);
        $address = $payment_meta["user_info"]["address"];
        $address = $address["line1"] . " " . $address["line2"] . ", " . $address["city"] . " " . $address["state"] . " " . $address["zip"] . " " . $address["country"];


        $this->replacement_data = array(
            "date" => date("M d, Y"),
            "producer_real_name" => get_user_meta(get_post($contract->download_id)->post_author, "first_name", true) . " " . get_user_meta(get_post($contract->download_id)->post_author, "last_name", true),
            "producer_name" => get_user_by("ID", get_post($contract->download_id)->post_author)->data->display_name,
            "customer_name" => get_the_title($contract->payment_id),
            "customer_address" => $address,
            "track_name" => get_the_title($contract->download_id),
            "sound_kit_name" => get_the_title($contract->download_id)
        );
        $patterns = array();
        $replacements = array();
        foreach($this->replacement_data as $k => $v) {
            $patterns[] = "/\[\[" . $k . "\]\]/";
            $replacements[] = $v;
        }
        $contract_content = preg_replace($patterns, $replacements, $this->content);
        $contract_content = wpautop($contract_content);
        $this->processed_content = $contract_content;
    }
}